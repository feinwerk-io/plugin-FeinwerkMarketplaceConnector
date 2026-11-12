<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto\UpgradeState;

/** Decides on every admin request / task run whether to remember the plugins or to start a swap. */
final class UpgradeDetector
{
    public const RECORD = 'record';
    public const PENDING = 'pending';
    public const NOTHING = 'nothing';

    public static function decide(?UpgradeState $stored, ?int $currentMajor, bool $swapPending, bool $outgrownInstalled): string
    {
        if ($currentMajor === null || $swapPending) {
            return self::NOTHING;   // unknown version, or the swapper owns the state until it finishes
        }
        if ($stored !== null && $stored->matomoMajor !== $currentMajor) {
            return self::PENDING;
        }
        if ($stored === null && $outgrownInstalled) {
            return self::PENDING;   // upgraded while an older connector ran: nothing remembered, builds outgrown
        }

        return self::RECORD;
    }
}
