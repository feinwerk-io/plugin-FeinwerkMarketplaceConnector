<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/**
 * The marketplace this connector talks to. Fixed to the Feinwerk store; a developer can point a
 * test instance elsewhere with `[FeinwerkMarketplaceConnector] marketplace_url = …` in config.ini.php.
 * Only an https URL is accepted as override (http only for localhost), so a typo cannot send the
 * instance token in clear text.
 */
final class MarketplaceEndpoint
{
    public const URL = 'https://plugins.feinwerk.io';

    /** The offline-installation section of the store's connector guide (German for German, English otherwise). */
    public static function offlineDocsUrl(string $storeBase, string $lang): string
    {
        $base = rtrim($storeBase, '/');

        return $lang === 'de'
            ? $base.'/de/docs/connector#ohne-internetverbindung'
            : $base.'/en/docs/connector#without-internet-access';
    }

    public static function resolve(mixed $override): string
    {
        if (! is_string($override) || trim($override) === '') {
            return self::URL;
        }

        $url = rtrim(trim($override), '/');
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $local = in_array($host, ['localhost', '127.0.0.1', '::1'], true);

        if ($host === '' || ! ($scheme === 'https' || ($scheme === 'http' && $local))) {
            return self::URL;
        }

        return $url;
    }
}
