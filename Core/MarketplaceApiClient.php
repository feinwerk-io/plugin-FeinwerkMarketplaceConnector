<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Contracts\HttpTransport;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto\DownloadedArtifact;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto\HttpResponse;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions\MarketplaceAuthException;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions\MarketplaceException;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions\MarketplaceFeatureMissingException;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions\MarketplaceLicenseException;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions\MarketplaceRefusedException;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions\MarketplaceUnreachableException;

final class MarketplaceApiClient
{
    private string $baseUrl;

    private string $token;

    /** @var list<string> */
    private array $extraHeaders;

    /** @param list<string> $extraHeaders "Name: value" lines sent with every request (the Matomo/PHP version) */
    public function __construct(string $baseUrl, string $token, private readonly HttpTransport $transport, array $extraHeaders = [])
    {
        // Both values are pasted into the settings UI; stray whitespace would
        // break the Authorization header and the request URL.
        $this->baseUrl = rtrim(trim($baseUrl), '/');
        $this->token = trim($token);
        // a header value must never smuggle in a second header line
        $this->extraHeaders = array_values(array_filter(
            $extraHeaders,
            fn ($header) => is_string($header) && $header !== '' && ! preg_match('/[\r\n]/', $header),
        ));
    }

    /** @return list<array<mixed>> */
    public function listPlugins(): array
    {
        $response = $this->transport->get($this->baseUrl.'/api/v1/plugins', $this->authHeaders());
        $this->ensureOk($response);
        $data = $response->jsonBody()['data'] ?? null;
        // A 200 without a list (a proxy's HTML page, an empty object) must not replace the cached list with nothing.
        if (! is_array($data) || ! array_is_list($data)) {
            throw new MarketplaceException("Marketplace answered without a plugin list (HTTP {$response->status}).");
        }

        return $data;
    }

    /**
     * The public plugin list for browsing before an account is connected (5.3.0, after the admin's click): no instance
     * token and none of the instance headers, only the language (the store has de and en).
     *
     * @return list<array<mixed>>
     */
    public function publicPlugins(string $locale): array
    {
        $locale = $locale === 'de' ? 'de' : 'en';
        $response = $this->transport->get($this->baseUrl.'/api/v1/public/plugins?locale='.$locale, ['Accept: application/json']);
        $this->ensureOk($response);
        $data = $response->jsonBody()['data'] ?? null;
        if (! is_array($data) || ! array_is_list($data)) {
            throw new MarketplaceException("Marketplace answered without a plugin list (HTTP {$response->status}).");
        }

        return $data;
    }

    /** The full detail payload for one plugin. @return array<mixed> */
    public function showPlugin(string $slug): array
    {
        $response = $this->transport->get($this->baseUrl.'/api/v1/plugins/'.rawurlencode($slug), $this->authHeaders());
        $this->ensureOk($response);
        $data = $response->jsonBody()['data'] ?? null;
        // Same for the details: the stale answer stays in PluginDetailCache instead of an empty window.
        if (! is_array($data) || $data === [] || array_is_list($data)) {
            throw new MarketplaceException("Marketplace answered without plugin details (HTTP {$response->status}).");
        }

        return $data;
    }

    /**
     * @param  array{matomo_version: string, php_version?: ?string, site_url?: mixed, user_count?: int}  $env
     * @param  list<array{slug: string, version: string}>  $installed
     * @return list<array<mixed>>
     */
    public function checkUpdates(array $env, array $installed): array
    {
        // Matomo gives false as its URL when none is stored (scheduled tasks on a localhost install). The marketplace
        // answers HTTP 422 to a site_url that is not a string (max 255) but accepts a missing one.
        $siteUrl = $env['site_url'] ?? null;
        if (! is_string($siteUrl) || trim($siteUrl) === '' || strlen($siteUrl) > 255) {
            unset($env['site_url']);
        }
        $body = array_merge($env, ['installed' => $installed]);
        $response = $this->transport->postJson($this->baseUrl.'/api/v1/instances/check-updates', $this->authHeaders(), $body);
        $this->ensureOk($response);

        return $response->jsonBody()['data'] ?? [];
    }

