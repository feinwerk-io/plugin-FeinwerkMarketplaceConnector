<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/** Readiness rows → what the notice says (the connector itself is left out: this build already runs on both lines). */
final class ReadinessSummary
{
    /** A cached answer is only meaningful while it targets the major right after the running one. */
    public static function appliesTo(int $cachedTarget, ?int $currentMajor): bool
    {
        return $currentMajor !== null && $cachedTarget === $currentMajor + 1;
    }

    /** @return array{ready: list<string>, beta: list<string>, missing: list<string>, any: bool} */
    public static function of(array $rows): array
    {
        $summary = ['ready' => [], 'beta' => [], 'missing' => [], 'any' => false];
        foreach ($rows as $row) {
            $slug = is_array($row) ? (string) ($row['slug'] ?? '') : '';
            if ($slug === '' || $slug === 'FeinwerkMarketplaceConnector') {
                continue;
            }
            if (empty($row['available_version'])) {
                $summary['missing'][] = $slug;
            } elseif (($row['channel'] ?? '') === 'beta') {
                $summary['beta'][] = $slug;
            } else {
                $summary['ready'][] = $slug;
            }
        }
        $summary['any'] = $summary['ready'] !== [] || $summary['beta'] !== [];

        return $summary;
    }
}
