<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Container\StaticContainer;
use Piwik\Nonce;
use Piwik\Notification;
use Piwik\Notification\Manager as NotificationManager;
use Piwik\Piwik;
use Piwik\Version;
use Piwik\Plugin\Manager;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\ActionItems;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\ConnectFlow;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\ConnectSource;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MatomoMajor;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto\CachedPlugins;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions\MarketplaceFeatureMissingException;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\FeedbackVote;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions\MarketplaceLicenseException;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions\MarketplaceRefusedException;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions\MarketplaceUnreachableException;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MarketplaceEndpoint;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\PluginName;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\PurchaseLink;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MarketplaceCache;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\PluginDetailCache;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MarketplaceApiClient;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\UpdateMapper;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Http\HttpTransportMatomo;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Http\OptionCacheStore;
use Piwik\Updater;

class Controller extends \Piwik\Plugin\ControllerAdmin
{
    /** "Update several at once": its own nonce (every step verifies it, the finish spends it) and its results. */
    private const BATCH_NONCE = 'FeinwerkMarketplaceConnector.batch';

    private const BATCH_OPTION = 'fwmc.batch';

    /** CorePluginsAdmin\Controller::ACTIVATE_NONCE, the same from Matomo 5.0 to 6.0.0-b6. */
    public const MATOMO_ACTIVATE_NONCE = 'CorePluginsAdmin.activatePlugin';

    /** The Manage tab; action forms send their tab as fwmc_tab to come back to it. */
    private const TAB_MANAGE = 'verwaltung';

    /**
     * Since 5.1.0 the screen is its own admin section (Peter, 2026-10-23: "Feinwerk Plugins" with sub entries to keep the
     * intentions apart): tab => action of its menu entry. index stays the Discover tab, so old links keep working.
     */
    public const TABS = ['aufgaben' => 'tasks', 'entdecken' => 'index', 'verwaltung' => 'manage', 'lizenzen' => 'licenses'];

    public function tasks(): string
    {
        return $this->screen('aufgaben');
    }

    public function manage(): string
    {
        return $this->screen(self::TAB_MANAGE);
    }

    public function licenses(): string
    {
        return $this->screen('lizenzen');
    }

    public function index(): string
    {
        // Links from before 5.1.0 (and the notices) open the Manage tab with fwmc_tab=verwaltung.
        return $this->screen(\Piwik\Common::getRequestVar('fwmc_tab', '', 'string') === self::TAB_MANAGE ? self::TAB_MANAGE : 'entdecken');
    }

    private function screen(string $tab): string
    {
        Piwik::checkUserHasSuperUserAccess();
        $dbHeld = array_map(fn ($row) => $row->slug, PendingUpdates::heldForDatabase());

        $settings = $this->settings();
        $rows = [];
        $error = null;

        $lastUpdated = null;
        $linesBySlug = [];
        $major = null;
        $browseFetchedAt = null;
        $refreshError = null;
        $refreshErrorKind = null;
        $licensingAvailable = false;
        $openSlug = '';

        $connected = $settings['url'] !== '' && $settings['token'] !== '';
        if ($connected) {
            // Cache-first: renders from the persisted last-good response. The only
            // network call is the first-run fetch, with a short timeout, and it
            // never throws — a dead/slow upstream cannot error or stall this page.
            $cached = $this->marketplaceCache($settings)->ensurePopulated();
            $installed = InstalledMarketplacePlugins::versions($cached->plugins);
            $rows = (new UpdateMapper($this->adminLang()))->map(
                $cached->plugins,
                $installed,
                InstalledMarketplacePlugins::devCheckouts($cached->plugins),
                UpgradeWatcher::switching(array_keys($installed)),
                InstalledMarketplacePlugins::allLogColumns($cached->plugins),
                InstalledMarketplacePlugins::inactive(array_keys($installed))
            );
            $lastUpdated = $cached->fetchedAt ? date('Y-m-d H:i', $cached->fetchedAt) : null;
            $refreshError = $cached->error;
            $refreshErrorKind = $cached->errorKind;
            // Feature detection: only a store that sends license_claimable knows the two new endpoints.
            $licensingAvailable = UpdateMapper::storeOffersLicensing($cached->plugins);
            // Back from a claim or a purchase: open the window of that plugin, if it is listed.
            $wanted = \Piwik\Common::getRequestVar('fwmc_open', '', 'string');
            foreach ($cached->plugins as $plugin) {
                if ($wanted !== '' && is_array($plugin) && ($plugin['slug'] ?? null) === $wanted) {
                    $openSlug = $wanted;
                }
            }
        } elseif (($public = PublicCatalog::cached($this->adminLang())) !== null) {
            // Not connected, but the admin asked to see the Feinwerk plugins (browse without an account, 5.3.0): the
            // public list the click loaded, read only. Which of them have a build for this Matomo comes from `lines`.
            $installed = InstalledMarketplacePlugins::versions($public['data']);
            $rows = (new UpdateMapper($this->adminLang()))->map(
                $public['data'],
                $installed,
                InstalledMarketplacePlugins::devCheckouts($public['data']),
                [],
                [],
                InstalledMarketplacePlugins::inactive(array_keys($installed))
            );
            $major = MatomoMajor::of(Version::VERSION);
            foreach ($public['data'] as $plugin) {
                if (is_array($plugin) && is_string($plugin['slug'] ?? null) && is_array($plugin['lines'] ?? null)) {
                    $linesBySlug[$plugin['slug']] = array_map('intval', $plugin['lines']);
                }
            }
            $browseFetchedAt = date('Y-m-d H:i', $public['fetchedAt']);
        }

        return $this->renderTemplate('index', [
            // Vue consumes plain objects, not PluginStatusRow instances. availableHere: the store has a build of it for
            // this Matomo (browse without an account; connected, the list already answers for this Matomo).
            'rows' => array_map(fn ($r) => get_object_vars($r) + [
                'availableHere' => $major === null || ! isset($linesBySlug[$r->slug]) || in_array($major, $linesBySlug[$r->slug], true),
            ], $rows),
            // Browse without an account: loaded by the admin's click on the screen of a Matomo that is not connected.
            'browse' => [
                'loaded' => $browseFetchedAt !== null,
                'fetchedAt' => $browseFetchedAt,
                'url' => 'index.php?module=FeinwerkMarketplaceConnector&action=browse',
            ],
            'error' => $error,
            'lastUpdated' => $lastUpdated,
            'refreshError' => $refreshError,
            'refreshErrorKind' => $refreshErrorKind,
            // 1h TTL: the default 600s only refreshes on page render — composing a
            // bug report longer than that would invalidate the nonce mid-flow.
            'nonce' => Nonce::getNonce('FeinwerkMarketplaceConnector.action', 3600),
            'connected' => $connected,
            // For the database-update warning before an install (InstallForm).
            'instance' => $connected ? self::instanceInfo() : null,
            'licensingAvailable' => $licensingAvailable,
            // "Activate" for an installed plugin Matomo has not switched on: Matomo's own action (password check included).
            'activateUrl' => self::activateUrl(),
            'offlineDocsUrl' => MarketplaceEndpoint::offlineDocsUrl(MarketplaceClientFactory::url(), $this->adminLang()),
            'openSlug' => $openSlug,
            'initialTab' => $tab,
            // What the page shows, for the check when it gets the focus again (ScreenState, backlog 13).
            'screenState' => $connected ? ScreenState::fingerprint() : '',
            // The "Aufgaben" tab (Core/ActionItems) and what its buttons need.
            'actionItems' => $connected ? self::withNames(ActionItems::from(
                $rows,
                $this->pluginName,
                $dbHeld,
                FeinwerkMarketplaceConnector::pendingFeinwerkComponents(),
                UpgradeWatcher::pending() !== null,
                $refreshErrorKind,
                date('Y-m-d')
            )) : [],
            'switchNowUrl' => 'index.php?module=FeinwerkMarketplaceConnector&action=switchNow&nonce='
                .urlencode(Nonce::getNonce('FeinwerkMarketplaceConnector.action', 3600)),
            'consoleCommand' => ConsoleCommand::of('core:update'),
            'batchNonce' => Nonce::getNonce(self::BATCH_NONCE, 3600),
            // The recommendation banner on the Discover tab (null when switched off or nothing to show).
            'promo' => $connected ? Recommendation::current() : null,
            'accountUrl' => rtrim(MarketplaceClientFactory::url(), '/').'/account',
            'selfSlug' => $this->pluginName,
            'dbHeld' => $dbHeld,
            // The feedback question under the tabs and the support link next to it (Feinwerk feedback kit).
            'feedback' => [
                'enabled' => Feedback::enabled(),
                'supportUrl' => FeedbackVote::supportUrl($this->adminLang()),
                'version' => self::ownVersion(),
            ],
        ]);
    }

