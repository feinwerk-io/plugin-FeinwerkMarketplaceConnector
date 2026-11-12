<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/** Matomo majors from version strings and from a plugin's require.matomo. */
final class MatomoMajor
{
    public static function of(string $version): ?int
    {
        return preg_match('/^v?(\d+)\.\d+/', trim($version), $m) ? (int) $m[1] : null;
    }

    /** The highest Matomo major a require.matomo constraint accepts, or null without an upper bound. */
    public static function servesUpTo(string $requireMatomo): ?int
    {
        if (! preg_match('/<(=?)\s*v?(\d+)\.(\d+)\.(\d+)/', $requireMatomo, $m)) {
            return null;
        }
        $major = (int) $m[2];
        // "<6.0.0" and "<6.0.0-b1" stop before Matomo 6; "<5.9.0" or "<=5.9.9" still serve Matomo 5
        return $m[1] === '' && (int) $m[3] === 0 && (int) $m[4] === 0 ? $major - 1 : $major;
    }

    /** True when the installed build was made for an older Matomo line than the running one. */
    public static function outgrows(string $requireMatomo, int $currentMajor): bool
    {
        $upTo = self::servesUpTo($requireMatomo);

        return $upTo !== null && $upTo < $currentMajor;
    }
}