    /**
     * Which installed plugins already have a build for the target Matomo line.
     *
     * @param  list<array{slug: string, version: string}>  $installed
     * @return list<array<mixed>>
     */
    public function readiness(int $targetMajor, array $installed): array
    {
        $response = $this->transport->postJson(
            $this->baseUrl.'/api/v1/instances/readiness',
            $this->authHeaders(),
            ['target_major' => $targetMajor, 'installed' => $installed]
        );
        $this->ensureOk($response);

        return $response->jsonBody()['data'] ?? [];
    }

    /** Create a bug report for a plugin. @return array<mixed> the created report ref ({id}) */
    public function reportBug(string $slug, array $payload): array
    {
        $response = $this->transport->postJson(
            $this->baseUrl.'/api/v1/plugins/'.rawurlencode($slug).'/bug-reports',
            $this->authHeaders(),
            $payload
        );
        $this->ensureOk($response);

        return $response->jsonBody()['data'] ?? [];
    }

    /** Attach one screenshot (base64 JSON) to an existing report. @return array<mixed> */
    public function attachScreenshot(int $reportId, array $payload): array
    {
        $response = $this->transport->postJson(
            $this->baseUrl.'/api/v1/bug-reports/'.$reportId.'/screenshots',
            $this->authHeaders(),
            $payload
        );
        $this->ensureOk($response);

        return $response->jsonBody()['data'] ?? [];
    }

    public function download(string $slug, string $version): DownloadedArtifact
    {
        $url = $this->baseUrl.'/api/v1/plugins/'.rawurlencode($slug).'/download?version='.rawurlencode($version);
        $response = $this->transport->get($url, $this->authHeaders());
        // The download's only 403 is the license check (a revoked token answers 403 on every call, so the update check
        // fails first). Matched on the status of this call, not on the message text.
        if ($response->status === 403) {
            throw new MarketplaceLicenseException("No valid license for {$slug} on this Matomo instance (HTTP 403). "
                .'Renew or assign the license in your account on plugins.feinwerk.io.'.$this->reason($response));
        }
        $this->ensureOk($response);

        return new DownloadedArtifact($response->body, $response->header('X-Checksum-SHA256') ?? '');
    }

    /** @return array<mixed> */
    public function validateLicense(string $slug): array
    {
        $response = $this->transport->postJson($this->baseUrl.'/api/v1/license/validate', $this->authHeaders(), ['slug' => $slug]);
        $this->ensureOk($response);

        return $response->jsonBody();
    }

    /**
     * Assigns a free seat of the customer's license for this plugin to this instance.
     *
     * @return array{result: string, license: ?array<mixed>}
     */
    public function claimLicense(string $slug): array
    {
        $response = $this->transport->postJson($this->baseUrl.'/api/v1/instances/licenses/claim', $this->authHeaders(), ['slug' => $slug]);
        $this->ensureAccepted($response);

        $body = $response->jsonBody();
        $result = $body['result'] ?? null;
        if (! is_string($result) || ! in_array($result, ['assigned', 'already_licensed', 'covered_as_test'], true)) {
            throw new MarketplaceException('The marketplace sent an unexpected answer to the license request.');
        }
        $license = $body['license'] ?? null;

        return ['result' => $result, 'license' => is_array($license) ? $license : null];
    }

    /** Asks the store for a buy page of this plugin that returns to $returnUrl; returns its URL. */
    public function createPurchaseIntent(string $slug, string $returnUrl, string $lang): string
    {
        $response = $this->transport->postJson(
            $this->baseUrl.'/api/v1/instances/purchase-intents',
            $this->authHeaders(),
            ['slug' => $slug, 'return_url' => $returnUrl, 'lang' => $lang]
        );
        $this->ensureAccepted($response);

        $url = $response->jsonBody()['url'] ?? null;
        if (! is_string($url) || $url === '') {
            throw new MarketplaceException('The marketplace sent no purchase link.');
        }

        return $url;
    }

