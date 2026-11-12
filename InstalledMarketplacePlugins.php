<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Plugin\Manager;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\DbChangePlan;

/**
 * Which of the marketplace plugins are installed locally, and at what version.
 * Shared by the admin screen (Controller) and the menu badge (Menu).
 */
final class InstalledMarketplacePlugins
{
    /**
     * @param  list<array<mixed>>  $available  Plugins from the marketplace list payload
     * @return array<string,string>  slug => installed version
     */
    public static function versions(array $available): array
    {
        $manager = Manager::getInstance();
        // One directory scan: isPluginInFilesystem() scans the whole plugins directory on every call.
        $present = $manager->readPluginsDirectory();
        $result = [];

        foreach ($available as $plugin) {
            $slug = (string) ($plugin['slug'] ?? '');
            if ($slug !== '' && in_array($slug, $present, true)) {
                $result[$slug] = self::installedVersion($manager, $slug);
            }
        }

        return $result;
    }

    /**
     * The installed version, null when the plugin's files are not here. Installed means the files are here: Matomo's
     * installer only copies them, and Matomo lists a plugin as installed once it is activated, so a plugin installed
     * without activation used to show "Install" again (Peter, 2026-10-29).
     */
    public static function versionOf(string $slug): ?string
    {
        $manager = Manager::getInstance();

        return $manager->isPluginInFilesystem($slug) ? self::installedVersion($manager, $slug) : null;
    }

    private static function installedVersion(Manager $manager, string $slug): string
    {
        $onDisk = self::versionOnDisk($slug);
        if ($manager->isPluginInstalled($slug)) {
            // Loaded on purpose: Matomo counts a required plugin as present once it is loaded (the swap's dependency check).
            $info = $manager->loadPlugin($slug);

            // The files decide, not Matomo's plugin information: that comes from its eager cache, which can still hold the
            // version from before an update (backlog 16: the list kept updated plugins until a reload).
            return $onDisk ?? ($info ? $info->getVersion() : '0.0.0');
        }

        // Never activated: read its plugin.json rather than load code Matomo has not switched on.
        return $onDisk ?? '0.0.0';
    }

    private static function versionOnDisk(string $slug): ?string
    {
        $json = json_decode((string) @file_get_contents(Manager::getPluginDirectory($slug).'/plugin.json'), true);

        return is_array($json) && is_string($json['version'] ?? null) && $json['version'] !== '' ? $json['version'] : null;
    }

    /**
     * The installed plugins Matomo has not switched on: never activated, or switched off by an admin.
     *
     * @param  list<string>  $slugs  installed plugins (keys of versions())
     * @return list<string>
     */
    public static function inactive(array $slugs): array
    {
        $manager = Manager::getInstance();

        return array_values(array_filter($slugs, fn ($slug) => ! $manager->isPluginActivated($slug)));
    }

    /**
     * Installed marketplace plugins that are git checkouts or symlinks here (development setups).
     * Updating one through the connector would overwrite the working copy, so they are never updated.
     *
     * @param  list<array<mixed>>  $available
     * @return list<string>
     */
    public static function devCheckouts(array $available): array
    {
        $result = [];
        foreach (array_keys(self::versions($available)) as $slug) {
            if (self::isDevCheckout($slug)) {
                $result[] = $slug;
            }
        }

        return $result;
    }

    public static function isDevCheckout(string $slug): bool
    {
        try {
            // Checked before resolving: getPluginDirectory() returns the symlink's real path.
            if (is_link(PIWIK_INCLUDE_PATH.'/plugins/'.$slug)) {
                return true;
            }
            $dir = Manager::getPluginDirectory($slug);

            return $dir !== '' && file_exists($dir.'/.git');
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Log table columns the installed plugin's dimensions already have ("log_visit.<column>"), read from its Columns/
     * files, so an update only warns about new ones. Empty when the plugin is not there or unreadable.
     *
     * @return list<string>
     */
    public static function logColumns(string $slug): array
    {
        try {
            $dir = Manager::getPluginDirectory($slug).'/Columns';
            if (! is_dir($dir)) {
                return [];
            }
            $columns = [];
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS));
            foreach ($files as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $column = DbChangePlan::logColumn((string) file_get_contents($file->getPathname()));
                    if ($column !== null) {
                        $columns[] = $column;
                    }
                }
            }
            sort($columns);

            return array_values(array_unique($columns));
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * logColumns() for every installed plugin of the list.
     *
     * @param  list<array<mixed>>  $available
     * @return array<string, list<string>>
     */
    public static function allLogColumns(array $available): array
    {
        $result = [];
        foreach (array_keys(self::versions($available)) as $slug) {
            $result[$slug] = self::logColumns($slug);
        }

        return $result;
    }
}
