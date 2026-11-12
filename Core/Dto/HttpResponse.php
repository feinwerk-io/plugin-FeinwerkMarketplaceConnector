<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto;

final class HttpResponse
{
    public function __construct(
        public readonly int $status,
        public readonly array $headers,
        public readonly string $body,
        // Set when the request itself failed (status 0): what the transport reported, for logs and notices.
        public readonly ?string $transportError = null,
    ) {}

    public function header(string $name): ?string
    {
        foreach ($this->headers as $key => $value) {
            if (strcasecmp((string) $key, $name) === 0) {
                return is_array($value) ? (string) reset($value) : (string) $value;
            }
        }

        return null;
    }

    /**
     * @return array<mixed>
     */
    public function jsonBody(): array
    {
        $decoded = json_decode($this->body, true);

        return is_array($decoded) ? $decoded : [];
    }
}
