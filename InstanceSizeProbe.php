<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Common;
use Piwik\Db;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\InstanceSize;

/**
 * Measures this Matomo for the database-update warning (Core/InstanceSize): MySQL's row estimate of log_visit and the
 * size of the log tables, from SHOW TABLE STATUS (no table scan). Never throws: unknown counts as not large.
 */
final class InstanceSizeProbe
{
    /** @var ?array{large: bool, visits: ?int} measured once per request (admin pages ask on every view); tests set it */
    public static ?array $measured = null;

    /** @return array{large: bool, visits: ?int} */
    public static function measure(): array
    {
        return self::$measured ??= self::probe();
    }

    /** @return array{large: bool, visits: ?int} */
    private static function probe(): array
    {
        $visits = null;
        $bytes = null;
        try {
            $tables = [Common::prefixTable('log_visit'), Common::prefixTable('log_link_visit_action'), Common::prefixTable('log_conversion')];
            $rows = Db::fetchAll('SHOW TABLE STATUS WHERE Name IN (?, ?, ?)', $tables);
            $bytes = 0;
            foreach ($rows as $row) {
                $bytes += (int) ($row['Data_length'] ?? 0) + (int) ($row['Index_length'] ?? 0);
                if (($row['Name'] ?? null) === $tables[0]) {
                    $visits = (int) ($row['Rows'] ?? 0);
                }
            }
        } catch (\Throwable $e) {
            // Unknown size: the general hint about large databases still shows.
        }

        $override = null;
        try {
            $section = \Piwik\Config::getInstance()->FeinwerkMarketplaceConnector;
            $override = is_array($section) ? ($section['large_instance'] ?? null) : null;
        } catch (\Throwable $e) {
        }

        return ['large' => InstanceSize::isLarge($visits, $bytes, $override), 'visits' => $visits];
    }
}
