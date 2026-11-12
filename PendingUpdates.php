<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Container\StaticContainer;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\ActionItems;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\AutoApplyPolicy;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto\PluginStatusRow;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MarketplaceCache;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\UpdateMapper;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Http\OptionCacheStore;

/**
 * Installed marketplace plugins that have an update, read from the persistent cache only.
 * Used by the menu badge and the admin notice, which render on admin pages — so this never
 * touches the network and never throws (an unconfigured or broken state yields no rows).
 */
final class PendingUpdates
{
    /**
     * @param  bool  $excludeAutoApplied  leave out plugins the daily task updates by itself
     * @return list<PluginStatusRow>
     */
    public static function rows(bool $excludeAutoApplied = false): array
    {
        try {
            $settings = StaticContainer::get(SystemSettings::class);
            $url = MarketplaceClientFactory::url();
            $token = (string) $settings->instanceToken->getValue();
            if ($url === '' || $token === '') {
                return [];
            }

            $client = MarketplaceClientFactory::make($url, $token);
            $cached = (new MarketplaceCache($client, new OptionCacheStore()))->cached();
            if (empty($cached->plugins)) {
                return [];
            }

            $devCheckouts = InstalledMarketplacePlugins::devCheckouts($cached->plugins);
            $installed = InstalledMarketplacePlugins::versions($cached->plugins);
            $rows = (new UpdateMapper())->map($cached->plugins, $installed, $devCheckouts, UpgradeWatcher::switching(array_keys($installed)));
            // Left out of the notice: what the daily task updates by itself. While a swap is pending it skips the swap's
            // plugins (given-up ones too), so those stay in the notice (re-review 0.8.2 N2).
            $exclude = $excludeAutoApplied
                ? array_values(array_diff(
                    AutoApplyPolicy::effective($settings->autoApplyPlugins->getValue(), 'FeinwerkMarketplaceConnector', $devCheckouts),
                    UpgradeWatcher::skippedByAutoUpdate(array_keys($installed)) ?? []
                ))
                : [];
            // An auto-apply plugin whose update was held back (database changes) needs an admin.
            $exclude = array_values(array_diff($exclude, array_keys(self::heldIn(UpdateMapper::updatesAvailable($rows)))));

            return UpdateMapper::updatesAvailable($rows, $exclude);
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Pending updates the daily task held back because they change the database — only while
     * the held version is still the latest one offered.
     *
     * @return list<PluginStatusRow>
     */
    public static function heldForDatabase(): array
    {
        return array_values(self::heldIn(self::rows()));
    }

    /**
     * @param  list<PluginStatusRow>  $pending
     * @return array<string,PluginStatusRow> slug => row
     */
    private static function heldIn(array $pending): array
    {
        $held = HeldDbUpdates::all();
        $result = [];
        foreach ($pending as $row) {
            if (($held[$row->slug] ?? null) === $row->latestVersion) {
                $result[$row->slug] = $row;
            }
        }

        return $result;
    }

    /**
     * The "Aufgaben" items for the menu count (Core/ActionItems), from the cache only: without Matomo's pending database
     * updates, which its own update page announces. Never throws.
     *
     * @return list<array<string, mixed>>
     */
    public static function actionItems(): array
    {
        try {
            $settings = StaticContainer::get(SystemSettings::class);
            $url = MarketplaceClientFactory::url();
            $token = (string) $settings->instanceToken->getValue();
            if ($url === '' || $token === '') {
                return [];
            }
            $cached = (new MarketplaceCache(MarketplaceClientFactory::make($url, $token), new OptionCacheStore()))->cached();
            $devCheckouts = InstalledMarketplacePlugins::devCheckouts($cached->plugins);
            $installed = InstalledMarketplacePlugins::versions($cached->plugins);
            $rows = (new UpdateMapper())->map($cached->plugins, $installed, $devCheckouts, UpgradeWatcher::switching(array_keys($installed)));

            return ActionItems::from(
                $rows,
                'FeinwerkMarketplaceConnector',
                array_keys(self::heldIn(UpdateMapper::updatesAvailable($rows))),   // from the rows at hand: this runs on every admin page
                [],
                UpgradeWatcher::pending() !== null,
                $cached->errorKind,
                date('Y-m-d')
            );
        } catch (\Throwable $e) {
            return [];
        }
    }

    /** @return list<array<mixed>> the cached marketplace list (empty when unconfigured or uncached) */
    public static function cachedPlugins(): array
    {
        try {
            $settings = StaticContainer::get(SystemSettings::class);
            $url = MarketplaceClientFactory::url();
            $token = (string) $settings->instanceToken->getValue();
            if ($url === '' || $token === '') {
                return [];
            }

            return (new MarketplaceCache(MarketplaceClientFactory::make($url, $token), new OptionCacheStore()))->cached()->plugins;
        } catch (\Throwable $e) {
            return [];
        }
    }
}
