<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Common;
use Piwik\Date;
use Piwik\Notification;
use Piwik\Notification\Manager as NotificationManager;
use Piwik\Option;
use Piwik\Piwik;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\LicenseEnd;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\PluginName;

/**
 * Lapsed licences (Peter, 2026-11-17, written in the store session: "warum sollten die Plugins teilweise ohne Hinweis
 * weiterlaufen? Alle Plugins, deren Lizenz mehr als 7 Tage abgelaufen sind, sollen allen Superadmins und Admins
 * Warnhinweise anzeigen"). One combined warning for every switched-on paid Feinwerk plugin whose bought licence ended
 * more than 7 days ago (Core/LicenseEnd), to super users and admins of a site, never to view-only users. It is a
 * persistent Matomo notification with a close button, on every full page until the user closes it: the × (Matomo's
 * CoreHome.markNotificationAsRead with this id, counted after Matomo's own token check) marks the day for that user, so it
 * is back the next day until renewed. A new session the same day shows it again unless it was closed. Withdrawn as soon
 * as the cached list says nothing is lapsed (a purchase in the plugin window refreshes it at once). The plugins keep working. From the
 * cached list only: no store call, nothing before connecting. Never throws.
 */
final class LicenseEndNotice
{
    public const ID = 'FeinwerkMarketplaceConnector_licenseEnded';

    /** Per user (md5 of the login): the day the user closed the warning. */
    public const OPTION_PREFIX = 'fwmc.licenseend.';

    /** For tests: the current time. */
    public static ?int $now = null;

    public static function maybeRaise(?string $module, ?string $action = null): void
    {
        try {
            if (in_array($module, ['API', 'Proxy', 'Widgetize', 'Login'], true) || Common::isPhpCliMode() || ! Piwik::isUserHasSomeAdminAccess()) {
                return;
            }
            $now = self::$now ?? time();
            // Only full pages show it: AJAX, widgets, sparkline images and anything that is no HTML page skip the list.
            $accept = (string) ($_SERVER['HTTP_ACCEPT'] ?? '');
            if (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest' || ! empty($_GET['widget']) || isset($_GET['viewDataTable'])
                || isset($_GET['forceView']) || ($accept !== '' && ! str_contains($accept, 'text/html'))) {
                return;
            }
            $manager = \Piwik\Plugin\Manager::getInstance();
            $rows = Licensing::cachedRows();
            $lapsed = array_filter(LicenseEnd::lapsed(array_values($rows), $now),
                fn ($slug) => $manager->isPluginActivated((string) $slug), ARRAY_FILTER_USE_KEY);
            if ($lapsed === []) {
                NotificationManager::cancel(self::ID);   // renewed (or nothing known): gone at once

                return;
            }
            if (Option::get(self::optionKey(Piwik::getCurrentUserLogin())) === gmdate('Y-m-d', $now)) {
                NotificationManager::cancel(self::ID);   // closed today, also in this user's other sessions

                return;
            }
            $notification = new Notification(self::message($lapsed, $rows, Piwik::hasUserSuperUserAccess()));
            $notification->title = 'Feinwerk Plugins';
            $notification->context = Notification::CONTEXT_WARNING;
            $notification->type = Notification::TYPE_PERSISTENT;
            $notification->flags = Notification::FLAG_CLEAR;
            $notification->raw = true;
            NotificationManager::notify(self::ID, $notification);   // the same id: shown once, refreshed per page
        } catch (\Throwable $e) {
            // A notice must never break a page.
        }
    }

    /**
     * The × on the warning, after Matomo's own token check (Controller.CoreHome.markNotificationAsRead.end): closed for
     * today for this user. Never throws.
     */
    public static function closed(): void
    {
        try {
            if (Common::getRequestVar('notificationId', '', 'string') === self::ID && ! Piwik::isUserIsAnonymous()) {
                Option::set(self::optionKey(Piwik::getCurrentUserLogin()), gmdate('Y-m-d', self::$now ?? time()));
            }
        } catch (\Throwable $e) {
        }
    }

    public static function optionKey(string $login): string
    {
        return self::OPTION_PREFIX.md5($login);
    }

    /**
     * @param  array<string, int>  $lapsed  slug => end
     * @param  array<string, array<mixed>>  $rows  the cached list by slug (names without another read)
     */
    private static function message(array $lapsed, array $rows, bool $superUser): string
    {
        $lang = str_starts_with((string) \Piwik\Plugins\LanguagesManager\LanguagesManager::getLanguageCodeForCurrentUser(), 'de') ? 'de' : 'en';
        $store = rtrim(MarketplaceClientFactory::url(), '/');
        $items = [];
        foreach ($lapsed as $slug => $endedAt) {
            // A super user renews in the plugin window (buying there refreshes the list, so the warning goes at once);
            // an admin gets the store page.
            $link = $superUser
                ? '<a href="'.htmlspecialchars('index.php?module=FeinwerkMarketplaceConnector&action=index&fwmc_open='.rawurlencode((string) $slug), ENT_QUOTES).'">'
                : '<a href="'.htmlspecialchars($store.'/'.$lang.'/plugins/'.rawurlencode((string) $slug), ENT_QUOTES).'" target="_blank" rel="noopener noreferrer">';
            $items[] = Piwik::translate('FeinwerkMarketplaceConnector_LicenseEndedItem', [
                htmlspecialchars(PluginName::of($rows[$slug] ?? ['slug' => $slug]), ENT_QUOTES),
                htmlspecialchars(Date::factory($endedAt)->getLocalized(Date::DATE_FORMAT_SHORT), ENT_QUOTES),
                $link,
                '</a>',
            ]);
        }
        $message = Piwik::translate('FeinwerkMarketplaceConnector_LicenseEnded', [implode('; ', $items)]);

        return $superUser ? $message : $message.' '.Piwik::translate('FeinwerkMarketplaceConnector_LicenseEndedAdminNote');
    }
}
