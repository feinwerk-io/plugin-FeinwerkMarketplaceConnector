<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto\PluginStatusRow;

final class UpdateMapper
{
    /** @param string $language the admin's UI language ('de' or 'en'): which version of the author's database note to show */
    public function __construct(private readonly string $language = 'en')
    {
    }

    /**
     * @param  list<array<mixed>>  $available   Plugins from MarketplaceApiClient::listPlugins()
     * @param  array<string,string>  $installed  slug => installed version (locally installed marketplace plugins)
     * @return list<PluginStatusRow>
     */
    /**
     * How many rows have an update available — used for the admin menu badge.
     *
     * @param  list<PluginStatusRow>  $rows
     */
    public static function countUpdatesAvailable(array $rows): int
    {
        return count(self::updatesAvailable($rows));
    }

    /**
     * Rows with an update available, minus the given slugs (e.g. auto-applied plugins).
     *
     * @param  list<PluginStatusRow>  $rows
     * @param  list<string>  $excludeSlugs
     * @return list<PluginStatusRow>
     */
    public static function updatesAvailable(array $rows, array $excludeSlugs = []): array
    {
        return array_values(array_filter(
            $rows,
            static fn (PluginStatusRow $row) => $row->status === PluginStatusRow::STATUS_UPDATE_AVAILABLE
                && ! $row->devCheckout
                && ! $row->switching
                && ! in_array($row->slug, $excludeSlugs, true)
        ));
    }

    /**
     * Whether the store knows the licensing endpoints: it then sends `license_claimable` on every list row.
     *
     * @param  list<array<mixed>>  $available
     */
    public static function storeOffersLicensing(array $available): bool
    {
        foreach ($available as $plugin) {
            if (is_array($plugin) && array_key_exists('license_claimable', $plugin)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  list<string>  $devCheckouts  installed slugs that are git checkouts/symlinks on this instance
     * @param  list<string>  $switching  installed slugs a pending upgrade swap is switching to their build for this Matomo
     * @param  array<string, list<string>>  $installedColumns  log table columns of the installed plugins (DbChangePlan)
     * @param  list<string>  $inactive  installed slugs Matomo has not switched on (InstalledMarketplacePlugins::inactive)
     */
    public function map(array $available, array $installed, array $devCheckouts = [], array $switching = [], array $installedColumns = [], array $inactive = []): array
    {
        $rows = [];

        foreach ($available as $plugin) {
            $slug = (string) ($plugin['slug'] ?? '');
            if ($slug === '') {
                continue;
            }

            $installedVersion = $installed[$slug] ?? null;
            $latestVersion = $plugin['latest']['version'] ?? null;

            if ($installedVersion === null) {
                $status = PluginStatusRow::STATUS_NOT_INSTALLED;
            } elseif ($latestVersion !== null && version_compare($latestVersion, $installedVersion, '>')) {
                $status = PluginStatusRow::STATUS_UPDATE_AVAILABLE;
            } else {
                $status = PluginStatusRow::STATUS_INSTALLED;
            }

            $license = $plugin['license'] ?? null;
            $switchingRow = $status === PluginStatusRow::STATUS_UPDATE_AVAILABLE && in_array($slug, $switching, true);
            $dbChange = $latestVersion !== null && $status !== PluginStatusRow::STATUS_INSTALLED
                ? DbChangePlan::for($installedVersion, (string) $latestVersion, $plugin['latest']['db_changes'] ?? null, $installedColumns[$slug] ?? [], $this->dbNote($plugin['latest'] ?? null))
                : null;

            $rows[] = new PluginStatusRow(
                $slug,
                PluginName::of($plugin),
                (string) ($plugin['tier'] ?? 'free'),
                $installedVersion,
                $latestVersion,
                $status,
                (bool) ($plugin['needs_license'] ?? false),
                is_array($license) ? $license : null,
                isset($plugin['description']) ? (string) $plugin['description'] : null,
                // Bound as a link on the screen: only an http(s) address (security review 5.1.0).
                isset($plugin['url']) && is_string($plugin['url']) && preg_match('#^https?://#i', $plugin['url']) === 1 ? $plugin['url'] : null,
                isset($plugin['category']) && is_scalar($plugin['category']) ? (string) $plugin['category'] : null,
                isset($plugin['price_from']) && is_array($plugin['price_from']) ? $plugin['price_from'] : null,
                isset($plugin['artwork_url']) && is_scalar($plugin['artwork_url']) ? (string) $plugin['artwork_url'] : null,
                $installedVersion !== null && in_array($slug, $devCheckouts, true),
                $switchingRow,
                $dbChange,
                (bool) ($plugin['license_claimable'] ?? false),
                isset($plugin['latest']['released_at']) && is_string($plugin['latest']['released_at']) ? $plugin['latest']['released_at'] : null,
                // Matomo has the plugins of a pending swap off after a major update: the swap switches them, not an admin.
                $installedVersion !== null && ! $switchingRow && in_array($slug, $inactive, true),
            );
        }

        return $rows;
    }

    /**
     * The author's database note (plugin.json feinwerk.db_warning) in the admin's language: the German one the store sends
     * as db_warning_de since cf01b6f (backlog 15), else the author's own.
     */
    private function dbNote(mixed $latest): mixed
    {
        $german = is_array($latest) ? ($latest['db_warning_de'] ?? null) : null;
        if ($this->language === 'de' && is_string($german) && trim($german) !== '') {
            return $german;
        }

        return is_array($latest) ? ($latest['db_warning'] ?? null) : null;
    }
}
