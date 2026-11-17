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
        return new MarketplaceApiClient($url, $token, new HttpTransportMatomo(), self::headers());
    }

    /**
     * Sent with every store request of a connected instance (the public list sends none): the Matomo and PHP version,
     * and since 5.6.0 the connector's own version. The store offers Matomo 6 betas to Matomo 6 instances automatically
     * only to connectors that label them and never apply them on their own (store gate on X-Connector-Version).
     *
     * @return list<string>
     */
    public static function headers(): array
    {
        $headers = ['X-Matomo-Version: '.Version::VERSION, 'X-PHP-Version: '.PHP_VERSION];
        $own = self::connectorVersion();
        if ($own !== null) {
            $headers[] = 'X-Connector-Version: '.$own;
        }

        return $headers;
    }

    /** This connector's version from its plugin.json; null when unreadable. Never throws. */
    public static function connectorVersion(): ?string
    {
        static $version = false;
        if ($version === false) {
            $version = null;
            try {
                $json = json_decode((string) @file_get_contents(__DIR__.'/plugin.json'), true);
                $value = is_array($json) ? ($json['version'] ?? null) : null;
                if (is_string($value) && preg_match('/^\d+\.\d+\.\d+(-[0-9A-Za-z.]+)?$/', $value) === 1) {
                    $version = $value;
                }
            } catch (\Throwable $e) {
            }
        }

        return $version;
    }
}
