<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto;

final class CachedPlugins
{
    /** The marketplace rejected the instance token. */
    public const ERROR_AUTH = 'auth';

    /** The marketplace could not be reached at all (no network, DNS, TLS, timeout). */
    public const ERROR_OFFLINE = 'offline';

    /** Any other failure: server error, unexpected answer. */
    public const ERROR_FAILED = 'failed';

    /**
     * @param  list<array<mixed>>  $plugins
     * @param  string|null  $errorKind  one of the ERROR_* constants when $error is set
     */
    public function __construct(
        public readonly array $plugins,
        public readonly ?int $fetchedAt,
        public readonly ?string $error,
        public readonly ?string $errorKind = null,
    ) {}

    public function isEmpty(): bool
    {
        return $this->plugins === [];
    }
}
