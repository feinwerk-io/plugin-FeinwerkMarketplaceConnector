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
        $this->hourly('refreshAfterEndedTrial', null, self::LOW_PRIORITY);
    }

    /** For tests: the current time. */
    public static ?int $now = null;

    /** How long after a trial's end the list is fetched hourly; later the daily task is enough. */
    public const ENDED_TRIAL_WINDOW = 30 * 86400;

    /**
     * Peter, 2026-11-23 ("ja, bau das stündliche Nachladen"): while a trial has ended (within ENDED_TRIAL_WINDOW), fetch the
     * list every hour, so a purchase on the store website reaches the plugins without a licence check of their own
     * (they read Licensing) within an hour instead of the next day. Only when connected, not when the list is less than
     * an hour old; Licensing::sync then clears the trial and posts the licensesChanged event. Never throws.
     */
    public function refreshAfterEndedTrial(): void
    {
        try {
            $settings = StaticContainer::get(SystemSettings::class);
            $url = MarketplaceClientFactory::url();
            $token = (string) $settings->instanceToken->getValue();
            $now = self::$now ?? time();
            if ($url === '' || $token === '' || ! Licensing::trialEndedWithin(self::ENDED_TRIAL_WINDOW, $now)) {
                return;
            }
            $cache = new MarketplaceCache(MarketplaceClientFactory::make($url, $token), new OptionCacheStore());
            $fetchedAt = $cache->cached()->fetchedAt;
            if ($fetchedAt !== null && $now - $fetchedAt < 3300) {
                return;   // fetched within the hour (daily task, Refresh or a click)
            }
            $refreshed = $cache->refresh();   // never throws; a failure stays in the cache with its kind
            if ($refreshed->error === null) {
                Licensing::sync($refreshed->plugins);
            } else {
                MarketplaceLog::failure('the hourly list refresh after an ended trial', new \RuntimeException($refreshed->error));
            }
        } catch (\Throwable $e) {
            MarketplaceLog::failure('the hourly list refresh after an ended trial', $e);
        }
    }

    /** Finish a Matomo major change: retry hourly for a day, then daily, until every plugin is switched. */
    public function runPendingSwap(): void
    {
        if (MatomoForWordPress::detected()) {
            return;   // WordPress updates the plugins after a Matomo update there
        }
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
            WordPressMode::forget();   // no contact before connect: nothing of ours stays in WordPress's update list

            return;
        }

        UpgradeWatcher::observe();

        try {
            $client = MarketplaceClientFactory::make($url, $token);

            // Warm the persistent cache so the admin screen stays instant and current.
            $refreshed = (new MarketplaceCache($client, new OptionCacheStore()))->refresh();
            $available = $refreshed->plugins;
            // Trial ends the paid plugins ask about (Licensing): remembered while the list still shows them.
            Licensing::sync($available);
            // Matomo for WordPress: the updates of the plugins installed in WordPress go into WordPress's own list.
            if (MatomoForWordPress::detected()) {
                WordPressMode::refreshUpdates($client);
            }
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
            // Store contract 2026-11-17: `installed` names every plugin of the list just fetched that is here (switched
            // off, development checkouts and the connector included), so the store may forget the others. Not from a
            // cached list: it may be older than the store's answer.
            if ($refreshed->error === null) {
                $env['installed_complete'] = true;
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
            // Matomo for WordPress: WordPress updates the plugins there, never the connector (MatomoForWordPress).
            if (! MatomoForWordPress::detected()) {
                $this->applyAutoUpdates($client, $updates, $autoApply, array_column($installed, 'version', 'slug'));
            }
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
            // A beta is installed only by a click (Peter, 2026-11-16: Matomo 6 betas, never by the daily auto-update).
            // Without a licence (an ended trial, a lapsed licence) the store refuses the download: no daily refusal.
            if (($update['channel'] ?? null) === 'beta' || ! empty($update['needs_license'])) {
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
