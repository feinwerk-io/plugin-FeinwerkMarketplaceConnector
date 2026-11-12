<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/**
 * When an instance counts as large for database updates: there an update in the browser can outlast the web server's
 * or proxy's time limit (the admin sees "Bad Gateway" while MySQL keeps working), so the connector sends database
 * updates to the console. Measured on the log tables (visits as MySQL estimates them, log data and indexes in bytes).
 * A config override wins: [FeinwerkMarketplaceConnector] large_instance = 1 or 0.
 */
final class InstanceSize
{
    public const LARGE_VISITS = 500000;

    public const LARGE_LOG_BYTES = 1073741824; // 1 GiB

    public static function isLarge(?int $visits, ?int $logBytes, mixed $override = null): bool
    {
        if ($override === '1' || $override === 1 || $override === true) {
            return true;
        }
        if ($override === '0' || $override === 0 || $override === false) {
            return false;
        }

        return ($visits ?? 0) >= self::LARGE_VISITS || ($logBytes ?? 0) >= self::LARGE_LOG_BYTES;
    }
}