    /**
     * The pending database components as people read them: a plugin with spaces ("Feinwerk Search Report"), a log table
     * column as Matomo names it (log_visit.channel_grouping).
     *
     * @param  list<array<string, mixed>>  $items
     * @return list<array<string, mixed>>
     */
    private static function withNames(array $items): array
    {
        foreach ($items as &$item) {
            $item['components'] = array_map(
                fn ($c) => preg_match('/^Feinwerk[A-Z]/', (string) $c) === 1 ? PluginName::spaced((string) $c) : (string) $c,
                (array) ($item['components'] ?? [])
            );
        }

        return $items;
    }

    /** The connector's own version (plugin.json), for the feedback vote. */
    private static function ownVersion(): string
    {
        $json = json_decode((string) @file_get_contents(__DIR__.'/plugin.json'), true);

        return is_array($json) ? (string) ($json['version'] ?? '') : '';
    }

    /**
     * A vote of the feedback question (JSON, form-encoded POST like reportBug). Nonce verified WITHOUT discard: the page
     * shares one nonce with its forms. Answers {"sent": bool} and never shows a store error; the Matomo server sends
     * the vote, never the browser (store privacy policy, section 7d).
     */
    public function sendFeedback(): string
    {
        Piwik::checkUserHasSuperUserAccess();
        \Piwik\Common::sendHeader('Content-Type: application/json; charset=utf-8');

        $nonce = \Piwik\Common::getRequestVar('nonce', '', 'string', $_POST);
        if (! Nonce::verifyNonce('FeinwerkMarketplaceConnector.action', $nonce) || ! Feedback::enabled()) {
            return (string) json_encode(['sent' => false]);
        }
        try {
            $payload = FeedbackVote::payload(
                trim(\Piwik\Common::getRequestVar('topic', '', 'string', $_POST)),
                trim(\Piwik\Common::getRequestVar('vote', '', 'string', $_POST)),
                self::ownVersion(),
                \Piwik\Version::VERSION,
                (string) ($_SERVER['HTTP_USER_AGENT'] ?? ''),
                (string) StaticContainer::get(\Piwik\Translation\Translator::class)->getCurrentLanguage()
            );
        } catch (\InvalidArgumentException $e) {
            return (string) json_encode(['sent' => false]);
        }
        if (! Feedback::allowed(Piwik::getCurrentUserLogin(), (string) $payload['topic'])) {
            return (string) json_encode(['sent' => false]);
        }

        return (string) json_encode(['sent' => Feedback::send($payload)]);
    }

    /**
     * "Switch recommendations off" in the banner (backlog 12): super user, the banner's nonce (verified, kept: nothing is
     * installed), JSON {"off": bool}. The setting under General settings switches them on again.
     */
    public function switchOffRecommendations(): string
    {
        Piwik::checkUserHasSuperUserAccess();
        \Piwik\Common::sendHeader('Content-Type: application/json; charset=utf-8');

        $nonce = \Piwik\Common::getRequestVar('nonce', '', 'string', $_POST);
        if (! Nonce::verifyNonce(Recommendation::NONCE, $nonce)) {
            return (string) json_encode(['off' => false]);
        }

        return (string) json_encode(['off' => Recommendation::switchOff()]);
    }

    /** The screen's current state for an open page (ScreenState, backlog 13): JSON {"state": fingerprint}. Read only. */
    public function state(): string
    {
        Piwik::checkUserHasSuperUserAccess();
        \Piwik\Common::sendHeader('Content-Type: application/json; charset=utf-8');
        \Piwik\Common::sendHeader('Cache-Control: no-store');

        return (string) json_encode(['state' => ScreenState::fingerprint()]);
    }

    /**
     * "Show Feinwerk plugins" on the screen of a Matomo that is not connected (5.3.0): loads the store's public list. The
     * click is the consent; the request has no instance token and no instance headers, only the language. An invalid
     * nonce returns to the screen quietly, like Refresh.
     */
    public function browse(): string
    {
        Piwik::checkUserHasSuperUserAccess();

        $nonce = \Piwik\Common::getRequestVar('nonce', '', 'string', $_POST);
        if (Nonce::verifyNonce('FeinwerkMarketplaceConnector.action', $nonce) && ! PublicCatalog::load($this->adminLang())) {
            $this->notify(Piwik::translate('FeinwerkMarketplaceConnector_BrowseFailed'), Notification::CONTEXT_WARNING);
        }
        $this->redirectToIndex('FeinwerkMarketplaceConnector', 'index');

        return '';
    }

