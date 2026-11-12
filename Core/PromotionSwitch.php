<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/**
 * The config switch of the Feinwerk promotions (house rule docs/promotions.md, Peter 2026-11-10):
 * `promotions = 0` in [FeinwerkMarketplaceConnector] of config.ini.php switches the notes on other Feinwerk offers off
 * for all Feinwerk plugins. The same reading as the promo kit's PromoLine::switchOn (FeinwerkTrackingSpamScore), so a
 * value means the same to the connector and to every plugin that reads the section itself.
 */
final class PromotionSwitch
{
    /**
     * Whether a config section switches promotions off. No `promotions` key: not off. Matomo's INI reader turns off, no
     * and false into PHP false; 0, an empty value and those words in any case count as off as well.
     *
     * @param array<string, mixed> $section
     */
    public static function offIn(array $section): bool
    {
        if (! array_key_exists('promotions', $section)) {
            return false;
        }
        $value = $section['promotions'];
        if ($value === false || $value === null) {
            return true;
        }
        if (! is_scalar($value)) {
            return false;
        }

        return in_array(strtolower(trim((string) $value)), ['0', '', 'off', 'no', 'false'], true);
    }
}
