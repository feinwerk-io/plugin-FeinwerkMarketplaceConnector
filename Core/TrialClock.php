<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/**
 * When a trial of a paid plugin ends (Peter, 2026-11-16, rule b: "features off, data kept"). The store sends the end as
 * `valid_until`, a date (the licence stops being active at the start of that day, UTC) or a full timestamp. A trial
 * counts as ended only GRACE seconds after its end, so a skewed server clock never ends it early (Auth SAML, relayed by
 * PluginLead, 2026-11-16). Pure: the caller passes the time.
 */
final class TrialClock
{
    public const GRACE = 86400;

    /** The end as unix time, or null when the value says nothing usable. */
    public static function endsAt(mixed $validUntil): ?int
    {
        if (! is_string($validUntil) || $validUntil === '') {
            return null;
        }
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $validUntil, $m) === 1) {
            return checkdate((int) $m[2], (int) $m[3], (int) $m[1]) ? gmmktime(0, 0, 0, (int) $m[2], (int) $m[3], (int) $m[1]) : null;
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}/', $validUntil) !== 1) {
            return null;
        }
        $time = strtotime($validUntil);

        return $time === false ? null : $time;
    }

    /** Ended, grace included; false when no end is known. */
    public static function ended(?int $endsAt, int $now): bool
    {
        return $endsAt !== null && $now >= $endsAt + self::GRACE;
    }

    /** Whole days left, the started one counted; 0 once the end has passed; null when no end is known. */
    public static function daysLeft(?int $endsAt, int $now): ?int
    {
        if ($endsAt === null) {
            return null;
        }

        return max(0, (int) ceil(($endsAt - $now) / 86400));
    }
}
