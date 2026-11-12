<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Container\StaticContainer;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MarketplaceCache;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Http\OptionCacheStore;

/**
 * What the Feinwerk Plugins screen shows that can change while a page stays open (backlog 13, Peter 2026-10-28: the
 * connector kept suggesting a database update that had already run, e.g. on the console): installed versions and their
 * on/off state, pending Feinwerk database updates, held automatic updates, a pending swap and the cached store list.
 * The page compares it with Controller::state when the admin comes back to it and loads itself again when it changed.
 */
final class ScreenState
{
    /** A short hash; '' when it cannot be read (the page then keeps what it shows). Never throws. */
    public static function fingerprint(): string
    {
        try {
            $url = MarketplaceClientFactory::url();
            $token = (string) StaticContainer::get(SystemSettings::class)->instanceToken->getValue();
            if ($url === '' || $token === '') {
                return '';
            }
            $cached = (new MarketplaceCache(MarketplaceClientFactory::make($url, $token), new OptionCacheStore()))->cached();
            $installed = InstalledMarketplacePlugins::versions($cached->plugins);

            return substr(sha1((string) json_encode([
                $installed,
                InstalledMarketplacePlugins::inactive(array_keys($installed)),
                FeinwerkMarketplaceConnector::pendingFeinwerkComponents(),
                HeldDbUpdates::all(),
                UpgradeWatcher::pending() !== null,
                $cached->fetchedAt,
                $cached->errorKind,
            ])), 0, 16);
        } catch (\Throwable $e) {
            return '';
        }
    }
}
