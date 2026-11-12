<template>
  <div class="fwmc" :data-tab="tab">
    <div class="fwmc-head">
      <div>
        <h2>{{ translate('FeinwerkMarketplaceConnector_MenuTitle') }}</h2>
      </div>
      <div class="fwmc-head-actions" v-if="!error && isConnected">
        <span class="fwmc-meta" v-if="lastUpdated">
          {{ translate('FeinwerkMarketplaceConnector_Updated', lastUpdated) }}
        </span>
        <form method="post" :action="refreshUrl" @submit="onFormSubmit($event, 'refresh')">
          <input type="hidden" name="nonce" :value="formNonce" />
          <button type="submit" class="btn" :disabled="busyForm === 'refresh'">
            {{ busyForm === 'refresh'
              ? translate('FeinwerkMarketplaceConnector_Working')
              : translate('FeinwerkMarketplaceConnector_Refresh') }}
          </button>
        </form>
      </div>
    </div>

    <template v-if="!isConnected">
    <div class="fwmc-connect">
      <h3>{{ translate('FeinwerkMarketplaceConnector_ConnectTitle') }}</h3>
      <p>{{ translate('FeinwerkMarketplaceConnector_ConnectIntro') }}</p>
      <form method="post" :action="connectUrl">
        <input type="hidden" name="nonce" :value="formNonce" />
        <button type="submit" class="btn">
          {{ translate('FeinwerkMarketplaceConnector_ConnectButton') }}
        </button>
      </form>
      <p class="fwmc-meta">
        {{ translate('FeinwerkMarketplaceConnector_ConnectManual') }}
        <a :href="settingsUrl">{{ translate('FeinwerkMarketplaceConnector_ConnectManualLink') }}</a>
      </p>
      <!-- Browse without an account (5.3.0): the click loads the public list. -->
      <!-- Second way, below the connect button: before the first load only. -->
      <div class="fwmc-browse-offer" v-if="browseInfo.url && !browseInfo.loaded">
        <form
          method="post"
          :action="browseInfo.url"
          @submit="onFormSubmit($event, 'browse')"
        >
          <input type="hidden" name="nonce" :value="formNonce" />
          <button
            type="submit"
            class="btn btn-outline"
            :disabled="busyForm === 'browse'"
          >
            {{ translate(busyForm === 'browse'
              ? 'FeinwerkMarketplaceConnector_Working'
              : 'FeinwerkMarketplaceConnector_BrowseButton') }}
          </button>
        </form>
        <p class="fwmc-meta">{{ translate('FeinwerkMarketplaceConnector_BrowseHint') }}</p>
      </div>
    </div>
    <div class="fwmc-public" v-if="browseInfo.loaded">
      <div class="fwmc-meta fwmc-browse-intro">
        {{ translate('FeinwerkMarketplaceConnector_BrowseIntro', browseInfo.fetchedAt || '') }}
        <form
          method="post"
          :action="browseInfo.url"
          @submit="onFormSubmit($event, 'browse')"
        >
          <input type="hidden" name="nonce" :value="formNonce" />
          <button type="submit" class="fwmc-textbtn" :disabled="busyForm === 'browse'">
            {{ translate(busyForm === 'browse'
              ? 'FeinwerkMarketplaceConnector_Working'
              : 'FeinwerkMarketplaceConnector_BrowseRefresh') }}
          </button>
        </form>
      </div>
      <BrowseGrid :rows="browseRows" @open="openStorePage" />
    </div>
    </template>
    <div class="alert alert-warning" v-else-if="error">{{ error }}</div>
    <template v-else>
      <div class="fwmc-note" v-if="stateChanged" aria-live="polite">
        {{ translate('FeinwerkMarketplaceConnector_StateChanged') }}
        <a href="" @click.prevent="reload">
          {{ translate('FeinwerkMarketplaceConnector_StateReload') }}
        </a>
      </div>
      <div class="fwmc-selfupdate" v-if="selfUpdate">
        <span>{{ translate(
          'FeinwerkMarketplaceConnector_SelfUpdateAvailable',
          selfUpdate.installedVersion,
          selfUpdate.latestVersion,
        ) }}</span>
        <form method="post" :action="updateUrl" @submit="onFormSubmit($event, 'self')">
          <input type="hidden" name="nonce" :value="formNonce" />
          <input type="hidden" name="slug" :value="selfUpdate.slug" />
          <input type="hidden" name="version" :value="selfUpdate.latestVersion" />
          <button type="submit" class="btn" :disabled="busyForm === 'self'">
            {{ busyForm === 'self'
              ? translate('FeinwerkMarketplaceConnector_Working')
              : translate('FeinwerkMarketplaceConnector_UpdateTo', selfUpdate.latestVersion) }}
          </button>
        </form>
      </div>

      <div class="fwmc-note" v-if="refreshError">
        <template v-if="refreshErrorKind === 'offline'">
          {{ translate('FeinwerkMarketplaceConnector_OfflineNote') }}
          <a
            v-if="safeOfflineDocsUrl"
            :href="safeOfflineDocsUrl"
            target="_blank"
            rel="noreferrer noopener"
          >
            {{ translate('FeinwerkMarketplaceConnector_OfflineLink') }} ↗
          </a>
        </template>
        <template v-else>
          {{ translate(refreshErrorKind === 'auth'
            ? 'FeinwerkMarketplaceConnector_AuthErrorNote'
            : 'FeinwerkMarketplaceConnector_CachedNote') }}
        </template>
        <form
          v-if="refreshErrorKind === 'auth'"
          method="post"
          :action="connectUrl"
          class="fwmc-note-action"
        >
          <input type="hidden" name="nonce" :value="formNonce" />
          <button type="submit" class="btn">
            {{ translate('FeinwerkMarketplaceConnector_ConnectAgain') }}
          </button>
        </form>
      </div>

      <!-- One tab per entry of the admin section "Feinwerk Plugins" (5.1.0): plain links. -->
      <ul class="fwmc-tabs">
        <li v-for="t in tabs" :key="t.id" :class="{ active: tab === t.id }">
          <a :href="t.url" :aria-current="tab === t.id ? 'page' : null">
            {{ translate(t.label) }}<template v-if="t.id === 'aufgaben' && actionItems.length">
              ({{ actionItems.length }})</template>
          </a>
        </li>
      </ul>

      <TaskList
        v-if="tab === 'aufgaben'"
        :items="actionItems"
        :rows="rows"
        :nonce="formNonce"
        :update-url="updateUrl"
        :claim-url="claimUrl"
        :buy-url="buyUrl"
        :connect-url="connectUrl"
        :switch-now-url="switchNowUrl"
        :account-url="accountUrl"
        :offline-docs-url="safeOfflineDocsUrl"
        :console-command="consoleCommand"
        :licensing-available="licensingOn"
        :self-slug="selfSlug"
        :batch-nonce="batchNonce"
        :batch-update-url="batchUpdateUrl"
        :batch-finish-url="batchFinishUrl"
        @open="openDetail"
      />
      <PromoBanner v-if="tab === 'entdecken' && promo" :promo="promo" inline @open="openDetail" />
      <BrowseGrid v-if="tab === 'entdecken'" :rows="browseRows" @open="openDetail" />
      <LicenseTable
        v-if="tab === 'lizenzen'"
        :rows="browseRows"
        :nonce="formNonce"
        :claim-url="claimUrl"
        :buy-url="buyUrl"
        :account-url="accountUrl"
        :licensing-available="licensingOn"
        @open="openDetail"
      />
      <ManageTable
        v-if="tab === 'verwaltung'"
        :rows="rows"
        :nonce="formNonce"
        :install-url="installUrl"
        :update-url="updateUrl"
        :activate-url="activateUrl"
        :db-held="dbHeld"
        :claim-url="claimUrl"
        :buy-url="buyUrl"
        :licensing-available="licensingOn"
        :self-slug="selfSlug"
        :batch-nonce="batchNonce"
        :batch-update-url="batchUpdateUrl"
        :batch-finish-url="batchFinishUrl"
      />
      <FeedbackBar
        :key="feedbackTopic"
        :topic="feedbackTopic"
        :question="translate(`FeinwerkMarketplaceConnector_FeedbackQuestion_${feedbackTopic}`)"
        :nonce="formNonce"
        :feedback="feedback"
      />
    </template>

    <DetailModal
      :slug="detailSlug"
      :row="detailRow"
      :nonce="formNonce"
      :install-url="installUrl"
      :update-url="updateUrl"
      :activate-url="activateUrl"
      :claim-url="claimUrl"
      :buy-url="buyUrl"
      :licensing-available="licensingOn"
      @close="closeDetail"
    />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { translate } from 'CoreHome';
