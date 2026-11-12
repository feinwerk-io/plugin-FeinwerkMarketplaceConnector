<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

/**
 * Read-only stand-in for the former `marketplaceUrl` setting (SystemSettings::$marketplaceUrl): other Feinwerk plugins
 * read `->getValue()` from it for their license checks. Not registered as a setting, so it never shows in the UI.
 */
final class MarketplaceUrlValue
{
    public function getValue(): string
    {
        return MarketplaceClientFactory::url();
    }
}
