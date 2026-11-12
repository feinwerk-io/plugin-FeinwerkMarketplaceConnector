<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Menu\MenuAdmin;
use Piwik\Piwik;

class Menu extends \Piwik\Plugin\Menu
{
    /** The section of the admin menu (translated by Matomo when it renders the menu). */
    private const SECTION = 'FeinwerkMarketplaceConnector_MenuTitle';

    /**
     * Since 5.1.0 an own section "Feinwerk Plugins" (Peter, 2026-10-23: keep the intentions apart) with Aufgaben (N),
     * Entdecken, Verwaltung and Lizenzen. Before, one entry under Plugins (or System on Matomo without that section).
     */
    public function configureAdminMenu(MenuAdmin $menu)
    {
        // The section takes the order of its first entry: Matomo's sections are 1 (Personal), 2 (System), 3 (Plugins,
        // Privacy). Ties sort by the section's own name, which a section without a header entry lacks, so it comes first:
        // 3 puts it right after System, before Plugins (checked on stats5 and stats6; 2 put it before System).
        if (! Piwik::hasUserSuperUserAccess()) {
            return;
        }

        if (method_exists($menu, 'registerMenuIcon')) {
            $menu->registerMenuIcon(self::SECTION, 'icon-marketplace');
        }
        $menu->addItem(self::SECTION, $this->tasksTitle(), $this->urlForAction('tasks'), 3);
        $menu->addItem(self::SECTION, Piwik::translate('FeinwerkMarketplaceConnector_TabDiscover'), $this->urlForAction('index'), 4);
        $menu->addItem(self::SECTION, Piwik::translate('FeinwerkMarketplaceConnector_TabManage'), $this->urlForAction('manage'), 5);
        $menu->addItem(self::SECTION, Piwik::translate('FeinwerkMarketplaceConnector_TabLicenses'), $this->urlForAction('licenses'), 6);
    }

    /** "Aufgaben (N)" while something needs the admin, like Matomo's "Plugins (N)". Cache-only, never throws. */
    private function tasksTitle(): string
    {
        $title = Piwik::translate('FeinwerkMarketplaceConnector_TabTasks');
        $count = count(PendingUpdates::actionItems());

        return $count > 0 ? $title.' ('.$count.')' : $title;
    }
}