<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/**
 * The completed days whose archives may lack the reports of plugins that were off after a Matomo major update: from
 * the update day up to yesterday (UTC), at most the last $maxDays days. Today is left out: Matomo rebuilds today's
 * archive by itself (pure).
 */
final class GapDays
{
    public const MAX_DAYS = 31;

    /** @return ?string the first day (Y-m-d) to rebuild, or null when the window has no completed day */
    public static function firstDay(int $since, int $now, int $maxDays = self::MAX_DAYS): ?string
    {
        $today = gmdate('Y-m-d', $now);
        $first = gmdate('Y-m-d', $since);
        if ($since <= 0 || $first >= $today) {
            return null;
        }
        $earliest = gmdate('Y-m-d', (int) strtotime($today.' UTC') - $maxDays * 86400);

        return max($first, $earliest);
    }
}
