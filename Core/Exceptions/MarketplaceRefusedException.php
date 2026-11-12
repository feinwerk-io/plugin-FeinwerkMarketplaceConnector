<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions;

/** The marketplace refused a request with a machine-readable code (e.g. no_free_seat, not_for_sale). */
class MarketplaceRefusedException extends MarketplaceException
{
    public function __construct(public readonly string $refusal, string $message = '')
    {
        parent::__construct($message);
    }
}
