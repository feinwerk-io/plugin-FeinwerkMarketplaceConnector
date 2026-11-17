<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

/**
 * Matomo for WordPress (the "Matomo" plugin for WordPress, which runs Matomo inside wp-content/plugins/matomo/app).
 * There the connector never installs or updates a plugin (Peter, 2026-11-17, relayed by PluginLead: "ja,
 * WordPress-Modus als Entwurf, Schutz-Release ohne Abschalten"): an update through Matomo's own installer put a second
 * copy next to the one WordPress installed, and every report page and the tracking endpoint answered HTTP 500 (test
 * report /root/fw-docs/matomo-for-wordpress-test/report.md). WordPress installs and updates the plugins there. Never
 * throws.
 */
final class MatomoForWordPress
{
    /** For tests: true or false instead of looking. */
    public static ?bool $detected = null;

    public static function detected(): bool
    {
        if (self::$detected !== null) {
            return self::$detected;
        }
        try {
            return defined('ABSPATH') && function_exists('matomo_add_plugin');
        } catch (\Throwable $e) {
            return false;
        }
    }

    /** Matomo Analytics › Marketplace › Install plugins in the WordPress admin, or null. */
    public static function marketplaceUrl(): ?string
    {
        try {
            if (self::detected() && function_exists('admin_url')) {
                $url = admin_url('admin.php?page=matomo-marketplace&tab=install');

                return is_string($url) && preg_match('#^https?://#i', $url) === 1 ? $url : null;
            }
        } catch (\Throwable $e) {
        }

        return null;
    }
}
