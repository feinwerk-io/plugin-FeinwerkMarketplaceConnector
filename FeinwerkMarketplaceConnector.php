<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Common;
use Piwik\Concurrency\Lock;
use Piwik\Concurrency\LockBackend;
use Piwik\Container\StaticContainer;
use Piwik\Nonce;
use Piwik\Notification;
use Piwik\Notification\Manager as NotificationManager;
use Piwik\Piwik;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\BrowserUpdateGuard;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MatomoMajor;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\PluginName;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MarketplaceCache;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\ReadinessSummary;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\SwapWaiting;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Http\OptionCacheStore;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\UnloadedNotice;
use Piwik\Translation\Translator;
use Piwik\Version;

class FeinwerkMarketplaceConnector extends \Piwik\Plugin
{
    /** Admin pages where super users think about plugins — the only places the update notice shows. */
    private const NOTICE_MODULES = ['FeinwerkMarketplaceConnector', 'CorePluginsAdmin', 'Marketplace'];

    /** Set on activation: the welcome notice is still due (Peter, 2026-11-11, Matomo Marketplace listing). */
    private const WELCOME_OPTION = 'fwmc.welcome';

    public function registerEvents(): array
    {
        return [
            'AssetManager.getStylesheetFiles' => 'getStylesheetFiles',
            'Translate.getClientSideTranslationKeys' => 'getClientSideTranslationKeys',
            'Controller.triggerAdminNotifications' => 'onTriggerAdminNotifications',
            'CoreUpdater.update.end' => 'onCoreUpdated',
            'Request.dispatch' => 'onRequestDispatch',
            'TwoFactorAuth.requiresTwoFactorAuthentication' => 'onRequiresTwoFactorAuth',
            'UsersManager.deleteUser' => 'onDeleteUser',
            'Template.beforeContent' => 'onBeforeContent',
        ];
    }

    /** The recommendation banner on the Administration home page (Recommendation). Never throws. */
    public function onBeforeContent(&$out, $type = null, $module = null, $action = null): void
    {
        if ($type === 'admin' && $module === 'CoreAdminHome' && $action === 'home') {
            $out .= Recommendation::adminHomeMarkup();
        }
    }

    /** The feedback throttle of a deleted user (Feedback::allowed). */
    public function onDeleteUser($userLogin): void
    {
        try {
            \Piwik\Option::deleteLike(Feedback::userPrefix((string) $userLogin).'%');
        } catch (\Throwable $e) {
        }
    }

    /** Matomo calls this when the plugin is activated: a welcome notice is due once (notifyWelcome). */
    public function activate()
    {
        try {
            \Piwik\Option::set(self::WELCOME_OPTION, '1');
        } catch (\Throwable $e) {
        }
    }

    public function uninstall()
    {
        // Everything the connector stored (caches, swap state, held updates, feedback throttles). Matomo deletes the
        // plugin's settings itself; deactivating keeps all of it (Peter, 2026-11-11, UninstallTest).
        try {
            \Piwik\Option::deleteLike('fwmc.%');
        } catch (\Throwable $e) {
        }
    }

    /**
     * Persistent notices after an install that point to the console (Controller); they clear themselves once done.
     * The activation notice is one per plugin: NOTICE_ACTIVATE.'_'.<plugin>.
     */
    public const NOTICE_DB_UPDATE = 'FeinwerkMarketplaceConnector_dbUpdateRequired';
    public const NOTICE_ACTIVATE = 'FeinwerkMarketplaceConnector_activateOnConsole';
    public const NOTICE_DB_UPDATE_DONE = 'FeinwerkMarketplaceConnector_dbUpdateDone';

    /**
     * Components pending when this request's browser run of the database update was stopped; Controller::consoleUpdate
     * shows its page only then.
     *
     * @var ?list<string>
     */
    public static ?array $stoppedUpdate = null;

