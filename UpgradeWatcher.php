<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Container\StaticContainer;
use Piwik\Option;
use Piwik\Plugin\Manager;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto\UpgradeState;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MarketplaceCache;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MatomoMajor;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\UpgradeDetector;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Http\OptionCacheStore;
use Piwik\Version;

/**
 * Remembers which Feinwerk plugins are installed/active on which Matomo major, and flags a swap when the major changes.
 * Cache-only (no network), never throws — it runs on admin page renders, in the tasks and in the core-update request.
 */
final class UpgradeWatcher
{
    public const STATE = 'fwmc.state';
    public const PENDING = 'fwmc.swap_pending';
    public const RESULT = 'fwmc.swap_result';
    public const SELF = 'FeinwerkMarketplaceConnector';

    public static function observe(): void
    {
        try {
            $current = MatomoMajor::of(Version::VERSION);
            $installed = self::installedFeinwerkPlugins();
            $outgrown = $current === null ? [] : self::outgrown(array_keys($installed), $current);
            $decision = UpgradeDetector::decide(self::storedState(), $current, self::pending() !== null, $outgrown !== []);

            if ($decision === UpgradeDetector::PENDING) {
                // 'reactivate' = what was active BEFORE the major change; the swap switches exactly these on, once.
                $stored = self::storedState();
                self::setPending(['since' => time(), 'last_attempt' => null, 'attempts' => 0, 'to_major' => $current,
                    'reactivate' => $stored ? $stored->active : []]);
            } elseif ($decision === UpgradeDetector::RECORD && $installed !== []) {
                self::record($current, $installed);
            }
        } catch (\Throwable $e) {
            // never break an admin page or the core update
        }
    }

    /** Remember the current state right away (after a finished swap). */
    public static function recordNow(): void
    {
        try {
            $current = MatomoMajor::of(Version::VERSION);
            if ($current !== null) {
                self::record($current, self::installedFeinwerkPlugins());
            }
        } catch (\Throwable $e) {
        }
    }

    /** @return array<string,string> slug => installed version of the Feinwerk plugins in the cached marketplace list */
    public static function installedFeinwerkPlugins(): array
    {
        $settings = StaticContainer::get(SystemSettings::class);
        $url = MarketplaceClientFactory::url();
        $token = (string) $settings->instanceToken->getValue();
        if ($url === '' || $token === '') {
            return [];
        }
        $cached = (new MarketplaceCache(MarketplaceClientFactory::make($url, $token), new OptionCacheStore()))->cached();
        $versions = InstalledMarketplacePlugins::versions($cached->plugins);
        unset($versions[self::SELF]);

        return $versions;
    }

    /**
     * @param  list<string>  $slugs
     * @return list<string> the slugs whose installed plugin.json serves only an older Matomo line
     */
    public static function outgrown(array $slugs, int $currentMajor): array
    {
        $result = [];
        foreach ($slugs as $slug) {
            try {
                $json = json_decode((string) @file_get_contents(Manager::getPluginDirectory($slug).'/plugin.json'), true);
                $require = (string) ($json['require']['matomo'] ?? $json['require']['piwik'] ?? '');
                if ($require !== '' && MatomoMajor::outgrows($require, $currentMajor)) {
                    $result[] = $slug;
                }
            } catch (\Throwable $e) {
            }
        }

        return $result;
    }

    /**
     * The plugins the daily auto-update leaves alone while a swap is pending: the outgrown builds and the plugins to
     * reactivate, given-up ones included. Empty without a pending swap. Shared by Tasks and PendingUpdates, so a plugin
     * the auto-update skips still shows in the "Updates available" notice (re-review 0.8.2 N2). Never throws: null when
     * the list cannot be built, so the daily task can skip its automatic updates instead (fail closed, review 5.1.0).
     *
     * @param  list<string>  $installedSlugs
     * @return list<string>|null
     */
    public static function skippedByAutoUpdate(array $installedSlugs): ?array
    {
        try {
            $pending = self::pending();
            $current = MatomoMajor::of(Version::VERSION);
            if ($pending === null || $current === null) {
                return [];
            }

            return array_values(array_unique(array_merge(
                self::outgrown($installedSlugs, $current),
                array_map('strval', (array) ($pending['reactivate'] ?? []))
            )));
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * While a swap is pending: the installed slugs it is still switching (their build serves only an older Matomo
     * line). An update offered for them may come from a list cached before the Matomo update, so the screen offers none.
     * Left out: what the swap gave up on (license refused, or MAX_FAILURES failed attempts), which the notice hands to
     * the admin to update on this screen, and development checkouts, which the swap never touches.
     *
     * @param  list<string>  $installedSlugs
     * @return list<string>
     */
    public static function switching(array $installedSlugs): array
    {
        try {
            $current = MatomoMajor::of(Version::VERSION);
            $pending = self::pending();
            if ($current === null || $pending === null) {
                return [];
            }
            $givenUp = array_merge(
                array_map('strval', (array) ($pending['unlicensed'] ?? [])),
                array_map('strval', array_keys(array_filter((array) ($pending['failures'] ?? []), fn ($n) => (int) $n >= UpgradeSwapper::MAX_FAILURES)))
            );
            $candidates = array_filter(
                $installedSlugs,
                fn ($slug) => ! in_array((string) $slug, $givenUp, true) && ! InstalledMarketplacePlugins::isDevCheckout((string) $slug)
            );

            return self::outgrown(array_values($candidates), $current);
        } catch (\Throwable $e) {
            return [];
        }
    }

    public static function storedState(): ?UpgradeState
    {
        return UpgradeState::fromArray(self::readJson(self::STATE));
    }

    /** @return ?array{since: int, last_attempt: ?int, attempts: int, to_major: int, reactivate?: list<string>, failures?: array<string,int>} */
    public static function pending(): ?array
    {
        $data = self::readJson(self::PENDING);

        return is_array($data) ? $data : null;
    }

    public static function setPending(array $pending): void
    {
        Option::set(self::PENDING, json_encode($pending), 1);
    }

    public static function clearPending(): void
    {
        Option::delete(self::PENDING);
    }

    public static function result(): ?array
    {
        $data = self::readJson(self::RESULT);

        return is_array($data) ? $data : null;
    }

    public static function setResult(array $result): void
    {
        Option::set(self::RESULT, json_encode($result), 1);
    }

    /** @param array<string,string> $installed */
    private static function record(int $major, array $installed): void
    {
        $manager = Manager::getInstance();
        $active = array_values(array_filter(array_keys($installed), fn ($slug) => $manager->isPluginActivated($slug)));
        $state = new UpgradeState($major, $installed, $active, date('c'));
        $stored = self::storedState();
        if ($stored === null || ! $stored->sameAs($state)) {
            Option::set(self::STATE, json_encode($state->toArray()), 1);
        }
    }

    private static function readJson(string $name): mixed
    {
        $raw = Option::get($name);

        return is_string($raw) && $raw !== '' ? json_decode($raw, true) : null;
    }
}
