<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Option;
use Piwik\Plugin\Manager;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MatomoMajor;
use Piwik\Version;

/**
 * Plugins the swap gave up on although they were active before the Matomo major update: license refused, or
 * UpgradeSwapper::MAX_FAILURES failed installs. Matomo switched them off when it updated, and the swap notice hands them
 * to the admin ("renew the license, then update on the Feinwerk Plugins screen"). The swap's own list of what to switch on
 * again ends with the swap, so this one remembers them until an update or install (one click, several at once, or the
 * automatic update) has put their build for this Matomo in place, and then switches them on again the way the swap does
 * for the others: dependencies checked first, the reports of the days they were off queued for rebuilding (rehearsal rc1,
 * finding RC-F1, 2026-11-11). Option fwmc.swap.activate_later: {slug: {to_major, since}}. Never throws.
 */
final class ActivateLater
{
    public const OPTION = 'fwmc.swap.activate_later';

    /**
     * @param  list<string>  $slugs  given up on, and active before the Matomo update
     * @param  int  $since  when the Matomo update was seen (fwmc.swap_pending.since), for the reports of the gap
     */
    public static function remember(array $slugs, int $toMajor, int $since): void
    {
        if ($slugs === []) {
            return;
        }
        try {
            $list = self::all();
            foreach ($slugs as $slug) {
                $list[(string) $slug] = ['to_major' => $toMajor, 'since' => $since];
            }
            self::store($list);
        } catch (\Throwable $e) {
        }
    }

    /**
     * Right after $slug was installed or updated successfully: switch it on again if the swap had to leave it off. The
     * entry is used up either way (switched on, already on, unmet dependencies, another Matomo major by now); a failed
     * install never gets here, so a refused download keeps it remembered for the next try.
     *
     * @return bool true when it was switched on
     */
    public static function afterInstall(string $slug): bool
    {
        try {
            $list = self::all();
            if (! isset($list[$slug])) {
                return false;
            }
            $entry = $list[$slug];
            unset($list[$slug]);
            self::store($list);

            $manager = Manager::getInstance();
            if ((int) ($entry['to_major'] ?? 0) !== MatomoMajor::of(Version::VERSION) || $manager->isPluginActivated($slug)) {
                return false;
            }
            $plugin = $manager->loadPlugin($slug);
            if ($plugin === null || $plugin->hasMissingDependencies()) {
                return false;
            }
            $manager->activatePlugin($slug);
            GapReArchiving::schedule([$slug], (int) ($entry['since'] ?? 0));

            return true;
        } catch (\Throwable $e) {
            MarketplaceLog::failure('switching on '.$slug.' after its update', $e);

            return false;
        }
    }

    /**
     * Daily: forget what no longer needs it: switched on by the admin, no longer installed, or from another Matomo major.
     *
     * @param  list<string>  $installed  installed Feinwerk plugins
     */
    public static function prune(array $installed): void
    {
        try {
            $list = self::all();
            if ($list === []) {
                return;
            }
            $manager = Manager::getInstance();
            $major = MatomoMajor::of(Version::VERSION);
            $kept = array_filter($list, fn ($entry, $slug) => in_array((string) $slug, $installed, true)
                && (int) ($entry['to_major'] ?? 0) === $major
                && ! $manager->isPluginActivated((string) $slug), ARRAY_FILTER_USE_BOTH);
            if (count($kept) !== count($list)) {
                self::store($kept);
            }
        } catch (\Throwable $e) {
        }
    }

    /** @return array<string, array{to_major?: int, since?: int}> */
    private static function all(): array
    {
        $list = json_decode((string) Option::get(self::OPTION), true);

        return is_array($list) ? $list : [];
    }

    /** @param array<string, mixed> $list */
    private static function store(array $list): void
    {
        if ($list === []) {
            Option::delete(self::OPTION);

            return;
        }
        Option::set(self::OPTION, (string) json_encode($list));
    }
}
