<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Container\StaticContainer;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\AutoApplyPolicy;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\DbChangeDetector;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MarketplaceCache;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MarketplaceApiClient;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MatomoMajor;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\SwapSchedule;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Http\OptionCacheStore;
use Piwik\SettingsPiwik;
use Piwik\Version;

class Tasks extends \Piwik\Plugin\Tasks
{
    public function schedule()
    {
        $this->daily('checkForUpdates', null, self::LOW_PRIORITY);
        $this->hourly('runPendingSwap', null, self::LOW_PRIORITY);
    }

    /** Finish a Matomo major change: retry hourly for a day, then daily, until every plugin is switched. */
    public function runPendingSwap(): void
    {
        try {
            $pending = UpgradeWatcher::pending();
            if ($pending !== null && SwapSchedule::shouldRun($pending, time())) {
                UpgradeSwapper::run();
            }
        } catch (\Throwable $e) {
            // A scheduled task must never throw.
        }
    }

    public function checkForUpdates(): void
    {
        $settings = StaticContainer::get(SystemSettings::class);
        $url = MarketplaceClientFactory::url();
        $token = (string) $settings->instanceToken->getValue();

        if ($url === '' || $token === '') {
            return;
        }

        UpgradeWatcher::observe();

        try {
            $client = MarketplaceClientFactory::make($url, $token);

            // Warm the persistent cache so the admin screen stays instant and current.
            $available = (new MarketplaceCache($client, new OptionCacheStore()))->refresh()->plugins;
            // After the list refresh: readiness asks about the Feinwerk plugins of the list just fetched.
            ReadinessCache::refresh($client);
            $installed = [];
            foreach (InstalledMarketplacePlugins::versions($available) as $slug => $version) {
                $installed[] = ['slug' => $slug, 'version' => $version];
            }
            HeldDbUpdates::prune(array_column($installed, 'version', 'slug'));
            ActivateLater::prune(array_column($installed, 'slug'));

            $env = [
                'matomo_version' => Version::VERSION,
                'php_version' => PHP_VERSION,
                'site_url' => SettingsPiwik::getPiwikUrl(),
            ];
            if (($users = $this->countUsers()) !== null) {
                $env['user_count'] = $users;
            }

            $updates = $client->checkUpdates($env, $installed);

            if (empty($updates)) {
                return;
            }

            // Pending (non-auto-applied) updates are surfaced to admins by the menu badge and the
            // admin notice (FeinwerkMarketplaceConnector::onTriggerAdminNotifications), both read
            // from the cache refreshed above — a notification raised here would never be seen.
            $autoApply = AutoApplyPolicy::effective(
                $settings->autoApplyPlugins->getValue(),
                'FeinwerkMarketplaceConnector',
                InstalledMarketplacePlugins::devCheckouts($available)
            );
            $pending = UpgradeWatcher::pending();
            if ($pending !== null) {
                // The swap owns the plugins it is still switching; every other plugin keeps updating.
                $currentMajor = MatomoMajor::of(Version::VERSION);
                if ($currentMajor === null) {
                    return;
                }
                $swapSlugs = UpgradeWatcher::skippedByAutoUpdate(array_keys(array_column($installed, 'version', 'slug')));
                // Unknown which plugins the swap owns: no automatic update this time (fail closed).
                $autoApply = $swapSlugs === null ? [] : array_values(array_diff($autoApply, $swapSlugs));
            }
            $this->applyAutoUpdates($client, $updates, $autoApply, array_column($installed, 'version', 'slug'));
        } catch (\Throwable $e) {
            // Any failure (unreachable, auth, rejected request): logged, never thrown. A scheduled task must never throw.
            MarketplaceLog::failure('the daily update check', $e);
        }
    }

    /**
     * @param  list<array<mixed>>  $updates
     * @param  list<string>  $autoApply
     * @param  array<string,string>  $installedVersions
     * @return list<string> slugs successfully applied
     */
    private function applyAutoUpdates(MarketplaceApiClient $client, array $updates, array $autoApply, array $installedVersions): array
    {
        $applied = [];

        foreach ($updates as $update) {
            $slug = (string) ($update['slug'] ?? '');
            if ($slug === '' || ! in_array($slug, $autoApply, true)) {
                continue;
            }

            try {
                $version = (string) $update['latest'];
                $artifact = $client->download($slug, $version);

                // Database changes never run unattended (update files, new log table columns): hold the update back and
                // warn the admins (menu badge + notice) until someone installs it and Matomo runs the migration.
                $newColumns = array_diff(DbChangeDetector::columns($artifact->bytes), InstalledMarketplacePlugins::logColumns($slug));
                if (DbChangeDetector::migrations($artifact->bytes, $installedVersions[$slug] ?? '0.0.0', $version) !== [] || $newColumns !== []) {
                    HeldDbUpdates::hold($slug, $version);

                    continue;
                }

                // An update keeps the plugin switched on or off as it was, like a click (an off plugin used to be switched on).
                (new PluginInstallerBridge())->install($artifact, \Piwik\Plugin\Manager::getInstance()->isPluginActivated($slug));
                HeldDbUpdates::release($slug);
                ActivateLater::afterInstall($slug);   // the swap gave up on it although it was active (RC-F1)
                $applied[] = $slug;
            } catch (\Throwable $e) {
                // Skip this one; it stays listed as an available update in the menu badge and notice.
                MarketplaceLog::failure('the automatic update of '.$slug, $e);
            }
        }

        return $applied;
    }

    /** Number of Matomo users, for seat checks on the marketplace. Never breaks the check. */
    private function countUsers(): ?int
    {
        try {
            return count((new \Piwik\Plugins\UsersManager\Model())->getUsersLogin());
        } catch (\Throwable $e) {
            return null;
        }
    }
}
