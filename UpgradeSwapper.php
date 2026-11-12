<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Concurrency\Lock;
use Piwik\Concurrency\LockBackend;
use Piwik\Container\StaticContainer;
use Piwik\Plugin\Manager;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions\MarketplaceLicenseException;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MarketplaceCache;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MatomoMajor;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\SwapPlanner;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\SwapResult;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Http\OptionCacheStore;
use Piwik\SettingsPiwik;
use Piwik\Version;

/**
 * After a Matomo major change: install the builds for the running Matomo and reactivate what was active before.
 * Runs from the hourly task or the "Switch now" action — never inside the core-update request. Never throws.
 */
final class UpgradeSwapper
{
    private const LOCK_TTL = 900;
    /** Failed attempts per plugin before the swap gives up and tells the admin. */
    public const MAX_FAILURES = 3;

    public static function run(): ?array
    {
        $lock = null;
        try {
            $lock = new Lock(StaticContainer::get(LockBackend::class), 'FwmcSwap');
            if (! $lock->acquireLock('run', self::LOCK_TTL)) {
                return null;
            }

            return self::swap();
        } catch (\Throwable $e) {
            // e.g. the marketplace is unreachable: still an attempt, so the retries fall back to once a day
            self::countFailedRun();
            MarketplaceLog::failure('switching the plugins after the Matomo update', $e);

            return null;
        } finally {
            if ($lock !== null) {
                try {
                    $lock->unlock();
                } catch (\Throwable $e) {
                    // Releasing must not throw either.
                }
            }
        }
    }

    private static function swap(): ?array
    {
        $settings = StaticContainer::get(SystemSettings::class);
        $url = MarketplaceClientFactory::url();
        $token = (string) $settings->instanceToken->getValue();
        $current = MatomoMajor::of(Version::VERSION);
        $pending = UpgradeWatcher::pending();
        if ($url === '' || $token === '' || $current === null || $pending === null) {
            return null;
        }

        $client = MarketplaceClientFactory::make($url, $token);
        $available = (new MarketplaceCache($client, new OptionCacheStore()))->refresh()->plugins;
        $installed = InstalledMarketplacePlugins::versions($available);
        unset($installed[UpgradeWatcher::SELF]);
        $manager = Manager::getInstance();
        $devCheckouts = InstalledMarketplacePlugins::devCheckouts($available);
        $activeNow = array_values(array_filter(array_keys($installed), fn ($slug) => $manager->isPluginActivated($slug)));
        $outgrown = UpgradeWatcher::outgrown(array_keys($installed), $current);

        $offered = [];
        $env = ['matomo_version' => Version::VERSION, 'php_version' => PHP_VERSION, 'site_url' => SettingsPiwik::getPiwikUrl()];
        $list = array_map(fn ($slug, $version) => ['slug' => $slug, 'version' => $version], array_keys($installed), $installed);
        foreach ($client->checkUpdates($env, $list) as $update) {
            if (isset($update['slug'], $update['latest'])) {
                $offered[(string) $update['slug']] = (string) $update['latest'];
            }
        }

        $failures = (array) ($pending['failures'] ?? []);
        // A license refusal goes to the admin at once: the planner treats it like a plugin it gave up on.
        $unlicensed = array_values(array_map('strval', (array) ($pending['unlicensed'] ?? [])));
        $giveUp = array_values(array_unique(array_merge(
            array_keys(array_filter($failures, fn ($n) => (int) $n >= self::MAX_FAILURES)),
            $unlicensed
        )));
        // What the connector knew before the major change (null: upgraded while an older connector ran).
        $before = UpgradeWatcher::storedState();

        $reactivate = array_values(array_map('strval', (array) ($pending['reactivate'] ?? [])));
        $plan = SwapPlanner::plan($reactivate, $installed, $outgrown, $devCheckouts, $activeNow, $offered, $giveUp);
        $result = ['to_major' => $current, 'switched' => [], 'activated' => [], 'inactive' => [], 'kept_off' => [],
            'waiting' => $plan['waiting'], 'skipped' => $plan['skipped'],
            'needs_admin' => array_values(array_diff($plan['needsAdmin'], $unlicensed)),
            'needs_license' => array_values(array_intersect($plan['needsAdmin'], $unlicensed)),
            'errors' => [], 'at' => date('c'), 'shown' => false];
        // Plugins the planner already gave up on need the admin and are not retried. Those that were active before the
        // Matomo update and still lack their build are switched on again by the update that brings it (ActivateLater,
        // RC-F1). A plugin whose build is in place (switching it on failed) is not remembered: no later automatic update
        // may switch it on unasked (review 5.2.1).
        $givenUp = array_values(array_intersect($reactivate, $plan['needsAdmin'], $outgrown));
        $reactivate = array_values(array_diff($reactivate, $plan['needsAdmin']));

        foreach ($plan['install'] as $action) {
            $slug = $action['slug'];
            try {
                // Always install without activating: activation below checks the plugin's dependencies first.
                (new PluginInstallerBridge())->install($client->download($slug, $action['version']), false);
                $result['switched'][$slug] = $action['version'];
                unset($failures[$slug]);
            } catch (MarketplaceLicenseException $e) {
                $result['needs_license'][] = $slug;
                $unlicensed[] = $slug;
                if (in_array($slug, $reactivate, true)) {
                    $givenUp[] = $slug;
                }
                $reactivate = array_values(array_diff($reactivate, [$slug]));
                MarketplaceLog::failure('switching '.$slug.' after the Matomo update', $e);

                continue;
            } catch (\Throwable $e) {
                $result['errors'][$slug] = $e->getMessage();
                $failures[$slug] = (int) ($failures[$slug] ?? 0) + 1;
                MarketplaceLog::failure('switching '.$slug.' after the Matomo update', $e);
                // The last attempt: from now on the admin updates it (UpgradeWatcher::switching), so remember it at once,
                // not one run later (review 5.2.1: a click in between left it off).
                if ($failures[$slug] >= self::MAX_FAILURES && in_array($slug, $reactivate, true)) {
                    $givenUp[] = $slug;
                    $reactivate = array_values(array_diff($reactivate, [$slug]));
                }

                continue;
            }
            if ($action['activate']) {
                self::activate($slug, $manager, $result, $failures, $reactivate);
            } elseif (! $manager->isPluginActivated($slug)) {
                // Known to be off before the update, or not known at all: the notice says which.
                $keptOff = $before !== null && isset($before->plugins[$slug]) && ! in_array($slug, $before->active, true);
                $result[$keptOff ? 'kept_off' : 'inactive'][] = $slug;
            }
        }
        foreach ($plan['activate'] as $slug) {
            self::activate($slug, $manager, $result, $failures, $reactivate);
        }
        // Switched on again: their reports for the days archived while they were off are rebuilt by core:archive.
        GapReArchiving::schedule($result['activated'], (int) ($pending['since'] ?? 0));
        ActivateLater::remember($givenUp, $current, (int) ($pending['since'] ?? 0));

        $result = SwapResult::merge(UpgradeWatcher::result(), $result);
        UpgradeWatcher::setResult($result);
        if ($plan['waiting'] === [] && $result['errors'] === []) {
            // State first: a request in between must not see the old major without the flag and start a new swap.
            UpgradeWatcher::recordNow();
            UpgradeWatcher::clearPending();
        } else {
            UpgradeWatcher::setPending(array_merge($pending, [
                'last_attempt' => time(),
                'attempts' => (int) ($pending['attempts'] ?? 0) + 1,
                'failures' => $failures,
                'reactivate' => $reactivate,
                'unlicensed' => $unlicensed,
            ]));
        }

        return $result;
    }

