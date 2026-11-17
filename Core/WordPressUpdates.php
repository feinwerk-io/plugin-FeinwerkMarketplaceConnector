<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/**
 * WordPress mode (Peter, 2026-11-17: "1-5 umsetzen"; docs/dev/wordpress-mode-plan.md): in Matomo for WordPress each
 * Feinwerk plugin is a WordPress plugin `<Plugin>/<Plugin>.php`, and its updates belong in WordPress's own update list.
 * The store answers POST /api/v1/wordpress/updates; this turns that answer into WordPress's update entries. The signed
 * download URL lives about 10 minutes, so the entry carries a marker instead and the connector asks the store for a
 * fresh URL only when WordPress starts the download. Pure.
 */
final class WordPressUpdates
{
    public const PACKAGE_PREFIX = 'feinwerk-package://';

    /** Never offered for WordPress: the login belongs to WordPress there (Peter, 2026-11-17). */
    private const NEVER = ['FeinwerkAuthSaml'];

    /**
     * @param  list<array<mixed>>  $data  the store's `data`
     * @param  array<string, string>  $installed  slug => version of the Feinwerk plugins installed as WordPress plugins
     * @param  array<string, list<string>>  $installedColumns  slug => log table columns the installed build has
     * @return array<string, array<string, mixed>> "<Plugin>/<Plugin>.php" => entry
     */
    public static function map(array $data, array $installed, array $installedColumns = []): array
    {
        $updates = [];
        foreach ($data as $entry) {
            $slug = is_array($entry) ? ($entry['slug'] ?? null) : null;
            $version = is_array($entry) ? ($entry['new_version'] ?? null) : null;
            if (! is_string($slug) || preg_match('/^Feinwerk[A-Za-z0-9]{1,79}$/', $slug) !== 1 || in_array($slug, self::NEVER, true)
                || ! is_string($version) || ! self::isVersion($version) || ! isset($installed[$slug])
                || version_compare($version, $installed[$slug], '<=')) {
                continue;
            }
            $file = $slug.'/'.$slug.'.php';
            $updates[$file] = [
                'slug' => $slug,
                'plugin' => $file,
                'new_version' => $version,
                'requires' => self::text($entry['requires_wp'] ?? null),
                'requires_php' => self::text($entry['requires_php'] ?? null),
                'tested' => self::text($entry['tested'] ?? null),
                'url' => self::httpUrl($entry['url'] ?? null),
                'package' => self::PACKAGE_PREFIX.$slug.'/'.$version,
                'icons' => self::icons($entry['icons'] ?? null),
                'db_changes' => self::hasDbChanges($entry['db_changes'] ?? null, $installed[$slug], $version, $installedColumns[$slug] ?? []),
            ];
        }

        return $updates;
    }

    /**
     * Adds an entry without a package for every installed plugin the store offers for WordPress that has no update: in
     * WordPress's no_update list it gets the auto-update toggle and "View details" (E2E 2026-11-17). Plugins from the
     * Matomo Marketplace (not in $available) stay with Matomo Marketplace for WordPress.
     *
     * @param  array<string, array<string, mixed>>  $updates  map()
     * @param  array<string, string>  $installed
     * @param  list<string>  $available  slugs with `wordpress.available`
     * @return array<string, array<string, mixed>>
     */
    public static function withCurrent(array $updates, array $installed, array $available): array
    {
        foreach ($installed as $slug => $version) {
            $slug = (string) $slug;
            $file = $slug.'/'.$slug.'.php';
            if (isset($updates[$file]) || ! in_array($slug, $available, true) || in_array($slug, self::NEVER, true)
                || preg_match('/^Feinwerk[A-Za-z0-9]{1,79}$/', $slug) !== 1 || ! self::isVersion((string) $version)) {
                continue;
            }
            $updates[$file] = ['slug' => $slug, 'plugin' => $file, 'new_version' => (string) $version, 'requires' => '', 'requires_php' => '',
                'tested' => '', 'url' => '', 'package' => '', 'icons' => [], 'db_changes' => false];
        }

        return $updates;
    }