    /** A confirmed "run in the browser anyway" holds this lock until Matomo's update ends, at most this long. */
    public const BROWSER_RUN_TTL = 1800;

    /** The lock of the run this request lets through (released on CoreUpdater.update.end). */
    private static ?Lock $browserRun = null;

    /** Why a "run in the browser anyway" post was refused, shown on the console page (ConsoleUpdatePage). */
    public static ?string $browserRunNote = null;

    /**
     * After a major update Matomo shows its own red notice once for a Feinwerk plugin it could not load ("could not be
     * loaded as it has missing dependencies: ... requires Matomo <6.0.0-b1"). While the swap is pending, that notice is
     * removed when it is about a plugin the swap switches; the swap notice explains it. A notice about any other plugin
     * is never touched. Costs one option read per page when no swap is pending. Never throws.
     *
     * The arguments are references (Matomo passes them so): keepUpdatesOutOfTheBrowser() may send the request elsewhere.
     */
    public function onRequestDispatch(&$module = null, &$action = null, &$parameters = null): void
    {
        $this->keepUpdatesOutOfTheBrowser($module, $action, $parameters);
        $this->clearDoneConsoleNotices($module);

        try {
            if ($module === 'API' || Common::isPhpCliMode() || UpgradeWatcher::pending() === null) {
                return;
            }
            $notice = NotificationManager::getAllNotificationsToDisplay()[UnloadedNotice::ID] ?? null;
            $current = MatomoMajor::of(Version::VERSION);
            if ($notice === null || $current === null) {
                return;
            }
            // The plugins the swap is still switching (not what it gave up on, not development checkouts): the same
            // list that hides their update offer, so a notice about any other plugin stays.
            $feinwerk = array_values(array_filter(array_map(fn ($plugin) => (string) ($plugin['slug'] ?? ''), PendingUpdates::cachedPlugins())));
            $switching = UpgradeWatcher::switching(array_values(array_diff($feinwerk, [UpgradeWatcher::SELF])));
            // Matomo writes the notice while the plugins load, before the admin's language is set: usually in its
            // default language. Compare with the sentence in the admin's language, the default one and English.
            $translator = StaticContainer::get(Translator::class);
            foreach (array_unique([$translator->getCurrentLanguage(), $translator->getDefaultLanguage(), 'en']) as $language) {
                $sentence = fn (string $plugin, string $missing) => Piwik::translate(UnloadedNotice::SENTENCE, [$plugin, $missing], $language);
                if (UnloadedNotice::about((string) $notice->message, $switching, $sentence) !== null) {
                    NotificationManager::cancel(UnloadedNotice::ID);

                    return;
                }
            }
        } catch (\Throwable $e) {
            // A notice must never break a page.
        }
    }

    /**
     * B1 (Peter, 2026-10-21): on a large instance the button on Matomo's update page does not run the database update
     * in the browser while a Feinwerk component is pending; the request goes to Controller::consoleUpdate, which shows
     * the console command. It stops the whole run, a core update in it too (one run updates everything). Off with
     * `[FeinwerkMarketplaceConnector] large_instance = 0`. Never throws: on any error Matomo goes on as usual.
     */
    private function keepUpdatesOutOfTheBrowser(&$module, &$action, &$parameters): void
    {
        try {
            $flag = Common::getRequestVar('updateCorePlugins', '');
            // After its own redirect (action=) Matomo passes the action as false.
            if (! BrowserUpdateGuard::runsUpdates(is_string($module) ? $module : null, is_string($action) ? $action : '', $flag)
                || ! \Piwik\SettingsPiwik::isAutoUpdateEnabled() || ! InstanceSizeProbe::measure()['large']) {
                return;
            }
            $pending = (new \Piwik\Updater())->getComponentUpdates() ?? [];
            if (BrowserUpdateGuard::feinwerkComponents($pending, self::feinwerkLogColumns()) === [] || self::confirmedBrowserRun()) {
                return;
            }

            self::$stoppedUpdate = array_map('strval', array_keys($pending));
            $module = 'FeinwerkMarketplaceConnector';
            $action = 'consoleUpdate';
            $parameters = [];
        } catch (\Throwable $e) {
            // Matomo's own update runs as before.
        }
    }

