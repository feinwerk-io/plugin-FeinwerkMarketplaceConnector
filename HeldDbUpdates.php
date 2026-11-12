<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Option;

/**
 * Auto-apply updates that were held back because they change the database (plugin => version).
 * They stay pending until an admin installs them, so the menu badge and the admin notice can warn.
 * Never throws: a broken store just means no warning.
 */
final class HeldDbUpdates
{
    private const OPTION = 'fwmc.autoapply.dbheld';

    /** @return array<string,string> */
    public static function all(): array
    {
        try {
            $value = json_decode((string) Option::get(self::OPTION), true);

            return is_array($value) ? array_filter($value, 'is_string') : [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    public static function hold(string $slug, string $version): void
    {
        $held = self::all();
        $held[$slug] = $version;
        self::store($held);
    }

    public static function release(string $slug): void
    {
        $held = self::all();
        if (isset($held[$slug])) {
            unset($held[$slug]);
            self::store($held);
        }
    }

    /**
     * Forgets holds the admin has dealt with: the plugin is gone, or installed at the held version or a newer one (by
     * a click, a ZIP or git). The screen already hides them; this keeps the stored list current (backlog 13).
     *
     * @param  array<string,string>  $installed  slug => installed version (InstalledMarketplacePlugins::versions)
     */
    public static function prune(array $installed): void
    {
        $held = self::all();
        $kept = array_filter(
            $held,
            fn ($version, $slug) => isset($installed[$slug]) && version_compare($installed[$slug], $version, '<'),
            ARRAY_FILTER_USE_BOTH
        );
        if ($kept !== $held) {
            self::store($kept);
        }
    }

    /** @param array<string,string> $held */
    private static function store(array $held): void
    {
        try {
            Option::set(self::OPTION, json_encode($held));
        } catch (\Throwable $e) {
            // Losing the warning is better than breaking the scheduled task.
        }
    }
}
