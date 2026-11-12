<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/**
 * Finds the database migrations a plugin update would run, by reading the downloaded ZIP:
 * Matomo runs a plugin's `Updates/<version>.php` for every version above the installed one
 * up to the new one. Updates that bring such files are never applied automatically.
 */
final class DbChangeDetector
{
    /**
     * @return list<string> versions of the update files between the two versions, sorted;
     *                      empty if the update has no migrations
     */
    public static function migrations(string $zipBytes, string $installedVersion, string $newVersion): array
    {
        $path = tempnam(sys_get_temp_dir(), 'fwmc_');
        if ($path === false || file_put_contents($path, $zipBytes) === false) {
            throw new \RuntimeException('Could not inspect the downloaded plugin.');
        }

        try {
            $zip = new \ZipArchive();
            if ($zip->open($path) !== true) {
                throw new \RuntimeException('The downloaded plugin is not a readable ZIP.');
            }

            $versions = [];
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = (string) $zip->getNameIndex($i);
                if (! preg_match('#(?:^|/)Updates/([^/]+)\.php$#', $name, $m)) {
                    continue;
                }
                $version = $m[1];
                if (version_compare($version, $installedVersion, '>') && version_compare($version, $newVersion, '<=')) {
                    $versions[] = $version;
                }
            }
            $zip->close();

            usort($versions, 'version_compare');

            return array_values(array_unique($versions));
        } finally {
            @unlink($path);
        }
    }

    /**
     * Log table columns the build's dimensions add ("log_visit.<column>", see DbChangePlan::logColumn).
     *
     * @return list<string>
     */
    public static function columns(string $zipBytes): array
    {
        $path = tempnam(sys_get_temp_dir(), 'fwmc_');
        if ($path === false || file_put_contents($path, $zipBytes) === false) {
            throw new \RuntimeException('Could not inspect the downloaded plugin.');
        }

        try {
            $zip = new \ZipArchive();
            if ($zip->open($path) !== true) {
                throw new \RuntimeException('The downloaded plugin is not a readable ZIP.');
            }

            $columns = [];
            for ($i = 0; $i < $zip->numFiles; $i++) {
                if (preg_match('#(?:^|/)Columns/.+\.php$#', (string) $zip->getNameIndex($i))) {
                    $column = DbChangePlan::logColumn((string) $zip->getFromIndex($i));
                    if ($column !== null) {
                        $columns[] = $column;
                    }
                }
            }
            $zip->close();
            sort($columns);

            return array_values(array_unique($columns));
        } finally {
            @unlink($path);
        }
    }
}
