<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/**
 * Rehearsal rc1, RC-F4: the swap notice names the plugins of the last swap run that had no build for the new Matomo yet
 * ("waiting"). A Refresh in between can already offer their builds while the next run is still to come (hourly, or
 * "Switch now"). A plugin list fetched after the Matomo update was seen (so with the new major's version headers) that
 * offers a newer version than the installed one means its build is out: the notice no longer calls it unavailable.
 * A list from before the update proves nothing: its "latest" is a build for the old Matomo.
 */
final class SwapWaiting
{
    /**
     * @param  list<string>  $waiting  the last swap run's waiting plugins
     * @param  list<mixed>  $plugins  the cached plugin list
     * @param  array<string, string>  $installed  installed version by plugin
     * @param  int|null  $fetchedAt  when that list was fetched
     * @param  int  $since  when the Matomo update was seen (fwmc.swap_pending.since)
     * @return list<string> the plugins still without a build
     */
    public static function still(array $waiting, array $plugins, array $installed, ?int $fetchedAt, int $since): array
    {
        if ($fetchedAt === null || $since <= 0 || $fetchedAt < $since) {
            return array_values($waiting);
        }
        $latest = [];
        foreach ($plugins as $plugin) {
            if (is_array($plugin) && is_string($plugin['slug'] ?? null) && is_array($plugin['latest'] ?? null)) {
                $latest[$plugin['slug']] = (string) ($plugin['latest']['version'] ?? '');
            }
        }

        return array_values(array_filter($waiting, fn ($slug) => ! (
            ($latest[$slug] ?? '') !== '' && isset($installed[$slug]) && version_compare($latest[$slug], $installed[$slug], '>')
        )));
    }
}
