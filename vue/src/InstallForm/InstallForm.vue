<template>
  <form method="post" :action="actionUrl" class="fwmc-install" ref="form" @submit="onSubmit">
    <input type="hidden" name="nonce" :value="nonce" />
    <input type="hidden" name="slug" :value="slug" />
    <input type="hidden" name="version" :value="version" />
    <input v-if="activateOnConsole" type="hidden" name="activate" value="0" />
    <button type="submit" class="btn" :disabled="busy" :aria-busy="busy" @click="onClick">
      {{ busy ? translate('FeinwerkMarketplaceConnector_Working') : label }}
    </button>
    <span
      v-if="dbChange || held"
      class="fwmc-badge fwmc-badge--dbwarn"
      :title="badgeHint"
    >
      ⚠ {{ translate('FeinwerkMarketplaceConnector_DbHeld') }}
    </span>

    <Teleport to="body">
      <div
        v-if="confirming"
        class="fwmc fwmc-dbdialog"
        role="alertdialog"
        aria-modal="true"
        :aria-label="title"
        @click.self="confirming = false"
      >
        <div class="fwmc-dbdialog-box">
          <div class="fwmc-dbdialog-head">
            <span aria-hidden="true">⚠</span> {{ title }}
          </div>
          <div class="fwmc-dbdialog-body">
            <p class="fwmc-dbdialog-plugin">{{ pluginName || slug }} {{ version }}</p>
            <ul>
              <li v-if="dbChange.migrations.length">{{ migrationsText }}</li>
              <li v-if="dbChange.columns.length">{{ columnsText }}</li>
              <li v-if="dbChange.note">{{ dbChange.note }}</li>
            </ul>
            <p class="fwmc-dbdialog-duration">
              {{ translate('FeinwerkMarketplaceConnector_DbChangeDuration') }}
            </p>

            <div v-if="large" class="fwmc-dbdialog-large">
              <p><strong>{{ largeIntro }}</strong></p>
              <ol v-if="activateOnConsole">
                <li>{{ translate('FeinwerkMarketplaceConnector_DbChangeStepInstallOnly') }}</li>
                <li>
                  {{ translate('FeinwerkMarketplaceConnector_DbChangeStepActivate') }}
                  <code>{{ consoleCmd }} plugin:activate {{ slug }}</code>
                </li>
              </ol>
              <ol v-else>
                <li>{{ stepClick }}</li>
                <li>
                  {{ translate('FeinwerkMarketplaceConnector_DbChangeStepConsole') }}
                  <code>{{ consoleCmd }} core:update</code>
                </li>
              </ol>
              <label class="fwmc-dbdialog-ack">
                <input type="checkbox" v-model="acknowledged" />
                <span>{{ ackLabel }}</span>
              </label>
            </div>
            <div v-else-if="addsColumns" class="fwmc-dbdialog-option">
              <label class="fwmc-dbdialog-ack">
                <input type="checkbox" v-model="consoleActivate" />
                <span>
                  {{ translate('FeinwerkMarketplaceConnector_DbChangeOptConsoleActivate') }}
                </span>
              </label>
              <code v-if="consoleActivate">{{ consoleCmd }} plugin:activate {{ slug }}</code>
            </div>
            <p v-else class="fwmc-meta">
              {{ translate('FeinwerkMarketplaceConnector_DbChangeCliHint') }}
              <code>{{ consoleCmd }} core:update</code>
            </p>
          </div>
          <div class="fwmc-dbdialog-actions">
            <button type="button" class="btn" :disabled="large && !acknowledged" @click="submit">
              {{ confirmLabel }}
            </button>
            <button type="button" class="btn btn-flat" @click="confirming = false">
              {{ translate('FeinwerkMarketplaceConnector_DbChangeCancel') }}
            </button>
          </div>
        </div>
      </div>
    </Teleport>
  </form>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { translate } from 'CoreHome';
import { addActionContext, claimSubmit } from '../actionContext';

interface DbChange { migrations: string[]; columns: string[]; note: string|null }
interface InstanceInfo {
  large: boolean; visits: number|null; visitsLabel: string|null; console: string;
}

