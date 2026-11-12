<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions\MarketplaceException;

final class ArtifactVerifier
{
    public function verify(string $bytes, string $expectedSha256): void
    {
        if ($expectedSha256 === '') {
            throw new MarketplaceException('Marketplace did not provide a checksum for the artifact.');
        }

        $actual = hash('sha256', $bytes);

        if (! hash_equals(strtolower($expectedSha256), $actual)) {
            throw new MarketplaceException("Artifact checksum mismatch (expected {$expectedSha256}, got {$actual}).");
        }
    }
}
