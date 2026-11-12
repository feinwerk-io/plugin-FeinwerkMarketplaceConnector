<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/**
 * "Connect with your Feinwerk account": an authorization code flow with PKCE. The connector keeps a random state and
 * verifier, sends the browser to the store's /connect page with the state and the verifier's challenge, and trades the
 * one-time code that comes back for the instance token, sending the verifier along. The verifier never leaves this
 * Matomo except in that server-to-server request, so a code caught in the browser or on a plain-http intranet is useless.
 */
final class ConnectFlow
{
    /** How long a started connection may take, login on the store included. */
    public const TTL_SECONDS = 900;

    /** @return array{state: string, verifier: string, created_at: int} */
    public static function begin(int $now): array
    {
        return ['state' => self::secret(), 'verifier' => self::secret(), 'created_at' => $now];
    }

    /** PKCE S256: base64url(sha256(verifier)) without padding. */
    public static function challengeFor(string $verifier): string
    {
        return rtrim(strtr(base64_encode(hash('sha256', $verifier, true)), '+/', '-_'), '=');
    }

    /** @param array{state: string, verifier: string, created_at: int} $pending */
    public static function authorizeUrl(string $baseUrl, array $pending, string $returnUrl, mixed $siteUrl, string $lang, ?string $source = null): string
    {
        $query = [
            'state' => $pending['state'],
            'challenge' => self::challengeFor($pending['verifier']),
            'return_url' => $returnUrl,
        ];
        if (is_string($siteUrl) && $siteUrl !== '' && strlen($siteUrl) <= 255) {
            $query['site_url'] = $siteUrl;
        }
        $query['lang'] = $lang === 'de' ? 'de' : 'en';
        // Campaign for Feinwerk's own Matomo: which installs lead to a connection (ConnectSource).
        if ($source !== null) {
            $query['mtm_campaign'] = 'connector-connect';
            $query['mtm_source'] = $source;
        }

        return rtrim($baseUrl, '/').'/connect?'.http_build_query($query);
    }

    /** Whether the state that came back belongs to the connection this Matomo started, and it is still fresh. */
    public static function accepts(mixed $pending, mixed $state, int $now): bool
    {
        return is_array($pending)
            && is_string($pending['state'] ?? null) && is_string($pending['verifier'] ?? null)
            && is_int($pending['created_at'] ?? null)
            && is_string($state) && $state !== ''
            && hash_equals($pending['state'], $state)
            && $now - $pending['created_at'] <= self::TTL_SECONDS
            && $now >= $pending['created_at'];
    }

    /** 32 random bytes, base64url: 43 characters, valid as PKCE verifier and as state. */
    private static function secret(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