import {
  addActionContext, claimSubmit, submitted, TAB_MANAGE,
} from '../actionContext';
import ManageTable from '../ManageTable/ManageTable.vue';
import BrowseGrid from '../BrowseGrid/BrowseGrid.vue';
import TaskList from '../TaskList/TaskList.vue';
import LicenseTable from '../LicenseTable/LicenseTable.vue';
import PromoBanner from '../PromoBanner/PromoBanner.vue';
import DetailModal from '../DetailModal/DetailModal.vue';
import FeedbackBar from '../FeedbackBar/FeedbackBar.vue';

export default defineComponent({
  components: {
    ManageTable, BrowseGrid, DetailModal, FeedbackBar, TaskList, LicenseTable, PromoBanner,
  },
  props: {
    rows: { type: Array, required: true },
    formNonce: { type: String, required: true },
    lastUpdated: String,
    refreshError: String,
    refreshErrorKind: String,
    // The connector's own plugin name: it is listed in the marketplace (so it can update itself)
    // but shown as a banner, not as a card.
    selfSlug: { type: String, default: 'FeinwerkMarketplaceConnector' },
    // Auto-apply updates held back because they change the database (slugs).
    dbHeld: { type: Array, default: () => [] },
    // The feedback question under the tabs (Controller::index): switch, support URL, version.
    feedback: { type: Object, default: () => ({ enabled: false, supportUrl: '', version: '' }) },
    // The "Aufgaben" tab (Core/ActionItems) and what its buttons need.
    actionItems: { type: Array, default: () => [] },
    consoleCommand: { type: String, default: '' },
    accountUrl: { type: String, default: '' },
    switchNowUrl: { type: String, default: '' },
    // The recommendation banner of the Discover tab (Recommendation, null when switched off).
    promo: { type: Object, default: null },
    // "Update several at once" (BatchUpdate): its own nonce and the two actions.
    batchNonce: { type: String, default: '' },
    batchUpdateUrl: { type: String, default: '' },
    batchFinishUrl: { type: String, default: '' },
    // The menu entries of the section, one per tab.
    tasksUrl: { type: String, default: '' },
    discoverUrl: { type: String, default: '' },
    manageUrl: { type: String, default: '' },
    licensesUrl: { type: String, default: '' },
    error: String,
    // false until an instance token is stored; vue-entry may hand it over as a string.
    connected: { type: [Boolean, String], default: true },
    // Browse without an account: {loaded, fetchedAt, url} (Controller::screen).
    browse: { type: [Object, String], default: () => ({}) },
    // Size and update settings of this Matomo for the database-update warning (InstallForm,
    // injected).
    instance: { type: Object, default: null },
    connectUrl: { type: String, default: '' },
    settingsUrl: { type: String, default: '' },
    // Built server-side with linkTo() so they keep the admin page's idSite/period/date
    // params, exactly like the pre-Vue twig forms did — see ManageTable for slug/version.
    refreshUrl: { type: String, required: true },
    installUrl: { type: String, required: true },
    updateUrl: { type: String, required: true },
    // Matomo's own activate link for an installed plugin that is off ('' when not allowed).
    activateUrl: { type: String, default: '' },
    // Claim / buy actions and the licensing switch (the store knows the licensing endpoints),
    // the guide for servers without internet access, a plugin to open after returning from the
    // store.
    licensingAvailable: { type: [Boolean, String], default: false },
    offlineDocsUrl: { type: String, default: '' },
    openSlug: { type: String, default: '' },
    // The tab to open after an action (fwmc_tab, Controller::backToScreen).
    initialTab: { type: String, default: '' },
    claimUrl: { type: String, default: '' },
    buyUrl: { type: String, default: '' },
    // What the page shows and where to ask for the current state (ScreenState, backlog 13).
    screenState: { type: String, default: '' },
    stateUrl: { type: String, default: '' },
  },
  provide() {
    return { fwmcInstance: this.instance };
  },
  data() {
    return {
      // The server says which tab (one action per tab); index#verwaltung is an old bookmark.
      tab: (window.location.hash === `#${TAB_MANAGE}` && (this.initialTab || 'entdecken') === 'entdecken')
        ? TAB_MANAGE : (this.initialTab || 'entdecken'),
      detailSlug: null as string|null,
      // The header form just sent (refresh, self-update): its button stays off, so a double
      // click sends nothing.
      busyForm: null as string|null,
      // The state changed while something was open here: a note offers to load the page again.
      stateChanged: false,
      stateChecking: false,
      stateCheckedAt: Date.now(),
    };
  },
  mounted() {
    if (this.openSlug) {
      this.detailSlug = this.openSlug;
    }
    this.tidyUrl();
    window.addEventListener('pageshow', this.onPageShow);
    window.addEventListener('popstate', this.onPopState);
    if (this.screenState && this.stateUrl) {
      // Only when the admin comes back, never on a timer: a timer would keep a forgotten page's
      // session alive past Matomo's idle logout (review 5.1.3).
      window.addEventListener('focus', this.checkState);
      document.addEventListener('visibilitychange', this.checkState);
    }
  },
  beforeUnmount() {
    window.removeEventListener('pageshow', this.onPageShow);
    window.removeEventListener('popstate', this.onPopState);
    window.removeEventListener('focus', this.checkState);
    document.removeEventListener('visibilitychange', this.checkState);
  },
  computed: {
    // One question per tab (Core/FeedbackVote::TOPICS).
    feedbackTopic(): string {
      const topics: Record<string, string> = {
        aufgaben: 'tasks', entdecken: 'discover', verwaltung: 'manage', lizenzen: 'licenses',
      };
      return topics[this.tab] || 'discover';
    },
    tabs(): Array<{ id: string, label: string, url: string }> {
      const label = (key: string) => `FeinwerkMarketplaceConnector_${key}`;
      return [
        { id: 'aufgaben', label: label('TabTasks'), url: this.tasksUrl },
        { id: 'entdecken', label: label('TabDiscover'), url: this.discoverUrl },
        { id: TAB_MANAGE, label: label('TabManage'), url: this.manageUrl },
        { id: 'lizenzen', label: label('TabLicenses'), url: this.licensesUrl },
      ];
    },
    licensingOn(): boolean {
      return this.licensingAvailable === true || this.licensingAvailable === 'true';
    },
    // Only an http(s) address is bound as a link.
    safeOfflineDocsUrl(): string {
      return /^https?:\/\//i.test(this.offlineDocsUrl) ? this.offlineDocsUrl : '';
    },
    browseInfo(): { loaded: boolean, fetchedAt: string|null, url: string } {
      const b = (typeof this.browse === 'object' && this.browse) ? this.browse as any : {};
      return { loaded: !!b.loaded, fetchedAt: b.fetchedAt || null, url: String(b.url || '') };
    },
    isConnected(): boolean {
      return this.connected === true || this.connected === 'true' || this.connected === '1';
    },
    browseRows(): any[] {
      return (this.rows as any[]).filter((r) => r.slug !== this.selfSlug);
    },
    selfUpdate(): any {
      const self = (this.rows as any[]).find((r) => r.slug === this.selfSlug);
      return self && self.status === 'update_available' && !self.devCheckout ? self : null;
    },
    detailRow(): any {
      return (this.rows as any[]).find((r: any) => r.slug === this.detailSlug) || null;
    },
  },
  methods: {
    translate,
    // The plugin window gets its own history entry (same address), so the browser's Back button
    // closes it instead of leaving the screen.
    openDetail(slug: string) {
      this.detailSlug = slug;
      try {
        window.history.pushState({ ...(window.history.state || {}), fwmcOpen: slug }, '');
      } catch (e) {
        // no history API: the window still opens
      }
    },
    // Without a connection the plugin window has no data: the card opens the store page.
    openStorePage(slug: string) {
      const row = (this.rows as any[]).find((r: any) => r.slug === slug);
      if (row && typeof row.url === 'string' && /^https?:\/\//.test(row.url)) {
        window.open(row.url, '_blank', 'noopener');
      }
    },
    closeDetail() {
      this.detailSlug = null;
      const { state } = window.history;
      if (state && state.fwmcOpen) {
        window.history.back();
      }
    },
    onPopState(event: PopStateEvent) {
      if (this.detailSlug && !(event.state && event.state.fwmcOpen)) {
        this.detailSlug = null;
      }
    },
    onFormSubmit(event: Event, form: string) {
      if (this.busyForm || !claimSubmit()) {
        event.preventDefault();
        return;
      }
      addActionContext(event.target as HTMLFormElement);
      this.busyForm = form;
    },
    // Back to this page through the browser's back cache: the nonce of a sent form is spent and the
    // rows may be outdated, so load the screen again instead of enabling the buttons
    // (review 1.2.1).
    onPageShow(event: PageTransitionEvent) {
      if (event.persisted) {
        window.location.reload();
      }
    },
    // Back on the page (window focus, tab shown again): has what it shows changed,
    // e.g. a database update that ran on the console (backlog 13)? Then load it again, unless
    // something is open here; that gets a note instead.
    async checkState() {
      if (document.visibilityState === 'hidden' || this.stateChecking || this.stateChanged
        || Date.now() - this.stateCheckedAt < 10000) {
        return;
      }
      this.stateChecking = true;
      this.stateCheckedAt = Date.now();
      try {
        const response = await fetch(this.stateUrl, {
          credentials: 'same-origin', cache: 'no-store', headers: { Accept: 'application/json' },
        });
        const result = await response.json();
        const state = result && typeof result.state === 'string' ? result.state : '';
        if (state && state !== this.screenState) {
          if (this.somethingOpen() || !this.mayReload()) {
            this.stateChanged = true;
          } else {
            this.reload();
          }
        }
      } catch (e) {
        // no answer of ours (offline, logged out): the page stays as it is
      } finally {
        this.stateChecking = false;
      }
    },
    somethingOpen(): boolean {
      const search = document.querySelector('.fwmc input[type="search"]') as HTMLInputElement|null;
      return !!this.detailSlug || !!this.busyForm || submitted()
        || !!document.querySelector('.fwmc-dbdialog') || !!(search && search.value.trim());
    },
    // At most one automatic reload every two minutes: a state that differs right after loading
    // must not reload the page over and over.
    mayReload(): boolean {
      try {
        const last = Number(window.sessionStorage.getItem('fwmc.stateReloadAt') || 0);
        if (Date.now() - last < 120000) {
          return false;
        }
        window.sessionStorage.setItem('fwmc.stateReloadAt', String(Date.now()));
      } catch (e) {
        // blocked storage: no limit can be kept, so the note offers it instead
        return false;
      }
      return true;
    },
    reload() {
      window.location.reload();
    },
    // After an action the address carries fwmc_open (and old links fwmc_tab): drop them, so a
    // reload does not reopen the window. The tab is the page's own action since 5.1.0.
    tidyUrl() {
      const url = new URL(window.location.href);
      if (!url.searchParams.has('fwmc_tab') && !url.searchParams.has('fwmc_open')) {
        return;
      }
      url.searchParams.delete('fwmc_tab');
      url.searchParams.delete('fwmc_open');
      window.history.replaceState(window.history.state, '', url.toString());
    },
  },
});
</script>
