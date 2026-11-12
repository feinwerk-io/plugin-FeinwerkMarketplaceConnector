<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/**
 * Which plugins the daily task may update on its own. The connector never updates itself
 * automatically (a broken release would disable the updater), and git checkouts/symlinks are never
 * overwritten — whatever the stored setting says.
 */
final class AutoApplyPolicy
{
    /**
     * @param  mixed  $configured  the stored setting: a list of plugin names (or a legacy newline string)
     * @param  list<string>  $devCheckouts
     * @return list<string>
     */
    public static function effective(mixed $configured, string $selfSlug, array $devCheckouts = []): array
    {
        $names = is_string($configured) ? preg_split('/\R/', $configured) : (array) $configured;

        $result = [];
        foreach ($names as $name) {
            if (! is_string($name)) {
                continue;
            }
            $name = trim($name);
            if ($name === '' || $name === $selfSlug || in_array($name, $devCheckouts, true) || in_array($name, $result, true)) {
                continue;
            }
            $result[] = $name;
        }

        return $result;
    }
}
