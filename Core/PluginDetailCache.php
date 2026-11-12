<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Contracts\CacheStore;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto\CachedPlugins;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions\MarketplaceAuthException;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions\MarketplaceUnreachableException;

/**
 * Per-slug cache for the rich detail payload shown in the browse modal.
 * Same resilience contract as MarketplaceCache: get() never throws — a dead
 * upstream yields the last good state flagged stale, or an error payload.
 */
final class PluginDetailCache
{
    /** When "Refresh" last outdated every cached answer (unix time). */
    private const INVALIDATED = 'details.invalidated_at';

    private \Closure $clock;

    public function __construct(
        private readonly MarketplaceApiClient $client,
        private readonly CacheStore $store,
        private readonly int $ttlSeconds = 3600,
        ?\Closure $clock = null,
    ) {
        $this->clock = $clock ?? static fn (): int => time();
    }

    /**
     * errorKind is one of CachedPlugins::ERROR_* when error is set, so the modal can tell a
     * rejected token apart from an unreachable marketplace.
     *
     * @return array{data: ?array, fetchedAt: ?int, stale: bool, error: ?string, errorKind: ?string}
     */
    public function get(string $slug): array
    {
        $now = ($this->clock)();
        $cached = $this->read($slug);

        if ($cached !== null && ($now - $cached['fetchedAt']) < $this->ttlSeconds && $cached['fetchedAt'] > $this->invalidatedAt()) {
            return ['data' => $cached['data'], 'fetchedAt' => $cached['fetchedAt'], 'stale' => false, 'error' => null, 'errorKind' => null];
        }

        try {
            $data = $this->client->showPlugin($slug);
            $this->store->set($this->key($slug), (string) json_encode(['fetchedAt' => $now, 'data' => $data]));

            return ['data' => $data, 'fetchedAt' => $now, 'stale' => false, 'error' => null, 'errorKind' => null];
        } catch (\Throwable $e) {
            $kind = match (true) {
                $e instanceof MarketplaceAuthException => CachedPlugins::ERROR_AUTH,
                $e instanceof MarketplaceUnreachableException => CachedPlugins::ERROR_OFFLINE,
                default => CachedPlugins::ERROR_FAILED,
            };
            if ($cached !== null) {
                return ['data' => $cached['data'], 'fetchedAt' => $cached['fetchedAt'], 'stale' => true, 'error' => $e->getMessage(), 'errorKind' => $kind];
            }

            return ['data' => null, 'fetchedAt' => null, 'stale' => false, 'error' => $e->getMessage(), 'errorKind' => $kind];
        }
    }

    /**
     * Outdate every cached answer at once ("Refresh"), so the next get() of any plugin asks the marketplace again.
     * The answers stay stored: if that request fails, the old one is still shown, flagged stale. Never throws.
     */
    public function invalidateAll(): void
    {
        try {
            $this->store->set(self::INVALIDATED, (string) ($this->clock)());
        } catch (\Throwable $e) {
            // Worst case the window shows the cached answer until its TTL ends, as before.
        }
    }

    private function invalidatedAt(): int
    {
        return (int) ($this->store->get(self::INVALIDATED) ?? 0);
    }

    /** @return array{fetchedAt: int, data: array}|null */
    private function read(string $slug): ?array
    {
        $raw = $this->store->get($this->key($slug));
        if ($raw === null) {
            return null;
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded) || !isset($decoded['fetchedAt'], $decoded['data'])) {
            return null;
        }

        return ['fetchedAt' => (int) $decoded['fetchedAt'], 'data' => (array) $decoded['data']];
    }

    private function key(string $slug): string
    {
        return 'detail.'.$slug;
    }
}
