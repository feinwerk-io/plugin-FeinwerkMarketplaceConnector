<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector;

use Piwik\Piwik;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\MarketplaceCache;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Core\PluginName;
use Piwik\Plugins\FeinwerkMarketplaceConnector\Http\OptionCacheStore;
use Piwik\Settings\FieldConfig;
use Piwik\Settings\Plugin\SystemSettings as BaseSystemSettings;

class SystemSettings extends BaseSystemSettings
{
    private const SELF_SLUG = 'FeinwerkMarketplaceConnector';

    /** Heading on the General settings page (Matomo shows the technical name without it). */
    protected $title = 'Feinwerk Connector';

    /**
     * Not a setting any more since 0.8.3 (the URL is built in), but the license checks of released Feinwerk plugins
     * (ConversionsApi, AuthSaml, SearchReport, TrackingSpamScore) still read `$settings->marketplaceUrl->getValue()`.
     * Keep this read-only stand-in until every reader is released without it.
     *
     * @var object{getValue: callable}
     */
    public $marketplaceUrl;

    /** @var \Piwik\Settings\Setting */
    public $instanceToken;

    /** @var \Piwik\Settings\Setting */
    public $autoApplyPlugins;

    /** @var \Piwik\Settings\Setting notices that only inform (readiness, updates available); warnings always show */
    public $showInfoNotices;

    /** @var \Piwik\Settings\Setting the recommendation banner on the Administration home and the Discover tab */
    public $showRecommendations;

    /**
     * @var \Piwik\Settings\Setting the other Feinwerk plugins' notes on Feinwerk offers (docs/promotions.md); read by
     *      them through Promotions::areEnabled(), never directly
     */
    public $showPromotions;

    protected function init()
    {
        $this->marketplaceUrl = new MarketplaceUrlValue();

        $this->instanceToken = $this->makeSetting('instanceToken', '', FieldConfig::TYPE_STRING, function (FieldConfig $field) {
            $field->title = Piwik::translate('FeinwerkMarketplaceConnector_SettingToken');
            $field->description = Piwik::translate('FeinwerkMarketplaceConnector_SettingTokenHelp');
            $field->uiControl = FieldConfig::UI_CONTROL_PASSWORD;
        });

        $this->autoApplyPlugins = $this->makeSetting('autoApplyPlugins', [], FieldConfig::TYPE_ARRAY, function (FieldConfig $field) {
            $field->title = Piwik::translate('FeinwerkMarketplaceConnector_SettingAutoApply');
            $field->description = Piwik::translate('FeinwerkMarketplaceConnector_SettingAutoApplyHelp');
            $field->uiControl = FieldConfig::UI_CONTROL_MULTI_SELECT;
            $field->availableValues = $this->autoApplyChoices();
            $field->validate = function ($value) {
                if (in_array(self::SELF_SLUG, (array) $value, true)) {
                    throw new \Exception(Piwik::translate('FeinwerkMarketplaceConnector_SettingAutoApplySelf'));
                }
            };
        });

        $this->showInfoNotices = $this->makeSetting('showInfoNotices', true, FieldConfig::TYPE_BOOL, function (FieldConfig $field) {
            $field->title = Piwik::translate('FeinwerkMarketplaceConnector_SettingInfoNotices');
            $field->description = Piwik::translate('FeinwerkMarketplaceConnector_SettingInfoNoticesHelp');
            $field->uiControl = FieldConfig::UI_CONTROL_CHECKBOX;
        });

        $this->showRecommendations = $this->makeSetting('showRecommendations', true, FieldConfig::TYPE_BOOL, function (FieldConfig $field) {
            $field->title = Piwik::translate('FeinwerkMarketplaceConnector_SettingRecommendations');
            $field->description = Piwik::translate('FeinwerkMarketplaceConnector_SettingRecommendationsHelp');
            if (Promotions::offInConfig()) {
                $field->description .= ' '.Piwik::translate('FeinwerkMarketplaceConnector_SettingRecommendationsConfigOff');
            }
            $field->uiControl = FieldConfig::UI_CONTROL_CHECKBOX;
        });

        $this->showPromotions = $this->makeSetting('showPromotions', true, FieldConfig::TYPE_BOOL, function (FieldConfig $field) {
            $field->title = Piwik::translate('FeinwerkMarketplaceConnector_SettingPromotions');
            $field->description = Piwik::translate('FeinwerkMarketplaceConnector_SettingPromotionsHelp');
            if (Promotions::offInConfig()) {
                // The config switch wins: say so, or ticking the box would seem broken.
                $field->description .= ' '.Piwik::translate('FeinwerkMarketplaceConnector_SettingPromotionsConfigOff');
            }
            $field->uiControl = FieldConfig::UI_CONTROL_CHECKBOX;
        });
    }

    /**
     * Installed marketplace plugins that may update automatically, read from the plugin-list cache
     * (no network). Values already stored stay selectable so an uninstalled plugin does not block saving.
     *
     * @return array<string,string> plugin name => display name
     */
    private function autoApplyChoices(): array
    {
        $choices = [];
        $names = [];
        try {
            $url = MarketplaceClientFactory::url();
            $token = (string) $this->instanceToken->getValue();
            if ($url !== '' && $token !== '') {
                $client = MarketplaceClientFactory::make($url, $token);
                $available = (new MarketplaceCache($client, new OptionCacheStore()))->cached()->plugins;
                $devCheckouts = InstalledMarketplacePlugins::devCheckouts($available);
                $installed = InstalledMarketplacePlugins::versions($available);
                foreach ($available as $plugin) {
                    $slug = (string) ($plugin['slug'] ?? '');
                    $names[$slug] = PluginName::of($plugin);
                    if (isset($installed[$slug]) && $slug !== self::SELF_SLUG && ! in_array($slug, $devCheckouts, true)) {
                        $choices[$slug] = $names[$slug];
                    }
                }
            }
        } catch (\Throwable $e) {
            // No cache yet or no marketplace: only the stored values below are offered.
        }

        foreach ((array) $this->autoApplyPlugins?->getValue() as $stored) {
            if (is_string($stored) && $stored !== '' && $stored !== self::SELF_SLUG && ! isset($choices[$stored])) {
                $choices[$stored] = $names[$stored] ?? PluginName::spaced($stored);
            }
        }
        asort($choices, SORT_NATURAL | SORT_FLAG_CASE);

        return $choices;
    }
}
