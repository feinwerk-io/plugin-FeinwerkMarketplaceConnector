<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Commands;

use Piwik\Container\StaticContainer;
use Piwik\Plugin\ConsoleCommand;
use Piwik\Plugins\FeinwerkMarketplaceConnector\ConsoleSupport;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto\CachedPlugins;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MarketplaceCache;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Http\OptionCacheStore;
use Piwik\Plugins\FeinwerkMarketplaceConnector\PluginOperations;
use Piwik\Plugins\FeinwerkMarketplaceConnector\SystemSettings;

/**
 * Connects this Matomo with the instance token from the customer's account, like pasting it into General settings. The
 * token comes from a file (or standard input with "-"), never from the command line, and is never printed. A token
 * the store rejects is not kept.
 */
class Connect extends ConsoleCommand
{
    protected function configure(): void
    {
        $this->setName('feinwerkmarketplaceconnector:connect');
        $this->setDescription('Connects this Matomo with a Feinwerk account using its instance token');
        $this->addRequiredValueOption('token-file', null, 'File with the instance token from your account on plugins.feinwerk.io ("-" reads standard input)');
    }

    protected function doExecute(): int
    {
        $out = $this->getOutput();
        $file = (string) $this->getInput()->getOption('token-file');
        if ($file === '') {
            $out->writeln('<error>Give the instance token in a file: --token-file=<file> (or --token-file=- for standard input).</error>');

            return 1;
        }
        $raw = $file === '-' ? stream_get_contents(STDIN) : (is_file($file) && is_readable($file) ? file_get_contents($file) : false);
        $token = trim((string) $raw);
        if ($raw === false || ! preg_match('/^[\x21-\x7e]{8,255}$/', $token)) {
            $out->writeln('<error>No instance token found in '.($file === '-' ? 'standard input' : $file).'. Copy it from your account on plugins.feinwerk.io.</error>');

            return 1;
        }

        $settings = StaticContainer::get(SystemSettings::class);
        $previous = (string) $settings->instanceToken->getValue();
        $store = new OptionCacheStore();
        $previousList = $store->get(MarketplaceCache::KEY);
        $settings->instanceToken->setValue($token);
        $settings->save();

        $list = PluginOperations::refresh();
        if ($list->errorKind === CachedPlugins::ERROR_AUTH) {
            // Not kept: the previous token and the list it loaded come back as they were.
            $settings->instanceToken->setValue($previous);
            $settings->save();
            if ($previousList !== null) {
                $store->set(MarketplaceCache::KEY, $previousList);
            }
            $out->writeln('<error>The store rejected this instance token. The previous setting is kept.</error>');

            return 1;
        }
        if ($list->error !== null) {
            $out->writeln('<comment>The instance token is saved, but '.lcfirst(ConsoleSupport::refreshFailed($list)).'</comment>');

            return 1;
        }
        $out->writeln(sprintf('<info>Connected with plugins.feinwerk.io: %d plugins in the list.</info>', count($list->plugins)));

        return 0;
    }
}
