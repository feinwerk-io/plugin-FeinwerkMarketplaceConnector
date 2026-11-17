<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\WordPress;

/**
 * WordPress side of the WordPress mode, registered from the connector's main file only when WordPress loads it
 * (docs/dev/wordpress-mode-plan.md). The updates the connector found (option OPTION, written in Matomo by the daily task
 * and Refresh) go into WordPress's update list; a package marker gets a fresh signed URL from the store only at download
 * time (Matomo is bootstrapped through Matomo for WordPress's own API then); updates with database changes stay out of
 * WordPress's automatic updates (HARD RULE 8). Loaded before Matomo: no Matomo class outside the download callback.
 */
final class Hooks
{
    public const OPTION = 'fwmc_wordpress_updates';

    public const PREFIX = 'feinwerk-package://';

    private static bool $registered = false;

    public static function register(): void
    {
        if (self::$registered || ! function_exists('add_filter')) {
            return;
        }
        self::$registered = true;
        add_filter('pre_set_site_transient_update_plugins', [self::class, 'onUpdateCheck']);
        add_filter('plugins_api', [self::class, 'onPluginsApi'], 10, 3);
        add_filter('upgrader_pre_download', [self::class, 'onDownload'], 10, 4);
        // Last word on our plugins: a theme's or another plugin's __return_true must not let a database change through.
        add_filter('auto_update_plugin', [self::class, 'onAutoUpdate'], PHP_INT_MAX, 2);
        add_action('admin_post_fwmc_return', [self::class, 'onReturn']);
    }

    /** WordPress's update check: our entries for the plugins WordPress knows, when newer than installed. */
    public static function onUpdateCheck(mixed $transient): mixed
    {
        return self::merge($transient, self::saved());
    }

    /** @return array<string, array<string, mixed>> the entries the connector saved; none on a multisite (Peter, 2026-11-17) */
    private static function saved(): array
    {
        if (! function_exists('get_site_option') || (function_exists('is_multisite') && is_multisite())) {
            return [];
        }
        $saved = get_site_option(self::OPTION, []);

        return is_array($saved) ? $saved : [];
    }

    /**
     * "View details" in WordPress: for our entries the store's details instead of a wordpress.org lookup (which would
     * answer "Plugin not found"). Matomo is booted only for this click.
     */
    public static function onPluginsApi(mixed $result, mixed $action = null, mixed $args = null): mixed
    {
        $slug = is_object($args) && is_string($args->slug ?? null) ? $args->slug : '';
        if ($action !== 'plugin_information' || ! isset(self::saved()[$slug.'/'.$slug.'.php'])) {
            return $result;
        }
        try {
            if (class_exists('\WpMatomo\Bootstrap')) {
                \WpMatomo\Bootstrap::do_bootstrap();
            }
            // Not connected, no licence, not offered for WordPress: each with its own sentence (review 2026-11-17).
            return (object) \Piwik\Plugins\FeinwerkMarketplaceConnector\WordPressMode::pluginDetails($slug);
        } catch (\Throwable $e) {
            return self::error('fwmc_details', $e->getMessage() !== '' ? $e->getMessage() : 'The Feinwerk store did not answer. Please try again later.', $result);
        }
    }

    /** @param array<string, array<string, mixed>> $saved */
    public static function merge(mixed $transient, array $saved): mixed
    {
        if (! is_object($transient)) {
            return $transient;
        }
        $checked = isset($transient->checked) && is_array($transient->checked) ? $transient->checked : [];
        foreach ($saved as $file => $entry) {
            if (! is_array($entry) || ! isset($checked[$file]) || ! is_string($entry['new_version'] ?? null)) {
                continue;
            }
            $package = is_string($entry['package'] ?? null) ? $entry['package'] : '';
            if (str_starts_with($package, self::PREFIX) && version_compare($entry['new_version'], (string) $checked[$file], '>')) {
                if (! isset($transient->response) || ! is_array($transient->response)) {
                    $transient->response = [];
                }
                $transient->response[$file] = (object) $entry;
                if (isset($transient->no_update) && is_array($transient->no_update)) {
                    unset($transient->no_update[$file]);
                }
                continue;
            }
            // Up to date: no_update gives WordPress's auto-update toggle and "View details" (E2E 2026-11-17).
            if (! isset($transient->response[$file])) {
                if (! isset($transient->no_update) || ! is_array($transient->no_update)) {
                    $transient->no_update = [];
                }
                $transient->no_update[$file] = (object) (['new_version' => (string) $checked[$file], 'package' => ''] + $entry);
            }
        }

        return $transient;
    }

