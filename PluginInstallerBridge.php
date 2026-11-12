<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Plugin\Manager;
use Piwik\Plugins\CorePluginsAdmin\PluginInstaller;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\ArtifactVerifier;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto\DownloadedArtifact;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Exceptions\MarketplaceException;

class PluginInstallerBridge
{
    public function __construct(private readonly ArtifactVerifier $verifier = new ArtifactVerifier()) {}

    /**
     * Verify the downloaded artifact, then unpack + install (+ activate unless $activate is false) it via
     * Matomo's own installer (the same path the native Marketplace uses).
     *
     * @return string the installed plugin name
     */
    public function install(DownloadedArtifact $artifact, bool $activate = true): string
    {
        $this->verifier->verify($artifact->bytes, $artifact->sha256);

        $zipPath = tempnam(sys_get_temp_dir(), 'fwmc_').'.zip';
        if ($zipPath === false || file_put_contents($zipPath, $artifact->bytes) === false) {
            throw new MarketplaceException('Could not write the downloaded plugin to a temporary file.');
        }

        try {
            $installer = new PluginInstaller();
            $metadata = $installer->installOrUpdatePluginFromFile($zipPath);
            $pluginName = $metadata->name;

            if ($activate) {
                Manager::getInstance()->activatePlugin($pluginName);
            }

            return $pluginName;
        } finally {
            @unlink($zipPath);
        }
    }
}
