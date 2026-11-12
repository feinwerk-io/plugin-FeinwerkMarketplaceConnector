<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/**
 * The feedback question under the Feinwerk Plugins screen (Feinwerk feedback kit, docs/feedback-kit.md of
 * FeinwerkEmailReportsPro; required in every plugin's next regular release, Peter 2026-10-23). This builds the body of
 * the store contract, POST /api/v1/feedback: plugin, versions, question, vote, browser family and UI language, nothing
 * about the user, the instance or the website (store privacy policy, section 7d).
 */
final class FeedbackVote
{
    public const PLUGIN = 'FeinwerkMarketplaceConnector';

    /** The questions (store "topic"): one per tab of the screen (tasks and licenses since 5.1.0). */
    public const TOPICS = ['tasks', 'discover', 'manage', 'licenses'];

    /** @return array<string, ?string> */
    public static function payload(string $topic, string $vote, string $pluginVersion, string $matomoVersion, string $userAgent, string $language): array
    {
        if (! in_array($topic, self::TOPICS, true)) {
            throw new \InvalidArgumentException('topic');
        }
        if (! in_array($vote, ['up', 'down'], true)) {
            throw new \InvalidArgumentException('vote');
        }
        $browser = self::browser($userAgent);
        $language = mb_substr((string) preg_replace('/[^A-Za-z_-]/', '', $language), 0, 10);

        return [
            'plugin' => self::PLUGIN,
            'plugin_version' => mb_substr($pluginVersion, 0, 30),
            'matomo_version' => mb_substr($matomoVersion, 0, 30),
            'topic' => $topic,
            'vote' => $vote,
            'browser' => $browser !== '' ? $browser : null,
            'language' => $language !== '' ? $language : null,
        ];
    }

    /** Browser family and major version ("Chrome 141"), empty when unknown. Order matters: Edge and Opera also say Chrome. */
    public static function browser(string $userAgent): string
    {
        $patterns = [
            'Edge' => '/\bEdg(?:e|A|iOS)?\/(\d+)/',
            'Opera' => '/\bOPR\/(\d+)/',
            'Firefox' => '/\bFirefox\/(\d+)/',
            'Chrome' => '/\b(?:Chrome|CriOS)\/(\d+)/',
            'Safari' => '/\bVersion\/(\d+)[.\d]* (?:Mobile\/\S+ )?Safari\//',
        ];
        foreach ($patterns as $family => $pattern) {
            if (preg_match($pattern, $userAgent, $m)) {
                return $family.' '.$m[1];
            }
        }

        return '';
    }

    /** The Feinwerk contact form with the topic "Plugins" preselected. */
    public static function supportUrl(string $language): string
    {
        $page = str_starts_with($language, 'de') ? 'de/kontakt' : 'en/contact';

        return 'https://feinwerk.io/'.$page.'?service=plugins&mtm_campaign=plugin-support&mtm_source='.self::PLUGIN;
    }

    /**
     * @param  array<string, mixed>  $section  the connector's config.ini section: feedback = 0 switches it off
     * @param  bool  $internetEnabled  Matomo's [General] enable_internet_features (its HTTP client does not check it)
     */
    public static function enabledBy(array $section, bool $internetEnabled): bool
    {
        return $internetEnabled && (string) ($section['feedback'] ?? '1') !== '0';
    }
}
