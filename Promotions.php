<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Container\StaticContainer;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\PluginName;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\PromotionSwitch;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\UpdateMapper;

/**
 * Shared interface for the other Feinwerk plugins (house rule docs/promotions.md, Peter 2026-11-10): may they show
 * their notes on other Feinwerk offers (admin home banner, context boxes, tip line)? Their promo kit calls areEnabled()
 * behind class_exists, method_exists and the connector's activation, inside catch (\Throwable). Released plugins call
 * it: never rename it or change its meaning (tests/Glue/PluginContractTest, tests/contract/consumers.php). The
 * connector itself shows no promotions; its own recommendation banner has the setting "Show recommendations".
 */
final class Promotions
{
    /** This request's answer, null until the first call (each web request is a new process; tests reset it). */
    private static ?bool $enabled = null;

    /** This request's demo switch, null until the first call. */
    private static ?bool $demo = null;

    /**
     * False when `promotions = 0` (or off, no, false, empty) stands in [FeinwerkMarketplaceConnector] of config.ini.php
     * or the setting "Show notes on other Feinwerk offers" is off. True otherwise, also when either cannot be read.
     * Reads both once per request and never throws.
     */
    public static function areEnabled(): bool
    {
        if (self::$enabled === null) {
            self::$enabled = ! self::offInConfig() && self::settingOn();
        }

        return self::$enabled;
    }