    /** A run that failed before it finished still counts for the retry schedule (SwapSchedule). Never throws. */
    private static function countFailedRun(): void
    {
        try {
            $pending = UpgradeWatcher::pending();
            if ($pending !== null) {
                UpgradeWatcher::setPending(array_merge($pending, [
                    'last_attempt' => time(),
                    'attempts' => (int) ($pending['attempts'] ?? 0) + 1,
                ]));
            }
        } catch (\Throwable $e) {
        }
    }

    /**
     * Switch a plugin on once, unless its dependencies are not met (then the admin has to look at it).
     * Whatever the outcome, the slug leaves the reactivate list (success) or is reported (needs admin / error).
     */
    private static function activate(string $slug, Manager $manager, array &$result, array &$failures, array &$reactivate): void
    {
        try {
            $plugin = $manager->loadPlugin($slug);
            if ($plugin !== null && $plugin->hasMissingDependencies()) {
                $result['needs_admin'][] = $slug;
                $reactivate = array_values(array_diff($reactivate, [$slug]));

                return;
            }
            if (! $manager->isPluginActivated($slug)) {
                $manager->activatePlugin($slug);
                $result['activated'][] = $slug;
            }
            $reactivate = array_values(array_diff($reactivate, [$slug]));
            unset($failures[$slug]);
        } catch (\Throwable $e) {
            $result['errors'][$slug] = $e->getMessage();
            $failures[$slug] = (int) ($failures[$slug] ?? 0) + 1;
            MarketplaceLog::failure('switching on '.$slug.' after the Matomo update', $e);
        }
    }
}
