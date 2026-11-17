<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\WordPress;

/**
 * The real WordPress side of the WordPress mode: only WordPress's own functions (installer, activation, options). Runs
 * only in Matomo for WordPress, where WordPress is loaded; covered by the end-to-end run, not by unit tests.
 */
final class WpPlatform implements Platform
{
    public function versions(): array
    {
        $mfw = '';
        if (defined('MATOMO_ANALYTICS_FILE') && function_exists('get_file_data')) {
            $mfw = (string) (get_file_data(MATOMO_ANALYTICS_FILE, ['Version' => 'Version'])['Version'] ?? '');
        }

        return ['wordpress_version' => (string) ($GLOBALS['wp_version'] ?? ''), 'mfw_version' => $mfw];
    }

    public function installedPlugins(): array
    {
        $this->load('wp-admin/includes/plugin.php');
        $out = [];
        foreach (function_exists('get_plugins') ? get_plugins() : [] as $file => $data) {
            if (preg_match('#^(Feinwerk[A-Za-z0-9]+)/\1\.php$#', (string) $file, $m) === 1) {
                $out[$m[1]] = (string) ($data['Version'] ?? '');
            }
        }

        return $out;
    }

    public function cannotInstall(): ?string
    {
        if (! function_exists('current_user_can') || ! current_user_can('install_plugins') || ! current_user_can('activate_plugins')) {
            return 'capability';
        }
        $this->load('wp-admin/includes/file.php');

        return function_exists('get_filesystem_method') && get_filesystem_method() === 'direct' ? null : 'filesystem';
    }

    public function install(string $packageUrl): ?string
    {
        if (($error = $this->prepare()) !== null) {
            return $error;
        }
        // The skin of WordPress's own AJAX install: it collects errors (get_errors) instead of printing them.
        $skin = new \WP_Ajax_Upgrader_Skin();
        $result = (new \Plugin_Upgrader($skin))->install($packageUrl);
        $error = $this->error($skin, $result);
        if ($error === null && $result !== true) {
            $error = $result === null ? 'WordPress could not reach its file system.' : 'WordPress did not install the package.';
        }
        if ($error === null) {
            wp_clean_plugins_cache();
        }

        return $error;
    }

    public function update(string $slug, string $version): ?string
    {
        if (($error = $this->prepare()) !== null) {
            return $error;
        }
        $file = $slug.'/'.$slug.'.php';
        // WordPress's own "Update now" (wp_ajax_update_plugin): bulk_upgrade keeps the plugin switched on and restores the
        // old folder when the update fails (temp_backup). It reads the package from the update list: for this request
        // only, the list names exactly this version with our marker (resolved at download time, Hooks::onDownload).
        $entry = (object) ['slug' => $slug, 'plugin' => $file, 'new_version' => $version, 'package' => Hooks::PREFIX.$slug.'/'.$version];
        $inject = static function ($transient) use ($file, $entry) {
            $transient = is_object($transient) ? $transient : new \stdClass();
            $transient->response = isset($transient->response) && is_array($transient->response) ? $transient->response : [];
            $transient->response[$file] = $entry;

            return $transient;
        };
        add_filter('site_transient_update_plugins', $inject, PHP_INT_MAX);
        try {
            $skin = new \WP_Ajax_Upgrader_Skin();
            $upgrader = new \Plugin_Upgrader($skin);
            $results = $upgrader->bulk_upgrade([$file]);
        } finally {
            remove_filter('site_transient_update_plugins', $inject, PHP_INT_MAX);
        }
        $error = $this->error($skin, null);
        if ($error !== null) {
            return $error;
        }
        if ($results === false) {
            return 'WordPress could not reach its file system.';
        }
        $result = is_array($results) ? ($results[$file] ?? null) : null;
        if (is_wp_error($result)) {
            return $result->get_error_message();
        }

        return is_array($result) ? null : 'WordPress did not update the plugin.';
    }

    public function isActive(string $file): bool
    {
        $this->load('wp-admin/includes/plugin.php');

        return function_exists('is_plugin_active') && is_plugin_active($file);
    }

    /** Loads WordPress's installer and opens its file system; null when ready, else why not. */
    private function prepare(): ?string
    {
        foreach (['wp-admin/includes/file.php', 'wp-admin/includes/misc.php', 'wp-admin/includes/plugin.php', 'wp-admin/includes/class-wp-upgrader.php',
            'wp-admin/includes/class-wp-ajax-upgrader-skin.php'] as $file) {
            $this->load($file);
        }
        if (! class_exists('\WP_Ajax_Upgrader_Skin') || ! class_exists('\Plugin_Upgrader')) {
            return 'WordPress\'s installer is not available.';
        }

        return WP_Filesystem() ? null : 'WordPress could not open its file system.';
    }

    /** The error WordPress's skin collected, the way wp_ajax_update_plugin reads it; null when none. */
    private function error(\WP_Ajax_Upgrader_Skin $skin, mixed $result): ?string
    {
        if (is_wp_error($result)) {
            return $result->get_error_message();
        }
        if (is_wp_error($skin->result)) {
            return $skin->result->get_error_message();
        }
        $errors = $skin->get_errors();

        return is_wp_error($errors) && $errors->has_errors() ? (string) $errors->get_error_message() : null;
    }

    public function activate(string $file): ?string
    {
        $this->load('wp-admin/includes/plugin.php');
        $result = activate_plugin($file);

        return is_wp_error($result) ? $result->get_error_message() : null;
    }

    public function saveUpdates(array $updates): void
    {
        if (get_site_option(Hooks::OPTION, []) !== $updates) {
            update_site_option(Hooks::OPTION, $updates);
            delete_site_transient('update_plugins');   // WordPress checks again and takes them in (Hooks::onUpdateCheck)
        }
    }

    public function isDevCheckout(string $slug): bool
    {
        if (preg_match('/^Feinwerk[A-Za-z0-9]{1,79}$/', $slug) !== 1 || ! defined('WP_PLUGIN_DIR')) {
            return false;
        }
        $dir = WP_PLUGIN_DIR.'/'.$slug;

        return is_link($dir) || file_exists($dir.'/.git');
    }

    public function returnUrl(string $target): string
    {
        return admin_url('admin-post.php?action=fwmc_return&to='.rawurlencode($target));
    }

    public function isMultisite(): bool
    {
        return function_exists('is_multisite') && is_multisite();
    }

    private function load(string $file): void
    {
        if (defined('ABSPATH') && is_file(ABSPATH.$file)) {
            require_once ABSPATH.$file;
        }
    }
}
