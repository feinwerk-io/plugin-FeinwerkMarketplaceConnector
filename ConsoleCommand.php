<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

/** "php <matomo>/console <args>" as an admin would type it on the server; the path quoted when it needs quotes. */
final class ConsoleCommand
{
    public static function of(string $args): string
    {
        $path = PIWIK_INCLUDE_PATH.'/console';
        $quoted = preg_match('#^[A-Za-z0-9_./-]+$#', $path) ? $path : "'".str_replace("'", "'\\''", $path)."'";

        return trim('php '.$quoted.' '.$args);
    }
}
