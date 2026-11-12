<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Archive\ArchiveInvalidator;
use Piwik\Container\StaticContainer;
use Piwik\Date;
use Piwik\Piwik;
use Piwik\Plugin\Manager;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\GapDays;

/**
 * While Feinwerk plugins are off after a Matomo major update, every archive Matomo builds lacks their reports, and
 * Matomo does not rebuild them when a plugin is switched on again. After the swap switched plugins on, their reports
 * for the completed days since the update are queued for re-archiving with Matomo's own mechanism (the same as
 * Plugin::schedulePluginReArchiving()): plugin-only invalidations, created and processed by the next core:archive run.
 */
final class GapReArchiving
{
    /** @param list<string> $plugins plugins the swap switched on in this run */
    public static function schedule(array $plugins, int $since): void
    {
        try {
            $first = GapDays::firstDay($since, time());
            $archiving = array_values(array_filter($plugins, fn ($plugin) => self::archives((string) $plugin)));
            if ($first === null || $archiving === []) {
                return;
            }
            if (Piwik::getEarliestDateToRearchive() === null) {
                // rearchive_reports_in_past_last_n_months = 0: the admin switched Matomo's re-archiving off.
                MarketplaceLog::info('the reports of '.implode(', ', $archiving).' for the days since '.$first
                    .' are not rebuilt (re-archiving is switched off); rebuild them with core:invalidate-report-data if needed');

                return;
            }
            $invalidator = StaticContainer::get(ArchiveInvalidator::class);
            foreach ($archiving as $plugin) {
                $invalidator->scheduleReArchiving('all', $plugin, null, Date::factory($first));
            }
            MarketplaceLog::info('the reports of '.implode(', ', $archiving).' for all sites from '.$first
                .' (archived while they were switched off) are queued for re-archiving by the next core:archive run');
        } catch (\Throwable $e) {
            MarketplaceLog::failure('queueing the re-archiving after the Matomo update', $e);
        }
    }

    /** Matomo builds a plugin's reports by archiving when it has an Archiver class or RecordBuilders. */
    private static function archives(string $plugin): bool
    {
        $dir = Manager::getPluginDirectory($plugin);

        return is_file($dir.'/Archiver.php') || is_dir($dir.'/RecordBuilders');
    }
}
