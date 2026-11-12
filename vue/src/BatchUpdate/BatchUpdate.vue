<template>
  <!-- "Update several at once" (backlog 4): one request per plugin to batchUpdate, a failed one
       does not stop the rest (Peter, 2026-10-27), then batchFinish shows one summary. -->
  <div class="fwmc-batch">
    <div v-if="!running" class="fwmc-batch-buttons">
      <button
        v-if="selected.length"
        type="button"
        class="btn"
        @click="start(selectedRows)"
      >{{ label('BatchUpdateSelected', selected.length) }}</button>
      <button
        v-if="candidates.length > 1"
        type="button"
        :class="selected.length ? 'btn btn-flat' : 'btn'"
        @click="start(candidates)"
      >{{ label('BatchUpdateAll', candidates.length) }}</button>
    </div>

    <div v-else class="fwmc-batch-progress" aria-live="polite">
      <p class="fwmc-batch-head">{{ progressText }}</p>
      <p v-if="expired" class="fwmc-batch-message">
        {{ expired }}
        <a href="" class="btn btn-flat" @click.prevent="reload">
          {{ translate('FeinwerkMarketplaceConnector_BatchReload') }}
        </a>
      </p>
      <ul>
        <li v-for="step in steps" :key="step.slug" :class="'fwmc-batch-' + step.state">
          <span class="fwmc-batch-name">{{ step.name }} {{ step.version }}</span>
          <span class="fwmc-batch-state">{{ stateLabel(step.state) }}</span>
          <span v-if="step.state === 'fail' && step.message" class="fwmc-batch-message">
            {{ step.message }}
          </span>
        </li>
      </ul>
    </div>

    <form ref="finish" method="post" :action="finishUrl" class="fwmc-batch-finish">
      <input type="hidden" name="nonce" :value="nonce" />
      <!-- what only the browser saw: the server adds it to the summary -->
      <input type="hidden" name="held" :value="slugsIn('held')" />
      <input type="hidden" name="no_answer" :value="slugsIn('noanswer')" />
      <input type="hidden" name="not_started" :value="slugsIn('notstarted')" />
    </form>

    <Teleport to="body">
      <div
        v-if="confirming"
        class="fwmc fwmc-dbdialog"
        role="alertdialog"
        aria-modal="true"
        :aria-label="translate('FeinwerkMarketplaceConnector_BatchDbTitle')"
        @click.self="confirming = false"
      >
        <div class="fwmc-dbdialog-box">
          <div class="fwmc-dbdialog-head">
            <span aria-hidden="true">⚠</span>
            {{ translate('FeinwerkMarketplaceConnector_BatchDbTitle') }}
          </div>
          <div class="fwmc-dbdialog-body">
            <p>{{ translate('FeinwerkMarketplaceConnector_BatchDbIntro') }}</p>
            <ul>
              <li v-for="row in dbRows" :key="row.slug">
                {{ row.displayName }} {{ row.latestVersion }}
              </li>
            </ul>
            <p class="fwmc-dbdialog-duration">
              {{ translate('FeinwerkMarketplaceConnector_DbChangeDuration') }}
            </p>
            <div v-if="large" class="fwmc-dbdialog-large">
              <p><strong>{{ largeIntro }}</strong></p>
              <ol>
                <li>{{ translate('FeinwerkMarketplaceConnector_BatchDbStepClick') }}</li>
                <li>
                  {{ translate('FeinwerkMarketplaceConnector_DbChangeStepConsole') }}
                  <code>{{ consoleCmd }} core:update</code>
                </li>
              </ol>
              <label class="fwmc-dbdialog-ack">
                <input type="checkbox" v-model="acknowledged" />
                <span>{{ translate('FeinwerkMarketplaceConnector_DbChangeAckConsole') }}</span>
              </label>
            </div>
          </div>
          <div class="fwmc-dbdialog-actions">
            <button type="button" class="btn" :disabled="large && !acknowledged" @click="confirm">
              {{ translate('FeinwerkMarketplaceConnector_BatchDbConfirm') }}
            </button>
            <button type="button" class="btn btn-flat" @click="confirming = false">
              {{ translate('FeinwerkMarketplaceConnector_DbChangeCancel') }}
            </button>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import { translate } from 'CoreHome';
import { addActionContext, claimSubmit } from '../actionContext';
import { PluginRow } from '../types';

interface Step {
  slug: string;
  name: string;
  version: string;
  state: 'waiting'|'running'|'ok'|'fail'|'held'|'noanswer'|'notstarted';
  message: string;
}

function changesDatabase(row: PluginRow): boolean {
  const change = row.dbChange as { migrations?: string[], columns?: string[] }|null|undefined;
  return !!change && ((change.migrations || []).length > 0 || (change.columns || []).length > 0);
}

interface InstanceInfo {
  large: boolean; visits: number|null; visitsLabel: string|null; console: string;
}