    public function refresh(): string
    {
        Piwik::checkUserHasSuperUserAccess();

        // CSRF-protected, but degrades gracefully: an invalid/expired nonce simply
        // skips the (idempotent) refresh and redirects back, instead of throwing
        // Matomo's security-error page. A forged request can never trigger the refresh.
        $nonce = \Piwik\Common::getRequestVar('nonce', '', 'string');
        if (Nonce::verifyNonce('FeinwerkMarketplaceConnector.action', $nonce)) {
            Nonce::discardNonce('FeinwerkMarketplaceConnector.action');

            $settings = $this->settings();
            if ($settings['url'] !== '' && $settings['token'] !== '') {
                $refreshed = $this->marketplaceCache($settings)->refresh(); // never throws

                // Only report when the marketplace answered — otherwise a dead upstream would
                // make the admin wait out a second timeout here.
                if ($refreshed->error === null) {
                    // A new release changes the plugin window too: its cached answers are outdated now.
                    (new PluginDetailCache($this->client($settings), new OptionCacheStore()))->invalidateAll(); // never throws
                    ReadinessCache::refresh($this->client($settings)); // never throws
                    try {
                        $env = [
                            'matomo_version' => \Piwik\Version::VERSION,
                            'php_version' => PHP_VERSION,
                        ];
                        $model = new \Piwik\Plugins\UsersManager\Model();
                        $env['user_count'] = count($model->getUsersLogin());
                        $this->client($settings)->checkUpdates($env, []);
                    } catch (\Throwable $e) {
                        // Reporting is best-effort; refresh must stay resilient.
                    }
                }
            }
        }

        $this->backToScreen();

        return '';
    }

    public function detail(): string
    {
        Piwik::checkUserHasSuperUserAccess();

        $slug = \Piwik\Common::getRequestVar('slug', '', 'string');
        $settings = $this->settings();

        if ($slug === '' || $settings['url'] === '' || $settings['token'] === '') {
            $result = ['data' => null, 'fetchedAt' => null, 'stale' => false, 'error' => 'Connector is not configured.', 'errorKind' => null];
        } else {
            $cache = new PluginDetailCache(
                $this->client($settings),
                new OptionCacheStore()
            );
            $result = $cache->get($slug);
        }

        \Piwik\Common::sendHeader('Content-Type: application/json; charset=utf-8');

        return (string) json_encode($result);
    }

    /**
     * CSP-safe image proxy: Matomo's admin CSP allows img-src 'self' only, so
     * marketplace artwork/screenshots are streamed through this action and
     * cached on disk. STRICTLY limited to the configured marketplace host —
     * this must never become an open proxy (SSRF).
     */
    public function image(): string
    {
        Piwik::checkUserHasSuperUserAccess();

        $src = \Piwik\Common::getRequestVar('src', '', 'string');
        $settings = $this->settings();
        $base = rtrim($settings['url'], '/');

        // Only images from the configured marketplace host, nothing else, and only once the instance is connected: no
        // request to the store before an admin has agreed to it (Matomo Marketplace rule, NoContactBeforeConnectTest).
        $consented = $settings['token'] !== '' || PublicCatalog::cached() !== null;   // connected, or the browse click
        if ($base === '' || ! $consented || strpos($src, $base.'/') !== 0) {
            http_response_code(404);
            return '';
        }

        $cacheDir = StaticContainer::get('path.tmp').'/cache/fwmc-images';
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
        }
        $cacheFile = $cacheDir.'/'.md5($src);
        $metaFile = $cacheFile.'.mime';

        if (!file_exists($cacheFile) || (time() - (int) filemtime($cacheFile)) > 86400) {
            try {
                $response = (new HttpTransportMatomo())->get($src, []);
                if ($response->status !== 200 || $response->body === '') {
                    throw new \RuntimeException('not fetchable');
                }
                $mime = $this->imageMime($response->body, $src);
                if ($mime === null) {
                    throw new \RuntimeException('not an image');
                }
                file_put_contents($cacheFile, $response->body);
                file_put_contents($metaFile, $mime);
            } catch (\Throwable $e) {
                if (!file_exists($cacheFile)) { // no stale copy either
                    http_response_code(404);
                    return '';
                }
                // stale copy exists — serve it
            }
        }

        \Piwik\Common::sendHeader('Content-Type: '.((string) @file_get_contents($metaFile) ?: 'application/octet-stream'));
        \Piwik\Common::sendHeader('Cache-Control: private, max-age=86400');
        // Opening the proxy URL directly (not as an <img>) must not execute script:
        // no inline/external JS, no MIME-sniffing into something executable.
        \Piwik\Common::sendHeader("Content-Security-Policy: default-src 'none'; style-src 'unsafe-inline'");
        \Piwik\Common::sendHeader('X-Content-Type-Options: nosniff');

