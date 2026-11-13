<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Commands;

use Piwik\Plugin\ConsoleCommand;
use Piwik\Plugins\FeinwerkMarketplaceConnector\ConsoleDatabaseStep;
use Piwik\Plugins\FeinwerkMarketplaceConnector\ConsoleSupport;
use Piwik\Plugins\FeinwerkMarketplaceConnector\PluginOperations;

/**
 * Updates Feinwerk plugins like a click on Update: development checkouts are never overwritten, an update keeps the
 * plugin switched on or off, a refused download is reported. --all leaves out the connector itself (self-update only
 * when asked for, HARD RULE 5); named, it goes last.
 */
class Update extends ConsoleCommand
{
    use ConsoleDatabaseStep;

    protected function configure(): void
    {
        $this->setName('feinwerkmarketplaceconnector:update');
        $this->setDescription('Updates Feinwerk plugins from plugins.feinwerk.io');
        $this->addOptionalArgument('plugins', 'Technical names of the plugins, for example FeinwerkSearchReport', [], true);
        $this->addNoValueOption('all', null, 'Update every Feinwerk plugin that has an update (not the connector itself)');
        $this->addNoValueOption('no-refresh', null, 'Use the plugin list stored in Matomo instead of loading it again');
        $this->addNoValueOption('core-update', null, 'Run Matomo\'s database update (core:update) afterwards when one is pending');
    }

    protected function doExecute(): int
    {
        $out = $this->getOutput();
        $input = $this->getInput();
        if (! PluginOperations::connected()) {
            $out->writeln('<error>'.ConsoleSupport::notConnected().'</error>');

            return 1;
        }
        $names = array_values(array_filter((array) $input->getArgument('plugins'), 'strlen'));
        $all = (bool) $input->getOption('all');
        if ($names === [] && ! $all) {
            $out->writeln('<error>Name the plugins to update, or use --all.</error>');

            return 1;
        }

        [$list, $note] = ConsoleSupport::plugins(! $input->getOption('no-refresh'));
        if ($note !== null) {
            $out->writeln(($list === null ? '<error>' : '<comment>').$note.($list === null ? '</error>' : '</comment>'));
        }
        if ($list === null) {
            return 1;
        }

        $rows = ConsoleSupport::rowsByName($list);
        $targets = [];
        $problems = 0;
        if ($all) {
            foreach ($rows as $row) {
                if ($row->slug !== ConsoleSupport::SELF && ConsoleSupport::whyNotUpdatable($row) === null) {
                    $targets[$row->slug] = $row;
                }
            }
        }
        foreach ($names as $name) {
            $row = $rows[strtolower($name)] ?? null;
            if ($row === null) {
                $out->writeln("<error>$name: not a Feinwerk plugin</error>");
                $problems++;
                continue;
            }
            $why = ConsoleSupport::whyNotUpdatable($row);
            if ($why !== null) {
                $upToDate = $row->status === 'installed' && ! $row->devCheckout && ! $row->switching;
                $out->writeln($upToDate ? $row->slug.': '.$why : '<error>'.$row->slug.': '.$why.'</error>');
                $problems += $upToDate ? 0 : 1;
                continue;
            }
            $targets[$row->slug] = $row;
        }

        if ($targets === []) {
            if ($problems === 0 && $all) {
                $out->writeln('<info>All Feinwerk plugins are up to date.</info>');
            }

            return $problems > 0 ? 1 : 0;
        }

        // Updates without database changes first, the connector itself last (its own code is replaced).
        uasort($targets, fn ($a, $b) => [$a->slug === ConsoleSupport::SELF, $a->dbChange !== null] <=> [$b->slug === ConsoleSupport::SELF, $b->dbChange !== null]);
        $operations = new PluginOperations();
        foreach ($targets as $row) {
            $out->writeln('Updating '.$row->slug.' '.$row->installedVersion.' to '.$row->latestVersion.' ...');
            $result = $operations->installOne($row->slug, (string) $row->latestVersion, null);
            $line = ConsoleSupport::resultLine($row->slug, $result, (string) $row->latestVersion, true);
            $out->writeln(ConsoleSupport::isProblem($result) ? '<error>'.$line.'</error>' : '<info>'.$line.'</info>');
            $problems += ConsoleSupport::isProblem($result) ? 1 : 0;
        }

        $database = $this->finishWithDatabaseUpdate((bool) $input->getOption('core-update'));

        return $problems > 0 || $database !== 0 ? 1 : 0;
    }
}
