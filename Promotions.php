<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Container\StaticContainer;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\PromotionSwitch;

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
