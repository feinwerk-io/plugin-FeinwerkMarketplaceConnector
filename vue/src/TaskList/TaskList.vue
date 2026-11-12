<template>
  <!-- The "Aufgaben" tab: Core/ActionItems, each kind with its text and its button. -->
  <div class="fwmc-tasks">
    <BatchUpdate
      v-if="batchCandidates.length > 1 && batchNonce"
      :candidates="batchCandidates"
      :nonce="batchNonce"
      :update-url="batchUpdateUrl"
      :finish-url="batchFinishUrl"
    />
    <p v-if="!items.length" class="fwmc-empty">{{ tr('TasksEmpty') }}</p>
    <ul v-else class="fwmc-task-list">
      <li
        v-for="item in items"
        :key="item.kind + ':' + (item.slug || '')"
        class="fwmc-task"
        :class="'fwmc-task--' + item.severity"
      >
        <div class="fwmc-task-text">
          <span>{{ text(item) }}</span>
          <code v-if="item.kind === 'db_update'" class="fwmc-task-code">{{ consoleCommand }}</code>
        </div>
        <div class="fwmc-task-action">
          <form v-if="item.kind === 'connection_auth'" method="post" :action="connectUrl">
            <input type="hidden" name="nonce" :value="nonce" />
            <button type="submit" class="btn">{{ tr('ConnectAgain') }}</button>
          </form>
          <a
            v-else-if="item.kind === 'connection_offline' && safe(offlineDocsUrl)"
            :href="offlineDocsUrl"
            target="_blank"
            rel="noreferrer noopener"
          >{{ tr('OfflineLink') }} ↗</a>
          <a v-else-if="item.kind === 'db_update'" class="btn" href="index.php?module=CoreUpdater">
            {{ tr('Task_db_update_page') }}
          </a>
          <a v-else-if="item.kind === 'switch_pending'" class="btn" :href="switchNowUrl">
            {{ tr('SwapNow') }}
          </a>
          <InstallForm
            v-else-if="['update', 'self_update', 'db_held'].includes(item.kind) && row(item)"
            :action-url="updateUrl"
            :nonce="nonce"
            :slug="item.slug"
            :version="row(item).latestVersion"
            :label="tr('UpdateTo', row(item).latestVersion)"
            :plugin-name="row(item).displayName"
            :is-update="true"
            :db-change="row(item).dbChange || null"
            :held="item.kind === 'db_held'"
          />
          <template v-else-if="item.kind === 'license_missing' && row(item)">
            <LicenseActions
              v-if="licensingAvailable && row(item).licenseClaimable"
              kind="claim"
              :url="claimUrl"
              :nonce="nonce"
              :slug="item.slug"
            />
            <LicenseActions
              v-else-if="licensingAvailable"
              kind="buy"
              :url="buyUrl"
              :nonce="nonce"
              :slug="item.slug"
            />
            <a
              v-else-if="row(item).url && safe(row(item).url)"
              :href="row(item).url"
              target="_blank"
              rel="noreferrer noopener"
            >
              {{ tr('LearnMore') }} ↗
            </a>
          </template>
          <a
            v-else-if="item.kind === 'license_expiring' && safe(accountUrl)"
            :href="accountUrl"
            target="_blank"
            rel="noreferrer noopener"
          >{{ tr('Task_renew') }} ↗</a>
          <button
            v-else-if="item.kind === 'license_claimable'"
            type="button"
            class="btn btn-flat"
            @click="$emit('open', item.slug)"
          >{{ tr('Task_view') }}</button>
        </div>
      </li>
    </ul>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import { translate, Matomo } from 'CoreHome';
import { ActionItem, PluginRow } from '../types';
import InstallForm from '../InstallForm/InstallForm.vue';
import LicenseActions from '../LicenseActions/LicenseActions.vue';
import BatchUpdate from '../BatchUpdate/BatchUpdate.vue';
import canBatch from '../batch';

const PREFIX = 'FeinwerkMarketplaceConnector_';

export default defineComponent({
  components: { InstallForm, LicenseActions, BatchUpdate },
  emits: ['open'],
  props: {
    items: { type: Array as PropType<ActionItem[]>, required: true },
    rows: { type: Array as PropType<PluginRow[]>, required: true },
    nonce: { type: String, required: true },
    updateUrl: { type: String, required: true },
    claimUrl: { type: String, default: '' },
    buyUrl: { type: String, default: '' },
    connectUrl: { type: String, default: '' },
    switchNowUrl: { type: String, default: '' },
    accountUrl: { type: String, default: '' },
    offlineDocsUrl: { type: String, default: '' },
    consoleCommand: { type: String, default: '' },
    licensingAvailable: { type: Boolean, default: false },
    selfSlug: { type: String, default: 'FeinwerkMarketplaceConnector' },
    batchNonce: { type: String, default: '' },
    batchUpdateUrl: { type: String, default: '' },
    batchFinishUrl: { type: String, default: '' },
  },
  computed: {
    // The updates of the list (plain and held for their database change), as rows.
    batchCandidates(): PluginRow[] {
      const slugs = this.items.filter((i) => i.kind === 'update' || i.kind === 'db_held').map((i) => i.slug);
      return this.rows.filter((r) => slugs.includes(r.slug) && canBatch(r, this.selfSlug));
    },
  },
  methods: {
    tr(key: string, ...args: string[]): string {
      return translate(`${PREFIX}${key}`, ...args);
    },
    row(item: ActionItem): PluginRow|null {
      return this.rows.find((r) => r.slug === item.slug) || null;
    },
    name(item: ActionItem): string {
      const row = this.row(item);
      return row ? row.displayName : String(item.slug || '');
    },
    // Only an http(s) address is bound as a link.
    safe(url: string): boolean {
      return /^https?:\/\//i.test(url || '');
    },
    date(value: string): string {
      try {
        return new Date(`${value}T12:00:00`).toLocaleDateString(Matomo.language || undefined);
      } catch (e) {
        return value;
      }
    },
    text(item: ActionItem): string {
      const row = this.row(item);
      switch (item.kind) {
        case 'connection_auth':
        case 'connection_offline':
        case 'switch_pending':
          return this.tr(`Task_${item.kind}`);
        case 'db_update':
          return this.tr('Task_db_update', (item.components || []).join(', '));
        case 'self_update':
          return this.tr('SelfUpdateAvailable', row?.installedVersion || '', row?.latestVersion || '');
        case 'update':
          return this.tr('Task_update', this.name(item), row?.installedVersion || '', row?.latestVersion || '');
        case 'db_held':
          return this.tr('Task_db_held', this.name(item), row?.latestVersion || '');
        case 'license_expiring':
          return this.tr('Task_license_expiring', this.name(item), this.date(item.date || ''));
        default:
          return this.tr(`Task_${item.kind}`, this.name(item));
      }
    },
  },
});
</script>
