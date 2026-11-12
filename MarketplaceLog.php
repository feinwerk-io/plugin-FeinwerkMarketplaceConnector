<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Container\StaticContainer;
use Piwik\Log\LoggerInterface;

/**
 * Log lines of the unattended marketplace work (daily task, upgrade swap), not shown as web notifications.
 *
 * Failures are logged at NOTICE, never WARNING: Matomo console commands exit 1 as soon as anything logs a WARNING
 * (core FailureLogMessageDetector), and core:archive runs the scheduled tasks in-process, so a WARNING here would turn
 * the customer's archiving cron red although archiving worked. NOTICE still shows in the core:archive output and in
 * log writers set to NOTICE or lower. Failure messages come from MarketplaceApiClient (HTTP status and the
 * marketplace's message, token masked). Never throws.
 */
final class MarketplaceLog
{
    public static function failure(string $what, \Throwable $e): void
    {
        try {
            StaticContainer::get(LoggerInterface::class)->notice(
                'Feinwerk Plugins: '.$what.' failed: '.$e->getMessage(),
                ['ignoreInScreenWriter' => true]
            );
        } catch (\Throwable $ignored) {
            // Logging must never break a task.
        }
    }

    public static function info(string $message): void
    {
        try {
            StaticContainer::get(LoggerInterface::class)->info('Feinwerk Plugins: '.$message, ['ignoreInScreenWriter' => true]);
        } catch (\Throwable $ignored) {
            // Logging must never break a task.
        }
    }
}
