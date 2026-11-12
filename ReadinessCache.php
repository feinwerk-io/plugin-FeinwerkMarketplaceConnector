<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Option;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MarketplaceApiClient;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MatomoMajor;
use Piwik\Version;

/** Cached answer to "which of my Feinwerk plugins already exist for the next Matomo major?" — refreshed by the tasks. */
final class ReadinessCache
{
    private const OPTION = 'fwmc.cache.readiness';

    public static function refresh(MarketplaceApiClient $client): void
    {
        try {
            $current = MatomoMajor::of(Version::VERSION);
            if ($current === null) {
                return;
            }
            // Development checkouts are left out: the connector never switches them.
            $installed = array_filter(
                UpgradeWatcher::installedFeinwerkPlugins(),
                fn ($slug) => ! InstalledMarketplacePlugins::isDevCheckout((string) $slug),
                ARRAY_FILTER_USE_KEY
            );
            $list = array_map(fn ($slug, $version) => ['slug' => $slug, 'version' => $version], array_keys($installed), $installed);
            $rows = $client->readiness($current + 1, $list);
            Option::set(self::OPTION, json_encode(['target' => $current + 1, 'rows' => $rows, 'fetched_at' => date('c')]), 1);
        } catch (\Throwable $e) {
            // Keep the last answer (a marketplace hiccup must not hide the notice for a day). An answer for an
            // older target, or none (the marketplace does not know the line yet), shows nothing (ReadinessSummary).
        }
    }

    /** @return ?array{target: int, rows: list<array<mixed>>, fetched_at: string} */
    public static function read(): ?array
    {
        $raw = Option::get(self::OPTION);
        $data = is_string($raw) && $raw !== '' ? json_decode($raw, true) : null;

        return is_array($data) && isset($data['target'], $data['rows']) ? $data : null;
    }
}
