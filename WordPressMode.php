<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions\MarketplaceLicenseException;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MarketplaceApiClient;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\WordPressUpdates;
use Piwik\Plugins\FeinwerkMarketplaceConnector\WordPress\Platform;
use Piwik\Plugins\FeinwerkMarketplaceConnector\WordPress\WpPlatform;
use Piwik\Piwik;
use Piwik\Version;

/**
 * WordPress mode (Peter, 2026-11-17: "1-5 umsetzen"; docs/dev/wordpress-mode-plan.md). In Matomo for WordPress the
 * plugins the store offers as WordPress plugins (`wordpress.available`, never Auth SAML) are installed and updated with
 * WordPress's own installer from the store's signed package, and their updates go into WordPress's update list
 * (WordPress\Hooks). Not on a multisite (Peter: no multisite for now). Everything else keeps the guard
 * (MatomoForWordPress).
 */
final class WordPressMode
{
    /** For tests: a stand-in instead of WordPress. */
    public static ?Platform $platform = null;

    public static function platform(): Platform
    {
        return self::$platform ??= new WpPlatform();
    }

    /** Matomo for WordPress on a single site. Never throws. */
    public static function active(): bool
    {
        try {
            return MatomoForWordPress::detected() && ! self::platform()->isMultisite();
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * @param  list<array<mixed>>  $plugins  the cached store list
     * @return list<string> the plugins installed and updated through WordPress here; empty outside the WordPress mode
     */
    public static function availableSlugs(array $plugins): array
    {
        if (! self::active()) {
            return [];
        }
        $slugs = [];
        foreach ($plugins as $plugin) {
            $slug = is_array($plugin) ? ($plugin['slug'] ?? null) : null;
            if (is_string($slug) && $slug !== 'FeinwerkAuthSaml' && (($plugin['wordpress'] ?? [])['available'] ?? false) === true) {
                $slugs[] = $slug;
            }
        }

        return $slugs;
    }

    public static function isAvailable(string $slug): bool
    {
        return in_array($slug, self::availableSlugs(PendingUpdates::cachedPlugins()), true);
    }

    /**
     * What stops an install or update through WordPress here, as the sentence for the admin; null when nothing does.
     * Runs before anything that cannot be undone (a trial is started only once).
     */
    public static function refusal(string $slug): ?string
    {
        $name = PluginOperations::displayName($slug);
        // Two copies are what broke Matomo there (guard check 2026-11-17): a copy in Matomo's own plugin folder goes first.
        if (defined('PIWIK_INCLUDE_PATH') && is_dir(PIWIK_INCLUDE_PATH.'/plugins/'.$slug)) {
            return Piwik::translate('FeinwerkMarketplaceConnector_WordPressCopy', [$name]);
        }
        $platform = self::platform();
        if ($platform->isDevCheckout($slug)) {   // HARD RULE 7
            return Piwik::translate('FeinwerkMarketplaceConnector_DevCheckoutRefused', [$name]);
        }
        $why = $platform->cannotInstall();
        if ($why !== null) {
            return Piwik::translate($why === 'filesystem' ? 'FeinwerkMarketplaceConnector_WordPressNoFilesystem' : 'FeinwerkMarketplaceConnector_WordPressNoCapability', [$name]);
        }

        return null;
    }

    /**
     * Installs or updates one plugin through WordPress (a click), exactly at $version. The shape of
     * PluginOperations::installOne; never throws, whatever WordPress does (review 2026-11-17).
     *
     * @return array{kind: string, installed: string, message: string}
     */
    public static function install(string $slug, string $version, ?bool $activate): array
    {
        $name = PluginOperations::displayName($slug);
        try {
            return self::installOrUpdate($slug, $version, $activate, $name);
        } catch (\Throwable $e) {
            MarketplaceLog::failure('the WordPress install of '.$slug, $e);

            return ['kind' => 'failed', 'installed' => $slug, 'message' => Piwik::translate('FeinwerkMarketplaceConnector_InstallFailed', [$name, $e->getMessage()])];
        }
    }

    /** @return array{kind: string, installed: string, message: string} */
    private static function installOrUpdate(string $slug, string $version, ?bool $activate, string $name): array
    {
        $platform = self::platform();
        $file = $slug.'/'.$slug.'.php';
        $current = $platform->installedPlugins()[$slug] ?? null;
        // A second click or an older page: nothing to download (review 2026-11-17); an Install click still switches it on.
        if ($current !== null && $version !== '' && version_compare($current, $version, '>=')) {
            if ($activate === true && ! $platform->isActive($file)) {
                $error = $platform->activate($file);
                if ($error !== null) {
                    return self::notActivated($slug, $name, $current, $error);
                }

                return ['kind' => 'done', 'installed' => $slug, 'message' => Piwik::translate('FeinwerkMarketplaceConnector_WordPressActivated', [$name, $current])];
            }

            return ['kind' => 'done', 'installed' => $slug, 'message' => Piwik::translate('FeinwerkMarketplaceConnector_AlreadyInstalled', [$name, $current])];
        }
        $refusal = self::refusal($slug);
        if ($refusal !== null) {
            return ['kind' => 'wordpress', 'installed' => $slug, 'message' => $refusal];
        }

        try {
            $details = PluginOperations::client()->wordpressPlugin($slug, self::lang(), self::matomoEnv());
        } catch (MarketplaceLicenseException $e) {
            return ['kind' => 'refused', 'installed' => $slug, 'message' => Piwik::translate('FeinwerkMarketplaceConnector_InstallRefused', [$name])];
        }
        // HARD RULE 8: only the version the admin saw (and its database changes), never a newer build in between.
        if ($version !== '' && $details['version'] !== $version) {
            PluginOperations::refresh();   // so that reloading shows what the store offers now (review 2026-11-17)

            return ['kind' => 'failed', 'installed' => $slug, 'message' => Piwik::translate('FeinwerkMarketplaceConnector_WordPressVersionMoved', [$name, $details['version']])];
        }

        if ($current !== null) {
            // WordPress's own update path: the plugin stays switched on, a failed update restores the old folder.
            $error = $platform->update($slug, $details['version']);
            if ($error !== null) {
                return ['kind' => 'failed', 'installed' => $slug, 'message' => Piwik::translate('FeinwerkMarketplaceConnector_InstallFailed', [$name, $error])];
            }

            return ['kind' => 'done', 'installed' => $slug, 'message' => Piwik::translate('FeinwerkMarketplaceConnector_UpdateDone', [$name, $details['version']])];
        }

        $error = $platform->install($details['package']);
        if ($error !== null) {
            return ['kind' => 'failed', 'installed' => $slug, 'message' => Piwik::translate('FeinwerkMarketplaceConnector_InstallFailed', [$name, $error])];
        }
        if ($activate !== false) {
            $error = $platform->activate($file);
            if ($error !== null) {
                return self::notActivated($slug, $name, $details['version'], $error);
            }
        }

        return ['kind' => 'done', 'installed' => $slug, 'message' => Piwik::translate('FeinwerkMarketplaceConnector_InstallDone', [$name, $details['version']])];
    }

    /** @return array{kind: string, installed: string, message: string} */
    private static function notActivated(string $slug, string $name, string $version, string $error): array
    {
        return ['kind' => 'wordpress', 'installed' => $slug, 'message' => Piwik::translate('FeinwerkMarketplaceConnector_WordPressNotActivated', [$name, $version, $error])];
    }

    /**
     * Where the browser goes after an install through WordPress: Matomo for WordPress runs its installer for a new
     * plugin only on a wp-admin page and sends Matomo pages to WordPress's dashboard until then (E2E 2026-11-17), so the
     * way back to $target leads through wp-admin (WordPress\Hooks::onReturn).
     */
    public static function backUrl(string $target): string
    {
        return self::platform()->returnUrl($target);
    }

    /** The daily task and Refresh: the updates of the Feinwerk plugins installed in WordPress, for WordPress's list. Never throws. */
    public static function refreshUpdates(MarketplaceApiClient $client): void
    {
        try {
            if (! self::active()) {
                self::forget();   // a multisite keeps the guard: no entries of ours there

                return;
            }
            $platform = self::platform();
            $installed = array_filter($platform->installedPlugins(), fn ($slug) => ! $platform->isDevCheckout((string) $slug), ARRAY_FILTER_USE_KEY);
            $data = $installed === [] ? [] : $client->wordpressUpdates(
                $platform->versions() + self::matomoEnv(),
                array_map(fn ($slug, $version) => ['slug' => $slug, 'version' => $version], array_keys($installed), $installed)
            );
            $columns = [];
            foreach (array_keys($installed) as $slug) {
                $columns[$slug] = InstalledMarketplacePlugins::logColumns((string) $slug);
            }
            // Current plugins too: WordPress gives them the auto-update toggle and "View details" (E2E 2026-11-17).
            $platform->saveUpdates(WordPressUpdates::withCurrent(WordPressUpdates::map($data, $installed, $columns), $installed,
                self::availableSlugs(PendingUpdates::cachedPlugins())));
        } catch (\Throwable $e) {
            MarketplaceLog::failure('the WordPress update check', $e);
        }
    }

    /** Without a token nothing of ours stays in WordPress's update list (no contact before connect). Never throws. */
    public static function forget(): void
    {
        try {
            if (MatomoForWordPress::detected()) {
                self::platform()->saveUpdates([]);
            }
        } catch (\Throwable $e) {
            MarketplaceLog::failure('clearing the WordPress update entries', $e);
        }
    }

    /**
     * A fresh signed URL for a package marker of WordPress's update list (WordPress\Hooks, at download time), only for
     * exactly the marker's version.
     *
     * @throws \RuntimeException with the sentence WordPress shows
     */
    public static function packageUrl(string $marker): string
    {
        $parsed = WordPressUpdates::fromPackage($marker);
        if ($parsed === null) {
            throw new \RuntimeException('Not a Feinwerk package.');
        }
        [$slug, $version] = $parsed;
        try {
            $details = self::storeDetails($slug);
        } catch (MarketplaceLicenseException $e) {
            throw new \RuntimeException(Piwik::translate('FeinwerkMarketplaceConnector_InstallRefused', [PluginOperations::displayName($slug)]));
        } catch (\Throwable $e) {
            MarketplaceLog::failure('the WordPress download of '.$slug, $e);   // under WP-cron the only trace
            throw new \RuntimeException(Piwik::translate('FeinwerkMarketplaceConnector_InstallFailed', [PluginOperations::displayName($slug), $e->getMessage()]));
        }
        if ($details === null) {
            throw new \RuntimeException(Piwik::translate('FeinwerkMarketplaceConnector_WordPressNotConnected'));
        }
        if (self::platform()->isDevCheckout($slug)) {
            throw new \RuntimeException(Piwik::translate('FeinwerkMarketplaceConnector_DevCheckoutRefused', [PluginOperations::displayName($slug)]));
        }
        if ($details['version'] !== $version) {   // HARD RULE 8: a newer build may change the database
            PluginOperations::refresh();   // WordPress's list then names the version the store offers now
            throw new \RuntimeException(Piwik::translate('FeinwerkMarketplaceConnector_WordPressVersionMoved', [PluginOperations::displayName($slug), $details['version']]));
        }

        return $details['package'];
    }

    /**
     * WordPress's "View details" (WordPress\Hooks::onPluginsApi). Null when not connected or the store has no WordPress
     * version of it.
     *
     * @return array<string, mixed>|null
     */
    public static function pluginInfo(string $slug): ?array
    {
        try {
            return self::pluginDetails($slug);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * The same for WordPress\Hooks::onPluginsApi, with the reason as the exception's sentence.
     *
     * @return array<string, mixed>
     * @throws \RuntimeException
     */
    public static function pluginDetails(string $slug): array
    {
        $name = PluginOperations::displayName($slug);
        try {
            $details = self::storeDetails($slug);
        } catch (MarketplaceLicenseException $e) {
            throw new \RuntimeException(Piwik::translate('FeinwerkMarketplaceConnector_InstallRefused', [$name]));
        } catch (\Throwable $e) {
            MarketplaceLog::failure('the WordPress details of '.$slug, $e);
            throw new \RuntimeException(Piwik::translate('FeinwerkMarketplaceConnector_WordPressDetailsFailed', [$name, $e->getMessage()]));
        }
        if ($details === null) {
            throw new \RuntimeException(Piwik::translate('FeinwerkMarketplaceConnector_WordPressNotConnected'));
        }
        $info = WordPressUpdates::details($details);
        if ($info === null) {
            throw new \RuntimeException(Piwik::translate('FeinwerkMarketplaceConnector_WordPressDetailsFailed', [$name, 'no details']));
        }

        return $info;
    }

    /**
     * The store's WordPress details; null without a token (nothing goes to the store then).
     *
     * @return array<string, mixed>|null
     */
    private static function storeDetails(string $slug): ?array
    {
        if (! self::active() || PluginOperations::settings()['token'] === '') {
            return null;
        }

        return PluginOperations::client()->wordpressPlugin($slug, self::lang(), self::matomoEnv());
    }

    /** @return array{matomo_version: string, php_version: string} */
    private static function matomoEnv(): array
    {
        return ['matomo_version' => Version::VERSION, 'php_version' => PHP_VERSION];
    }

    private static function lang(): string
    {
        try {
            return str_starts_with((string) \Piwik\Plugins\LanguagesManager\LanguagesManager::getLanguageCodeForCurrentUser(), 'de') ? 'de' : 'en';
        } catch (\Throwable $e) {
            return 'en';
        }
    }
}
