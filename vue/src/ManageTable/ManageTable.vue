<template>
  <div class="fwmc-manage">
    <BatchUpdate
      v-if="batchCandidates.length && batchNonce"
      :candidates="batchCandidates"
      :selected="selected"
      :nonce="batchNonce"
      :update-url="batchUpdateUrl"
      :finish-url="batchFinishUrl"
    />
    <!-- Matomo 6 pads table cells wider: a wide table scrolls inside its card. -->
    <div class="fwmc-tablewrap">
      <table class="fwmc-table entityTable">
        <thead>
          <tr>
            <th class="fwmc-select">
              <label v-if="batchCandidates.length > 1">
                <input
                  type="checkbox"
                  :checked="allSelected"
                  :aria-label="translate('FeinwerkMarketplaceConnector_BatchSelectAll')"
                  @change="toggleAll"
                />
                <span></span>
              </label>
            </th>
            <th>{{ translate('FeinwerkMarketplaceConnector_ColumnPlugin') }}</th>
            <th>{{ translate('FeinwerkMarketplaceConnector_ColumnTier') }}</th>
            <th>{{ translate('FeinwerkMarketplaceConnector_ColumnLicense') }}</th>
            <th>{{ translate('FeinwerkMarketplaceConnector_ColumnInstalled') }}</th>
            <th>{{ translate('FeinwerkMarketplaceConnector_ColumnLatest') }}</th>
            <th>{{ translate('FeinwerkMarketplaceConnector_ColumnStatus') }}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="row in rows" :key="row.slug">
            <td class="fwmc-select">
              <label v-if="canBatch(row)">
                <input
                  v-model="selected"
                  type="checkbox"
                  :value="row.slug"
                  :aria-label="translate(
                    'FeinwerkMarketplaceConnector_BatchSelect',
                    row.displayName,
                  )"
                />
                <span></span>
              </label>
            </td>
            <td>
              <div class="fwmc-plugin-cell">
                <span class="fwmc-avatar" aria-hidden="true">{{ monogram(row.displayName) }}</span>
                <div>
                  <div class="fwmc-name">{{ row.displayName }}</div>
                  <div class="fwmc-desc" v-if="row.description">{{ row.description }}</div>
                  <a
                    v-if="row.url"
                    :href="row.url"
                    target="_blank"
                    rel="noreferrer noopener"
                    class="fwmc-learn"
                  >
                    {{ translate('FeinwerkMarketplaceConnector_LearnMore') }} ↗
                  </a>
                </div>
              </div>
            </td>
            <td>
              <span class="fwmc-badge" :class="'fwmc-badge--' + row.tier">{{ row.tier }}</span>
            </td>
            <td>
              <span v-if="row.license" class="fwmc-badge fwmc-badge--license-ok">
                {{ translate('FeinwerkMarketplaceConnector_LicenseActive') }}
                <template v-if="row.license.valid_until">
                  · {{ translate(
                    'FeinwerkMarketplaceConnector_LicenseUntil',
                    row.license.valid_until,
                  ) }}
                </template>
              </span>
              <span v-else-if="row.needsLicense" class="fwmc-badge fwmc-badge--license-missing">
                {{ translate('FeinwerkMarketplaceConnector_NoLicense') }}
              </span>
              <span v-else>-</span>
            </td>
            <td>{{ row.installedVersion || '-' }}</td>
            <td>{{ row.latestVersion || '-' }}</td>
            <td>
              <!-- The status says it, not only the action cell (rc1 RC-F2). -->
              <span
                v-if="row.switching"
                class="fwmc-badge fwmc-status--not_installed"
                :title="translate('FeinwerkMarketplaceConnector_SwitchingHint')"
              >{{ translate('FeinwerkMarketplaceConnector_Switching') }}</span>
              <span v-else class="fwmc-badge" :class="'fwmc-status--' + row.status">
                {{ statusLabel(row.status) }}
              </span>
              <span
                v-if="row.inactive"
                class="fwmc-badge fwmc-status--inactive"
                :title="translate('FeinwerkMarketplaceConnector_StatusInactiveHint')"
              >{{ translate('FeinwerkMarketplaceConnector_StatusInactive') }}</span>
            </td>
            <td>
              <InstallForm
                v-if="actionFor(row)"
                :action-url="actionUrl(row)"
                :nonce="nonce"
                :slug="row.slug"
                :version="row.latestVersion"
                :label="actionFor(row)"
                :plugin-name="row.displayName"
                :is-update="row.status === 'update_available'"
                :db-change="row.dbChange || null"
                :held="dbHeld.includes(row.slug) && row.status === 'update_available'"
              />
              <LicenseActions
                v-else-if="licensingAvailable && row.licenseClaimable"
                kind="claim"
                :url="claimUrl"
                :nonce="nonce"
                :slug="row.slug"
              />
              <LicenseActions
                v-else-if="canBuy(row)"
                kind="buy"
                :url="buyUrl"
                :nonce="nonce"
                :slug="row.slug"
              />
              <a
                v-else-if="row.inactive && activateUrl"
                class="btn"
                :href="activateLink(activateUrl, row.slug)"
                :title="translate('FeinwerkMarketplaceConnector_StatusInactiveHint')"
              >{{ translate('FeinwerkMarketplaceConnector_Activate') }}</a>
              <span
                v-else-if="row.devCheckout && row.status === 'update_available'"
                class="fwmc-badge fwmc-status--not_installed"
                :title="translate('FeinwerkMarketplaceConnector_DevCheckoutHint')"
              >
                {{ translate('FeinwerkMarketplaceConnector_DevCheckout') }}
              </span>
              <span v-else-if="!row.latestVersion" class="fwmc-meta">
                {{ translate('FeinwerkMarketplaceConnector_NoVersionYet') }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { translate } from 'CoreHome';
import InstallForm from '../InstallForm/InstallForm.vue';
import LicenseActions from '../LicenseActions/LicenseActions.vue';
import BatchUpdate from '../BatchUpdate/BatchUpdate.vue';
import canBatch from '../batch';
import activateLink from '../activate';

interface Row {
  slug: string; displayName: string; tier: string; status: string;
  installedVersion: string|null; latestVersion: string|null;
  needsLicense: boolean; license: { status: string, valid_until: string|null }|null;
  description: string|null; url: string|null; devCheckout?: boolean;
  dbChange?: { migrations: string[]; columns: string[]; note: string|null }|null;
  switching?: boolean;
  licenseClaimable?: boolean; inactive?: boolean;
}

export default defineComponent({
  components: { InstallForm, LicenseActions, BatchUpdate },
  props: {
    rows: { type: Array as () => Row[], required: true },
    nonce: { type: String, required: true },
    // Base URLs for the install/update actions, built server-side with linkTo()
    // so they keep whatever idSite/period/date params the admin page carries.
    // slug/version travel as hidden POST fields, mirroring the pre-Vue twig forms.
    installUrl: { type: String, required: true },
    updateUrl: { type: String, required: true },
    activateUrl: { type: String, default: '' },
    dbHeld: { type: Array as () => string[], default: () => [] },
    claimUrl: { type: String, default: '' },
    buyUrl: { type: String, default: '' },
    licensingAvailable: { type: Boolean, default: false },
    // "Update several at once" (BatchUpdate).
    selfSlug: { type: String, default: 'FeinwerkMarketplaceConnector' },
    batchNonce: { type: String, default: '' },
    batchUpdateUrl: { type: String, default: '' },
    batchFinishUrl: { type: String, default: '' },
  },
  data() {
    return { selected: [] as string[] };
  },
  computed: {
    batchCandidates(): Row[] {
      return this.rows.filter((r) => canBatch(r, this.selfSlug));
    },
    allSelected(): boolean {
      const all = this.batchCandidates.length;
      return all > 0 && this.selected.length === all;
    },
  },
  methods: {
    translate,
    activateLink,
    canBatch(row: Row): boolean {
      return canBatch(row, this.selfSlug);
    },
    toggleAll() {
      this.selected = this.allSelected ? [] : this.batchCandidates.map((r) => r.slug);
    },
    monogram(name: string): string {
      return (name || '?').trim().charAt(0).toUpperCase();
    },
    statusLabel(status: string): string {
      const map: Record<string, string> = {
        installed: 'FeinwerkMarketplaceConnector_StatusInstalled',
        update_available: 'FeinwerkMarketplaceConnector_StatusUpdate',
        not_installed: 'FeinwerkMarketplaceConnector_StatusNotInstalled',
      };
      return translate(map[status] || 'FeinwerkMarketplaceConnector_StatusNotInstalled');
    },
    // A paid plugin without a license that the account cannot claim: buy it from the store.
    canBuy(row: Row): boolean {
      return this.licensingAvailable && row.needsLicense && !row.license && !row.licenseClaimable
        && !!row.latestVersion && !row.devCheckout;
    },
    actionFor(row: Row): string|null {
      if (!row.latestVersion) return null;
      if (row.devCheckout) return null;
      if (row.switching) return null;
      if (row.needsLicense && !row.license) return null;
      if (row.status === 'not_installed') return translate('FeinwerkMarketplaceConnector_Install');
      if (row.status === 'update_available') {
        return translate('FeinwerkMarketplaceConnector_UpdateTo', row.latestVersion);
      }
      return null;
    },
    actionUrl(row: Row): string {
      return row.status === 'update_available' ? this.updateUrl : this.installUrl;
    },
  },
});
</script>
