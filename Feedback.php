<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Container\StaticContainer;
use Piwik\Http;
use Piwik\Option;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\FeedbackVote;
use Psr\Log\LoggerInterface;

/**
 * Sends a vote of the feedback question (Core/FeedbackVote) from the Matomo server to the store. Its own request on
 * purpose: the marketplace client adds the instance token and the Matomo and PHP version headers to every call, and
 * the store's privacy policy (section 7d) promises a vote without any reference to the instance. About 3 s timeout, no
 * retry, failures only logged. [FeinwerkMarketplaceConnector] feedback = 0 switches the question off.
 */
final class Feedback
{
    private const TIMEOUT = 3;

    /** Sent instead of the admin's browser string, which Matomo's HTTP client would otherwise forward. */
    public const USER_AGENT = 'Feinwerk-Feedback/1.0';

    /** Per user and question: time of the last vote (one every 10 seconds). Deleted with the user and on uninstall. */
    public const THROTTLE_PREFIX = 'fwmc.feedback.';

    public static function enabled(): bool
    {
        try {
            $section = \Piwik\Config::getInstance()->FeinwerkMarketplaceConnector;
        } catch (\Throwable $e) {
            $section = [];
        }
        try {
            $internet = (bool) \Piwik\SettingsPiwik::isInternetEnabled();
        } catch (\Throwable $e) {
            $internet = false;
        }

        return FeedbackVote::enabledBy(is_array($section) ? $section : [], $internet);
    }

    public static function userPrefix(string $login): string
    {
        return self::THROTTLE_PREFIX.md5($login).'.';
    }

    /** One vote per user and question every 10 seconds; records this one. */
    public static function allowed(string $login, string $topic): bool
    {
        $key = self::userPrefix($login).$topic;
        if (time() - (int) Option::get($key) < 10) {
            return false;
        }
        Option::set($key, (string) time());

        return true;
    }

    /** @param  array<string, ?string>  $payload  FeedbackVote::payload() */
    public static function send(array $payload): bool
    {
        // Matomo's client also copies an incoming proxy chain (HTTP_VIA) into its Via header: leave it out.
        $via = $_SERVER['HTTP_VIA'] ?? null;
        unset($_SERVER['HTTP_VIA']);
        try {
            $response = Http::sendHttpRequestBy(
                Http::getTransportMethod(),
                rtrim(MarketplaceClientFactory::url(), '/').'/api/v1/feedback',
                self::TIMEOUT,
                self::USER_AGENT,
                null,
                null,
                0,
                false,
                false,
                false,
                true,
                'POST',
                null,
                null,
                (string) json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                ['Content-Type: application/json', 'Accept: application/json']
            );
        } catch (\Throwable $e) {
            self::log('feedback not sent: '.$e->getMessage());

            return false;
        } finally {
            if ($via !== null) {
                $_SERVER['HTTP_VIA'] = $via;
            }
        }
        $status = is_array($response) ? (int) ($response['status'] ?? 0) : 0;
        if ($status < 200 || $status >= 300) {
            self::log('feedback not accepted (HTTP '.$status.')');

            return false;
        }

        return true;
    }

    private static function log(string $message): void
    {
        try {
            StaticContainer::get(LoggerInterface::class)->info('FeinwerkMarketplaceConnector: '.$message);
        } catch (\Throwable $e) {
        }
    }
}