    /**
     * Shared interface (2026-11-13) for the promotion placements Peter defines per plugin (PageFilterReport, CampaignScore):
     * up to $max paid Feinwerk plugins this Matomo does not have, in the store's order, from the cached plugin list only
     * (no request). Each entry: slug, name (the store's display name), description and price ("from 59,00 € / month") in
     * the admin's language ('' without), image (the connector's image proxy for the artwork, '' without one), url (the
     * plugin's window on the Feinwerk Plugins screen). Both links need a super user, so the list is empty for anyone
     * else, when promotions are off (areEnabled) or before the connector is connected. Never throws; released plugins
     * call it: never rename it or change its meaning. For admins as well: paidPluginRecommendations().
     *
     * @return list<array{slug: string, name: string, description: string, price: string, image: string, url: string}>
     */
    public static function paidPluginsNotInstalled(int $max = 3): array
    {
        try {
            if (! \Piwik\Piwik::hasUserSuperUserAccess()) {
                return [];
            }

            return array_map(fn ($c) => [
                'slug' => $c['slug'],
                'name' => $c['name'],
                'description' => $c['description'],
                'price' => $c['price'],
                'image' => $c['image'],
                'url' => $c['installUrl'],
            ], self::candidates($max, true));
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Shared interface (2026-11-16, Peter: admins see the recommendations too, "Oft ist es so, dass der Superuser der IT
     * Admin / Verwalter ist, der nicht mit den Zahlen arbeitet"): the same plugins as paidPluginsNotInstalled() for every
     * user with admin access to at least one site. Each entry: slug, name, description, price, image (the image proxy,
     * super users only, else ''), storeUrl (the plugin's public store page in the user's language), installUrl (the
     * plugin's window on the Feinwerk Plugins screen, super users only, else ''). Empty for users without admin access,
     * when promotions are off or before the connector is connected. From the cached list only, never throws; released
     * plugins will call it: never rename it or change its meaning.
     *
     * @return list<array{slug: string, name: string, description: string, price: string, image: string, storeUrl: string, installUrl: string}>
     */
    public static function paidPluginRecommendations(int $max = 3): array
    {
        try {
            if (! \Piwik\Piwik::isUserHasSomeAdminAccess()) {
                return [];
            }

            return self::candidates($max, \Piwik\Piwik::hasUserSuperUserAccess());
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Shared interface (2026-11-25, asked by Page Filter Report for the kit's Campaign Manager tip, Peter's decision 8 of
     * 2026-11-23): the address of one Feinwerk plugin's window on the Feinwerk Plugins screen, where a super user installs
     * it, free or paid. '' unless the current user is a super user, promotions are on, the connector is connected, this
     * is no Matomo for WordPress, and the cached store list names the plugin with a build for this Matomo while it is not
     * installed here (demo mode: any listed plugin). Never the connector itself. From the cached list only, no request,
     * never throws; released plugins will call it: never rename it or change its meaning.
     *
     * @param  string  $slug  the plugin's technical name, e.g. "FeinwerkCampaignManager"
     * @return string  a relative Matomo URL (index.php?module=FeinwerkMarketplaceConnector&action=index&fwmc_open=<slug>) or ''
     */
    public static function pluginInstallUrl(string $slug): string
    {
        try {
            if (! preg_match('/^Feinwerk[A-Za-z0-9]+$/', $slug) || $slug === 'FeinwerkMarketplaceConnector'
                || ! \Piwik\Piwik::hasUserSuperUserAccess() || ! self::areEnabled() || MatomoForWordPress::detected()) {
                return '';
            }
            foreach (PendingUpdates::cachedPlugins() as $plugin) {   // empty without an instance token
                if (! is_array($plugin) || ($plugin['slug'] ?? null) !== $slug) {
                    continue;
                }
                if (! self::demo() && (empty($plugin['latest']['version']) || isset(InstalledMarketplacePlugins::versions([$plugin])[$slug]))) {
                    return '';
                }

                return 'index.php?module=FeinwerkMarketplaceConnector&action=index&fwmc_open='.rawurlencode($slug);
            }

            return '';
        } catch (\Throwable $e) {
            return '';
        }
    }

    /**
     * Paid Feinwerk plugins this Matomo lacks (has a build, not installed, not the connector), store order, cached list.
     *
     * @return list<array{slug: string, name: string, description: string, price: string, image: string, storeUrl: string, installUrl: string}>
     */
    private static function candidates(int $max, bool $superUser): array
    {
        // Matomo for WordPress: paid Feinwerk plugins cannot be installed there yet (MatomoForWordPress).
        if (! self::areEnabled() || MatomoForWordPress::detected()) {
            return [];
        }
        $plugins = PendingUpdates::cachedPlugins();   // empty without an instance token
        $installed = InstalledMarketplacePlugins::versions($plugins);
        $lang = str_starts_with((string) \Piwik\Plugins\LanguagesManager\LanguagesManager::getLanguageCodeForCurrentUser(), 'de') ? 'de' : 'en';
        $mapper = new UpdateMapper($lang);
        $store = rtrim(MarketplaceClientFactory::url(), '/');
        $demo = self::demo();
        $out = [];
        foreach ($plugins as $plugin) {
            $slug = is_array($plugin) ? (string) ($plugin['slug'] ?? '') : '';
            if (! preg_match('/^Feinwerk[A-Za-z0-9]+$/', $slug) || $slug === 'FeinwerkMarketplaceConnector'
                || ($plugin['tier'] ?? '') !== 'paid'
                || (! $demo && (empty($plugin['latest']['version']) || isset($installed[$slug])))) {
                continue;
            }
            $artwork = (string) ($plugin['artwork_url'] ?? '');
            $out[] = [
                'slug' => $slug,
                'name' => PluginName::of($plugin),
                'description' => (string) $mapper->describe($plugin),
                'price' => self::priceFrom($plugin['price_from'] ?? null),
                'image' => $superUser && preg_match('#^https?://#i', $artwork)
                    ? 'index.php?module=FeinwerkMarketplaceConnector&action=image&src='.rawurlencode($artwork) : '',
                'storeUrl' => $store.'/'.$lang.'/plugins/'.rawurlencode($slug),
                'installUrl' => $superUser ? 'index.php?module=FeinwerkMarketplaceConnector&action=index&fwmc_open='.rawurlencode($slug) : '',
            ];
            if (count($out) >= max(1, $max)) {
                break;
            }
        }

        return $out;
    }

    /** "from 59,00 € / month" (or "... incl. VAT" with the store's VAT rate) in the admin's language, '' without a price. */
    private static function priceFrom($price): string
    {
        if (! is_array($price) || ! is_numeric($price['amount_eur'] ?? null)) {
            return '';
        }
        $intervals = ['monthly' => 'IntervalMonthly', 'yearly' => 'IntervalYearly', 'two_years' => 'IntervalTwoYears', 'once' => 'IntervalOnce'];
        try {
            $amount = \Piwik\NumberFormatter::getInstance()->formatNumber((float) $price['amount_eur'], 2, 2);
        } catch (\Throwable $e) {
            $amount = number_format((float) $price['amount_eur'], 2);
        }

        // The store's prices are gross; with its VAT rate the text says so (Peter, 2026-11-26: "Netto und brutto").
        $vat = is_numeric($price['vat_rate'] ?? null) && (float) $price['vat_rate'] > 0;

        return \Piwik\Piwik::translate($vat ? 'FeinwerkMarketplaceConnector_PriceFromVat' : 'FeinwerkMarketplaceConnector_PriceFrom', [
            $amount,
            \Piwik\Piwik::translate('FeinwerkMarketplaceConnector_'.($intervals[$price['interval'] ?? ''] ?? 'IntervalMonthly')),
        ]);
    }

    /**
     * Demo mode for the test instances (Peter, 2026-11-16: "kannst du einen demo modus einführen, damit stats6 auch wieder
     * die werbung zeigt?"; docs/promotions.md): `promotions_demo = 1` in [FeinwerkMarketplaceConnector] of config.ini.php.
     * Config only, default off, never at customers. Then the recommendations show the whole paid catalog (installed
     * plugins and ones without a build too) and the admin-home banner shows even when everything is installed. Every off
     * switch still wins, the audience stays the same. Read once per request, never throws.
     */
    public static function demo(): bool
    {
        if (self::$demo === null) {
            try {
                $section = \Piwik\Config::getInstance()->FeinwerkMarketplaceConnector;
                self::$demo = PromotionSwitch::demoIn(is_array($section) ? $section : []);
            } catch (\Throwable $e) {
                self::$demo = false;
            }
        }

        return self::$demo;
    }

    /** `promotions = 0` in [FeinwerkMarketplaceConnector]: wins over the setting, whose help then says so. */
    public static function offInConfig(): bool
    {
        try {
            $section = \Piwik\Config::getInstance()->FeinwerkMarketplaceConnector;

            return PromotionSwitch::offIn(is_array($section) ? $section : []);
        } catch (\Throwable $e) {
            return false;
        }
    }

    private static function settingOn(): bool
    {
        try {
            // ?? keeps a settings object without the field from raising a PHP warning in Matomo's log.
            $setting = StaticContainer::get(SystemSettings::class)->showPromotions ?? null;

            return ! is_object($setting) || (bool) $setting->getValue();
        } catch (\Throwable $e) {
            return true;
        }
    }
}
