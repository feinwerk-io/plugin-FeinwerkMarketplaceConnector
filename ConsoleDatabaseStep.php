<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

/**
 * The end of feinwerkmarketplaceconnector:update and :install: when Matomo now has a database update pending, name
 * `core:update`, or run it with --core-update (Matomo's own command, without its question).
 */
trait ConsoleDatabaseStep
{
    /** @return int the exit code of core:update, 0 when nothing ran */
    private function finishWithDatabaseUpdate(bool $run): int
    {
        if (! PluginOperations::hasPendingDbUpdates()) {
            return 0;
        }
        if (! $run) {
            $this->getOutput()->writeln('<comment>Matomo has a database update pending. Run: '.ConsoleCommand::of('core:update').'</comment>');

            return 0;
        }
        $this->getOutput()->writeln('Running the database update (core:update):');

        return $this->runCommand('core:update', ['--yes' => true]);
    }
}
