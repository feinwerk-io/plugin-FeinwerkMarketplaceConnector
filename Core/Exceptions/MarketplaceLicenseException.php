<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions;

/** The marketplace refused a download because this instance has no valid license for the plugin (HTTP 403). */
class MarketplaceLicenseException extends MarketplaceException
{
}
