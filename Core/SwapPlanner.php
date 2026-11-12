<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/** What the upgrade swap has to do now (pure; the glue executes it). */
final class SwapPlanner
{
    /**
     * @param  list<string>  $reactivate  slugs that still need (re)activation — what was active before the update and
     *                                   has not been switched on again yet ([] when the earlier state is unknown)
     * @param  array<string,string>  $installed  Feinwerk plugins installed now, slug => version (connector excluded)
     * @param  list<string>  $outgrown  installed slugs whose build serves only an older Matomo line
     * @param  list<string>  $devCheckouts  git checkouts / symlinks — never touched
     * @param  list<string>  $activeNow
     * @param  array<string,string>  $offered  slug => latest version the marketplace offers this instance now
     * @param  list<string>  $giveUp  slugs the glue gave up on after repeated failures — reported as needsAdmin, never retried
     * @return array{install: list<array{slug: string, version: string, activate: bool}>, activate: list<string>, waiting: list<string>, skipped: list<string>, needsAdmin: list<string>}
     */
    public static function plan(array $reactivate, array $installed, array $outgrown, array $devCheckouts, array $activeNow, array $offered, array $giveUp = []): array
    {
        $plan = ['install' => [], 'activate' => [], 'waiting' => [], 'skipped' => [], 'needsAdmin' => []];

        foreach ($outgrown as $slug) {
            if (in_array($slug, $devCheckouts, true)) {
                $plan['skipped'][] = $slug;
            } elseif (in_array($slug, $giveUp, true)) {
                $plan['needsAdmin'][] = $slug;
            } elseif (isset($offered[$slug])) {
                $plan['install'][] = ['slug' => $slug, 'version' => $offered[$slug], 'activate' => in_array($slug, $reactivate, true)];
            } else {
                $plan['waiting'][] = $slug;
            }
        }

        foreach ($reactivate as $slug) {
            if (isset($installed[$slug]) && ! in_array($slug, $outgrown, true)
                && ! in_array($slug, $activeNow, true) && ! in_array($slug, $devCheckouts, true)) {
                $plan[in_array($slug, $giveUp, true) ? 'needsAdmin' : 'activate'][] = $slug;
            }
        }

        return $plan;
    }

    /** @param array{install: list<mixed>, activate: list<string>, waiting: list<string>} $plan */
    public static function isComplete(array $plan): bool
    {
        return $plan['install'] === [] && $plan['activate'] === [] && $plan['waiting'] === [];
    }
}