    /** WordPress starts a download: a Feinwerk package marker is fetched from a fresh signed URL. */
    public static function onDownload(mixed $reply, mixed $package, mixed $upgrader = null, mixed $hookExtra = []): mixed
    {
        if (! is_string($package) || ! str_starts_with($package, self::PREFIX)) {
            return $reply;
        }
        // HARD RULE 8, second line: whatever another filter decided, an unattended run never installs a database change.
        if (self::heldForAutomaticRun($package, self::saved(), self::isAutomatic($upgrader))) {
            return self::error('fwmc_db_held', 'This update changes the database. Install it with a click on Update now or on the Feinwerk Plugins screen.', $reply);
        }
        try {
            if (class_exists('\WpMatomo\Bootstrap')) {
                \WpMatomo\Bootstrap::do_bootstrap();
            }
            $url = \Piwik\Plugins\FeinwerkMarketplaceConnector\WordPressMode::packageUrl($package);   // logs its failures
        } catch (\Throwable $e) {
            return self::error('fwmc_package', $e->getMessage(), $reply);
        }

        return function_exists('download_url') ? download_url($url) : $reply;
    }

    /**
     * Whether an automatic run may not download this marker: its entry has database changes, or there is no entry to
     * tell (fail closed). A click (Update now, WP-CLI, the Feinwerk Plugins screen) may.
     *
     * @param  array<string, array<string, mixed>>  $saved
     */
    public static function heldForAutomaticRun(string $marker, array $saved, bool $automatic): bool
    {
        if (! $automatic) {
            return false;
        }
        foreach ($saved as $entry) {
            if (is_array($entry) && ($entry['package'] ?? null) === $marker) {
                return ! empty($entry['db_changes']);
            }
        }

        return true;
    }

    /** WordPress's background updates (cron, Automatic_Upgrader_Skin); not its AJAX "Update now", whose skin extends that class. */
    private static function isAutomatic(mixed $upgrader): bool
    {
        if (function_exists('wp_doing_cron') && wp_doing_cron()) {
            return true;
        }
        $skin = is_object($upgrader) && isset($upgrader->skin) ? $upgrader->skin : null;

        return $skin instanceof \Automatic_Upgrader_Skin && ! $skin instanceof \WP_Ajax_Upgrader_Skin;
    }

    private static function error(string $code, string $message, mixed $fallback): mixed
    {
        return class_exists('\WP_Error') ? new \WP_Error($code, $message) : $fallback;
    }

    /**
     * No automatic update in WordPress for an update that changes the database (HARD RULE 8). The Plugins screen asks
     * with null to see whether the toggle is forced: it never is, so the admin can always switch it (E2E 2026-11-17).
     */
    public static function onAutoUpdate(mixed $update, mixed $item): mixed
    {
        if ($update !== null && is_object($item) && is_string($item->package ?? null) && str_starts_with($item->package, self::PREFIX) && ! empty($item->db_changes)) {
            return false;
        }

        return $update;
    }

    /**
     * The way back after an install through WordPress (WordPressMode::backUrl): this wp-admin request has run Matomo
     * for WordPress's installer for the new plugin (on init), so the Matomo screen opens instead of WordPress's dashboard.
     */
    public static function onReturn(): void
    {
        $to = isset($_GET['to']) && is_string($_GET['to']) ? (function_exists('wp_unslash') ? wp_unslash($_GET['to']) : $_GET['to']) : '';
        $app = defined('MATOMO_ANALYTICS_FILE') && function_exists('plugins_url') ? plugins_url('app/', MATOMO_ANALYTICS_FILE) : '';
        if ($app !== '' && function_exists('wp_safe_redirect')) {
            wp_safe_redirect(self::returnTarget((string) $to, $app));
            exit;
        }
    }

    /** Only the connector's screen in this Matomo for WordPress; anything else goes to the screen's start. */
    public static function returnTarget(string $to, string $app): string
    {
        $fallback = $app.'index.php?module=FeinwerkMarketplaceConnector&action=index';
        if (! str_starts_with($to, $app.'index.php?') || str_contains($to, '..') || preg_match('/[\\s\\\\]/', $to) === 1) {
            return $fallback;
        }
        parse_str((string) parse_url($to, PHP_URL_QUERY), $query);

        return ($query['module'] ?? null) === 'FeinwerkMarketplaceConnector' ? $to : $fallback;
    }
}
