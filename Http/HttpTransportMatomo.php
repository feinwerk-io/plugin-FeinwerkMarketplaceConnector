<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Http;

use Piwik\Http;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Contracts\HttpTransport;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto\HttpResponse;

class HttpTransportMatomo implements HttpTransport
{
    // Short, hard timeout: a slow/dead upstream must never stall the Matomo screen.
    private const TIMEOUT = 5;

    /**
     * Sent instead of Matomo's default, which is the admin's browser user agent (HTTP_USER_AGENT) in a web request: the
     * store needs nothing about the browser (Matomo Marketplace listing, 2026-11-11).
     */
    public const USER_AGENT = 'Feinwerk-Connector/1.0';

    public function get(string $url, array $headers): HttpResponse
    {
        return $this->send('GET', $url, $headers, null);
    }

    public function postJson(string $url, array $headers, array $body): HttpResponse
    {
        $headers[] = 'Content-Type: application/json';

        return $this->send('POST', $url, $headers, json_encode($body));
    }

    private function send(string $httpMethod, string $url, array $headers, ?string $requestBody): HttpResponse
    {
        // Matomo's client copies an incoming proxy chain (HTTP_VIA) into its Via header: leave it out, as Feedback does.
        $via = $_SERVER['HTTP_VIA'] ?? null;
        unset($_SERVER['HTTP_VIA']);
        try {
            $result = Http::sendHttpRequestBy(
                Http::getTransportMethod(),
                $url,
                self::TIMEOUT,
                self::USER_AGENT,
                null,            // destinationPath
                null,            // file
                0,               // followDepth
                false,           // acceptLanguage
                false,           // acceptInvalidSslCertificate
                false,           // byteRange
                true,            // getExtendedInfo
                $httpMethod,
                null,            // httpUsername
                null,            // httpPassword
                $requestBody,
                $headers,        // additionalHeaders
            );
        } catch (\Exception $e) {
            // Matomo's Http throws an Exception when the request fails (DNS, connection, TLS, timeout): that is a transport failure. Errors (bugs) are not caught.
            return new HttpResponse(0, [], '', $e->getMessage());
        } finally {
            if ($via !== null) {
                $_SERVER['HTTP_VIA'] = $via;
            }
        }

        if (! is_array($result)) {
            // A hard transport failure (false) — surface as a 0 status the client maps to an error.
            return new HttpResponse(0, [], '', 'no answer');
        }

        return new HttpResponse(
            (int) ($result['status'] ?? 0),
            is_array($result['headers'] ?? null) ? $result['headers'] : [],
            (string) ($result['data'] ?? ''),
        );
    }
}
