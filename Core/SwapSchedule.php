<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/** A pending swap retries with every hourly task run for a day, then once a day (missing builds can take weeks). */
final class SwapSchedule
{
    private const HOUR = 3600;
    private const DAY = 86400;

    /** @param array{since?: int, last_attempt?: ?int} $pending */
    public static function shouldRun(array $pending, int $now): bool
    {
        $last = $pending['last_attempt'] ?? null;
        if ($last === null || $last > $now) {   // never tried, or the clock stepped back
            return true;
        }
        $since = (int) ($pending['since'] ?? $last);
        $interval = $now - $since < self::DAY ? self::HOUR - 300 : self::DAY;   // 5 min slack for task jitter

        return $now - $last >= $interval;
    }
}
