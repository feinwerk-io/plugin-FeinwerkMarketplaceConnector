<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core;

/**
 * What an install or update changes in this Matomo's database, from the marketplace's `latest.db_changes` (update files
 * and log table columns of the build) and what is installed here. Shown as a warning before the click: update files run
 * in Matomo's database upgrade, new log table columns are an ALTER TABLE on the biggest tables. Both can take long.
 */
final class DbChangePlan
{
    /**
     * @param  ?string  $installed  installed version, null for a fresh install (Matomo runs no update files then)
     * @param  list<string>  $installedColumns  log table columns the installed version already has ("log_visit.x")
     * @return array{migrations: list<string>, columns: list<string>, note: ?string}|null null when nothing changes
     */
    public static function for(?string $installed, string $latest, mixed $dbChanges, array $installedColumns, mixed $note = null): ?array
    {
        $dbChanges = is_array($dbChanges) ? $dbChanges : [];

        $migrations = [];
        if ($installed !== null) {
            foreach ((array) ($dbChanges['migrations'] ?? []) as $version) {
                if (is_string($version) && version_compare($version, $installed, '>') && version_compare($version, $latest, '<=')) {
                    $migrations[] = $version;
                }
            }
        }

        $columns = [];
        foreach ((array) ($dbChanges['log_columns'] ?? []) as $column) {
            if (is_string($column) && preg_match('/^log_[a-z_]+\.[A-Za-z0-9_]+$/', $column) === 1 && ! in_array($column, $installedColumns, true)) {
                $columns[] = $column;
            }
        }

        $note = is_string($note) && trim($note) !== '' ? mb_substr(trim($note), 0, 1000) : null;

        if ($migrations === [] && $columns === [] && $note === null) {
            return null;
        }
        usort($migrations, 'version_compare');

        return ['migrations' => $migrations, 'columns' => $columns, 'note' => $note];
    }

    /** "log_visit.<column>" for a dimension class that adds a log table column (same rule as the marketplace), else null. */
    public static function logColumn(string $php): ?string
    {
        $tables = ['VisitDimension' => 'log_visit', 'ActionDimension' => 'log_link_visit_action', 'ConversionDimension' => 'log_conversion'];
        if (! preg_match('/\bextends\s+\\\\?(?:[\w\\\\]+\\\\)?(VisitDimension|ActionDimension|ConversionDimension)\b/', $php, $parent)
            || ! preg_match('/protected\s+\$columnName\s*=\s*[\'"]([A-Za-z0-9_]+)[\'"]/', $php, $name)
            || ! preg_match('/protected\s+\$columnType\s*=\s*[\'"][^\'"]+[\'"]/', $php)) {
            return null;
        }

        return $tables[$parent[1]].'.'.$name[1];
    }
}