    /** @return list<string> */
    private function authHeaders(): array
    {
        return array_merge([
            'Authorization: Bearer '.$this->token,
            'Accept: application/json',
        ], $this->extraHeaders);
    }

    /**
     * Trades the one-time code of the connect button for the instance token (see ConnectFlow). Sent without a bearer
     * token: this instance has none yet, or a rejected one.
     *
     * @return array{token: string, label: ?string}
     */
    public function exchangeConnectCode(string $code, string $verifier): array
    {
        $headers = array_merge(['Accept: application/json'], $this->extraHeaders);
        $response = $this->transport->postJson($this->baseUrl.'/api/v1/connect/token', $headers, ['code' => $code, 'verifier' => $verifier]);
        if ($response->status === 400) {
            throw new MarketplaceException('The connection code was not accepted (expired or already used). Please connect again.');
        }
        $this->ensureOk($response);

        $data = $response->jsonBody()['data'] ?? null;
        $token = is_array($data) ? ($data['token'] ?? null) : null;
        if (! is_string($token) || preg_match('/^[\x21-\x7E]{8,255}$/', $token) !== 1) {
            throw new MarketplaceException('The marketplace sent no usable instance token.');
        }
        $label = $data['instance']['label'] ?? null;

        return ['token' => $token, 'label' => is_string($label) && $label !== '' ? $label : null];
    }

    /** Like ensureOk(), but a coded 4xx refusal and a store without the endpoint get their own exceptions. */
    private function ensureAccepted(HttpResponse $response): void
    {
        if ($response->status >= 400 && $response->status < 500 && $response->status !== 401) {
            $code = $response->jsonBody()['error'] ?? null;
            if (is_string($code) && preg_match('/^[a-z_]{1,40}\z/', $code) === 1) {
                throw new MarketplaceRefusedException($code, "Marketplace refused the request ({$code}, HTTP {$response->status}).".$this->reason($response));
            }
            if ($response->status === 404) {
                throw new MarketplaceFeatureMissingException('The marketplace does not offer this yet (HTTP 404).');
            }
        }

        $this->ensureOk($response);
    }

    private function ensureOk(HttpResponse $response): void
    {
        if ($response->status === 0) {
            throw new MarketplaceUnreachableException('Marketplace not reachable (HTTP 0).'.$this->transportReason($response));
        }

        if ($response->status === 401) {
            throw new MarketplaceAuthException('Marketplace rejected the instance token (HTTP 401). Check the token in the settings.');
        }

        if ($response->status < 200 || $response->status >= 300) {
            throw new MarketplaceException("Marketplace API error (HTTP {$response->status}).".$this->reason($response));
        }
    }

    /** What the transport reported about a failed request, one line, never the token. */
    private function transportReason(HttpResponse $response): string
    {
        $error = trim((string) preg_replace('/[\x00-\x1F\x7F]+/', ' ', (string) $response->transportError));
        if ($error !== '' && $this->token !== '') {
            $error = str_replace($this->token, '***', $error);
        }

        return $error === '' ? '' : ' '.mb_substr($error, 0, 300);
    }

    /** The marketplace's own message (e.g. a validation error) for logs and notices, one line, never the token. */
    private function reason(HttpResponse $response): string
    {
        $message = $response->jsonBody()['message'] ?? null;
        if (! is_string($message)) {
            return '';
        }
        $message = trim((string) preg_replace('/[\x00-\x1F\x7F]+/', ' ', $message));
        if ($this->token !== '') {
            $message = str_replace($this->token, '***', $message);
        }

        return $message === '' ? '' : ' '.mb_substr($message, 0, 300);
    }
}
