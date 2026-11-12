<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto\PluginStatusRow;

/**
 * The "Aufgaben" list of the Feinwerk Plugins screen (Peter, 2026-10-23): everything the admin can or should act on, from
 * what the connector already knows (cached plugin list, held updates, a pending switch or database update). Warnings
 * first, then updates, then hints. The screen renders each kind with its own text and button.
 */
final class ActionItems
{
    /** A license that ends within this many days is announced. */
    public const EXPIRY_DAYS = 30;

    public const WARNING = 'warning';
    public const INFO = 'info';

    /**
     * @param  list<PluginStatusRow>  $rows
     * @param  list<string>  $dbHeld  auto-updates held back because they change the database
     * @param  list<string>  $pendingDbComponents  Feinwerk components whose database update Matomo has not run yet
     * @param  string|null  $connectionError  the cached refresh error kind (auth, offline, failed)
     * @param  string  $today  Y-m-d
     * @return list<array{kind: string, severity: string, slug: ?string, date: ?string, components: list<string>}>
     */
    public static function from(
        array $rows,
        string $selfSlug,
        array $dbHeld,
        array $pendingDbComponents,
        bool $switchPending,
        ?string $connectionError,
        string $today
    ): array {
        $warnings = [];
        $updates = [];
        $hints = [];

        if (in_array($connectionError, ['auth', 'offline'], true)) {
            $warnings[] = self::item('connection_'.$connectionError, self::WARNING);
        }
        if ($pendingDbComponents !== []) {
            $warnings[] = self::item('db_update', self::WARNING, null, null, array_values($pendingDbComponents));
        }
        if ($switchPending) {
            $warnings[] = self::item('switch_pending', self::WARNING);
        }

        foreach ($rows as $row) {
            $installed = $row->installedVersion !== null;
            $updatable = $row->status === PluginStatusRow::STATUS_UPDATE_AVAILABLE && ! $row->devCheckout && ! $row->switching;

            if ($installed && $row->needsLicense && ! $row->devCheckout) {
                $warnings[] = self::item('license_missing', self::WARNING, $row->slug);
            }
            if ($updatable && in_array($row->slug, $dbHeld, true)) {
                $warnings[] = self::item('db_held', self::WARNING, $row->slug);
            } elseif ($updatable && $row->slug === $selfSlug) {
                array_unshift($updates, self::item('self_update', self::INFO, $row->slug));
            } elseif ($updatable) {
                $updates[] = self::item('update', self::INFO, $row->slug);
            }

            $until = is_array($row->license) && is_string($row->license['valid_until'] ?? null) ? substr($row->license['valid_until'], 0, 10) : null;
            if ($until !== null && preg_match('/^\d{4}-\d{2}-\d{2}$/', $until) === 1 && $until >= $today
                && $until <= date('Y-m-d', strtotime($today.' +'.self::EXPIRY_DAYS.' days'))) {
                $hints[] = self::item('license_expiring', self::INFO, $row->slug, $until);
            }
            if (! $installed && $row->licenseClaimable && $row->needsLicense) {
                $hints[] = self::item('license_claimable', self::INFO, $row->slug);
            }
        }

        return array_merge($warnings, $updates, $hints);
    }

    /** @return array{kind: string, severity: string, slug: ?string, date: ?string, components: list<string>} */
    private static function item(string $kind, string $severity, ?string $slug = null, ?string $date = null, array $components = []): array
    {
        return ['kind' => $kind, 'severity' => $severity, 'slug' => $slug, 'date' => $date, 'components' => $components];
    }
}
