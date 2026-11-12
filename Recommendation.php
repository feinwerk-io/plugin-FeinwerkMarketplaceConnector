<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Container\StaticContainer;
use Piwik\Nonce;
use Piwik\Piwik;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Promotion;

/**
 * The recommendation banner (backlog 1): the plugin of the day (Core/Promotion) for a super user, unless the setting
 * "Show recommendations" is off. Cache only, never throws, nothing is sent anywhere.
 */
final class Recommendation
{
    /** The banner's own nonce for "switch recommendations off" (Controller::switchOffRecommendations). */
    public const NONCE = 'FeinwerkMarketplaceConnector.recommendations';

    /** @return array<string, mixed>|null */
    public static function current(): ?array
    {
        try {
            if (! Piwik::hasUserSuperUserAccess() || ! self::enabled()) {
                return null;
            }
            $plugins = PendingUpdates::cachedPlugins();
            if ($plugins === []) {
                return null;
            }
            $promo = Promotion::pick($plugins, InstalledMarketplacePlugins::versions($plugins), 'FeinwerkMarketplaceConnector', intdiv(time(), 86400));
            if ($promo === null) {
                return null;
            }

            // Switched off right in the banner (backlog 12, Peter 2026-10-28), on both places it shows.
            return $promo + [
                'offUrl' => 'index.php?module=FeinwerkMarketplaceConnector&action=switchOffRecommendations',
                'offNonce' => Nonce::getNonce(self::NONCE, 3600),
            ];
        } catch (\Throwable $e) {
            return null;
        }
    }

    /** Sets "Show recommendations" off, like unticking it under General settings. False when that failed. */
    public static function switchOff(): bool
    {
        try {
            $settings = StaticContainer::get(SystemSettings::class);
            $settings->showRecommendations->setValue(false);
            $settings->save();

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function enabled(): bool
    {
        // promotions = 0 in [FeinwerkMarketplaceConnector] hides the recommendations too (Peter, 2026-11-10: whoever
        // writes it expects no Feinwerk notes at all). The setting "Show notes on other Feinwerk offers" does not: the
        // two checkboxes stay separate.
        if (Promotions::offInConfig()) {
            return false;
        }
        try {
            return (bool) StaticContainer::get(SystemSettings::class)->showRecommendations->getValue();
        } catch (\Throwable $e) {
            return true;
        }
    }

    /** The banner on Matomo's Administration home page (event Template.beforeContent, layout admin). */
    public static function adminHomeMarkup(): string
    {
        $promo = self::current();
        if ($promo === null) {
            return '';
        }
        $json = htmlspecialchars((string) json_encode($promo), ENT_QUOTES, 'UTF-8');

        return '<div class="fwmc fwmc-promo-host"><div vue-entry="FeinwerkMarketplaceConnector.PromoBanner" promo="'.$json.'"></div></div>';
    }
}
