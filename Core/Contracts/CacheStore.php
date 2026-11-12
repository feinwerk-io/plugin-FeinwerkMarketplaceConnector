<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Contracts;

interface CacheStore
{
    public function get(string $key): ?string;

    public function set(string $key, string $value): void;
}
