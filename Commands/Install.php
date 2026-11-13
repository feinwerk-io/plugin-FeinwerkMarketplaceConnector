<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Commands;

use Piwik\Plugin\ConsoleCommand;
use Piwik\Plugins\FeinwerkMarketplaceConnector\ConsoleDatabaseStep;
use Piwik\Plugins\FeinwerkMarketplaceConnector\ConsoleSupport;
use Piwik\Plugins\FeinwerkMarketplaceConnector\PluginOperations;

/** Installs Feinwerk plugins like a click on Install: the build for this Matomo version, activated unless --no-activate. */
class Install extends ConsoleCommand
{
    use ConsoleDatabaseStep;

    protected function configure(): void
    {
        $this->setName('feinwerkmarketplaceconnector:install');
        $this->setDescription('Installs Feinwerk plugins from plugins.feinwerk.io');
        $this->addRequiredArgument('plugins', 'Technical names of the plugins, for example FeinwerkSearchReport', null, true);
        $this->addNoValueOption('no-activate', null, 'Only copy the plugins; activate them later with plugin:activate');
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

        [$list, $note] = ConsoleSupport::plugins(! $input->getOption('no-refresh'));
        if ($note !== null) {
            $out->writeln(($list === null ? '<error>' : '<comment>').$note.($list === null ? '</error>' : '</comment>'));
        }
        if ($list === null) {
            return 1;
        }

        $rows = ConsoleSupport::rowsByName($list);
        $activate = ! $input->getOption('no-activate');
        $operations = new PluginOperations();
        $problems = 0;
        foreach (array_values(array_filter((array) $input->getArgument('plugins'), 'strlen')) as $name) {
            $row = $rows[strtolower($name)] ?? null;
            if ($row === null) {
                $out->writeln("<error>$name: not a Feinwerk plugin</error>");
                $problems++;
                continue;
            }
            $why = ConsoleSupport::whyNotInstallable($row);
            if ($why !== null) {
                $installed = $row->status !== 'not_installed';
                $out->writeln($installed ? $row->slug.': '.$why : '<error>'.$row->slug.': '.$why.'</error>');
                $problems += $installed ? 0 : 1;
                continue;
            }
            $out->writeln('Installing '.$row->slug.' '.$row->latestVersion.' ...');
            $result = $operations->installOne($row->slug, (string) $row->latestVersion, $activate);
            $line = ConsoleSupport::resultLine($row->slug, $result, (string) $row->latestVersion, false);
            if (in_array($result['kind'], ['done', 'db'], true) && $activate) {
                $line .= ' and activated';
            }
            $out->writeln(ConsoleSupport::isProblem($result) ? '<error>'.$line.'</error>' : '<info>'.$line.'</info>');
            $problems += ConsoleSupport::isProblem($result) ? 1 : 0;
        }

        $database = $this->finishWithDatabaseUpdate((bool) $input->getOption('core-update'));

        return $problems > 0 || $database !== 0 ? 1 : 0;
    }
}
