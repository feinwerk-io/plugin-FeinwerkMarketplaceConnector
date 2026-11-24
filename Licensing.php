<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Option;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\TrialClock;

/**
 * Shared interface for paid Feinwerk plugins (since 5.6.0; contract docs/dev/trials.md, pinned in
 * tests/contract/consumers.php): whether a trial of a plugin has ended (Peter, 2026-11-16, rule b: "features off, data
 * kept"). Read from the cached store list only, never a request: a licence flagged `trial` with its `valid_until`.
 * The store lists only active licences, so the connector remembers each trial end it saw (option fwmc.trials) and
 * forgets it as soon as the list shows a licence that is no trial. Doubt (nothing cached, unreadable data) means no
 * trial: false and null. Never fwmc.license.<Plugin> (HARD RULE 9). Static, never throws, one read per request.
 */
final class Licensing
{
    public const OPTION = 'fwmc.trials';

    /**
     * Posted (no parameters) when the licence picture of the store list changed: a trial started or ended, a licence was
     * bought, claimed or is gone (Tracking Router, 2026-11-17: plugins refresh their own licence state at once). Shared
     * interface since 5.6.0. Listeners must not throw and must not call the store.
     */
    public const EVENT_CHANGED = 'FeinwerkMarketplaceConnector.licensesChanged';

    /** The fingerprint of the last licence picture seen (for EVENT_CHANGED). */
    public const OPTION_SEEN = 'fwmc.licenses.seen';

    /** @var array<string, int>|null slug => trial end (unix time), this request's answer */
    private static ?array $ends = null;

    /** @var array<string, bool>|null slug => the cached list shows an active licence that is no trial */
    private static ?array $paid = null;

    /** The end of the plugin's trial as unix time; null when no trial is known (also for every paid licence). */
    public static function trialEndsAt(string $plugin): ?int
    {
        return self::ends()[$plugin] ?? null;
    }

    /**
     * True only when the plugin's licence was a trial and its end passed more than a day ago (TrialClock::GRACE); false
     * for every other licence, a lapsed paid one included, and on any doubt.
     */
    public static function trialEnded(string $plugin): bool
    {
        return TrialClock::ended(self::trialEndsAt($plugin), time());
    }

    /**
     * For a plugin without its own licence check (TrackingRouter, 2026-11-17), to clear its trial memory only on proof:
     * true = the cached list shows an active licence that is no trial (bought); false = a trial is known (running or
     * ended); null = doubt (not listed, nothing cached, no licence and no trial known).
     */
    public static function licensedWithoutTrial(string $plugin): ?bool
    {
        if (self::trialEndsAt($plugin) !== null) {
            return false;
        }

        return (self::$paid ?? [])[$plugin] ?? null;
    }

    /**
     * Takes in what a fresh store list says (the daily task, the screen): remembers trial ends, forgets the plugins that
     * show a licence that is no trial. Writes only on a change. Never throws.
     *
     * @param  list<array<mixed>>  $plugins
     */
    public static function sync(array $plugins): void
    {
        self::$rows = null;   // a new list: read it again
        try {
            $before = self::stored();
            $after = self::merge($before, $plugins);
            self::$paid = self::paid($plugins);
            if ($after !== $before) {
                Option::set(self::OPTION, (string) json_encode($after));
            }
            self::$ends = $after;
            self::announce($plugins, $after);
        } catch (\Throwable $e) {
        }
    }

    /**
     * Posts EVENT_CHANGED when the licence picture differs from the last one seen. Only a list with plugins counts (an
     * empty cache says nothing). Stored first, so a listener that reads the helpers again finds the new picture.
     *
     * @param  list<array<mixed>>  $plugins
     * @param  array<string, int>  $ends
     */
    private static function announce(array $plugins, array $ends): void
    {
        if ($plugins === []) {
            return;
        }
        $picture = [];
        foreach ($plugins as $plugin) {
            if (is_array($plugin) && is_string($plugin['slug'] ?? null)) {
                $license = is_array($plugin['license'] ?? null) ? $plugin['license'] : null;
                $picture[$plugin['slug']] = $license === null ? null
                    : [$license['status'] ?? 'active', $license['valid_until'] ?? null, ($license['trial'] ?? false) === true];
            }
        }
        ksort($picture);
        $seen = md5((string) json_encode([$picture, $ends]));
        try {
            if (Option::get(self::OPTION_SEEN) === $seen) {
                return;
            }
            Option::set(self::OPTION_SEEN, $seen);
            \Piwik\Piwik::postEvent(self::EVENT_CHANGED);
        } catch (\Throwable $e) {
        }
    }

    /** For tests: forget this request's answer. */
    public static function forget(): void
    {
        self::$ends = null;
        self::$paid = null;
        self::$rows = null;
    }