// Install/Update button. When the install changes the database (row.dbChange from the
// marketplace's db_changes, see Core/DbChangePlan), the click opens a dialog: what changes, that
// it can take long, and on a large instance (InstanceSizeProbe) the console way, confirmed with a
// checkbox. Only the dialog's button installs.
export default defineComponent({
  props: {
    actionUrl: { type: String, required: true },
    nonce: { type: String, required: true },
    slug: { type: String, required: true },
    version: { type: String, required: true },
    label: { type: String, required: true },
    pluginName: { type: String, default: '' },
    isUpdate: { type: Boolean, default: false },
    dbChange: { type: Object as () => DbChange|null, default: null },
    // Held back by the automatic updates for database changes (older marketplace answers
    // without db_changes).
    held: { type: Boolean, default: false },
  },
  inject: {
    instance: { from: 'fwmcInstance', default: null },
    // Inside a plugin window: the server reopens it after the action.
    inWindow: { from: 'fwmcInWindow', default: false },
  },
  data() {
    // busy: sent once, the button stays off so a double click cannot spend the nonce twice.
    return {
      confirming: false, acknowledged: false, consoleActivate: false, busy: false,
    };
  },
  computed: {
    info(): InstanceInfo|null {
      return (this as any).instance as InstanceInfo|null;
    },
    // The console way only matters when Matomo really runs something: update files or new
    // log columns (a plan with only the author's note needs no core:update).
    large(): boolean {
      const change = this.dbChange;
      return !!this.info?.large && !!change
        && (change.migrations.length > 0 || change.columns.length > 0);
    },
    // A fresh install whose dimensions add log table columns: on a large instance the
    // ALTER TABLE runs on the console.
    addsColumns(): boolean {
      return !this.isUpdate && !!this.dbChange && this.dbChange.columns.length > 0;
    },
    activateOnConsole(): boolean {
      return this.addsColumns && (this.large || this.consoleActivate);
    },
    consoleCmd(): string {
      return this.info?.console || './console';
    },
    badgeHint(): string {
      return translate(this.dbChange
        ? 'FeinwerkMarketplaceConnector_DbChangeBadgeHint'
        : 'FeinwerkMarketplaceConnector_DbHeldHint');
    },
    migrationsText(): string {
      const versions = (this.dbChange?.migrations || []).join(', ');
      return translate('FeinwerkMarketplaceConnector_DbChangeMigrations', versions);
    },
    columnsText(): string {
      const columns = (this.dbChange?.columns || []).join(', ');
      return translate('FeinwerkMarketplaceConnector_DbChangeColumns', columns);
    },
    stepClick(): string {
      return translate(this.isUpdate
        ? 'FeinwerkMarketplaceConnector_DbChangeStepClickUpdate'
        : 'FeinwerkMarketplaceConnector_DbChangeStepClickInstall');
    },
    ackLabel(): string {
      return translate(this.activateOnConsole
        ? 'FeinwerkMarketplaceConnector_DbChangeAckActivate'
        : 'FeinwerkMarketplaceConnector_DbChangeAckConsole');
    },
    confirmLabel(): string {
      return translate(this.isUpdate
        ? 'FeinwerkMarketplaceConnector_DbChangeConfirmUpdate'
        : 'FeinwerkMarketplaceConnector_DbChangeConfirmInstall');
    },
    // Update files alone may only re-archive reports (no table change): then "needs a database
    // update", with new log columns "changes the database".
    title(): string {
      if (this.isUpdate && this.dbChange && !this.dbChange.columns.length
        && this.dbChange.migrations.length) {
        return translate('FeinwerkMarketplaceConnector_DbChangeTitleMigration');
      }
      return translate(this.isUpdate
        ? 'FeinwerkMarketplaceConnector_DbChangeTitleUpdate'
        : 'FeinwerkMarketplaceConnector_DbChangeTitleInstall');
    },
    largeIntro(): string {
      const visits = this.info?.visits;
      if (!visits) {
        return translate('FeinwerkMarketplaceConnector_DbChangeLargeUnknown');
      }
      // Formatted by Matomo in the admin's language (Controller::instanceInfo).
      const count = this.info?.visitsLabel || String(visits);
      return translate('FeinwerkMarketplaceConnector_DbChangeLarge', count);
    },
  },
  methods: {
    translate,
    onClick(event: Event) {
      if (this.busy) {
        event.preventDefault();
        return;
      }
      if (this.dbChange) {
        event.preventDefault();
        this.acknowledged = false;
        this.consoleActivate = false;
        this.confirming = true;
      }
    },
    onSubmit(event: Event) {
      if (this.busy || !claimSubmit()) {
        event.preventDefault();
        return;
      }
      this.prepare();
    },
    prepare() {
      const { inWindow } = this as unknown as { inWindow: boolean };
      addActionContext(this.$refs.form as HTMLFormElement, inWindow ? this.slug : '');
      this.busy = true;
    },
    submit() {
      if (this.busy || (this.large && !this.acknowledged) || !claimSubmit()) {
        return;
      }
      this.confirming = false;
      this.prepare();
      // form.submit() fires no submit event, so onSubmit() does not run twice.
      (this.$refs.form as HTMLFormElement).submit();
    },
  },
  beforeUnmount() {
    const { onKey } = this as any;
    if (onKey) {
      document.removeEventListener('keydown', onKey, true);
    }
  },
  watch: {
    confirming(open: boolean) {
      const onKey = (this as any).onKey || ((e: KeyboardEvent) => {
        if (e.key === 'Escape') {
          e.stopPropagation();
          this.confirming = false;
        }
      });
      (this as any).onKey = onKey;
      if (open) {
        document.addEventListener('keydown', onKey, true);
      } else {
        document.removeEventListener('keydown', onKey, true);
      }
    },
  },
});
</script>
