<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/**
 * Checks that a purchase link the marketplace sent really is a buy page of that marketplace before the browser is
 * sent there (the link comes out of an API answer).
 */
final class PurchaseLink
{
    public static function isOnStore(string $url, string $storeBase): bool
    {
        $link = parse_url($url);
        $base = parse_url(trim($storeBase));
        if (! is_array($link) || ! is_array($base)) {
            return false;
        }
        if (isset($link['user']) || isset($link['pass']) || isset($link['query']) || isset($link['fragment'])) {
            return false;
        }
        // parse_url drops an empty query or fragment ("...?" and "...#"); reject those as well
        if (str_contains($url, '?') || str_contains($url, '#')) {
            return false;
        }

        $scheme = strtolower((string) ($link['scheme'] ?? ''));
        $host = strtolower((string) ($link['host'] ?? ''));
        $baseScheme = strtolower((string) ($base['scheme'] ?? ''));
        $baseHost = strtolower((string) ($base['host'] ?? ''));
        if ($scheme === '' || $host === '' || $scheme !== $baseScheme || $host !== $baseHost) {
            return false;
        }
        if (self::port($link, $scheme) !== self::port($base, $baseScheme)) {
            return false;
        }

        $prefix = rtrim((string) ($base['path'] ?? ''), '/').'/buy/';

        return preg_match('~^'.preg_quote($prefix, '~').'[A-Za-z0-9]{40}$~', (string) ($link['path'] ?? '')) === 1;
    }

    /** @param  array<string, mixed>  $parts */
    private static function port(array $parts, string $scheme): int
    {
        return isset($parts['port']) ? (int) $parts['port'] : ($scheme === 'https' ? 443 : 80);
    }
}
