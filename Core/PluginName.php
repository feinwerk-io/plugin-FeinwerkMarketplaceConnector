<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/**
 * A plugin's name for people to read: the store's display name, else the technical name written as words
 * ("FeinwerkSearchReport" → "Feinwerk Search Report", "FeinwerkAuthSaml" → "Feinwerk Auth SAML"). Console commands and
 * anything else the admin types keep the technical name.
 */
final class PluginName
{
    /** Words written in capitals inside plugin names. */
    private const ACRONYMS = ['Api' => 'API', 'Csv' => 'CSV', 'Pdf' => 'PDF', 'Saml' => 'SAML', 'Seo' => 'SEO', 'Sso' => 'SSO', 'Url' => 'URL'];

    /** @param  array<string, mixed>  $plugin  a row of the marketplace list (slug, display_name) */
    public static function of(array $plugin): string
    {
        $name = $plugin['display_name'] ?? null;
        if (is_string($name) && trim($name) !== '') {
            return trim($name);
        }

        return self::spaced((string) ($plugin['slug'] ?? ''));
    }

    public static function spaced(string $slug): string
    {
        $words = preg_split('/(?<=[a-z])(?=[A-Z0-9])|(?<=[0-9])(?=[A-Z])|(?<=[A-Z])(?=[A-Z][a-z])/', $slug) ?: [$slug];

        return implode(' ', array_map(fn (string $word) => self::ACRONYMS[$word] ?? $word, $words));
    }
}
