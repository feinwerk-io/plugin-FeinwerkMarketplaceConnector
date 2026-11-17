<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions;

/**
 * The store did not start a trial (409 trial_used, 422 not_available, not_eligible or email_unverified). It sends its
 * own sentence in the request's language, shown to the admin as it is (Matomo escapes notices).
 */
final class MarketplaceTrialRefusedException extends MarketplaceRefusedException
{
    public function __construct(string $refusal, public readonly string $storeMessage = '')
    {
        parent::__construct($refusal, "Marketplace did not start the trial ({$refusal}).");
    }
}
