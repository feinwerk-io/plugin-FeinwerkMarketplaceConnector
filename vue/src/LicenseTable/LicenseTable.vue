<template>
  <!-- The "Lizenzen" tab: the paid plugins and their license for this Matomo instance. -->
  <div class="fwmc-licenses">
    <p class="fwmc-licenses-intro">
      {{ translate('FeinwerkMarketplaceConnector_LicensesIntro') }}
      <a v-if="safeAccountUrl" :href="safeAccountUrl" target="_blank" rel="noreferrer noopener">
        {{ translate('FeinwerkMarketplaceConnector_LicensesAccount') }} ↗
      </a>
    </p>
    <p v-if="!paid.length" class="fwmc-empty">
      {{ translate('FeinwerkMarketplaceConnector_LicensesEmpty') }}
    </p>
    <div v-else class="fwmc-tablewrap">
      <table class="entityTable dataTable fwmc-table">
        <thead>
          <tr>
            <th>{{ translate('FeinwerkMarketplaceConnector_ColumnPlugin') }}</th>
            <th>{{ translate('FeinwerkMarketplaceConnector_ColumnLicense') }}</th>
            <th>{{ translate('FeinwerkMarketplaceConnector_ColumnInstalled') }}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="row in paid" :key="row.slug">
            <td>
              <button type="button" class="fwmc-link-button" @click="$emit('open', row.slug)">
                {{ row.displayName }}
              </button>
            </td>
            <td>
              <span v-if="row.license" class="fwmc-badge fwmc-badge--license-ok">
                {{ translate('FeinwerkMarketplaceConnector_LicenseActive') }}
                <template v-if="row.license.valid_until">
                  · {{ translate(
                    'FeinwerkMarketplaceConnector_LicenseUntil',
                    date(row.license.valid_until),
                  ) }}
                </template>
              </span>
              <span v-else-if="row.needsLicense" class="fwmc-badge fwmc-badge--license-missing">
                {{ translate('FeinwerkMarketplaceConnector_NoLicense') }}
              </span>
              <span v-else>-</span>
            </td>
            <td>
              {{ row.installedVersion || translate('FeinwerkMarketplaceConnector_NotInstalled') }}
            </td>
            <td>
              <LicenseActions
                v-if="licensingAvailable && row.needsLicense && row.licenseClaimable"
                kind="claim"
                :url="claimUrl"
                :nonce="nonce"
                :slug="row.slug"
              />
              <LicenseActions
                v-else-if="licensingAvailable && row.needsLicense"
                kind="buy"
                :url="buyUrl"
                :nonce="nonce"
                :slug="row.slug"
              />
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import { translate, Matomo } from 'CoreHome';
import { PluginRow } from '../types';
import LicenseActions from '../LicenseActions/LicenseActions.vue';

export default defineComponent({
  components: { LicenseActions },
  emits: ['open'],
  props: {
    rows: { type: Array as PropType<PluginRow[]>, required: true },
    nonce: { type: String, required: true },
    claimUrl: { type: String, default: '' },
    buyUrl: { type: String, default: '' },
    accountUrl: { type: String, default: '' },
    licensingAvailable: { type: Boolean, default: false },
  },
  computed: {
    paid(): PluginRow[] {
      return this.rows.filter((r) => r.tier === 'paid' || r.needsLicense || r.license);
    },
    safeAccountUrl(): string {
      return /^https?:\/\//i.test(this.accountUrl) ? this.accountUrl : '';
    },
  },
  methods: {
    translate,
    date(value: string): string {
      const day = String(value || '').slice(0, 10);
      try {
        return new Date(`${day}T12:00:00`).toLocaleDateString(Matomo.language || undefined);
      } catch (e) {
        return value;
      }
    },
  },
});
</script>