        return (string) file_get_contents($cacheFile);
    }

    /** Detect a safe image mime (svg by extension+sniff, raster via getimagesize). */
    private function imageMime(string $bytes, string $src): ?string
    {
        if (str_ends_with(parse_url($src, PHP_URL_PATH) ?? '', '.svg')) {
            // Serve as SVG only when it looks like one; svgz/scripts stay out.
            return (stripos(substr($bytes, 0, 300), '<svg') !== false) ? 'image/svg+xml' : null;
        }
        $info = @getimagesizefromstring($bytes);
        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

        return ($info !== false && in_array($info['mime'], $allowed, true)) ? $info['mime'] : null;
    }

    public function reportBug(): string
    {
        Piwik::checkUserHasSuperUserAccess();

        return $this->jsonResult(function () {
            $this->requireVerifiedNonce();

            $slug = \Piwik\Common::getRequestVar('slug', '', 'string');
            // getRequestVar html-escapes free text (e.g. `<` becomes `&lt;`); undo that
            // before it's stored, or the bug report reads back garbled.
            $title = trim(\Piwik\Common::unsanitizeInputValue(\Piwik\Common::getRequestVar('title', '', 'string')));
            $description = trim(\Piwik\Common::unsanitizeInputValue(\Piwik\Common::getRequestVar('description', '', 'string')));

            if ($slug === '' || $title === '' || $description === '') {
                throw new \InvalidArgumentException('Title and description are required.');
            }

            $settings = $this->settings();
            $version = $this->installedVersionOf($slug);
            $data = $this->client($settings)->reportBug($slug, [
                'title' => mb_substr($title, 0, 120),
                'description' => mb_substr($description, 0, 5000),
                'plugin_version' => $version !== null ? mb_substr($version, 0, 32) : null,
                'matomo_version' => \Piwik\Version::VERSION,
                // Distro PHP builds (e.g. Debian sury) append suffixes that can exceed
                // the server's max:32 validation on this field — truncate defensively.
                'php_version' => mb_substr(PHP_VERSION, 0, 32),
            ]);

            return ['ok' => true, 'id' => (int) ($data['id'] ?? 0)];
        });
    }

    public function reportBugScreenshot(): string
    {
        Piwik::checkUserHasSuperUserAccess();

        return $this->jsonResult(function () {
            $this->requireVerifiedNonce();

            $id = (int) \Piwik\Common::getRequestVar('id', 0, 'int');
            $imageBase64 = \Piwik\Common::getRequestVar('image_base64', '', 'string');
            $mime = \Piwik\Common::getRequestVar('mime', '', 'string');

            if ($id <= 0 || $imageBase64 === '') {
                throw new \InvalidArgumentException('Missing screenshot data.');
            }

            $settings = $this->settings();
            $data = $this->client($settings)->attachScreenshot($id, [
                'image_base64' => $imageBase64,
                'mime' => $mime,
            ]);

            return ['ok' => true, 'count' => (int) ($data['count'] ?? 0)];
        });
    }

    /** Shared JSON envelope: exceptions become {ok:false, error} — never a fatal. */
    private function jsonResult(callable $fn): string
    {
        \Piwik\Common::sendHeader('Content-Type: application/json; charset=utf-8');

        try {
            $result = $fn();
        } catch (\Throwable $e) {
            $result = ['ok' => false, 'error' => $e->getMessage()];
        }

        return (string) json_encode($result);
    }

    /**
     * The check of install, update and "Switch now", which install code: the nonce is spent once it passes, as with
     * Nonce::checkNonce. A failed check refuses the action just the same, but leaves the admin on the screen with Matomo's
     * reason and the advice to reload, instead of Matomo's error page (an expired page, a proxy that drops the referrer).
     */
    private function passedSecurityCheck(): bool
    {
        $nonce = \Piwik\Common::getRequestVar('nonce', '', 'string');
        $error = Nonce::verifyNonceWithErrorMessage('FeinwerkMarketplaceConnector.action', $nonce);
        if ($error === '') {
            Nonce::discardNonce('FeinwerkMarketplaceConnector.action');

            return true;
        }

        if ($nonce !== '') {
            Nonce::discardNonce('FeinwerkMarketplaceConnector.action');   // like checkNonce: a failed attempt spends it
        }
        // Matomo's own sentence already says what failed and to reload; the connector only adds that nothing happened.
        $reason = trim((string) preg_replace('/\s+/', ' ', strip_tags($error)));
        if ($reason !== '' && ! preg_match('/[.!?]$/u', $reason)) {
            $reason .= '.';
        }
        $this->notify(Piwik::translate('FeinwerkMarketplaceConnector_SecurityCheckFailed', [$reason]), Notification::CONTEXT_WARNING);

        return false;
    }

    /** Multi-request flow: verify WITHOUT discarding (see CLAUDE.md CSRF note). */
    private function requireVerifiedNonce(): void
    {
        $nonce = \Piwik\Common::getRequestVar('nonce', '', 'string');
        // The error-message variant tells us WHICH check failed (token expiry vs
        // referrer vs origin) — this instance sits behind a proxy where origin
        // heuristics have failed before, so a precise message beats a generic one.
        $error = Nonce::verifyNonceWithErrorMessage('FeinwerkMarketplaceConnector.action', $nonce);
        if ($error !== '') {
            throw new \RuntimeException(
                'Security check failed: '.rtrim(trim(strip_tags($error)), '.').'. Reload the page and try again.'
            );
        }
    }

    /**
     * Matomo's own activation (CorePluginsAdmin activate, a GET link with its nonce, back to this page afterwards); the
     * screen appends &pluginName=. Empty when [General] enable_plugins_admin = 0, where Matomo refuses it.
     */
    private static function activateUrl(): string
    {
        try {
            if (! (bool) (\Piwik\Config::getInstance()->General['enable_plugins_admin'] ?? true)) {
                return '';
            }
        } catch (\Throwable $e) {
            // Matomo's default allows it.
        }

        return 'index.php?module=CorePluginsAdmin&action=activate&redirectTo=referrer&nonce='
            .urlencode(Nonce::getNonce(self::MATOMO_ACTIVATE_NONCE, 3600));
    }

    private function installedVersionOf(string $slug): ?string
    {
        try {
            return InstalledMarketplacePlugins::versionOf($slug);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function install(): string
    {
        Piwik::checkUserHasSuperUserAccess();
        if (! $this->passedSecurityCheck()) {
            $this->backToScreen();
            return '';
        }

        $slug = \Piwik\Common::getRequestVar('slug', '', 'string');
        $version = \Piwik\Common::getRequestVar('version', '', 'string');
        // activate=0: on a large instance a plugin that adds log table columns is activated on the console, where the
        // ALTER TABLE cannot run into the web server's time limit (InstallForm).
        $activate = \Piwik\Common::getRequestVar('activate', '1', 'string') !== '0';

        $this->runInstall($slug, $version, $activate);

        $this->backToScreen();
        return '';
    }

    public function update(): string
    {
        Piwik::checkUserHasSuperUserAccess();
        if (! $this->passedSecurityCheck()) {
            $this->backToScreen();
            return '';
        }

        $slug = \Piwik\Common::getRequestVar('slug', '', 'string');
        $version = \Piwik\Common::getRequestVar('version', '', 'string');

        $this->runInstall($slug, $version, null);

        $this->backToScreen();
        return '';
    }

    /**
     * Connect button: remember a fresh state and PKCE verifier, then send the admin to the store's /connect page
     * (see Core/ConnectFlow). An invalid nonce just returns to the screen, like Refresh.
     */
    public function connect(): string
    {
        Piwik::checkUserHasSuperUserAccess();

        $nonce = \Piwik\Common::getRequestVar('nonce', '', 'string');
        if (! Nonce::verifyNonce('FeinwerkMarketplaceConnector.action', $nonce)) {
            $this->redirectToIndex('FeinwerkMarketplaceConnector', 'index');

            return '';
        }

        $pending = ConnectFlow::begin(time());
        ConnectState::put($pending);

        $base = \Piwik\Url::getCurrentUrlWithoutFileName();
        $returnUrl = $base.'index.php?module=FeinwerkMarketplaceConnector&action=connectReturn';
        $siteUrl = \Piwik\SettingsPiwik::getPiwikUrl() ?: $base;

        $source = ConnectSource::of(__DIR__, InstalledMarketplacePlugins::isDevCheckout($this->pluginName));
        \Piwik\Url::redirectToUrl(ConnectFlow::authorizeUrl(MarketplaceClientFactory::url(), $pending, $returnUrl, $siteUrl, $this->adminLang(), $source));

        return '';
    }

    /**
     * The store sends the admin back here with a one-time code (or error=access_denied). The state must be the one
     * this Matomo started; the code plus the verifier buys the instance token, which is stored like a typed-in one.
     */
    public function connectReturn(): string
    {
        Piwik::checkUserHasSuperUserAccess();

        $state = \Piwik\Common::getRequestVar('state', '', 'string');
        $code = \Piwik\Common::getRequestVar('code', '', 'string');
        $pending = ConnectState::take();

        if (! ConnectFlow::accepts($pending, $state, time())) {
            $this->notify(Piwik::translate('FeinwerkMarketplaceConnector_ConnectExpired'), Notification::CONTEXT_WARNING);
        } elseif ($code === '') {
            $this->notify(Piwik::translate('FeinwerkMarketplaceConnector_ConnectCancelled'), Notification::CONTEXT_INFO);
        } else {
            try {
                $result = MarketplaceClientFactory::make(MarketplaceClientFactory::url(), '')
                    ->exchangeConnectCode($code, $pending['verifier']);

                $systemSettings = StaticContainer::get(SystemSettings::class);
                $systemSettings->instanceToken->setValue($result['token']);
                $systemSettings->save();

                $settings = $this->settings();
                $this->marketplaceCache($settings)->refresh(); // never throws
                (new PluginDetailCache($this->client($settings), new OptionCacheStore()))->invalidateAll(); // never throws

                $this->notify(
                    Piwik::translate('FeinwerkMarketplaceConnector_ConnectDone', $result['label'] ?? '?'),
                    Notification::CONTEXT_SUCCESS
                );
            } catch (\Throwable $e) {
                $this->notify(Piwik::translate('FeinwerkMarketplaceConnector_ConnectFailed', $e->getMessage()), Notification::CONTEXT_ERROR);
            }
        }

        $this->redirectToIndex('FeinwerkMarketplaceConnector', 'index');

        return '';
    }

    /**
     * "Use an existing license": the store assigns a free seat of the account's license to this instance. Degrades to
     * the screen like connect(): an invalid nonce or slug just returns without a request.
     */
    public function claimLicense(): string
    {
        Piwik::checkUserHasSuperUserAccess();

        $slug = $this->licensingSlug();
        if ($slug === null) {
            $this->redirectToIndex('FeinwerkMarketplaceConnector', 'index');

            return '';
        }

        try {
            $settings = $this->settings();
            $result = $this->client($settings)->claimLicense($slug)['result'];
            $this->refreshLists($settings);

            if ($result === 'assigned') {
                $this->notify(Piwik::translate('FeinwerkMarketplaceConnector_ClaimDone', [$this->displayName($slug)]), Notification::CONTEXT_SUCCESS);
            } elseif ($result === 'covered_as_test') {
                $this->notify(Piwik::translate('FeinwerkMarketplaceConnector_ClaimCoveredAsTest', [$this->displayName($slug)]), Notification::CONTEXT_INFO);
            } else {
                $this->notify(Piwik::translate('FeinwerkMarketplaceConnector_ClaimAlreadyLicensed', [$this->displayName($slug)]), Notification::CONTEXT_INFO);
            }
        } catch (\Throwable $e) {
            $this->notifyLicensingRefusal($e, $slug);
        }

        $this->backToScreen($slug);

        return '';
    }

    /**
     * "Buy a license": the store makes a one-time buy page for this plugin and instance; the browser goes there only
     * when the address is a buy page of the store this connector talks to.
     */
    public function buy(): string
    {
        Piwik::checkUserHasSuperUserAccess();

        $slug = $this->licensingSlug();
        if ($slug === null) {
            $this->redirectToIndex('FeinwerkMarketplaceConnector', 'index');

            return '';
        }

        try {
            $returnUrl = \Piwik\Url::getCurrentUrlWithoutFileName().'index.php?'
                .http_build_query(['module' => 'FeinwerkMarketplaceConnector', 'action' => 'buyReturn', 'slug' => $slug]);
            $url = $this->client($this->settings())->createPurchaseIntent($slug, $returnUrl, $this->adminLang());

            if (PurchaseLink::isOnStore($url, MarketplaceClientFactory::url())) {
                \Piwik\Url::redirectToUrl($url);

                return '';
            }

            $this->notify(
                Piwik::translate('FeinwerkMarketplaceConnector_LicensingFailed', [$this->displayName($slug), 'the store answered with an unexpected address']),
                Notification::CONTEXT_ERROR
            );
        } catch (\Throwable $e) {
            $this->notifyLicensingRefusal($e, $slug);
        }

        $this->backToScreen($slug);

        return '';
    }

    /**
     * The store sends the admin back here after the purchase. No nonce: it only refreshes the list and shows a notice
     * (idempotent), and the store cannot know a Matomo nonce.
     */
    public function buyReturn(): string
    {
        Piwik::checkUserHasSuperUserAccess();

        $slug = \Piwik\Common::getRequestVar('slug', '', 'string');
        if (! $this->isSlug($slug)) {
            $this->redirectToIndex('FeinwerkMarketplaceConnector', 'index');

            return '';
        }

        $ready = false;
        foreach ($this->refreshLists($this->settings())->plugins as $plugin) {
            if (is_array($plugin) && ($plugin['slug'] ?? null) === $slug) {
                $ready = empty($plugin['needs_license']) || ! empty($plugin['license']);
            }
        }

        $this->notify(
            Piwik::translate($ready ? 'FeinwerkMarketplaceConnector_BuyReturnReady' : 'FeinwerkMarketplaceConnector_BuyReturnPending', [$this->displayName($slug)]),
            $ready ? Notification::CONTEXT_SUCCESS : Notification::CONTEXT_INFO
        );
        $this->backToScreen($slug);

        return '';
    }

    /** The slug of a claim or buy request, or null when the nonce is invalid or the slug malformed (nothing is sent then). */
    private function licensingSlug(): ?string
    {
        $nonce = \Piwik\Common::getRequestVar('nonce', '', 'string');
        $slug = \Piwik\Common::getRequestVar('slug', '', 'string');

        return Nonce::verifyNonce('FeinwerkMarketplaceConnector.action', $nonce) && $this->isSlug($slug) ? $slug : null;
    }

    private function isSlug(string $slug): bool
    {
        return preg_match('/^[A-Za-z][A-Za-z0-9_]{0,99}$/', $slug) === 1;
    }

    /**
     * Back to the screen as the admin left it: the forms (InstallForm, LicenseActions, Refresh) send the tab as fwmc_tab
     * and, from a plugin window, its plugin as fwmc_return_open. $open opens that plugin's window in any case (license
     * claimed, back from the store). index() opens only a listed plugin. Read from the POST body only: the action URLs
     * are built with linkTo() from the current address, which after a return carries fwmc_tab itself (review 1.2.1).
     */
    private function backToScreen(?string $open = null): void
    {
        $parameters = [];
        $returnOpen = \Piwik\Common::getRequestVar('fwmc_return_open', '', 'string', $_POST);
        $open ??= $this->isSlug($returnOpen) ? $returnOpen : null;
        if ($open !== null) {
            $parameters['fwmc_open'] = $open;
        }
        // Back to the menu entry of the tab the form was sent from (5.1.0: one action per tab).
        $action = self::TABS[\Piwik\Common::getRequestVar('fwmc_tab', '', 'string', $_POST)] ?? 'index';

        $this->redirectToIndex('FeinwerkMarketplaceConnector', $action, null, null, null, $parameters);
    }

    /** Re-reads the plugin list and, when the store answered, drops the outdated plugin window answers. Never throws. */
    private function refreshLists(array $settings): CachedPlugins
    {
        $refreshed = $this->marketplaceCache($settings)->refresh();
        if ($refreshed->error === null) {
            (new PluginDetailCache($this->client($settings), new OptionCacheStore()))->invalidateAll();
        }

        return $refreshed;
    }

    /** The one place that turns a refused or failed claim/buy into a notice. */
    private function notifyLicensingRefusal(\Throwable $e, string $slug): void
    {
        $name = $this->displayName($slug);

        if ($e instanceof MarketplaceRefusedException) {
            $known = [
                'no_free_seat' => ['FeinwerkMarketplaceConnector_ClaimNoFreeSeat', Notification::CONTEXT_WARNING],
                'no_license' => ['FeinwerkMarketplaceConnector_ClaimNoLicense', Notification::CONTEXT_WARNING],
                'not_for_sale' => ['FeinwerkMarketplaceConnector_BuyNotForSale', Notification::CONTEXT_WARNING],
                'no_account' => ['FeinwerkMarketplaceConnector_BuyNoAccount', Notification::CONTEXT_WARNING],
            ][$e->refusal] ?? null;
            if ($known !== null) {
                // BuyNoAccount names no plugin, so it is translated without arguments.
                $this->notify(Piwik::translate($known[0], $e->refusal === 'no_account' ? [] : [$name]), $known[1]);

                return;
            }
        } elseif ($e instanceof MarketplaceFeatureMissingException) {
            $this->notify(Piwik::translate('FeinwerkMarketplaceConnector_LicensingUnavailable'), Notification::CONTEXT_INFO);

            return;
        } elseif ($e instanceof MarketplaceUnreachableException) {
            $this->notify(Piwik::translate('FeinwerkMarketplaceConnector_Offline'), Notification::CONTEXT_WARNING);

            return;
        }

        $this->notify(Piwik::translate('FeinwerkMarketplaceConnector_LicensingFailed', [$name, $e->getMessage()]), Notification::CONTEXT_ERROR);
    }

    /** "de" for German admins, else "en": the language of the store pages this Matomo links to. */
    private function adminLang(): string
    {
        return str_starts_with((string) \Piwik\Plugins\LanguagesManager\LanguagesManager::getLanguageCodeForCurrentUser(), 'de') ? 'de' : 'en';
    }

    public function checkNow(): string
    {
        Piwik::checkUserHasSuperUserAccess();
        $this->passedSecurityCheck();

        $this->redirectToIndex('FeinwerkMarketplaceConnector', 'index');
        return '';
    }

    public function switchNow(): string
    {
        Piwik::checkUserHasSuperUserAccess();
        if ($this->passedSecurityCheck()) {
            UpgradeSwapper::run();
        }

        $this->redirectToIndex('FeinwerkMarketplaceConnector', 'index');
        return '';
    }

    /**
     * Shown instead of Matomo's browser run of the database update on a large instance (B1,
     * FeinwerkMarketplaceConnector::keepUpdatesOutOfTheBrowser): the console command and "Check again". Reached only
     * through that handler, also without login (Matomo's update page needs none): then only the command and the hint.
     */
    public function consoleUpdate(): string
    {
        if (FeinwerkMarketplaceConnector::$stoppedUpdate === null) {
            \Piwik\Url::redirectToUrl('index.php');

            return '';
        }

        $page = ConsoleUpdatePage::variables();
        try {
            $view = new \Piwik\View('@FeinwerkMarketplaceConnector/consoleUpdate');
            foreach ($page as $name => $value) {
                $view->$name = $value;
            }
            $view->browserRunUrl = ConsoleUpdatePage::BROWSER_RUN_URL;
            // What Matomo's own update layout reads (logo, favicon).
            $this->addCustomLogoInfo($view);
            $logo = new \Piwik\Plugins\CoreAdminHome\CustomLogo();
            $view->logoHeader = $logo->getHeaderLogoUrl();
            $view->logoLarge = $logo->getLogoUrl();
            $view->logoSVG = $logo->getSVGLogoUrl();
            $view->hasSVGLogo = $logo->hasSVGLogo();

            return $view->render();
        } catch (\Throwable $e) {
            return ConsoleUpdatePage::plain($page);
        }
    }

    /** $activate: true activates, false leaves it for the console, null keeps it on or off as it was (an update). */
    private function runInstall(string $slug, string $version, ?bool $activate = true): void
    {
        $result = $this->installOne($slug, $version, $activate);
        switch ($result['kind']) {
            case 'activate':
                $this->notifyActivateOnConsole($result['installed']);
                break;
            case 'db':
                $this->notifyDbUpdateRequired($result['installed']);
                break;
            default:
                $contexts = ['done' => Notification::CONTEXT_SUCCESS, 'dev' => Notification::CONTEXT_WARNING];
                $this->notify($result['message'], $contexts[$result['kind']] ?? Notification::CONTEXT_ERROR);
        }
    }

    /**
     * Downloads and installs one plugin (one click, or one step of "update several at once"). Never throws.
     *
     * @param  bool|null  $activate  true: activate; false: leave it for plugin:activate on the console; null: an update
     *     keeps the plugin switched on or off as it was (review 5.1.0: an update switched on what the admin had turned off)
     * @return array{kind: string, installed: string, message: string} kind: done, db (a database update is pending now),
     *     activate (left for plugin:activate on the console), dev (development checkout, refused), refused (403), failed
     */
    private function installOne(string $slug, string $version, ?bool $activate): array
    {
        if (InstalledMarketplacePlugins::isDevCheckout($slug)) {
            return ['kind' => 'dev', 'installed' => $slug, 'message' => Piwik::translate('FeinwerkMarketplaceConnector_DevCheckoutRefused', [$this->displayName($slug)])];
        }
        $current = $this->alreadyInstalled($slug, $version);
        if ($current !== null) {
            return $current;
        }

        try {
            $client = $this->client($this->settings());
            $artifact = $client->download($slug, $version);
            $alreadyActive = Manager::getInstance()->isPluginActivated($slug);
            $installed = (new PluginInstallerBridge())->install($artifact, $activate === null ? $alreadyActive : ($activate || $alreadyActive));
            HeldDbUpdates::release($slug);   // a held automatic update the admin has now installed
            $done = Piwik::translate('FeinwerkMarketplaceConnector_InstallDone', [$this->displayName($slug), $version]);

            if ($activate === false && ! $alreadyActive) {
                return ['kind' => 'activate', 'installed' => $installed, 'message' => $done];
            }
            // Active before the Matomo update, but the swap had to give up on it (license, failed installs): on again (RC-F1).
            ActivateLater::afterInstall($slug);

            return ['kind' => $this->hasPendingDbUpdates() ? 'db' : 'done', 'installed' => $installed, 'message' => $done];
        } catch (MarketplaceLicenseException $e) {
            // A 403 on the download: no license for this instance, or a revoked instance token (also 403, no stable
            // signal tells them apart), so the translated sentence names both. The marketplace's English one is not shown.
            return ['kind' => 'refused', 'installed' => $slug, 'message' => Piwik::translate('FeinwerkMarketplaceConnector_InstallRefused', [$this->displayName($slug)])];
        } catch (\Throwable $e) {
            return ['kind' => 'failed', 'installed' => $slug, 'message' => Piwik::translate('FeinwerkMarketplaceConnector_InstallFailed', [$this->displayName($slug), $e->getMessage()])];
        }
    }

    /**
     * One plugin of "update several at once" (Manage and Aufgaben tabs, Peter 2026-10-22): the browser sends them one
     * after the other, so no single request has to outlast several downloads and installs (the Bad Gateway lesson), and a
     * failed one does not stop the rest (Peter, 2026-10-27). JSON {ok, message, stop}; the results are kept in option
     * fwmc.batch for batchFinish(). Its own nonce, verified WITHOUT discard for every step and spent by batchFinish().
     * Once an update leaves a database update pending, Matomo sends every further request to its update page, so the
     * step answers stop: the rest ("rest", the slugs still to come) waits for the database update. The summary notice is
     * raised after every step, because the finish request may land on Matomo's update page as well.
     */
    public function batchUpdate(): string
    {
        Piwik::checkUserHasSuperUserAccess();
        \Piwik\Common::sendHeader('Content-Type: application/json; charset=utf-8');

        $post = fn (string $name) => trim(\Piwik\Common::getRequestVar($name, '', 'string', $_POST));
        if (! Nonce::verifyNonce(self::BATCH_NONCE, $post('nonce'))) {
            // The browser stops at once and asks to reload (review 5.1.0: an expired page used to fail silently).
            return (string) json_encode(['ok' => false, 'expired' => true, 'message' => Piwik::translate('FeinwerkMarketplaceConnector_BatchExpired')]);
        }
        $slug = $post('slug');
        $version = $post('version');
        // Already at that version (the page showed an older state, backlog 16): done, not "cannot be updated this way".
        $current = $this->isSlug($slug) && $slug !== $this->pluginName ? $this->alreadyInstalled($slug, $version) : null;
        if ($current === null && ! $this->batchCandidate($slug, $version)) {
            return (string) json_encode(['ok' => false, 'message' => Piwik::translate('FeinwerkMarketplaceConnector_BatchInvalid')]);
        }

        $batch = $post('first') === '1' ? self::emptyBatch() : self::batchState();
        $before = $this->pendingComponents();
        $result = $current ?? $this->installOne($slug, $version, null);   // an update leaves the plugin switched on or off as it was
        $added = array_diff($this->pendingComponents(), $before);
        // Only while Matomo runs updates in the browser does CoreUpdater::dispatch send every further request to its update
        // page; with enable_auto_update = 0 it returns early and the batch goes on.
        $stop = $added !== [] && \Piwik\SettingsPiwik::isAutoUpdateEnabled();
        $batch['results'][$slug] = ['kind' => $result['kind'], 'installed' => $result['installed'], 'version' => $version, 'message' => $result['message']];
        if ($stop) {
            $batch['held'] = array_values(array_unique(array_merge($batch['held'], $this->slugList($post('rest')))));
        }
        \Piwik\Option::set(self::BATCH_OPTION, (string) json_encode($batch));
        $this->notifyBatch($batch);

        return (string) json_encode(['ok' => in_array($result['kind'], ['done', 'db', 'activate'], true), 'message' => $result['message'], 'stop' => $stop]);
    }

    /**
     * The end of "update several at once": one summary notice, then back to the tab it was started from. The browser
     * adds what the server cannot know: a step without an answer and the plugins it did not start after it.
     */
    public function batchFinish(): string
    {
        Piwik::checkUserHasSuperUserAccess();

        $post = fn (string $name) => trim(\Piwik\Common::getRequestVar($name, '', 'string', $_POST));
        if (Nonce::verifyNonce(self::BATCH_NONCE, $post('nonce'))) {
            Nonce::discardNonce(self::BATCH_NONCE);
            $batch = self::batchState();
            $batch['held'] = array_values(array_unique(array_merge($batch['held'], $this->slugList($post('held')))));
            $batch['noAnswer'] = $this->slugList($post('no_answer'));
            $batch['notStarted'] = $this->slugList($post('not_started'));
            $this->notifyBatch($batch);
            \Piwik\Option::delete(self::BATCH_OPTION);
        } else {
            $this->notify(Piwik::translate('FeinwerkMarketplaceConnector_BatchExpired'), Notification::CONTEXT_WARNING);
        }
        $this->backToScreen();

        return '';
    }

    /**
     * The plugin is installed at $version or newer already (a page that still showed the update, backlog 16): a result
     * of kind done that says so, without a download. Null when it still needs the install.
     *
     * @return array{kind: string, installed: string, message: string}|null
     */
    private function alreadyInstalled(string $slug, string $version): ?array
    {
        $installed = $this->installedVersionOf($slug);   // guarded: installOne never throws (review 5.2.2)
        if ($installed === null || $version === '' || version_compare($installed, $version, '<')) {
            return null;
        }

        return ['kind' => 'done', 'installed' => $slug,
            'message' => Piwik::translate('FeinwerkMarketplaceConnector_AlreadyInstalled', [$this->displayName($slug), $installed])];
    }

    /**
     * Only what the admin could also update with a click: an installed plugin whose update is offered at exactly this
     * version, not a development checkout, not switching, licensed, not the connector itself (review 5.1.0: the check
     * was in the browser only).
     */
    private function batchCandidate(string $slug, string $version): bool
    {
        if (! $this->isSlug($slug) || $slug === $this->pluginName || $version === '') {
            return false;
        }
        foreach (PendingUpdates::rows() as $row) {
            if ($row->slug === $slug) {
                return $row->latestVersion === $version && ! ($row->needsLicense && $row->license === null);
            }
        }

        return false;
    }

    /** @return list<string> the components Matomo has a database update pending for; empty on any error */
    private function pendingComponents(): array
    {
        try {
            return array_map('strval', array_keys((new Updater())->getComponentUpdates() ?? []));
        } catch (\Throwable $e) {
            return [];
        }
    }

    /** @return list<string> the plugin names of a comma list (anything else is dropped) */
    private function slugList(string $list): array
    {
        return array_values(array_unique(array_filter(explode(',', $list), fn ($s) => $this->isSlug($s))));
    }

    /** @return array{results: array<string, array<string, string>>, held: list<string>, noAnswer: list<string>, notStarted: list<string>} */
    private static function emptyBatch(): array
    {
        return ['results' => [], 'held' => [], 'noAnswer' => [], 'notStarted' => []];
    }

    /** @return array{results: array<string, array<string, string>>, held: list<string>, noAnswer: list<string>, notStarted: list<string>} */
    private static function batchState(): array
    {
        $batch = json_decode((string) \Piwik\Option::get(self::BATCH_OPTION), true);

        return is_array($batch) ? array_merge(self::emptyBatch(), array_intersect_key($batch, self::emptyBatch())) : self::emptyBatch();
    }

    /** @param  array{results: array<string, array<string, string>>, held: list<string>, noAnswer: list<string>, notStarted: list<string>}  $batch */
    private function notifyBatch(array $batch): void
    {
        $done = [];
        $failed = [];
        $dbPlugin = null;
        foreach ((array) $batch['results'] as $slug => $result) {
            if (in_array($result['kind'] ?? '', ['done', 'db', 'activate'], true)) {
                $done[] = $this->displayName((string) $slug).' '.(string) ($result['version'] ?? '');
                if (($result['kind'] ?? '') === 'db') {
                    $dbPlugin = (string) ($result['installed'] ?? $slug);
                }
                if (($result['kind'] ?? '') === 'activate') {
                    $this->notifyActivateOnConsole((string) ($result['installed'] ?? $slug));
                }
            } else {
                $failed[] = (string) ($result['message'] ?? $slug);
            }
        }
        $names = fn (array $slugs) => implode(', ', array_map(fn ($s) => $this->displayName((string) $s), $slugs));
        $parts = [];
        if ($done !== []) {
            $parts[] = Piwik::translate('FeinwerkMarketplaceConnector_BatchDone', [implode(', ', $done)]);
        }
        if ($failed !== []) {
            $parts[] = Piwik::translate('FeinwerkMarketplaceConnector_BatchFailed', [implode(' ', $failed)]);
        }
        if ($batch['noAnswer'] !== []) {
            $parts[] = Piwik::translate('FeinwerkMarketplaceConnector_BatchNoAnswer', [$names($batch['noAnswer'])]);
        }
        if ($batch['held'] !== []) {
            $parts[] = Piwik::translate('FeinwerkMarketplaceConnector_BatchHeld', [$names($batch['held'])]);
        }
        if ($batch['notStarted'] !== []) {
            $parts[] = Piwik::translate('FeinwerkMarketplaceConnector_BatchNotStarted', [$names($batch['notStarted'])]);
        }
        $allWell = $failed === [] && $batch['noAnswer'] === [] && $batch['held'] === [] && $batch['notStarted'] === [];
        if ($parts !== []) {
            $this->notify(implode(' ', $parts), $allWell ? Notification::CONTEXT_SUCCESS : Notification::CONTEXT_WARNING);
        }
        if ($dbPlugin !== null) {
            $this->notifyDbUpdateRequired($dbPlugin);
        }
    }

    /** The plugin's name as the marketplace shows it (cached list), else its technical name as words. */
    private function displayName(string $slug): string
    {
        foreach (PendingUpdates::cachedPlugins() as $plugin) {
            if (($plugin['slug'] ?? null) === $slug) {
                return PluginName::of($plugin);
            }
        }

        return PluginName::spaced($slug);
    }

    /**
     * Does the just-installed plugin (or anything else) leave Matomo with a
     * pending database migration? If so, CoreUpdater will intercept the next
     * request and detour the user through its upgrade wizard — which ends on
     * the dashboard, not on our screen.
     */
    private function hasPendingDbUpdates(): bool
    {
        try {
            return !empty((new Updater())->getComponentUpdates());
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Persistent (must be dismissed) so it survives the CoreUpdater detour and
     * hands the user a way back to this screen wherever they land.
     */
    private function notifyDbUpdateRequired(string $installed): void
    {
        $safe = htmlspecialchars($this->displayName($installed), ENT_QUOTES, 'UTF-8');
        $backUrl = 'index.php?module=FeinwerkMarketplaceConnector&action=index';
        $command = '<code>'.htmlspecialchars(ConsoleCommand::of('core:update'), ENT_QUOTES, 'UTF-8').'</code>';
        // Large instance or no updates in the browser: the database update belongs on the console.
        $console = ! \Piwik\SettingsPiwik::isAutoUpdateEnabled() || InstanceSizeProbe::measure()['large'];

        $notification = new Notification(
            Piwik::translate($console ? 'FeinwerkMarketplaceConnector_DbUpdateConsole' : 'FeinwerkMarketplaceConnector_DbUpdateBrowser', [$safe, $command])
            .' <a href="'.$backUrl.'">'.htmlspecialchars(Piwik::translate('FeinwerkMarketplaceConnector_OpenScreen'), ENT_QUOTES, 'UTF-8').'</a>'
        );
        $notification->context = Notification::CONTEXT_WARNING;
        $notification->type = Notification::TYPE_PERSISTENT;
        $notification->raw = true;
        NotificationManager::notify(FeinwerkMarketplaceConnector::NOTICE_DB_UPDATE, $notification);
    }

    /** Size of this Matomo and its console command for the warning before an install (InstallForm). */
    private static function instanceInfo(): array
    {
        $info = InstanceSizeProbe::measure() + [
            'console' => ConsoleCommand::of(''),
            'visitsLabel' => null,
        ];
        try {
            // In the admin's language; the page itself carries no locale the browser could use.
            if ($info['visits'] !== null) {
                $info['visitsLabel'] = \Piwik\NumberFormatter::getInstance()->formatNumber($info['visits']);
            }
        } catch (\Throwable $e) {
        }

        return $info;
    }

    /** Installed without activation (large instance, new log table columns): activate it on the console. */
    private function notifyActivateOnConsole(string $installed): void
    {
        $notification = new Notification(Piwik::translate('FeinwerkMarketplaceConnector_ActivateOnConsole', [
            htmlspecialchars($this->displayName($installed), ENT_QUOTES, 'UTF-8'),
            '<code>'.htmlspecialchars(ConsoleCommand::of('plugin:activate '.$installed), ENT_QUOTES, 'UTF-8').'</code>',
        ]));
        $notification->context = Notification::CONTEXT_WARNING;
        $notification->type = Notification::TYPE_PERSISTENT;
        $notification->raw = true;
        // One notice per plugin: FeinwerkMarketplaceConnector::clearDoneConsoleNotices() reads the plugin from the id.
        NotificationManager::notify(FeinwerkMarketplaceConnector::NOTICE_ACTIVATE.'_'.$installed, $notification);
    }

    private function notify(string $message, string $context): void
    {
        $notification = new Notification($message);
        $notification->context = $context;
        $notification->type = Notification::TYPE_TRANSIENT;
        NotificationManager::notify('FeinwerkMarketplaceConnector_result', $notification);
    }

    /** @return array{url: string, token: string} */
    private function settings(): array
    {
        $settings = StaticContainer::get(SystemSettings::class);

        return [
            'url' => MarketplaceClientFactory::url(),
            'token' => (string) $settings->instanceToken->getValue(),
        ];
    }

    /** @param array{url: string, token: string} $settings */
    private function client(array $settings): MarketplaceApiClient
    {
        return MarketplaceClientFactory::make($settings['url'], $settings['token']);
    }

    /** @param array{url: string, token: string} $settings */
    private function marketplaceCache(array $settings): MarketplaceCache
    {
        return new MarketplaceCache($this->client($settings), new OptionCacheStore());
    }
}
