<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto\PluginStatusRow;

/**
 * The first-step card right after Connect (Peter, 2026-11-16, relayed by PluginLead: "1. ok"). The store's plugin list
 * carries a top-level `starter` next to `data`: null, or {plugin, line, line_de} naming one plugin to suggest first and
 * one plain line of text. The store sets it only for a plugin this instance lacks; the connector checks again here, so
 * the card never offers what one click cannot install.
 */
final class Starter
{
    public const MAX_LINE = 200;

    /**
     * @return array{plugin: string, line: string, line_de: ?string, landing: ?array{module: string, action: string}}|null
     *     the store's field, or null when it says nothing usable; `landing` (store 2026-11-17) only for a page of that plugin
     */
    public static function parse(mixed $raw): ?array
    {
        if (! is_array($raw)) {
            return null;
        }
        $plugin = $raw['plugin'] ?? null;
        $line = self::line($raw['line'] ?? null);
        if (! is_string($plugin) || preg_match('/^[A-Za-z][A-Za-z0-9]{1,79}$/', $plugin) !== 1 || $line === null) {
            return null;
        }

        $landing = $raw['landing'] ?? null;
        $module = is_array($landing) ? ($landing['module'] ?? null) : null;
        $action = is_array($landing) ? ($landing['action'] ?? null) : null;
        $ok = $module === $plugin && is_string($action) && preg_match('/^[A-Za-z][A-Za-z0-9]{1,79}$/', $action) === 1;

        return ['plugin' => $plugin, 'line' => $line, 'line_de' => self::line($raw['line_de'] ?? null),
            'landing' => $ok ? ['module' => $plugin, 'action' => $action] : null];
    }

    /**
     * The card for this Matomo: the named plugin when it is listed, not installed, has a stable build here and needs no
     * license; null otherwise.
     *
     * @param  array{plugin: string, line: string, line_de: ?string}|null  $starter
     * @param  list<PluginStatusRow>  $rows
     * @return array{slug: string, name: string, line: string, version: string, artworkUrl: ?string}|null
     */
    public static function pick(?array $starter, array $rows, string $lang): ?array
    {
        if ($starter === null) {
            return null;
        }
        foreach ($rows as $row) {
            if ($row->slug !== $starter['plugin']) {
                continue;
            }
            if ($row->status !== PluginStatusRow::STATUS_NOT_INSTALLED || $row->latestVersion === null || $row->beta
                || $row->devCheckout || ($row->needsLicense && $row->license === null)) {
                return null;
            }

            return [
                'slug' => $row->slug,
                'name' => $row->displayName,
                'line' => $lang === 'de' && $starter['line_de'] !== null ? $starter['line_de'] : $starter['line'],
                'version' => $row->latestVersion,
                'artworkUrl' => $row->artworkUrl,
            ];
        }

        return null;
    }

    /** Whether the named plugin is installed already (the card is used up then). */
    public static function installed(?array $starter, array $rows): bool
    {
        foreach ($rows as $row) {
            if ($starter !== null && $row->slug === $starter['plugin']) {
                return $row->installedVersion !== null;
            }
        }

        return false;
    }

    private static function line(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }
        $value = trim($value);

        return $value === '' || mb_strlen($value) > self::MAX_LINE ? null : $value;
    }
}
