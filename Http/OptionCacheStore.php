<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Http;

use Piwik\Option;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Contracts\CacheStore;

/**
 * Persistent cache backed by Matomo's Option store (a DB key/value table), so
 * the last good marketplace response survives requests and upstream outages.
 */
class OptionCacheStore implements CacheStore
{
    public const PREFIX = 'fwmc.cache.';

    public function get(string $key): ?string
    {
        $value = Option::get(self::PREFIX.$key);

        return $value === false ? null : (string) $value;
    }

    public function set(string $key, string $value): void
    {
        Option::set(self::PREFIX.$key, $value);
    }
}
