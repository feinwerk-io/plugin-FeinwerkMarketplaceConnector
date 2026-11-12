<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions;

/** The marketplace answered 404 without an error code: it is a store that does not have this endpoint yet. */
class MarketplaceFeatureMissingException extends MarketplaceException
{
}
