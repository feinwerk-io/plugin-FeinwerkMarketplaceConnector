<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/**
 * The plugin the recommendation banner shows (backlog 1, Peter 2026-10-27: build it, with a switch to turn it off): one
 * Feinwerk plugin this Matomo does not have yet, from the cached store list (no request of its own), paid ones first,
 * a different one each day. The banner is labelled as a recommendation and sends nothing.
 */
final class Promotion
{
    /**
     * @param  list<array<string, mixed>>  $plugins  the cached store list
     * @param  array<string, string>  $installed  slug => installed version
     * @param  int  $day  days since the epoch, so the pick changes once a day
     * @return array{slug: string, name: string, description: string, artworkUrl: ?string, tier: string, priceFrom: ?array}|null
     */
    public static function pick(array $plugins, array $installed, string $selfSlug, int $day): ?array
    {
        $paid = [];
        $free = [];
        foreach ($plugins as $plugin) {
            $slug = is_string($plugin['slug'] ?? null) ? $plugin['slug'] : '';
            if ($slug === '' || $slug === $selfSlug || isset($installed[$slug]) || empty($plugin['latest']['version'])) {
                continue;
            }
            if (($plugin['tier'] ?? 'free') === 'paid') {
                $paid[$slug] = $plugin;
            } else {
                $free[$slug] = $plugin;
            }
        }
        $candidates = $paid !== [] ? $paid : $free;
        if ($candidates === []) {
            return null;
        }
        ksort($candidates);
        $plugin = array_values($candidates)[abs($day) % count($candidates)];
        $artwork = is_string($plugin['artwork_url'] ?? null) && preg_match('#^https?://#i', $plugin['artwork_url']) === 1 ? $plugin['artwork_url'] : null;

        return [
            'slug' => (string) $plugin['slug'],
            'name' => PluginName::of($plugin),
            'description' => is_string($plugin['description'] ?? null) ? $plugin['description'] : '',
            'artworkUrl' => $artwork,
            'tier' => (string) ($plugin['tier'] ?? 'free'),
            'priceFrom' => is_array($plugin['price_from'] ?? null) ? $plugin['price_from'] : null,
        ];
    }
}
