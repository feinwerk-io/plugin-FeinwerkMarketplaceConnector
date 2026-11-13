<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto\CachedPlugins;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto\PluginStatusRow;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MarketplaceEndpoint;

/**
 * Shared parts of the console commands in Commands/ (5.4.0): the hints, the plugin list and why a plugin cannot be
 * updated or installed. Kept outside Commands/, where Matomo looks for command classes.
 */
final class ConsoleSupport
{
    public const SELF = 'FeinwerkMarketplaceConnector';

    /** Not connected: nothing may go to the store (NoContactBeforeConnectTest), the command says how to connect. */
    public static function notConnected(): string
    {
        return 'This Matomo is not connected with a Feinwerk account. Connect it under Administration › Feinwerk Plugins, '
            .'or run: '.ConsoleCommand::of('feinwerkmarketplaceconnector:connect --token-file=<file with the instance token>');
    }

    /** The sentence for a failed refresh, by its kind. */
    public static function refreshFailed(CachedPlugins $list): string
    {
        return match ($list->errorKind) {
            CachedPlugins::ERROR_AUTH => 'The store rejected the instance token. Connect this Matomo again: '
                .ConsoleCommand::of('feinwerkmarketplaceconnector:connect --token-file=<file>'),
            CachedPlugins::ERROR_OFFLINE => 'plugins.feinwerk.io could not be reached ('.$list->error.'). See '
                .MarketplaceEndpoint::offlineDocsUrl(MarketplaceClientFactory::url(), 'en'),
            default => 'Loading the plugin list failed: '.$list->error,
        };
    }

    /**
     * The plugin list for update and install: loaded anew (like Refresh) unless $refresh is false.
     *
     * @return array{0: ?CachedPlugins, 1: ?string} the list (null: stop) and a note for the admin
     */
    public static function plugins(bool $refresh): array
    {
        if (! $refresh) {
            $cached = PluginOperations::cache()->cached();

            return $cached->plugins === [] ? [null, 'No plugin list is stored yet. Run the command without --no-refresh.'] : [$cached, null];
        }
        $list = PluginOperations::refresh();
        if ($list->error === null) {
            return [$list, null];
        }
        if ($list->errorKind === CachedPlugins::ERROR_AUTH || $list->plugins === []) {
            return [null, self::refreshFailed($list)];
        }

        return [$list, self::refreshFailed($list).' Using the list from '.date('Y-m-d H:i', (int) $list->fetchedAt).'.'];
    }

    /** @return array<string, PluginStatusRow> rows by lowercase technical name, so names work in any case */
    public static function rowsByName(CachedPlugins $list): array
    {
        $rows = [];
        foreach (PluginOperations::rows($list) as $row) {
            $rows[strtolower($row->slug)] = $row;
        }

        return $rows;
    }

    /** Why this installed plugin cannot be updated now; null when it can. 'up to date' is no problem. */
    public static function whyNotUpdatable(PluginStatusRow $row): ?string
    {
        return match (true) {
            $row->devCheckout => 'development checkout, not overwritten',
            $row->status === PluginStatusRow::STATUS_NOT_INSTALLED => 'not installed (install it with '
                .ConsoleCommand::of('feinwerkmarketplaceconnector:install '.$row->slug).')',
            $row->switching => 'switch pending: the connector installs its build for this Matomo version itself',
            $row->status === PluginStatusRow::STATUS_INSTALLED => 'up to date ('.$row->installedVersion.')',
            $row->latestVersion === null => 'no build for this Matomo version',
            $row->needsLicense && $row->license === null => self::needsLicense(),
            default => null,
        };
    }

    /** Why this plugin cannot be installed now; null when it can. */
    public static function whyNotInstallable(PluginStatusRow $row): ?string
    {
        return match (true) {
            $row->status !== PluginStatusRow::STATUS_NOT_INSTALLED => 'already installed ('.$row->installedVersion.'), update it with '
                .ConsoleCommand::of('feinwerkmarketplaceconnector:update '.$row->slug),
            $row->latestVersion === null => 'no build for this Matomo version yet',
            $row->needsLicense && $row->license === null => self::needsLicense(),
            default => null,
        };
    }

    public static function needsLicense(): string
    {
        return 'needs a license for this Matomo: use an existing one or buy one under Administration › Feinwerk Plugins › Licenses';
    }

    /** The line for one result of PluginOperations::installOne(). */
    public static function resultLine(string $slug, array $result, string $version, bool $update): string
    {
        return $slug.': '.match ($result['kind']) {
            'done', 'db' => ($update ? 'updated to ' : 'installed ').$version,
            'activate' => 'installed '.$version.', not activated. Activate it with: '.ConsoleCommand::of('plugin:activate '.$slug),
            'dev' => 'development checkout, not overwritten',
            'refused' => 'the store refused the download: this Matomo has no license for it, or its instance token was revoked',
            default => 'failed: '.$result['message'],
        };
    }

    /** Problems are results the admin has to look at; done, db and activate are not. */
    public static function isProblem(array $result): bool
    {
        return ! in_array($result['kind'], ['done', 'db', 'activate'], true);
    }

    /** The plain text of a row's status for the table. */
    public static function statusText(PluginStatusRow $row): string
    {
        $text = match (true) {
            $row->devCheckout => 'development checkout',
            $row->switching => 'switch pending',
            default => str_replace('_', ' ', $row->status),
        };

        return $row->inactive ? $text.', not activated' : $text;
    }

    public static function licenseText(PluginStatusRow $row): string
    {
        if (is_array($row->license)) {
            $until = $row->license['valid_until'] ?? null;

            return (string) ($row->license['status'] ?? 'active').($until ? ' until '.substr((string) $until, 0, 10) : '');
        }

        return $row->needsLicense ? 'missing' : '-';
    }
}
