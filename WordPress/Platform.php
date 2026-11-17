<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\WordPress;

/**
 * What the WordPress mode needs from WordPress (docs/dev/wordpress-mode-plan.md). The real one (WpPlatform) calls
 * WordPress's own functions; tests use a stand-in, so the decisions around it run without WordPress.
 */
interface Platform
{
    /** @return array{wordpress_version: string, mfw_version: string} */
    public function versions(): array;

    /** @return array<string, string> slug => version of the Feinwerk plugins installed as WordPress plugins */
    public function installedPlugins(): array;

    /** Why this request cannot install plugins ("capability" or "filesystem"), or null when it can. */
    public function cannotInstall(): ?string;

    /** A fresh install from a package URL with WordPress's installer; null when done, else why not. */
    public function install(string $packageUrl): ?string;

    /**
     * An update to exactly $version through WordPress's own update path (as its "Update now": the plugin stays switched
     * on, WordPress keeps a backup and restores it when the update fails); null when done, else why not.
     */
    public function update(string $slug, string $version): ?string;

    /** Whether the WordPress plugin "<Plugin>/<Plugin>.php" is switched on. */
    public function isActive(string $file): bool;

    /** Switches a WordPress plugin on ("<Plugin>/<Plugin>.php"); null when done, else why not. */
    public function activate(string $file): ?string;

    /** Whether wp-content/plugins/<slug> is a symlink or holds .git (HARD RULE 7: never overwritten). */
    public function isDevCheckout(string $slug): bool;

    /** A WordPress multisite: the WordPress mode stays off there (Peter, 2026-11-17). */
    public function isMultisite(): bool;

    /** The wp-admin URL that runs Matomo for WordPress's installer and then opens $target (WordPress\\Hooks::onReturn). */
    public function returnUrl(string $target): string;

    /** @param array<string, array<string, mixed>> $updates WordPressUpdates::map() entries, kept for WordPress's update list */
    public function saveUpdates(array $updates): void;
}
