<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Nonce;
use Piwik\Option;
use Piwik\Piwik;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto\PluginStatusRow;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Starter;

/**
 * The first-step card on Discover right after Connect (Peter, 2026-11-16, relayed by PluginLead: "1. ok"; a connector
 * placement Peter named). Connecting with the button starts it (option fwmc.starter); it shows the plugin the store's
 * `starter` names, in place of the recommendation banner, for super users only, while recommendations and promotions
 * are on, for at most DAYS days, and ends for good when closed or once that plugin is installed. Nothing is sent: the
 * data comes from the cached list. Never throws.
 */
final class StarterCard
{
    public const OPTION = 'fwmc.starter';

    public const NONCE = 'FeinwerkMarketplaceConnector.starter';

    public const DAYS = 14;

    /** After a successful Connect with the button. */
    public static function begin(): void
    {
        try {
            Option::set(self::OPTION, (string) json_encode(['since' => time()]));
        } catch (\Throwable $e) {
        }
    }

    /**
     * The card for the Discover tab, or null.
     *
     * @param  array{plugin: string, line: string, line_de: ?string}|null  $starter  the cached list's `starter`
     * @param  list<PluginStatusRow>  $rows
     * @return array<string, mixed>|null
     */
    public static function current(?array $starter, array $rows, string $lang): ?array
    {
        try {
            $since = json_decode((string) Option::get(self::OPTION), true)['since'] ?? null;
            if (! is_int($since)) {
                return null;
            }
            if ($since < time() - self::DAYS * 86400 || Starter::installed($starter, $rows)) {
                self::close();

                return null;
            }
            if (! Piwik::hasUserSuperUserAccess() || ! Recommendation::enabled()) {
                return null;
            }
            $card = Starter::pick($starter, $rows, $lang);

            return $card === null ? null : $card + [
                'closeUrl' => 'index.php?module=FeinwerkMarketplaceConnector&action=closeStarter',
                'closeNonce' => Nonce::getNonce(self::NONCE, 3600),
            ];
        } catch (\Throwable $e) {
            return null;
        }
    }

    public static function close(): bool
    {
        try {
            Option::delete(self::OPTION);

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
