<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Piwik;

/**
 * The data of the page that replaces Matomo's browser run of the database update on a large instance (B1,
 * Controller::consoleUpdate, templates/consoleUpdate.twig), and a plain fallback if the template cannot render.
 */
final class ConsoleUpdatePage
{
    public const OVERRIDE = '[FeinwerkMarketplaceConnector] large_instance = 0';

    /** "Run in the browser anyway": the button posts Matomo's own run with this single-use nonce (and the tick). */
    public const BROWSER_RUN_NONCE = 'FeinwerkMarketplaceConnector.browserRun';

    /** Matomo's run, addressed to CoreUpdater directly: the form on its page goes through a redirect that drops a POST body. */
    public const BROWSER_RUN_URL = 'index.php?module=CoreUpdater&action=index&updateCorePlugins=1';

    /** @return array{command: string, superUser: bool, themeMode: string, override: string, browserRunNonce: string, browserRunNote: string} */
    public static function variables(): array
    {
        $superUser = false;
        $nonce = '';
        try {
            $superUser = Piwik::hasUserSuperUserAccess();
            if ($superUser) {
                $nonce = (string) \Piwik\Nonce::getNonce(self::BROWSER_RUN_NONCE, 3600);
            }
        } catch (\Throwable $e) {
        }

        return ['command' => self::command(), 'superUser' => $superUser, 'themeMode' => self::themeMode(), 'override' => self::OVERRIDE,
            'browserRunNonce' => $nonce, 'browserRunNote' => (string) FeinwerkMarketplaceConnector::$browserRunNote];
    }

    /** The command of Matomo's own update page, which also names the instance of a multi-instance install. */
    public static function command(): string
    {
        $command = ConsoleCommand::of('core:update');
        try {
            $instance = \Piwik\SettingsPiwik::getPiwikInstanceId();
            if (is_string($instance) && $instance !== '') {
                $command .= ' --matomo-domain='.escapeshellarg($instance);
            }
        } catch (\Throwable $e) {
        }

        return $command;
    }

    /**
     * Matomo's update layout has no theme of its own: the page follows the admin's theme (Matomo 5.12+), otherwise
     * the browser's light or dark setting.
     */
    private static function themeMode(): string
    {
        try {
            $preferences = \Piwik\Plugins\UsersManager\UserPreferences::class;
            if (! Piwik::isUserIsAnonymous() && method_exists($preferences, 'getThemeMode')) {
                $mode = (new $preferences())->getThemeMode();
                if (in_array($mode, ['light', 'dark', 'auto'], true)) {
                    return $mode;
                }
            }
        } catch (\Throwable $e) {
        }

        return 'auto';
    }

    /** @param  array{command: string, superUser: bool, themeMode: string, override: string, browserRunNonce: string, browserRunNote: string}  $page */
    public static function plain(array $page): string
    {
        $text = function (string $key, array $args = []): string {
            try {
                return Piwik::translate('FeinwerkMarketplaceConnector_'.$key, $args);
            } catch (\Throwable $e) {
                return $key;
            }
        };
        $e = fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

        return '<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="robots" content="noindex,nofollow">'
            .'<title>'.$e($text('ConsoleUpdateTitle')).'</title></head><body>'
            .'<h1>'.$e($text('ConsoleUpdateTitle')).'</h1>'
            .'<p>'.$e($text('ConsoleUpdateIntro')).'</p>'
            .'<pre>'.$e($page['command']).'</pre>'
            .'<p>'.$e($text('ConsoleUpdateThen')).'</p>'
            .'<p><a href="index.php">'.$e($text('ConsoleUpdateRecheck')).'</a></p>'
            .(($page['browserRunNote'] ?? '') !== '' ? '<p><strong>'.$e($page['browserRunNote']).'</strong></p>' : '')
            .(($page['browserRunNonce'] ?? '') !== '' ? '<form method="post" action="'.$e(self::BROWSER_RUN_URL).'">'
                .'<input type="hidden" name="fwmc_browser_nonce" value="'.$e($page['browserRunNonce']).'">'
                .'<p>'.$e($text('ConsoleUpdateAnywayHint')).'</p>'
                .'<p><label><input type="checkbox" name="fwmc_browser_confirm" value="1" required> '.$e($text('ConsoleUpdateAnywayConfirm')).'</label></p>'
                .'<p><button type="submit">'.$e($text('ConsoleUpdateAnywayButton')).'</button></p></form>' : '')
            .'</body></html>';
    }
}
