<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/**
 * Lapsed licences (Peter, 2026-11-17: "Alle Plugins, deren Lizenz mehr als 7 Tage abgelaufen sind, sollen allen
 * Superadmins und Admins Warnhinweise anzeigen"). The store sends per plugin row `license_ended_at` (ISO 8601, the end
 * of this instance's most recent licence; null when one covers it now or there never was one) and
 * `license_ended_was_trial` (bool, null with it); both always together, so a missing key means an older store, where
 * nothing is shown. Ended trials stay with the plugins' own trial notes. Pure.
 */
final class LicenseEnd
{
    /** Quiet for 7 days after the end (Peter: "mehr als 7 Tage"). */
    public const GRACE = 7 * 86400;

    /** @param array<mixed> $row */
    public static function known(array $row): bool
    {
        return array_key_exists('license_ended_at', $row) && array_key_exists('license_ended_was_trial', $row);
    }

    /**
     * When the bought licence ended; null when one covers the instance, there never was one, it was a trial or the store
     * does not say.
     *
     * @param array<mixed> $row
     */
    public static function endedAt(array $row): ?int
    {
        if (! self::known($row) || $row['license_ended_was_trial'] !== false) {
            return null;
        }

        return TrialClock::endsAt($row['license_ended_at']);
    }

    /** Whether the central notice covers this row: a paid Feinwerk plugin on a store that sends the fields. */
    public static function covers(array $row): bool
    {
        $slug = $row['slug'] ?? null;

        return self::known($row) && is_string($slug) && preg_match('/^Feinwerk[A-Za-z0-9]{1,79}$/', $slug) === 1
            && ($row['tier'] ?? 'free') !== 'free';
    }

    /**
     * The paid plugins of the list whose bought licence ended more than GRACE ago.
     *
     * @param  list<mixed>  $plugins  the cached store list
     * @return array<string, int> slug => end
     */
    public static function lapsed(array $plugins, int $now): array
    {
        $lapsed = [];
        foreach ($plugins as $row) {
            if (! is_array($row) || ! self::covers($row)) {
                continue;
            }
            $endedAt = self::endedAt($row);
            if ($endedAt !== null && $now - $endedAt > self::GRACE) {
                $lapsed[(string) $row['slug']] = $endedAt;
            }
        }

        return $lapsed;
    }
}
