<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Container\StaticContainer;
use Piwik\Piwik;
use Piwik\Plugin\Manager;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto\CachedPlugins;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto\PluginStatusRow;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions\MarketplaceLicenseException;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MarketplaceApiClient;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MarketplaceCache;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\PluginDetailCache;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\PluginName;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\UpdateMapper;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Http\OptionCacheStore;
use Piwik\Updater;

/**
 * What the Feinwerk Plugins screen and the console commands (Commands/, 5.4.0) do alike: refresh the list, map it into
 * rows, install or update one plugin. The screen's click and `feinwerkmarketplaceconnector:update` run the same checks
 * (development checkout, already installed, license refusal, activation state, held database updates).
 */
final class PluginOperations
{
    /** @return array{url: string, token: string} */
    public static function settings(): array
    {
        $settings = StaticContainer::get(SystemSettings::class);

        return [
            'url' => MarketplaceClientFactory::url(),
            'token' => (string) $settings->instanceToken->getValue(),
        ];
    }

    /** An instance token is set: before that nothing may go to the store (NoContactBeforeConnectTest). */
    public static function connected(): bool
    {
        $settings = self::settings();

        return $settings['url'] !== '' && $settings['token'] !== '';
    }

    public static function client(): MarketplaceApiClient
    {
        $settings = self::settings();

        return MarketplaceClientFactory::make($settings['url'], $settings['token']);
    }

    public static function cache(): MarketplaceCache
    {
        return new MarketplaceCache(self::client(), new OptionCacheStore());
    }

    /**
     * The Refresh button: a new plugin list and, when the store answered, fresh plugin windows, readiness and the update
     * report. Never throws; the caller checks `->error`.
     */
    public static function refresh(): CachedPlugins
    {
        $refreshed = self::cache()->refresh(); // never throws

        // Only when the marketplace answered: otherwise a dead upstream would make the admin wait out more timeouts.
        if ($refreshed->error === null) {
            $client = self::client();
            // A new release changes the plugin window too: its cached answers are outdated now.
            (new PluginDetailCache($client, new OptionCacheStore()))->invalidateAll(); // never throws
            ReadinessCache::refresh($client); // never throws
            try {
                $env = [
                    'matomo_version' => \Piwik\Version::VERSION,
                    'php_version' => PHP_VERSION,
                ];
                $model = new \Piwik\Plugins\UsersManager\Model();
                $env['user_count'] = count($model->getUsersLogin());
                $client->checkUpdates($env, []);
            } catch (\Throwable $e) {
                // Reporting is best-effort; refresh must stay resilient.
            }
        }

        return $refreshed;
    }

    /**
     * The rows of the Manage tab for a plugin list.
     *
     * @return list<PluginStatusRow>
     */
    public static function rows(CachedPlugins $cached, string $lang = 'en'): array
    {
        $installed = InstalledMarketplacePlugins::versions($cached->plugins);

        return (new UpdateMapper($lang))->map(
            $cached->plugins,
            $installed,
            InstalledMarketplacePlugins::devCheckouts($cached->plugins),
            UpgradeWatcher::switching(array_keys($installed)),
            InstalledMarketplacePlugins::allLogColumns($cached->plugins),
            InstalledMarketplacePlugins::inactive(array_keys($installed))
        );
    }

    /**
     * Downloads and installs one plugin (one click, one step of "update several at once", one plugin of a console
     * command). Never throws.
     *
     * @param  bool|null  $activate  true: activate; false: leave it for plugin:activate on the console; null: an update
     *     keeps the plugin switched on or off as it was (review 5.1.0: an update switched on what the admin had turned off)
     * @return array{kind: string, installed: string, message: string} kind: done, db (a database update is pending now),
     *     activate (left for plugin:activate on the console), dev (development checkout, refused), refused (403), failed
     */
    public function installOne(string $slug, string $version, ?bool $activate): array
    {
        if (InstalledMarketplacePlugins::isDevCheckout($slug)) {
            return ['kind' => 'dev', 'installed' => $slug, 'message' => Piwik::translate('FeinwerkMarketplaceConnector_DevCheckoutRefused', [self::displayName($slug)])];
        }
        $current = self::alreadyInstalled($slug, $version);
        if ($current !== null) {
            return $current;
        }

        try {
            $artifact = self::client()->download($slug, $version);
            $alreadyActive = Manager::getInstance()->isPluginActivated($slug);
            $installed = (new PluginInstallerBridge())->install($artifact, $activate === null ? $alreadyActive : ($activate || $alreadyActive));
            HeldDbUpdates::release($slug);   // a held automatic update the admin has now installed
            $done = Piwik::translate('FeinwerkMarketplaceConnector_InstallDone', [self::displayName($slug), $version]);

            if ($activate === false && ! $alreadyActive) {
                return ['kind' => 'activate', 'installed' => $installed, 'message' => $done];
            }
            // Active before the Matomo update, but the swap had to give up on it (license, failed installs): on again (RC-F1).
            ActivateLater::afterInstall($slug);

            return ['kind' => self::hasPendingDbUpdates() ? 'db' : 'done', 'installed' => $installed, 'message' => $done];
        } catch (MarketplaceLicenseException $e) {
            // A 403 on the download: no license for this instance, or a revoked instance token (also 403, no stable
            // signal tells them apart), so the translated sentence names both. The marketplace's English one is not shown.
            return ['kind' => 'refused', 'installed' => $slug, 'message' => Piwik::translate('FeinwerkMarketplaceConnector_InstallRefused', [self::displayName($slug)])];
        } catch (\Throwable $e) {
            return ['kind' => 'failed', 'installed' => $slug, 'message' => Piwik::translate('FeinwerkMarketplaceConnector_InstallFailed', [self::displayName($slug), $e->getMessage()])];
        }
    }

    /**
     * Installed at this version or newer: a "done" result without a download (backlog 16: a stale page's second "update
     * all" ended as an error). Null otherwise.
     *
     * @return array{kind: string, installed: string, message: string}|null
     */
    public static function alreadyInstalled(string $slug, string $version): ?array
    {
        $installed = self::installedVersionOf($slug);   // guarded: installOne never throws (review 5.2.2)
        if ($installed === null || $version === '' || version_compare($installed, $version, '<')) {
            return null;
        }

        return ['kind' => 'done', 'installed' => $slug,
            'message' => Piwik::translate('FeinwerkMarketplaceConnector_AlreadyInstalled', [self::displayName($slug), $installed])];
    }

    public static function installedVersionOf(string $slug): ?string
    {
        try {
            return InstalledMarketplacePlugins::versionOf($slug);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /** The name people read: the store's display name from the cached list, else the technical name as words. */
    public static function displayName(string $slug): string
    {
        foreach (PendingUpdates::cachedPlugins() as $plugin) {
            if (($plugin['slug'] ?? null) === $slug) {
                return PluginName::of($plugin);
            }
        }

        return PluginName::spaced($slug);
    }

    /**
     * Does the just-installed plugin (or anything else) leave Matomo with a pending database migration? In the browser
     * CoreUpdater then intercepts the next request; on the console it is `core:update`.
     */
    public static function hasPendingDbUpdates(): bool
    {
        try {
            return ! empty((new Updater())->getComponentUpdates());
        } catch (\Throwable $e) {
            return false;
        }
    }

    /** @return list<string> the components Matomo has a database update pending for; empty on any error */
    public static function pendingComponents(): array
    {
        try {
            return array_map('strval', array_keys((new Updater())->getComponentUpdates() ?? []));
        } catch (\Throwable $e) {
            return [];
        }
    }
}
