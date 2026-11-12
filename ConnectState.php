<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Option;

/**
 * The connection this Matomo started with the connect button (state, PKCE verifier, start time; see Core/ConnectFlow).
 * One at a time: a new start replaces it, the return consumes it. Never throws.
 */
final class ConnectState
{
    private const OPTION = 'fwmc.connect';

    /** @param array{state: string, verifier: string, created_at: int} $pending */
    public static function put(array $pending): void
    {
        try {
            Option::set(self::OPTION, json_encode($pending));
        } catch (\Throwable $e) {
            // The return then finds nothing and asks to start again.
        }
    }

    /** Reads and removes the pending connection, so a state works once. */
    public static function take(): ?array
    {
        try {
            $value = json_decode((string) Option::get(self::OPTION), true);
            Option::delete(self::OPTION);

            return is_array($value) ? $value : null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
