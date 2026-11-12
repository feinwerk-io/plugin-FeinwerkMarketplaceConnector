<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/** Combines the result of a swap run with the previous one so an admin never misses an unseen part (pure). */
final class SwapResult
{
    /** Where a plugin ended up; a plugin is in one of these lists only. */
    private const STATUS_LISTS = ['inactive', 'kept_off', 'activated', 'needs_admin', 'needs_license'];

    /**
     * @param  ?array<string,mixed>  $previous  the stored result of the last run (null = none)
     * @param  array<string,mixed>  $current  the result of this run (shown = false)
     * @return array<string,mixed>
     */
    public static function merge(?array $previous, array $current): array
    {
        if ($previous === null) {
            return $current;
        }

        if (empty($previous['shown'])) {
            // Not seen yet: keep what it announced, add what is new.
            $current['switched'] = (array) ($current['switched'] ?? []) + (array) ($previous['switched'] ?? []);
            $now = [];
            foreach (self::STATUS_LISTS as $key) {
                $now[$key] = array_map('strval', (array) ($current[$key] ?? []));
            }
            foreach ($now as $key => $list) {
                // A plugin sits in one of these lists only: what this run says about it replaces the earlier word.
                $movedOn = array_merge(...array_values(array_diff_key($now, [$key => true])));
                $current[$key] = array_values(array_unique(array_merge(
                    array_diff(array_map('strval', (array) ($previous[$key] ?? [])), $movedOn),
                    $list
                )));
            }
            $current['shown'] = false;

            return $current;
        }

        // Already shown: an unchanged result is not announced again.
        if (self::visibleParts($previous) === self::visibleParts($current)) {
            $current['shown'] = true;
        }

        return $current;
    }

    /** The parts of a result an admin sees in the notice. */
    private static function visibleParts(array $result): array
    {
        $sorted = function ($list): array {
            $list = array_values(array_map('strval', (array) $list));
            sort($list);

            return $list;
        };

        return [
            $sorted(array_keys((array) ($result['switched'] ?? []))),
            $sorted($result['activated'] ?? []),
            $sorted($result['inactive'] ?? []),
            $sorted($result['kept_off'] ?? []),
            $sorted(array_keys((array) ($result['errors'] ?? []))),
            $sorted($result['needs_admin'] ?? []),
            $sorted($result['needs_license'] ?? []),
        ];
    }
}