    /** @return array{0: string, 1: string}|null [slug, version] of a package marker, null for anything else */
    public static function fromPackage(mixed $package): ?array
    {
        if (! is_string($package) || ! str_starts_with($package, self::PACKAGE_PREFIX)) {
            return null;
        }
        $parts = explode('/', substr($package, strlen(self::PACKAGE_PREFIX)));
        if (count($parts) !== 2 || preg_match('/^Feinwerk[A-Za-z0-9]{1,79}$/', $parts[0]) !== 1 || ! self::isVersion($parts[1])) {
            return null;
        }

        return [$parts[0], $parts[1]];
    }

    private static function isVersion(string $version): bool
    {
        return preg_match('/^\d+\.\d+\.\d+(-[0-9A-Za-z.]+)?$/', $version) === 1;
    }

    private static function text(mixed $value): string
    {
        return is_string($value) && preg_match('/^[0-9A-Za-z. -]{1,40}$/', $value) === 1 ? $value : '';
    }

    private static function httpUrl(mixed $value): string
    {
        return is_string($value) && preg_match('#^https://#i', $value) === 1 ? $value : '';
    }

    /** @return array<string, string> */
    private static function icons(mixed $icons): array
    {
        $out = [];
        foreach (is_array($icons) ? ['1x', '2x'] : [] as $size) {
            $url = self::httpUrl($icons[$size] ?? null);
            if ($url !== '') {
                $out[$size] = $url;
            }
        }

        return $out;
    }

    /**
     * Whether the update changes the database here (HARD RULE 8): update files after the installed version or log table
     * columns the installed build lacks, as on the Manage tab (DbChangePlan). An answer without that shape counts as a
     * change (fail closed): WordPress then leaves the update to a click.
     *
     * @param  list<string>  $installedColumns
     */
    private static function hasDbChanges(mixed $changes, string $installed, string $new, array $installedColumns): bool
    {
        if (! is_array($changes) || ! is_array($changes['migrations'] ?? null) || ! is_array($changes['log_columns'] ?? null)) {
            return true;
        }
        // An entry DbChangePlan cannot read (an object, a number, a word) would be dropped there: count it as a change.
        foreach ($changes['migrations'] as $migration) {
            if (! is_string($migration) || ! self::isVersion($migration)) {
                return true;
            }
        }
        foreach ($changes['log_columns'] as $column) {
            if (! is_string($column) || preg_match('/^log_[a-z_]+\.[A-Za-z0-9_]+$/', $column) !== 1) {
                return true;
            }
        }
        $plan = DbChangePlan::for($installed, $new, $changes, $installedColumns);

        return $plan !== null && ($plan['migrations'] !== [] || $plan['columns'] !== []);
    }

    /**
     * WordPress's "View details" (plugins_api) from the store's GET /api/v1/wordpress/plugins/{slug}: plain values and
     * the store's sections (WordPress filters them with wp_kses), the package marker instead of the signed URL. Null
     * for an answer without a valid slug and version.
     *
     * @param  array<mixed>  $details
     * @return array<string, mixed>|null
     */
    public static function details(array $details): ?array
    {
        $slug = $details['slug'] ?? null;
        $version = $details['version'] ?? null;
        if (! is_string($slug) || preg_match('/^Feinwerk[A-Za-z0-9]{1,79}$/', $slug) !== 1 || in_array($slug, self::NEVER, true)
            || ! is_string($version) || ! self::isVersion($version)) {
            return null;
        }
        $sections = [];
        foreach (['description', 'installation', 'changelog', 'faq'] as $name) {
            if (is_string($details['sections'][$name] ?? null) && $details['sections'][$name] !== '') {
                $sections[$name] = $details['sections'][$name];
            }
        }
        $plain = fn (mixed $value) => is_string($value) ? mb_substr(trim(strip_tags($value)), 0, 200) : '';

        return [
            'name' => $plain($details['name'] ?? null) ?: $slug,
            'slug' => $slug,
            'version' => $version,
            'author' => $plain($details['author'] ?? null),
            'homepage' => self::httpUrl($details['homepage'] ?? null),
            'requires' => self::text($details['requires_wp'] ?? null),
            'requires_php' => self::text($details['requires_php'] ?? null),
            'tested' => self::text($details['tested'] ?? null),
            'last_updated' => $plain($details['last_updated'] ?? null),
            'sections' => $sections,
            'icons' => self::icons($details['icons'] ?? null),
            'download_link' => self::PACKAGE_PREFIX.$slug.'/'.$version,
        ];
    }
}
