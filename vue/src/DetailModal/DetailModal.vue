<template>
  <MatomoDialog v-model="opened" @close="$emit('close')">
    <div class="fwmc fwmc-modal uiControl">
      <!-- What the last action here answered (e.g. the store refused a purchase), at the top of the
           window instead of behind it (Peter, 2026-11-26). -->
      <div v-if="notice && notice.message" class="alert fwmc-window-notice" :class="noticeClass">
        <p>{{ notice.message }}</p>
        <!-- Buying and the free trial both need the customer account (store, 2026-11-26). -->
        <p v-if="notice.connect">
          {{ translate('FeinwerkMarketplaceConnector_NoAccountNext') }}
        </p>
        <form
          v-if="notice.connect && connectUrl"
          method="post"
          :action="connectUrl"
          @submit="onConnectSubmit"
        >
          <input type="hidden" name="nonce" :value="nonce" />
          <button type="submit" class="btn">
            {{ translate('FeinwerkMarketplaceConnector_ConnectButton') }}
          </button>
        </form>
      </div>
      <div v-if="loading" class="loadingPiwik">
        <img src="plugins/Morpheus/images/loading-blue.gif" alt="" />
        {{ translate('General_Loading') }}
      </div>

      <div v-else-if="errorMessage !== null" class="alert alert-warning">
        {{ translate('FeinwerkMarketplaceConnector_DetailError') }} {{ errorMessage }}
        <!-- A way out (UI review 2026-11-23, F10). -->
        <button v-if="slug" type="button" class="btn btn-outline fwmc-retry" @click="open(slug)">
          {{ translate('FeinwerkMarketplaceConnector_TryAgain') }}
        </button>
      </div>

      <template v-else-if="detail">
        <div class="fwmc-note" v-if="stale">
          {{ translate(errorKind === 'auth'
            ? 'FeinwerkMarketplaceConnector_AuthErrorNote'
            : 'FeinwerkMarketplaceConnector_CachedNote') }}
        </div>

        <div class="fwmc-modal-head">
          <div class="fwmc-card-media fwmc-card-media--modal">
            <img
              v-if="detail.artwork_url && !artworkFailed"
              :src="proxied(detail.artwork_url)"
              :alt="detail.display_name"
              @error="artworkFailed = true"
            />
            <span v-else class="fwmc-avatar fwmc-avatar--lg">{{ monogram }}</span>
          </div>
          <div class="fwmc-modal-headtext">
            <h2 tabindex="-1" class="fwmc-modal-title">{{ detail.display_name }}</h2>
            <p class="fwmc-sub" v-if="description">{{ description }}</p>
            <p class="fwmc-meta">
              <span class="fwmc-badge" :class="'fwmc-badge--' + detail.tier">
                {{ tierLabel(detail.tier) }}
              </span>
              <span v-if="detail.category" class="fwmc-badge fwmc-badge--category">
                {{ categoryLabel(detail.category) }}
              </span>
              <template v-if="latestVersionInfo"> · v{{ latestVersionInfo.version }}
                <template v-if="latestVersionInfo.matomo_min">
                  · Matomo ≥ {{ latestVersionInfo.matomo_min }}</template>
                <template v-if="latestVersionInfo.php_min">
                  · PHP ≥ {{ latestVersionInfo.php_min }}</template>
              </template>
            </p>

            <div class="fwmc-cta fwmc-cta--top">
              <InstallForm
                v-if="cta === 'install' || cta === 'update'"
                :action-url="actionUrl"
                :nonce="nonce"
                :slug="slug"
                :version="row.latestVersion"
                :label="cta === 'install'
                  ? translate('FeinwerkMarketplaceConnector_Install')
                  : translate('FeinwerkMarketplaceConnector_UpdateTo', row.latestVersion)"
                :plugin-name="row.displayName"
                :is-update="cta === 'update'"
                :db-change="row.dbChange || null"
                :held="cta === 'update' && targetHasDbWarning"
              />
              <LicenseActions
                v-else-if="cta === 'claim'"
                kind="claim"
                :url="claimUrl"
                :nonce="nonce"
                :slug="slug"
              />
              <!-- One filled action: the free trial when there is one, buying as the quiet second
                   button (the plan cards buy directly; Peter, 2026-11-26: "2. ja"). -->
              <template v-else-if="cta === 'buyhere'">
                <LicenseActions
                  v-if="trialOffered"
                  kind="trial"
                  :url="trialUrl"
                  :nonce="nonce"
                  :slug="slug"
                />
                <LicenseActions
                  kind="buy"
                  :url="buyUrl"
                  :nonce="nonce"
                  :slug="slug"
                  :quiet="trialOffered"
                />
              </template>
              <a
                v-else-if="cta === 'buy' && safeStorefrontUrl"
                :href="safeStorefrontUrl"
                target="_blank"
                rel="noreferrer noopener"
                class="fwmc-buy-link"
              >
                {{ translate('FeinwerkMarketplaceConnector_BuyOnMarketplace') }} ↗
              </a>
              <span
                v-else-if="cta === 'dev'"
                class="fwmc-badge fwmc-status--not_installed"
                :title="translate('FeinwerkMarketplaceConnector_DevCheckoutHint')"
              >
                {{ translate('FeinwerkMarketplaceConnector_DevCheckout') }}
              </span>
              <span v-else-if="cta === 'switching'" class="fwmc-badge fwmc-status--not_installed"
                    :title="translate('FeinwerkMarketplaceConnector_SwitchingHint')">
                {{ translate('FeinwerkMarketplaceConnector_Switching') }}
              </span>
              <a
                v-else-if="cta === 'activate'"
                class="btn"
                :href="activateHref"
                :title="translate('FeinwerkMarketplaceConnector_StatusInactiveHint')"
              >{{ translate('FeinwerkMarketplaceConnector_Activate') }}</a>
              <span v-else-if="cta === 'current'" class="fwmc-badge fwmc-status--installed">
                {{ translate('FeinwerkMarketplaceConnector_StatusCurrent') }}
              </span>
              <span v-else class="fwmc-meta">
                {{ translate('FeinwerkMarketplaceConnector_NoVersionYet') }}
              </span>
              <LicenseActions
                v-if="buyDuringTrial"
                kind="buy"
                :url="buyUrl"
                :nonce="nonce"
                :slug="slug"
              />
            </div>
            <!-- Trying comes before paying (Peter, 2026-11-26: "anstatt 'Lizenz kaufen' sollte der
                 Marketplace Connector die Testphase bewerben"). -->
            <p v-if="cta === 'buyhere' && trialOffered" class="fwmc-trial-pitch">
              {{ translate('FeinwerkMarketplaceConnector_TrialPitch') }}
            </p>
            <!-- No trial here: why, when the admin can change it (store field
                 trial_unavailable_reason, 2026-11-26). -->
            <p v-else-if="cta === 'buyhere' && trialReasonText" class="fwmc-trial-pitch">
              {{ trialReasonText }}
              <a v-if="trialReasonAccount" :href="trialReasonAccount" target="_blank"
                 rel="noreferrer noopener">
                {{ translate('FeinwerkMarketplaceConnector_LicensesAccount') }} ↗
              </a>
            </p>
          </div>
        </div>

        <div class="fwmc-modal-columns">
          <div class="fwmc-modal-main">
            <!-- Trusted only because the store sanitizes both fields (html_input strip). -->
            <div v-if="longDescription" v-html="longDescription"></div>
            <template v-if="screenshots.length">
              <h3>{{ translate('FeinwerkMarketplaceConnector_Screenshots') }}</h3>
              <div class="fwmc-shots">
                <!-- Real buttons, no role attribute: Matomo's dialog turns every [role]
                     element in the window into an empty footer button (HarnessTest). -->
                <button v-for="(shot, i) in screenshots" :key="shot" type="button" class="fwmc-shot"
                        @click="lightbox = shot">
                  <img :src="proxied(shot)" @error="hideImage"
                       :alt="`${translate('FeinwerkMarketplaceConnector_Screenshots')} ${i + 1}`" />
                </button>
              </div>
            </template>
          </div>

          <div class="fwmc-modal-side">
            <template v-if="features.length">
              <h3>{{ translate('FeinwerkMarketplaceConnector_Features') }}</h3>
              <ul class="fwmc-features">
                <li v-for="f in features" :key="f">{{ f }}</li>
              </ul>
            </template>

            <template v-if="detail.plans && detail.plans.length">
              <h3>{{ translate('FeinwerkMarketplaceConnector_Plans') }}</h3>
              <template v-for="plan in detail.plans" :key="plan.name_en">
                <!-- Not bought yet (or in a trial): straight to this plan's checkout, bound to this
                     Matomo, back here afterwards (Peter, 2026-11-24). -->
                <form
                  v-if="planCheckout(plan)"
                  method="post"
                  :action="buyUrl"
                  class="fwmc-plan-form"
                  @submit="onPlanSubmit($event, plan)"
                >
                  <input type="hidden" name="nonce" :value="nonce" />
                  <input type="hidden" name="slug" :value="slug" />
                  <input type="hidden" name="plan" :value="plan.id" />
                  <button
                    type="submit"
                    class="fwmc-plan-link fwmc-plan-button"
                    :disabled="planBusy !== null"
                    :aria-busy="planBusy === plan.id"
                  >
                    <PlanCard :plan="plan" buy :busy="planBusy === plan.id" />
                  </button>
                </form>
                <!-- A bought licence: the plans are information; a change of plan happens in the
                     customer account. -->
                <PlanCard v-else-if="ownsLicense" :plan="plan" />
                <a v-else-if="safeStorefrontUrl" :href="safeStorefrontUrl" target="_blank"
                   rel="noreferrer noopener" class="fwmc-plan-link">
                  <PlanCard :plan="plan" />
                </a>
                <PlanCard v-else :plan="plan" />
              </template>
            </template>

            <div class="fwmc-license-state">
              <span v-if="row && row.license && trialText" class="fwmc-badge fwmc-badge--trial">
                {{ trialText }}
              </span>
              <span v-else-if="row && row.license" class="fwmc-badge fwmc-badge--license-ok">
                {{ translate('FeinwerkMarketplaceConnector_LicenseActive') }}
                <template v-if="row.license.valid_until">
                  · {{ translate(
                    'FeinwerkMarketplaceConnector_LicenseUntil',
                    formatDay(row.license.valid_until),
                  ) }}
                </template>
              </span>
              <span
                v-else-if="row && row.needsLicense && row.trialEndedOn"
                class="fwmc-badge fwmc-badge--license-missing"
              >
                {{ translate(
                  'FeinwerkMarketplaceConnector_TrialEnded',
                  formatDay(row.trialEndedOn),
                ) }}
              </span>
              <span
                v-else-if="row && row.needsLicense"
                class="fwmc-badge fwmc-badge--license-missing"
              >
                {{ translate('FeinwerkMarketplaceConnector_NoLicense') }}
              </span>
            </div>
          </div>
        </div>

        <div class="fwmc-modal-foot">
          <details class="fwmc-versions" v-if="detail.versions && detail.versions.length">
            <summary>{{ translate('FeinwerkMarketplaceConnector_Versions') }}</summary>
            <div class="fwmc-tablewrap">
              <table class="fwmc-table entityTable">
                <tbody>
                  <tr v-for="v in detail.versions" :key="v.version">
                    <td>v{{ v.version }}</td>
                    <td>{{ formatDay(v.released_at) }}</td>
                    <td>{{ channelLabel(v.channel) }}</td>
                    <td>
                      <template v-if="v.matomo_min">Matomo ≥ {{ v.matomo_min }}</template>
                      <template v-if="v.php_min"> · PHP ≥ {{ v.php_min }}</template>
                    </td>
                    <td>
                      <span v-if="v.db_warning" class="fwmc-badge fwmc-badge--dbwarn">
                        {{ translate('FeinwerkMarketplaceConnector_DbWarning') }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </details>

          <div class="fwmc-cta">
            <InstallForm
              v-if="cta === 'install' || cta === 'update'"
              :action-url="actionUrl"
              :nonce="nonce"
              :slug="slug"
              :version="row.latestVersion"
              :label="cta === 'install'
                ? translate('FeinwerkMarketplaceConnector_Install')
                : translate('FeinwerkMarketplaceConnector_UpdateTo', row.latestVersion)"
              :plugin-name="row.displayName"
              :is-update="cta === 'update'"
              :db-change="row.dbChange || null"
              :held="cta === 'update' && targetHasDbWarning"
            />
            <LicenseActions
              v-else-if="cta === 'claim'"
              kind="claim"
              :url="claimUrl"
              :nonce="nonce"
              :slug="slug"
            />
            <!-- One filled action: the free trial when there is one, buying as the quiet second
                 button (the plan cards buy directly; Peter, 2026-11-26: "2. ja"). -->
            <template v-else-if="cta === 'buyhere'">
              <LicenseActions
                v-if="trialOffered"
                kind="trial"
                :url="trialUrl"
                :nonce="nonce"
                :slug="slug"
              />
              <LicenseActions
                kind="buy"
                :url="buyUrl"
                :nonce="nonce"
                :slug="slug"
                :quiet="trialOffered"
              />
            </template>
            <a
              v-else-if="cta === 'buy' && safeStorefrontUrl"
              :href="safeStorefrontUrl"
              target="_blank"
              rel="noreferrer noopener"
              class="fwmc-buy-link"
            >
              {{ translate('FeinwerkMarketplaceConnector_BuyOnMarketplace') }} ↗
            </a>
            <span
              v-else-if="cta === 'dev'"
              class="fwmc-badge fwmc-status--not_installed"
              :title="translate('FeinwerkMarketplaceConnector_DevCheckoutHint')"
            >
              {{ translate('FeinwerkMarketplaceConnector_DevCheckout') }}
            </span>
            <span v-else-if="cta === 'switching'" class="fwmc-badge fwmc-status--not_installed"
                  :title="translate('FeinwerkMarketplaceConnector_SwitchingHint')">
              {{ translate('FeinwerkMarketplaceConnector_Switching') }}
            </span>
            <a
              v-else-if="cta === 'activate'"
              class="btn"
              :href="activateHref"
              :title="translate('FeinwerkMarketplaceConnector_StatusInactiveHint')"
            >{{ translate('FeinwerkMarketplaceConnector_Activate') }}</a>
            <span v-else-if="cta === 'current'" class="fwmc-badge fwmc-status--installed">
              {{ translate('FeinwerkMarketplaceConnector_StatusCurrent') }}
            </span>
            <span v-else class="fwmc-meta">
              {{ translate('FeinwerkMarketplaceConnector_NoVersionYet') }}
            </span>
            <LicenseActions
              v-if="buyDuringTrial"
              kind="buy"
              :url="buyUrl"
              :nonce="nonce"
              :slug="slug"
            />
          </div>

          <BugReportForm v-if="slug" :slug="slug" :nonce="nonce" />
        </div>

        <!-- Teleported to <body>: Materialize gives the modal a transform, which makes it the
             containing block for position:fixed — inside it the "fullscreen" lightbox would be
             clipped to the modal box. The root carries .fwmc so the CSS stays scoped. -->
        <Teleport to="body">
          <div class="fwmc fwmc-lightbox" v-if="lightbox" ref="lightboxEl"
               @click="lightbox = null" @keydown.enter="lightbox = null"
               role="button" tabindex="0"
               :aria-label="translate('FeinwerkMarketplaceConnector_CloseImage')">
            <img
              :src="proxied(lightbox)"
              :alt="translate('FeinwerkMarketplaceConnector_Screenshots')"
            />
          </div>
        </Teleport>
      </template>
    </div>
  </MatomoDialog>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { translate, MatomoDialog, Matomo } from 'CoreHome';
import proxied from '../proxied';
import activateLink from '../activate';
import BugReportForm from '../BugReportForm/BugReportForm.vue';
import InstallForm from '../InstallForm/InstallForm.vue';
import LicenseActions from '../LicenseActions/LicenseActions.vue';
import PlanCard from '../PlanCard/PlanCard.vue';
import { addActionContext, claimSubmit } from '../actionContext';
import trialLabel, { trialRunning } from '../trial';
import failureText from '../failure';
import {
  tierLabel, categoryLabel, channelLabel, formatDay, inAdminLanguage,
} from '../labels';

export default defineComponent({
  components: {
    MatomoDialog, BugReportForm, InstallForm, LicenseActions, PlanCard,
  },
  // Forms in this window tell the server to reopen it after their action (actionContext.ts).
  provide: { fwmcInWindow: true },
  // Matomo for WordPress buys only what installs through WordPress here (as LicenseActions).
  inject: {
    wordpress: { from: 'fwmcWordPress', default: false },
    wordpressAvailable: { from: 'fwmcWordPressAvailable', default: () => [] },
  },
  emits: ['close'],
  props: {
    slug: { type: String, default: null },
    // {context, message, connect} from the page (Controller::takeResultNotice), or null.
    notice: {
      type: Object as () => { context: string, message: string, connect: boolean }|null,
      default: null,
    },
    connectUrl: { type: String, default: '' },
    accountUrl: { type: String, default: '' },
    row: { type: Object as () => any, default: null },
    nonce: { type: String, required: true },
    // Base URLs for the install/update actions, built server-side with linkTo()
    // and passed down from PluginsPage — mirrors ManageTable's pattern so slug/version
    // travel as hidden POST fields instead of a hand-built index.php query string.
    installUrl: { type: String, required: true },
    updateUrl: { type: String, required: true },
    // Matomo's own activate link (PluginsPage), '' when Matomo does not allow it.
    activateUrl: { type: String, default: '' },
    claimUrl: { type: String, default: '' },
    buyUrl: { type: String, default: '' },
    trialUrl: { type: String, default: '' },
    licensingAvailable: { type: Boolean, default: false },
  },
  data() {
    return {
      // The id of the plan whose checkout is being opened (one send per page).
      planBusy: null as number|null,
      opened: false,
      loading: false,
      detail: null as any,
      stale: false,
      errorKind: null as string|null,
      errorMessage: null as string|null,
      lightbox: null as string|null,
      artworkFailed: false,
      requestSeq: 0,
    };
  },
  watch: {
    slug(newSlug: string|null) {
      if (newSlug) this.open(newSlug);
      else this.opened = false;
    },
    lightbox(src: string|null) {
      // Materialize closes the whole modal on Escape via a bubbling document listener;
      // a capture-phase listener lets Escape close just the lightbox first.
      if (src) {
        window.addEventListener('keydown', this.onLightboxKeydown, true);
        this.$nextTick(() => (this.$refs.lightboxEl as HTMLElement|undefined)?.focus());
      } else {
        window.removeEventListener('keydown', this.onLightboxKeydown, true);
      }
    },
  },
  beforeUnmount() {
    this.stopGuard();
    window.removeEventListener('keydown', this.onLightboxKeydown, true);
  },
  computed: {
    // The store's texts in a German Matomo in German when it sends them (W1): description_de,
    // listing.long_description_html_de; else the English ones.
    description(): string {
      // eslint-disable-next-line @typescript-eslint/no-explicit-any
      const d = this.detail as any;
      return d ? inAdminLanguage(d.description || '', d.description_de) : '';
    },
    longDescription(): string {
      // eslint-disable-next-line @typescript-eslint/no-explicit-any
      const listing = (this.detail as any)?.listing;
      return listing
        ? inAdminLanguage(listing.long_description_html || '', listing.long_description_html_de)
        : '';
    },
    monogram(): string {
      return (this.detail?.display_name || '?').trim().charAt(0).toUpperCase();
    },
    features(): string[] {
      return this.detail?.listing?.features || [];
    },
    screenshots(): string[] {
      return this.detail?.listing?.screenshots || [];
    },
    latestVersionInfo(): any {
      return (this.detail?.versions || [])[0] || null;
    },
    targetHasDbWarning(): boolean {
      const target = (this.detail?.versions || [])
        .find((v: any) => v.version === this.row?.latestVersion);
      return !!target?.db_warning;
    },
    trialText(): string {
      return trialLabel(this.row ? this.row.license : null, formatDay);
    },
    // Peter, 2026-11-17 ("3. ja"): during a free trial "Buy a license" stands next to the action.
    // Buying is offered here (the window's own buy button shows) and this Matomo may buy the
    // plugin; then a plan card opens that plan's checkout.
    canBuyPlans(): boolean {
      if (!this.buyUrl || !this.slug) return false;
      const { wordpress, wordpressAvailable } = this as unknown as {
        wordpress: boolean, wordpressAvailable: string[],
      };
      if (wordpress && !wordpressAvailable.includes(this.slug)) return false;
      return this.cta === 'buyhere' || this.buyDuringTrial;
    },
    noticeClass(): string {
      const context = this.notice?.context || 'info';
      return {
        error: 'alert-danger', warning: 'alert-warning', success: 'alert-success',
      }[context] || 'alert-info';
    },
    // Why the store offers no trial here, as one sentence, only where the admin can act on it.
    trialReasonText(): string {
      const key = ({
        email_unverified: 'TrialReasonEmailUnverified',
        no_account: 'TrialReasonNoAccount',
        trial_used: 'TrialReasonUsed',
      } as Record<string, string>)[String(this.detail?.trial_unavailable_reason || '')];
      return key ? translate(`FeinwerkMarketplaceConnector_${key}`) : '';
    },
    trialReasonAccount(): string {
      return this.detail?.trial_unavailable_reason === 'email_unverified'
        && /^https?:\/\//i.test(this.accountUrl) ? this.accountUrl : '';
    },
    // The store offers a free trial of this plugin here.
    trialOffered(): boolean {
      return !!(this.row && this.row.trialAvailable && this.trialUrl);
    },
    // A bought licence is active (a row carries only an active one, UpdateMapper): the plans
    // are information only.
    ownsLicense(): boolean {
      const license = this.row?.license;
      return !!license && !trialRunning(license);
    },
    buyDuringTrial(): boolean {
      const { row } = this;
      return !!this.licensingAvailable && !!row && trialRunning(row.license)
        && !row.licenseClaimable;
    },
    cta(): string {
      const { row } = this;
      if (!row || !row.latestVersion) return 'none';
      if (row.devCheckout && row.status !== 'not_installed') return row.status === 'update_available' ? 'dev' : 'current';
      if (row.switching) return 'switching';
      if (this.licensingAvailable && row.licenseClaimable) return 'claim';
      if (row.needsLicense && !row.license) return this.licensingAvailable ? 'buyhere' : 'buy';
      if (row.status === 'not_installed') return 'install';
      if (row.status === 'update_available') return 'update';
      if (row.inactive && this.activateUrl) return 'activate';
      return 'current';
    },
    activateHref(): string {
      return activateLink(this.activateUrl, this.slug || '');
    },
    actionUrl(): string {
      return this.cta === 'update' ? this.updateUrl : this.installUrl;
    },
    // Marketplace-controlled URL: only bind it as an href when it's http(s), so a
    // crafted storefront_url can't smuggle a javascript: URI into the DOM.
    safeStorefrontUrl(): string|null {
      const u = this.detail?.storefront_url || '';
      return /^https?:\/\//i.test(u) ? u : null;
    },
  },
  methods: {
    translate,
    tierLabel,
    categoryLabel,
    channelLabel,
    formatDay,
    async open(slug: string) {
      this.opened = true;
      this.loading = true;
      this.errorMessage = null;
      this.detail = null;
      this.artworkFailed = false;
      this.lightbox = null;
      // Guard against out-of-order responses when plugins are opened in quick
      // succession: only the newest request may write the state.
      this.requestSeq += 1;
      const seq = this.requestSeq;
      // The server answers from its cache within seconds; a hanging connection must not leave the
      // window loading forever.
      const abort = new AbortController();
      const timer = window.setTimeout(() => abort.abort(), 20000);
      try {
        const response = await fetch(
          `index.php?module=FeinwerkMarketplaceConnector&action=detail&slug=${encodeURIComponent(slug)}`,
          { credentials: 'same-origin', headers: { Accept: 'application/json' }, signal: abort.signal },
        );
        const payload = await response.json();
        if (seq !== this.requestSeq) return;
        if (payload.data) {
          this.detail = payload.data;
          this.stale = !!payload.stale;
          this.errorKind = payload.errorKind || null;
          this.showTop();
        } else {
          this.errorMessage = payload.error || '';
        }
      } catch (e) {
        if (seq !== this.requestSeq) return;
        this.errorMessage = failureText(e);
      } finally {
        window.clearTimeout(timer);
        if (seq === this.requestSeq) this.loading = false;
      }
    },
    // The window starts at the plugin's name (UI review 2026-11-23, F2). Matomo focuses every
    // link of the open modal when its opening ends ($('.modal.open a').focus(), the last one
    // wins), at a moment no fixed delay hits; Matomo 6 scrolls .modal-content, 5.x the .modal
    // (measured 2026-11-24). The heading is looked up in the page: the window lives in
    // Matomo's modal.
    windowHeading(): HTMLElement|null {
      return document.querySelector('.modal.open .fwmc-modal-title');
    },
    toTop(heading: HTMLElement) {
      heading.focus({ preventScroll: true });
      [heading.closest('.modal-content'), heading.closest('.modal')].forEach((box) => {
        if (box) (box as HTMLElement).scrollTop = 0;
      });
    },
    showTop() {
      let tries = 0;
      const attempt = () => {
        const heading = this.windowHeading();
        if (heading) {
          this.toTop(heading);
        } else if (tries < 30) {
          tries += 1;
          window.setTimeout(attempt, 50);
        }
      };
      attempt();
      this.guardTop();
    },
    // For four seconds, until the admin presses a key, clicks or scrolls, a focus that lands
    // elsewhere in the window goes back to the heading at the top.
    guardTop() {
      this.stopGuard();
      const stop = () => this.stopGuard();
      const onFocus = (event: FocusEvent) => {
        const heading = this.windowHeading();
        const modal = heading ? heading.closest('.modal') : null;
        const { target } = event;
        const inWindow = !!modal && target instanceof Node && modal.contains(target);
        if (heading && inWindow && target !== heading) {
          this.toTop(heading);
          // Matomo focuses its links in one go: once that has passed, the guard ends, so a
          // screen reader moving focus without key events is not pulled back (UI review, W9).
          window.setTimeout(stop, 300);
        }
      };
      const inputs = ['keydown', 'pointerdown', 'wheel', 'touchstart'];
      document.addEventListener('focusin', onFocus, true);
      inputs.forEach((type) => document.addEventListener(type, stop, true));
      const timer = window.setTimeout(stop, 4000);
      // eslint-disable-next-line @typescript-eslint/no-explicit-any
      (this as any).topGuard = () => {
        document.removeEventListener('focusin', onFocus, true);
        inputs.forEach((type) => document.removeEventListener(type, stop, true));
        window.clearTimeout(timer);
      };
    },
    stopGuard() {
      // eslint-disable-next-line @typescript-eslint/no-explicit-any
      const self = this as any;
      if (self.topGuard) {
        self.topGuard();
        self.topGuard = null;
      }
    },
    onConnectSubmit(event: Event) {
      if (!claimSubmit()) {
        event.preventDefault();
      }
    },
    // Only with an id from a store that offers the plan checkout (store, 2026-11-24), and not
    // for a plan the store lists but does not sell yet (`buyable: false`; missing = true).
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    planCheckout(plan: any): boolean {
      return this.canBuyPlans && Number.isInteger(plan.id) && plan.id > 0 && plan.buyable !== false;
    },
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    onPlanSubmit(event: Event, plan: any) {
      if (this.planBusy !== null || !claimSubmit()) {
        event.preventDefault();
        return;
      }
      addActionContext(event.target as HTMLFormElement, this.slug || '');
      this.planBusy = plan.id;
    },
    proxied,
    onLightboxKeydown(event: KeyboardEvent) {
      if (event.key === 'Escape' && this.lightbox) {
        event.preventDefault();
        event.stopImmediatePropagation();
        this.lightbox = null;
      }
    },
    hideImage(event: Event) {
      // A screenshot goes with its button, any other image on its own.
      const image = event.target as HTMLElement;
      ((image.closest('.fwmc-shot') as HTMLElement|null) || image).style.display = 'none';
    },
  },
});
</script>
