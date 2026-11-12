<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/**
 * Matomo's own error notice "The plugin X could not be loaded as it has missing dependencies: ..." (core
 * Plugin\Manager, one notification id for all plugins, the last one wins). Tells which plugin a notice is about by
 * rebuilding the start of that sentence in the current language, so no English text is matched (pure).
 */
final class UnloadedNotice
{
    public const ID = 'PluginManager_PluginUnloaded';
    public const SENTENCE = 'CorePluginsAdmin_WeCouldNotLoadThePluginAsItHasMissingDependencies';

    private const MARKER = "\u{1}fwmc-missing\u{1}";

    /**
     * @param  list<string>  $plugins  the plugins it may be about
     * @param  callable(string, string): string  $sentence  Matomo's translated sentence for (plugin, missing dependencies)
     * @return ?string the plugin the notice is about, or null when it names none of them or the sentence cannot tell
     */
    public static function about(string $message, array $plugins, callable $sentence): ?string
    {
        foreach ($plugins as $plugin) {
            $text = (string) $sentence($plugin, self::MARKER);
            $cut = strpos($text, self::MARKER);
            if ($cut === false) {
                continue;
            }
            $start = substr($text, 0, $cut);
            // The name must stand before the dependencies and be followed by something, or a longer name would match.
            if (strpos($start, $plugin) === false || str_ends_with($start, $plugin)) {
                continue;
            }
            if (str_starts_with($message, $start)) {
                return $plugin;
            }
        }

        return null;
    }
}
