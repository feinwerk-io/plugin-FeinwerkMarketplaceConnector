<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto;

final class PluginStatusRow
{
    public const STATUS_NOT_INSTALLED = 'not_installed';
    public const STATUS_INSTALLED = 'installed';
    public const STATUS_UPDATE_AVAILABLE = 'update_available';

    /**
     * @param  array{status?: string, valid_until?: ?string}|null  $license
     * @param  array{amount_eur?: string, interval?: string}|null  $priceFrom
     */
    public function __construct(
        public readonly string $slug,
        public readonly string $displayName,
        public readonly string $tier,
        public readonly ?string $installedVersion,
        public readonly ?string $latestVersion,
        public readonly string $status,
        public readonly bool $needsLicense,
        public readonly ?array $license = null,
        public readonly ?string $description = null,
        public readonly ?string $url = null,
        public readonly ?string $category = null,
        public readonly ?array $priceFrom = null,
        public readonly ?string $artworkUrl = null,
        // Installed as a git checkout or symlink: never updated through the connector.
        public readonly bool $devCheckout = false,
        // A pending upgrade swap installs this plugin's build for the running Matomo: the offered update may be the old
        // line's build from a list cached before the Matomo update, so it is not offered.
        public readonly bool $switching = false,
        // What the offered install or update changes in the database ({migrations, columns, note}, see DbChangePlan):
        // the screen warns before the click.
        public readonly ?array $dbChange = null,
        // The store can assign a free seat of the customer's license for this plugin to this instance (feature flag
        // of the store list; false for a store that does not send it).
        public readonly bool $licenseClaimable = false,
        // When the offered build was released (ISO 8601 from the store), for "recently updated" on the Discover tab.
        public readonly ?string $releasedAt = null,
        // Installed, but Matomo has not switched it on (never activated, or switched off by an admin).
        public readonly bool $inactive = false,
    ) {}
}
