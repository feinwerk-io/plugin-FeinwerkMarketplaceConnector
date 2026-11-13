<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Commands;

use Piwik\Plugin\ConsoleCommand;
use Piwik\Plugins\FeinwerkMarketplaceConnector\ConsoleSupport;
use Piwik\Plugins\FeinwerkMarketplaceConnector\ConsoleCommand as ConsoleLine;
use Piwik\Plugins\FeinwerkMarketplaceConnector\PendingUpdates;
use Piwik\Plugins\FeinwerkMarketplaceConnector\PluginOperations;

/** The Manage tab on the console: installed and latest versions, status, licenses, database updates. */
class Status extends ConsoleCommand
{
    protected function configure(): void
    {
        $this->setName('feinwerkmarketplaceconnector:status');
        $this->setDescription('Lists the Feinwerk plugins with installed and latest version, status and license');
        $this->addNoValueOption('json', null, 'Print the list as JSON');
    }

    protected function doExecute(): int
    {
        $out = $this->getOutput();
        if (! PluginOperations::connected()) {
            $out->writeln('<error>'.ConsoleSupport::notConnected().'</error>');

            return 1;
        }

        $list = PluginOperations::cache()->ensurePopulated(); // fetches only when no list is stored yet
        $rows = PluginOperations::rows($list);
        $pending = PluginOperations::pendingComponents();
        $held = array_map(fn ($row) => $row->slug.' '.$row->latestVersion, PendingUpdates::heldForDatabase());

        if ($this->getInput()->getOption('json')) {
            $out->writeln((string) json_encode([
                'connected' => true,
                'fetchedAt' => $list->fetchedAt ? date('c', $list->fetchedAt) : null,
                'error' => $list->errorKind,
                'plugins' => array_map(fn ($row) => [
                    'plugin' => $row->slug,
                    'name' => $row->displayName,
                    'installed' => $row->installedVersion,
                    'latest' => $row->latestVersion,
                    'status' => $row->status,
                    'activated' => $row->installedVersion !== null && ! $row->inactive,
                    'developmentCheckout' => $row->devCheckout,
                    'switchPending' => $row->switching,
                    'needsLicense' => $row->needsLicense,
                    'license' => $row->license,
                    'databaseUpdate' => $row->dbChange !== null,
                ], $rows),
                'pendingDatabaseUpdate' => $pending,
                'heldBack' => $held,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            return 0;
        }

        $out->writeln('Feinwerk plugins, list of '.($list->fetchedAt ? date('Y-m-d H:i', $list->fetchedAt) : 'never'));
        if ($list->error !== null) {
            $out->writeln('<comment>'.ConsoleSupport::refreshFailed($list).'</comment>');
        }
        $this->renderTable(['Plugin', 'Installed', 'Latest', 'Status', 'License', 'Note'], array_map(fn ($row) => [
            $row->slug,
            $row->installedVersion ?? '-',
            $row->latestVersion ?? '-',
            ConsoleSupport::statusText($row),
            ConsoleSupport::licenseText($row),
            $row->latestVersion === null ? 'no build for this Matomo' : ($row->dbChange !== null && $row->status !== 'installed' ? 'changes the database' : ''),
        ], $rows));
        if ($held !== []) {
            $out->writeln('Held back by the automatic updates (database changes): '.implode(', ', $held).'. Update them with '
                .ConsoleLine::of('feinwerkmarketplaceconnector:update <plugin>'));
        }
        if ($pending !== []) {
            $out->writeln('<comment>Matomo has a database update pending ('.implode(', ', $pending).'). Run: '.ConsoleLine::of('core:update').'</comment>');
        }

        return 0;
    }
}
