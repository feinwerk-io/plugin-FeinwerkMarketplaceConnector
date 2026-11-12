<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/**
 * Keeps Matomo's database update out of the browser on large instances while a Feinwerk component is pending (B1,
 * Peter 2026-10-21). Matomo runs all pending updates in one browser request, the GET form on its update page
 * (module CoreUpdater, updateCorePlugins=1, no nonce, no login), and on a large database that request outlasts the web
 * server's time limit ("Bad Gateway") while MySQL keeps working. Only that request is stopped; the update page itself,
 * Matomo's Marketplace, the one-click update and `console core:update` stay as they are.
 */
final class BrowserUpdateGuard
{
    /** CoreUpdater actions that run the updates when the flag is set (after Matomo's redirect the action is empty). */
    private const ACTIONS = ['', 'index', 'runUpdaterAndExit'];

    /** Does this request run the pending updates? $flag = the request's updateCorePlugins. */
    public static function runsUpdates(?string $module, ?string $action, mixed $flag): bool
    {
        if ($module !== 'CoreUpdater' || ! in_array((string) $action, self::ACTIONS, true) || ! is_scalar($flag)) {
            return false;
        }

        // Matomo 5.14 and 6 read it with getBoolParameter, 5.0 as an integer: treat anything but "off" as running.
        return ! in_array(strtolower(trim((string) $flag)), ['', '0', 'false', 'no', 'off'], true);
    }

    /**
     * The pending components that belong to Feinwerk plugins: the plugins themselves (update files) and the log table
     * columns of their dimensions, which Matomo's Columns\Updater lists as "<table>.<column>" (e.g.
     * log_visit.channel_grouping), not under the plugin name.
     *
     * @param  array<string, mixed>  $pending  Updater::getComponentUpdates(): component => update files
     * @param  list<string>  $feinwerkColumns  "<table>.<column>" of the activated Feinwerk plugins
     * @return list<string>
     */
    public static function feinwerkComponents(array $pending, array $feinwerkColumns): array
    {
        $components = [];
        foreach (array_keys($pending) as $component) {
            $component = (string) $component;
            if (preg_match('/^Feinwerk[A-Z][A-Za-z0-9]*$/', $component) === 1 || in_array($component, $feinwerkColumns, true)) {
                $components[] = $component;
            }
        }

        return $components;
    }
}
