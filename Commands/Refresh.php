<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Commands;

use Piwik\Plugin\ConsoleCommand;
use Piwik\Plugins\FeinwerkMarketplaceConnector\ConsoleSupport;
use Piwik\Plugins\FeinwerkMarketplaceConnector\PluginOperations;

/** The Refresh button on the console: loads the plugin list from plugins.feinwerk.io again. */
class Refresh extends ConsoleCommand
{
    protected function configure(): void
    {
        $this->setName('feinwerkmarketplaceconnector:refresh');
        $this->setDescription('Loads the list of Feinwerk plugins from plugins.feinwerk.io again');
    }

    protected function doExecute(): int
    {
        $out = $this->getOutput();
        if (! PluginOperations::connected()) {
            $out->writeln('<error>'.ConsoleSupport::notConnected().'</error>');

            return 1;
        }

        $list = PluginOperations::refresh();
        if ($list->error !== null) {
            $out->writeln('<error>'.ConsoleSupport::refreshFailed($list).'</error>');

            return 1;
        }
        $updates = array_filter(PluginOperations::rows($list), fn ($row) => ConsoleSupport::whyNotUpdatable($row) === null);
        $out->writeln(sprintf('<info>Plugin list updated: %d plugins, %d updates available.</info>', count($list->plugins), count($updates)));

        return 0;
    }
}
