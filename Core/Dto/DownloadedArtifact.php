<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto;

final class DownloadedArtifact
{
    public function __construct(
        public readonly string $bytes,
        public readonly string $sha256,
    ) {}
}
