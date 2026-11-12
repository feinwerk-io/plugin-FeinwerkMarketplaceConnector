<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions;

/** The marketplace could not be reached at all (no network, DNS, TLS or timeout), as opposed to an HTTP error answer. */
class MarketplaceUnreachableException extends MarketplaceException
{
}
