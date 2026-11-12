<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Option;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MarketplaceApiClient;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Http\HttpTransportMatomo;

/**
 * Browsing without an account (5.3.0, Peter 2026-11-11, before the Matomo Marketplace listing): the store's public plugin
 * list, loaded only when an admin clicks "Show Feinwerk plugins" on the screen of a Matomo that is not connected. The
 * request carries no instance token and no instance headers, only the language. The list is kept in option
 * fwmc.cache.public and shown until the next click; its presence is the admin's consent (the image proxy then serves
 * the store's artwork). Never throws.
 */
final class PublicCatalog
{
    public const OPTION = 'fwmc.cache.public';

    /** Loads the public list now. False when the store did not answer with one (an earlier list stays). */
    public static function load(string $lang): bool
    {
        $lang = self::locale($lang);
        try {
            $client = new MarketplaceApiClient(MarketplaceClientFactory::url(), '', new HttpTransportMatomo());
            $data = $client->publicPlugins($lang);
            $lists = self::lists();
            $lists[$lang] = ['fetchedAt' => time(), 'data' => $data];
            Option::set(self::OPTION, (string) json_encode(['lists' => $lists]));

            return true;
        } catch (\Throwable $e) {
            MarketplaceLog::info('loading the public plugin list failed: '.$e->getMessage());

            return false;
        }
    }

    /**
     * The list loaded in that language (one per language: a German list is never shown to an English admin, who gets
     * the button again), or with null any loaded list (the admin's click, consent for the plugin images).
     *
     * @return array{fetchedAt: int, data: list<array<mixed>>}|null null before the first click
     */
    public static function cached(?string $lang = null): ?array
    {
        $lists = self::lists();
        if ($lang !== null) {
            return $lists[self::locale($lang)] ?? null;
        }

        return $lists === [] ? null : reset($lists);
    }

    /** The store knows de and en; every other language gets the English list. */
    private static function locale(string $lang): string
    {
        return $lang === 'de' ? 'de' : 'en';
    }

    /** @return array<string, array{fetchedAt: int, data: list<array<mixed>>}> */
    private static function lists(): array
    {
        try {
            $stored = json_decode((string) Option::get(self::OPTION), true);
        } catch (\Throwable $e) {
            return [];
        }
        $lists = [];
        foreach ((is_array($stored) && is_array($stored['lists'] ?? null)) ? $stored['lists'] : [] as $lang => $list) {
            if (in_array($lang, ['de', 'en'], true) && is_array($list) && is_array($list['data'] ?? null) && array_is_list($list['data'])) {
                $lists[$lang] = ['fetchedAt' => (int) ($list['fetchedAt'] ?? 0), 'data' => $list['data']];
            }
        }

        return $lists;
    }
}
