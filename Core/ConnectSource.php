<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/**
 * Where this install came from, for the campaign of the connect link (Peter, 2026-11-11): the store's ZIP has no docs/
 * (the store's ZipBuilder strips it), the build on the Matomo Marketplace mirror keeps docs/faq.md. A development
 * checkout counts as neither. Only the connect link carries it, when the admin clicks Connect.
 */
final class ConnectSource
{
    public const MATOMO_MARKETPLACE = 'matomo-marketplace';

    public const STORE = 'feinwerk-store';

    public static function of(string $pluginDir, bool $devCheckout): ?string
    {
        if ($devCheckout) {
            return null;
        }

        return is_file(rtrim($pluginDir, '/').'/docs/faq.md') ? self::MATOMO_MARKETPLACE : self::STORE;
    }
}