    /** "<table>.<column>" of the activated Feinwerk plugins' dimensions: how Matomo lists them as pending components. */
    private static function feinwerkLogColumns(): array
    {
        $columns = [];
        foreach (\Piwik\Plugin\Manager::getInstance()->getActivatedPlugins() as $plugin) {
            if (str_starts_with((string) $plugin, 'Feinwerk')) {
                $columns = array_merge($columns, InstalledMarketplacePlugins::logColumns((string) $plugin));
            }
        }

        return $columns;
    }

    /** The Feinwerk components whose database update Matomo has not run yet (the "Aufgaben" tab). Never throws. */
    public static function pendingFeinwerkComponents(): array
    {
        try {
            return BrowserUpdateGuard::feinwerkComponents((new \Piwik\Updater())->getComponentUpdates() ?? [], self::feinwerkLogColumns());
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * "Run in the browser anyway" on the console page (Peter, 2026-10-23: without SSH access the admin was locked out):
     * a super user's POST with the page's single-use nonce and the ticked confirmation lets this one run through. The
     * nonce alone cannot stop a second run (a double click, a reload after "Bad Gateway"): Matomo saves the session only
     * when the long request ends, so the spent nonce still counts meanwhile, and its updater has no lock. A lock taken at
     * once does (review 5.0.0). A refused post says why on the console page.
     */
    private static function confirmedBrowserRun(): bool
    {
        $nonce = Common::getRequestVar('fwmc_browser_nonce', '', 'string', $_POST);
        if ($nonce === '' || Common::getRequestVar('fwmc_browser_confirm', '', 'string', $_POST) !== '1'
            || ! Piwik::hasUserSuperUserAccess()) {
            return false;
        }
        $error = Nonce::verifyNonceWithErrorMessage(ConsoleUpdatePage::BROWSER_RUN_NONCE, $nonce);
        if ($error !== '') {
            $reason = trim((string) preg_replace('/\s+/', ' ', strip_tags($error)));
            if ($reason !== '' && ! preg_match('/[.!?]$/u', $reason)) {
                $reason .= '.';
            }
            self::$browserRunNote = Piwik::translate('FeinwerkMarketplaceConnector_ConsoleUpdateAnywayRefused', [$reason]);

            return false;
        }
        $lock = new Lock(StaticContainer::get(LockBackend::class), 'FwmcBrowserRun');
        if (! $lock->acquireLock('run', self::BROWSER_RUN_TTL)) {
            self::$browserRunNote = Piwik::translate('FeinwerkMarketplaceConnector_ConsoleUpdateAnywayBusy');

            return false;
        }
        self::$browserRun = $lock;
        Nonce::discardNonce(ConsoleUpdatePage::BROWSER_RUN_NONCE);

        return true;
    }

    /**
     * TwoFactorAuth checks Request.dispatch after keepUpdatesOutOfTheBrowser() and exempts only CoreUpdater: a user who
     * must still set up 2FA would get its form instead of the console page, and Matomo sends that form back to the
     * update page (a loop). The console page shows no more than Matomo's update page, so it is exempt too, only in
     * the request whose update run was stopped.
     */
    public function onRequiresTwoFactorAuth(&$requiresAuth = null, $module = null, $action = null, $parameters = null): void
    {
        if (self::$stoppedUpdate !== null && $module === 'FeinwerkMarketplaceConnector' && $action === 'consoleUpdate') {
            $requiresAuth = false;
        }
    }

    /**
     * The notices after an install that point to the console (database update, activation) are persistent, so they
     * survive Matomo's update page, and Matomo keeps a persistent notice until it is closed (Peter, 2026-10-22: the
     * core:update warning stayed after the update). They go away once done: no component update pending (a short
     * notice then says so and links back to the screen, the way back the old notice gave), or the plugin active or
     * removed. One session read per page; the Updater runs only while the notice is shown. Never throws.
     */
    private function clearDoneConsoleNotices($module): void
    {
        try {
            if ($module === 'API' || Common::isPhpCliMode()) {
                return;
            }
            $shown = NotificationManager::getAllNotificationsToDisplay();
            // Done once no Feinwerk component is pending: a core update or another vendor's plugin update has Matomo's
            // own notice and used to keep this one alive (backlog 13). A failing check throws and keeps it.
            if (isset($shown[self::NOTICE_DB_UPDATE])
                && BrowserUpdateGuard::feinwerkComponents((new \Piwik\Updater())->getComponentUpdates() ?? [], self::feinwerkLogColumns()) === []) {
                NotificationManager::cancel(self::NOTICE_DB_UPDATE);
                $this->notifyRaw(
                    self::NOTICE_DB_UPDATE_DONE,
                    htmlspecialchars(Piwik::translate('FeinwerkMarketplaceConnector_DbUpdateDone'), ENT_QUOTES, 'UTF-8')
                    .' <a href="index.php?module=FeinwerkMarketplaceConnector&action=index">'
                    .htmlspecialchars(Piwik::translate('FeinwerkMarketplaceConnector_OpenScreen'), ENT_QUOTES, 'UTF-8').'</a>',
                    Notification::CONTEXT_SUCCESS
                );
            }
            $manager = \Piwik\Plugin\Manager::getInstance();
            foreach (array_keys($shown) as $id) {
                $slug = str_starts_with((string) $id, self::NOTICE_ACTIVATE.'_') ? substr((string) $id, strlen(self::NOTICE_ACTIVATE) + 1) : '';
                if ($slug !== '' && ($manager->isPluginActivated($slug) || ! $manager->isPluginInFilesystem($slug))) {
                    NotificationManager::cancel((string) $id);
                }
            }
        } catch (\Throwable $e) {
            // A notice must never break a page.
        }
    }

    /** Runs inside the core-update request: only flag a pending swap (no network, no installs here). */
    public function onCoreUpdated(): void
    {
        if (self::$browserRun !== null) {
            try {
                self::$browserRun->unlock();   // the confirmed browser run has finished
            } catch (\Throwable $e) {
            }
            self::$browserRun = null;
        }
        UpgradeWatcher::observe();
    }

    /**
     * Update notice for the logged-in super user. Raised while an admin page renders — a
     * notification posted from the daily task would land in the cron process's session and
     * never reach anyone. Cache-only and never throws (PendingUpdates).
     */
    public function onTriggerAdminNotifications(): void
    {
        UpgradeWatcher::observe();

        if (! Piwik::hasUserSuperUserAccess() || ! in_array(Piwik::getModule(), self::NOTICE_MODULES, true)) {
            return;
        }

        $this->notifySwap();
        $this->notifyWelcome();
        $informing = $this->showsInfoNotices();
        if ($informing) {
            $this->notifyReadiness();
        }

        $held = PendingUpdates::heldForDatabase();
        $heldSlugs = array_map(fn ($row) => $row->slug, $held);
        $pending = array_filter(PendingUpdates::rows(true), fn ($row) => ! in_array($row->slug, $heldSlugs, true));

        if (! empty($held)) {
            $this->notifyAdmins('FeinwerkMarketplaceConnector_dbheld', 'FeinwerkMarketplaceConnector_DbHeldNotice', $held, Notification::CONTEXT_WARNING);
        }
        if (! empty($pending) && $informing) {   // the menu badge and the Manage tab still show them
            $this->notifyAdmins('FeinwerkMarketplaceConnector_updates', 'FeinwerkMarketplaceConnector_UpdatesNotice', $pending, Notification::CONTEXT_INFO);
        }
    }

    /** "Show information notices" under General settings (Peter, 2026-10-23). Never throws: on any error they show. */
    private function showsInfoNotices(): bool
    {
        try {
            return (bool) StaticContainer::get(SystemSettings::class)->showInfoNotices->getValue();
        } catch (\Throwable $e) {
            return true;
        }
    }

    /** On the old Matomo line: which Feinwerk plugins already exist for the next one. Silent until the first one exists. */
    private function notifyReadiness(): void
    {
        try {
            $cache = ReadinessCache::read();
            if ($cache === null || UpgradeWatcher::pending() !== null) {
                return;
            }
            $current = MatomoMajor::of(Version::VERSION);
            if (! ReadinessSummary::appliesTo((int) $cache['target'], $current)) {
                return;
            }
            $summary = ReadinessSummary::of($cache['rows']);
            if (! $summary['any']) {
                return;
            }
            $names = $this->displayNames();
            $list = fn (array $slugs) => htmlspecialchars(implode(', ', array_map(fn ($s) => $names[$s] ?? PluginName::spaced($s), $slugs)), ENT_QUOTES, 'UTF-8');
            $target = (string) $cache['target'];
            $total = count($summary['ready']) + count($summary['beta']) + count($summary['missing']);
            if ($summary['missing'] === [] && $summary['beta'] === []) {
                $message = Piwik::translate('FeinwerkMarketplaceConnector_ReadinessAllReady', [$target]);
            } else {
                $message = Piwik::translate('FeinwerkMarketplaceConnector_ReadinessNotice', [$target, (string) count($summary['ready']), (string) $total]);
                if ($summary['beta'] !== []) {
                    $message .= ' '.Piwik::translate('FeinwerkMarketplaceConnector_ReadinessBeta', [$list($summary['beta'])]);
                }
                if ($summary['missing'] !== []) {
                    $message .= ' '.Piwik::translate('FeinwerkMarketplaceConnector_ReadinessMissing', [$list($summary['missing'])]);
                }
            }
            // Matomo will not run on a database that was already upgraded to the next major: the backup is the only way back.
            $message .= ' '.Piwik::translate('FeinwerkMarketplaceConnector_ReadinessBackup', [(string) $current]);
            $this->notifyRaw('FeinwerkMarketplaceConnector_readiness', $message, Notification::CONTEXT_INFO);
        } catch (\Throwable $e) {
        }
    }

    /**
     * The last swap run's waiting plugins minus those a plugin list fetched after the Matomo update already offers a
     * build for (rehearsal rc1, RC-F4: after a Refresh the notice called them unavailable until the next run). Cache
     * only; on any error the run's own list.
     *
     * @param  list<string>  $waiting
     * @return list<string>
     */
    private function stillWaiting(array $waiting, int $since): array
    {
        if ($waiting === []) {
            return [];
        }
        try {
            $url = MarketplaceClientFactory::url();
            $token = (string) StaticContainer::get(SystemSettings::class)->instanceToken->getValue();
            if ($url === '' || $token === '') {
                return $waiting;
            }
            $cached = (new MarketplaceCache(MarketplaceClientFactory::make($url, $token), new OptionCacheStore()))->cached();

            return SwapWaiting::still($waiting, $cached->plugins, InstalledMarketplacePlugins::versions($cached->plugins), $cached->fetchedAt, $since);
        } catch (\Throwable $e) {
            return $waiting;
        }
    }

    /**
     * Once after the activation, for a super user of a Matomo that is not connected yet: where to connect it. Who installed
     * the connector from Matomo's Marketplace may not know Feinwerk. The own screen shows the connect box itself, so the
     * notice is used up there without being shown. Never throws.
     */
    private function notifyWelcome(): void
    {
        try {
            if (\Piwik\Option::get(self::WELCOME_OPTION) === false) {
                return;
            }
            \Piwik\Option::delete(self::WELCOME_OPTION);
            $token = (string) StaticContainer::get(SystemSettings::class)->instanceToken->getValue();
            if ($token !== '' || Piwik::getModule() === 'FeinwerkMarketplaceConnector' || ! $this->showsInfoNotices()) {
                return;
            }
            $url = 'index.php?module=FeinwerkMarketplaceConnector&action=index';
            $message = htmlspecialchars(Piwik::translate('FeinwerkMarketplaceConnector_WelcomeNotice'), ENT_QUOTES, 'UTF-8')
                .' <a href="'.htmlspecialchars($url, ENT_QUOTES, 'UTF-8').'">'
                .htmlspecialchars(Piwik::translate('FeinwerkMarketplaceConnector_WelcomeOpen'), ENT_QUOTES, 'UTF-8').'</a>';
            $this->notifyRaw('FeinwerkMarketplaceConnector_welcome', $message, Notification::CONTEXT_INFO);
        } catch (\Throwable $e) {
        }
    }

    /** Status of a Matomo major change: pending (with "Switch now") and the last result once. Never throws. */
    private function notifySwap(): void
    {
        try {
            $names = $this->displayNames();
            $list = fn (array $slugs) => htmlspecialchars(implode(', ', array_map(fn ($s) => $names[$s] ?? PluginName::spaced($s), $slugs)), ENT_QUOTES, 'UTF-8');
            $pending = UpgradeWatcher::pending();
            $result = UpgradeWatcher::result();

            if ($pending !== null) {
                $major = htmlspecialchars((string) ($pending['to_major'] ?? ''), ENT_QUOTES, 'UTF-8');
                $message = Piwik::translate('FeinwerkMarketplaceConnector_SwapPendingNotice', [htmlspecialchars(Version::VERSION, ENT_QUOTES, 'UTF-8')]);
                $waiting = $this->stillWaiting((array) ($result['waiting'] ?? []), (int) ($pending['since'] ?? 0));
                if ($waiting !== []) {
                    $message .= ' '.Piwik::translate('FeinwerkMarketplaceConnector_SwapWaiting', [$major, $list($waiting)]);
                }
                $url = 'index.php?module=FeinwerkMarketplaceConnector&action=switchNow&nonce='
                    .urlencode(Nonce::getNonce('FeinwerkMarketplaceConnector.action', 3600));
                $message .= ' <a href="'.htmlspecialchars($url, ENT_QUOTES, 'UTF-8').'">'
                    .htmlspecialchars(Piwik::translate('FeinwerkMarketplaceConnector_SwapNow'), ENT_QUOTES, 'UTF-8').'</a>';
                $this->notifyRaw('FeinwerkMarketplaceConnector_swap', $message, Notification::CONTEXT_WARNING);
            }

            if ($result !== null && empty($result['shown'])) {
                $parts = [];
                $major = htmlspecialchars((string) ($result['to_major'] ?? ''), ENT_QUOTES, 'UTF-8');
                if (! empty($result['switched'])) {
                    $parts[] = Piwik::translate('FeinwerkMarketplaceConnector_SwapResultSwitched', [$major, $list(array_keys($result['switched']))]);
                }
                if (! empty($result['activated'])) {
                    $parts[] = Piwik::translate('FeinwerkMarketplaceConnector_SwapResultActivated', [$list($result['activated'])]);
                }
                if (! empty($result['inactive'])) {
                    $parts[] = Piwik::translate('FeinwerkMarketplaceConnector_SwapResultInactive', [$list($result['inactive'])]);
                }
                if (! empty($result['kept_off'])) {
                    $parts[] = Piwik::translate('FeinwerkMarketplaceConnector_SwapResultKeptOff', [$list($result['kept_off'])]);
                }
                if (! empty($result['errors'])) {
                    $parts[] = Piwik::translate('FeinwerkMarketplaceConnector_SwapResultErrors', [$list(array_keys($result['errors']))]);
                }
                if (! empty($result['needs_admin'])) {
                    $parts[] = Piwik::translate('FeinwerkMarketplaceConnector_SwapResultNeedsAdmin', [$list($result['needs_admin'])]);
                }
                if (! empty($result['needs_license'])) {
                    $parts[] = Piwik::translate('FeinwerkMarketplaceConnector_SwapResultNeedsLicense', [$list($result['needs_license'])]);
                }
                if (! empty($result['switched'])) {
                    $parts[] = Piwik::translate('FeinwerkMarketplaceConnector_SwapResultDbUpdate');
                }
                if ($parts !== []) {
                    $context = (empty($result['errors']) && empty($result['needs_admin']) && empty($result['needs_license']))
                        ? Notification::CONTEXT_SUCCESS : Notification::CONTEXT_WARNING;
                    $this->notifyRaw('FeinwerkMarketplaceConnector_swapresult', implode(' ', $parts), $context);
                }
                UpgradeWatcher::setResult(array_merge($result, ['shown' => true]));
            }
        } catch (\Throwable $e) {
            // Notices must never break an admin page.
        }
    }

    /** @return array<string,string> slug => name to show (PluginName) for every plugin in the cached marketplace list */
    private function displayNames(): array
    {
        $names = [];
        foreach (PendingUpdates::cachedPlugins() as $plugin) {
            $names[(string) ($plugin['slug'] ?? '')] = PluginName::of($plugin);
        }

        return $names;
    }

    private function notifyRaw(string $id, string $message, string $context): void
    {
        $notification = new Notification($message);
        $notification->title = Piwik::translate('FeinwerkMarketplaceConnector_MenuTitle');
        $notification->context = $context;
        $notification->type = Notification::TYPE_TRANSIENT;
        $notification->raw = true;
        NotificationManager::notify($id, $notification);
    }

    /** @param array<\Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto\PluginStatusRow> $rows */
    private function notifyAdmins(string $id, string $messageKey, array $rows, string $context): void
    {
        $items = array_map(
            fn ($row) => htmlspecialchars($row->displayName.' '.$row->latestVersion, ENT_QUOTES, 'UTF-8'),
            $rows
        );
        $message = Piwik::translate($messageKey, implode(', ', $items));
        if (Piwik::getModule() !== 'FeinwerkMarketplaceConnector') {
            $message .= ' <a href="index.php?module=FeinwerkMarketplaceConnector&action=index">'
                .htmlspecialchars(Piwik::translate('FeinwerkMarketplaceConnector_OpenScreen'), ENT_QUOTES, 'UTF-8').'</a>';
        }

        $notification = new Notification($message);
        $notification->title = Piwik::translate('FeinwerkMarketplaceConnector_MenuTitle');
        $notification->context = $context;
        $notification->type = Notification::TYPE_TRANSIENT;
        $notification->raw = true;
        NotificationManager::notify($id, $notification);
    }

    public function getStylesheetFiles(array &$files): void
    {
        $files[] = 'plugins/FeinwerkMarketplaceConnector/stylesheets/fwmc.less';
    }

    /**
     * Matomo only ships translation keys to the Vue frontend for keys listed here — every
     * `translate('FeinwerkMarketplaceConnector_…')` call in vue/src needs its key registered,
     * or it renders as a literal, untranslated error sentence. lang/en.json and lang/de.json
     * carry the same key set, so this list is exhaustive for both.
     */
    public function getClientSideTranslationKeys(array &$translationKeys): void
    {
        // Every text of the connector: the Vue app uses most of them, and a key that is not registered shows as the key
        // itself (5.1.0: the list was kept by hand, 99 entries).
        $json = json_decode((string) @file_get_contents(__DIR__.'/lang/en.json'), true);
        foreach (array_keys($json['FeinwerkMarketplaceConnector'] ?? []) as $key) {
            $translationKeys[] = 'FeinwerkMarketplaceConnector_'.$key;
        }
    }
}