    /** @var array<string, array<mixed>>|null this request's store list by slug (one read, also for the plugins' calls) */
    private static ?array $rows = null;

    /**
     * The cached store list, read once per request (the helpers below are called on every page by the plugins).
     *
     * @return array<string, array<mixed>> slug => row
     */
    public static function cachedRows(): array
    {
        if (self::$rows === null) {
            self::$rows = [];
            try {
                foreach (PendingUpdates::cachedPlugins() as $row) {
                    if (is_array($row) && is_string($row['slug'] ?? null)) {
                        self::$rows[$row['slug']] = $row;
                    }
                }
            } catch (\Throwable $e) {
            }
        }

        return self::$rows;
    }

    /** @return array<string, int> */
    private static function ends(): array
    {
        if (self::$ends === null) {
            self::$ends = [];
            self::sync(PendingUpdates::cachedPlugins());
        }

        return self::$ends;
    }

    /**
     * @param  list<array<mixed>>  $plugins
     * @return array<string, bool> slug => true for an active licence that is no trial
     */
    private static function paid(array $plugins): array
    {
        $paid = [];
        foreach ($plugins as $plugin) {
            $license = is_array($plugin) && is_string($plugin['slug'] ?? null) ? ($plugin['license'] ?? null) : null;
            if (is_array($license) && ($license['trial'] ?? null) === false && ($license['status'] ?? 'active') === 'active') {
                $paid[$plugin['slug']] = true;
            }
        }

        return $paid;
    }

    /** @return array<string, int> */
    private static function stored(): array
    {
        try {
            $value = json_decode((string) Option::get(self::OPTION), true);
        } catch (\Throwable $e) {
            return [];
        }
        $ends = [];
        foreach (is_array($value) ? $value : [] as $slug => $end) {
            if (is_string($slug) && is_int($end)) {
                $ends[$slug] = $end;
            }
        }

        return $ends;
    }

    /**
     * @param  array<string, int>  $ends
     * @param  list<array<mixed>>  $plugins
     * @return array<string, int>
     */
    private static function merge(array $ends, array $plugins): array
    {
        foreach ($plugins as $plugin) {
            $slug = is_array($plugin) && is_string($plugin['slug'] ?? null) ? $plugin['slug'] : null;
            $license = $slug !== null ? ($plugin['license'] ?? null) : null;
            if (! is_array($license)) {
                continue;   // no licence listed: keep what was seen (an ended trial leaves the list)
            }
            $trial = ($license['trial'] ?? null) === true;
            // Only an explicit `trial: false` on an active licence proves a bought one and wins for good (Conversions
            // API, 2026-11-17: a store without the field says nothing); an expired one proves nothing either.
            if (($license['trial'] ?? null) === false && ($license['status'] ?? 'active') === 'active') {
                unset($ends[$slug]);
                continue;
            }
            if (! $trial) {
                continue;
            }
            $end = TrialClock::endsAt($license['valid_until'] ?? null);
            if ($end !== null) {
                $ends[$slug] = $end;
            }
        }
        ksort($ends);

        return $ends;
    }

    /**
     * Shared interface (2026-11-17, docs/dev/license-end.md): when the bought licence of $plugin ended, as a Unix time;
     * null when a licence covers this Matomo, there never was one, it was a trial or the store does not say. From the
     * cached list only; never throws.
     */
    public static function licenseEndedAt(string $plugin): ?int
    {
        $row = self::cachedRows()[$plugin] ?? null;

        return $row === null ? null : \Piwik\Plugins\FeinwerkMarketplaceConnector\Core\LicenseEnd::endedAt($row);
    }

    /**
     * Shared interface (2026-11-17): true when this connector shows the central lapsed-licence notice for $plugin
     * (connected, and the store's answer carries license_ended_at); a plugin then drops its own lapsed notice. Never throws.
     */
    public static function handlesLicenseEnd(string $plugin): bool
    {
        try {
            // Switched off, the connector raises nothing (review 2026-11-17): then the plugin keeps its own notice.
            if (! \Piwik\Plugin\Manager::getInstance()->isPluginActivated('FeinwerkMarketplaceConnector')) {
                return false;
            }
            $row = self::cachedRows()[$plugin] ?? null;

            return $row !== null && \Piwik\Plugins\FeinwerkMarketplaceConnector\Core\LicenseEnd::covers($row);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Whether a remembered trial has ended (after its day of grace) within the last $window seconds: then a purchase on
     * the store website is likely and the hourly task fetches the list again (Tasks::refreshAfterEndedTrial). Never throws.
     */
    public static function trialEndedWithin(int $window, int $now): bool
    {
        try {
            foreach (self::ends() as $end) {
                if (TrialClock::ended($end, $now) && $now - $end <= $window) {
                    return true;
                }
            }
        } catch (\Throwable $e) {
        }

        return false;
    }
}
