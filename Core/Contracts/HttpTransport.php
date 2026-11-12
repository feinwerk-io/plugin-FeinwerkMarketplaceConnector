<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Contracts;

use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto\HttpResponse;

/**
 * A request that gets no HTTP answer at all (DNS, connection, TLS, timeout) must not throw: the implementation returns
 * an HttpResponse with status 0 and the reason in transportError. The client maps that to
 * MarketplaceUnreachableException ("offline"); any HTTP answer, whatever its status, is returned as it came.
 */
interface HttpTransport
{
    /**
     * @param  list<string>  $headers  Each entry "Name: value".
     */
    public function get(string $url, array $headers): HttpResponse;

    /**
     * @param  list<string>  $headers
     * @param  array<mixed>  $body
     */
    public function postJson(string $url, array $headers, array $body): HttpResponse;
}