export default defineComponent({
  props: {
    // Every row that can be updated this way (Manage and Aufgaben tabs).
    candidates: { type: Array as PropType<PluginRow[]>, required: true },
    // The slugs the admin ticked on the Manage tab.
    selected: { type: Array as PropType<string[]>, default: () => [] },
    nonce: { type: String, required: true },
    updateUrl: { type: String, required: true },
    finishUrl: { type: String, required: true },
  },
  inject: {
    instance: { from: 'fwmcInstance', default: null },
  },
  data() {
    return {
      running: false,
      confirming: false,
      acknowledged: false,
      pending: [] as PluginRow[],
      steps: [] as Step[],
      // The batch nonce expired (an hour old page, or finished in another tab): stop and ask
      // to reload.
      expired: '',
    };
  },
  computed: {
    info(): InstanceInfo|null {
      return (this as unknown as { instance: InstanceInfo|null }).instance;
    },
    selectedRows(): PluginRow[] {
      return this.candidates.filter((r) => this.selected.includes(r.slug));
    },
    dbRows(): PluginRow[] {
      return this.pending.filter(changesDatabase);
    },
    large(): boolean {
      return !!this.info?.large && this.dbRows.length > 0;
    },
    largeIntro(): string {
      const visits = this.info?.visits;
      if (!visits) {
        return translate('FeinwerkMarketplaceConnector_DbChangeLargeUnknown');
      }
      return translate('FeinwerkMarketplaceConnector_DbChangeLarge', this.info?.visitsLabel || String(visits));
    },
    consoleCmd(): string {
      return this.info?.console || './console';
    },
    progressText(): string {
      const done = this.steps.filter((s) => s.state !== 'waiting' && s.state !== 'running').length;
      if (done === this.steps.length) {
        return translate('FeinwerkMarketplaceConnector_BatchFinishing');
      }
      return translate('FeinwerkMarketplaceConnector_BatchProgress', String(done), String(this.steps.length));
    },
  },
  methods: {
    translate,
    label(key: string, count: number): string {
      return translate(`FeinwerkMarketplaceConnector_${key}`, String(count));
    },
    stateLabel(state: string): string {
      const keys: Record<string, string> = {
        waiting: 'BatchWaiting',
        running: 'BatchRunning',
        ok: 'BatchOk',
        fail: 'BatchFail',
        held: 'BatchHeldState',
        noanswer: 'BatchNoAnswerState',
        notstarted: 'BatchNotStartedState',
      };
      return translate(`FeinwerkMarketplaceConnector_${keys[state]}`);
    },
    slugsIn(state: string): string {
      return this.steps.filter((s) => s.state === state).map((s) => s.slug).join(',');
    },
    reload() {
      window.location.reload();
    },
    start(rows: PluginRow[]) {
      if (!rows.length || this.running) {
        return;
      }
      this.pending = rows;
      if (this.dbRows.length) {
        this.acknowledged = false;
        this.confirming = true;
        return;
      }
      this.run();
    },
    confirm() {
      if (this.large && !this.acknowledged) {
        return;
      }
      this.confirming = false;
      this.run();
    },
    async run() {
      // The page's other forms share a nonce: nothing else may be sent while this runs.
      if (!claimSubmit()) {
        return;
      }
      this.running = true;
      // Updates without a database change first: once one leaves a database update pending, Matomo
      // sends every further request to its update page, and the rest waits for it.
      const ordered = [...this.pending.filter((r) => !changesDatabase(r)), ...this.dbRows];
      this.steps = ordered.map((r) => ({
        slug: r.slug, name: r.displayName, version: r.latestVersion || '', state: 'waiting', message: '',
      }));
      for (let i = 0; i < this.steps.length; i += 1) {
        const step = this.steps[i];
        const restSlugs = this.steps.slice(i + 1).map((s) => s.slug).join(',');
        step.state = 'running';
        let stop = false;
        let rest: Step['state'] = 'held';
        try {
          // One request per plugin, in order: never several installs at once.
          // eslint-disable-next-line no-await-in-loop
          const response = await fetch(this.updateUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', Accept: 'application/json' },
            body: new URLSearchParams({
              nonce: this.nonce,
              slug: step.slug,
              version: step.version,
              first: i === 0 ? '1' : '0',
              rest: restSlugs,
            }),
          });
          const json = (response.headers.get('Content-Type') || '').includes('json');
          if (response.redirected && /module=CoreUpdater|action=consoleUpdate/.test(response.url)) {
            // Matomo's update page answered instead: nothing more can be installed before it ran.
            step.state = 'held';
            stop = true;
          } else if (!json) {
            // No answer of ours (Bad Gateway, an error page, the login page): the update may
            // still run on the server, so nothing more is started next to it.
            step.state = 'noanswer';
            step.message = `HTTP ${response.status}`;
            rest = 'notstarted';
            stop = true;
          } else {
            // eslint-disable-next-line no-await-in-loop
            const result = await response.json();
            if (result && result.expired) {
              step.state = 'notstarted';
              this.steps.slice(i + 1).forEach((s) => { s.state = 'notstarted'; });
              this.expired = String(result.message || '');
              return;
            }
            step.state = result && result.ok ? 'ok' : 'fail';
            step.message = result && !result.ok ? String(result.message || '') : '';
            stop = !!(result && result.stop);
          }
        } catch (e) {
          // The connection broke: as with no answer, nothing more is started.
          step.state = 'noanswer';
          step.message = String(e);
          rest = 'notstarted';
          stop = true;
        }
        if (stop) {
          this.steps.slice(i + 1).forEach((s) => { s.state = rest; });
          break;
        }
      }
      // The hidden fields read the step states: let Vue write them before the form goes.
      await this.$nextTick();
      const form = this.$refs.finish as HTMLFormElement;
      addActionContext(form);
      form.submit();
    },
  },
});
</script>
