<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MarketplaceApiClient;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MarketplaceEndpoint;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Http\HttpTransportMatomo;
use Piwik\Version;

/** Builds the API client; every call tells the marketplace which Matomo/PHP this instance runs. */
final class MarketplaceClientFactory
{
    /** The fixed store URL, or the developer override from config.ini.php (see Core/MarketplaceEndpoint). */
    public static function url(): string
    {
        $override = null;
        try {
            if (class_exists(\Piwik\Config::class)) {
                $section = \Piwik\Config::getInstance()->FeinwerkMarketplaceConnector;
                $override = is_array($section) ? ($section['marketplace_url'] ?? null) : null;
            }
        } catch (\Throwable $e) {
            // No config available: the fixed URL.
        }

        return MarketplaceEndpoint::resolve($override);
    }

    public static function make(string $url, string $token): MarketplaceApiClient
    {
        return new MarketplaceApiClient($url, $token, new HttpTransportMatomo(), [
            'X-Matomo-Version: '.Version::VERSION,
            'X-PHP-Version: '.PHP_VERSION,
        ]);
    }
}
