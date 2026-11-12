<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Contracts\CacheStore;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto\CachedPlugins;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions\MarketplaceAuthException;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions\MarketplaceUnreachableException;
use Throwable;

/**
 * Cache-first access to the marketplace plugin list. Reads never touch the
 * network; refresh() persists the last good response and NEVER throws, so a
 * dead or slow upstream can never error or stall the Matomo screen.
 */
final class MarketplaceCache
{
    private const KEY = 'plugins';

    public function __construct(
        private readonly MarketplaceApiClient $client,
        private readonly CacheStore $store,
    ) {}

    /** Read the cached list only — no network call. */
    public function cached(): CachedPlugins
    {
        return $this->read();
    }

    /** Return the cache, fetching once only if it has never been populated. */
    public function ensurePopulated(): CachedPlugins
    {
        $cached = $this->read();

        return $cached->fetchedAt === null ? $this->refresh() : $cached;
    }

    /** Fetch + persist. On any failure, return the previous cache with an error set. Never throws. */
    public function refresh(): CachedPlugins
    {
        try {
            $plugins = $this->client->listPlugins();
            $fetchedAt = time();
            $this->store->set(self::KEY, (string) json_encode(['fetchedAt' => $fetchedAt, 'data' => $plugins]));

            return new CachedPlugins($plugins, $fetchedAt, null);
        } catch (Throwable $e) {
            $previous = $this->read();
            $kind = match (true) {
                $e instanceof MarketplaceAuthException => CachedPlugins::ERROR_AUTH,
                $e instanceof MarketplaceUnreachableException => CachedPlugins::ERROR_OFFLINE,
                default => CachedPlugins::ERROR_FAILED,
            };

            // Keep the failure with the old data: the refresh action redirects, and the next
            // request only reads the cache — otherwise a failed refresh would look successful.
            if ($previous->fetchedAt !== null) {
                $this->store->set(self::KEY, (string) json_encode([
                    'fetchedAt' => $previous->fetchedAt,
                    'data' => $previous->plugins,
                    'error' => $e->getMessage(),
                    'errorKind' => $kind,
                ]));
            }

            return new CachedPlugins($previous->plugins, $previous->fetchedAt, $e->getMessage(), $kind);
        }
    }

    private function read(): CachedPlugins
    {
        $raw = $this->store->get(self::KEY);
        if ($raw === null) {
            return new CachedPlugins([], null, null);
        }

        $decoded = json_decode($raw, true);
        if (! is_array($decoded) || ! isset($decoded['data']) || ! is_array($decoded['data'])) {
            return new CachedPlugins([], null, null);
        }

        $fetchedAt = isset($decoded['fetchedAt']) ? (int) $decoded['fetchedAt'] : null;
        $error = isset($decoded['error']) && is_string($decoded['error']) ? $decoded['error'] : null;
        $kind = $error !== null && in_array($decoded['errorKind'] ?? null, [CachedPlugins::ERROR_AUTH, CachedPlugins::ERROR_OFFLINE, CachedPlugins::ERROR_FAILED], true)
            ? $decoded['errorKind']
            : ($error !== null ? CachedPlugins::ERROR_FAILED : null);

        return new CachedPlugins(array_values($decoded['data']), $fetchedAt, $error, $kind);
    }
}
