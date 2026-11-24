<template>
  <MatomoDialog v-model="opened" @close="$emit('close')">
    <div class="fwmc fwmc-modal uiControl">
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
          <div>
            <h2 ref="heading" tabindex="-1" class="fwmc-modal-title">{{ detail.display_name }}</h2>
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
              <template v-else-if="cta === 'buyhere'">
                <LicenseActions kind="buy" :url="buyUrl" :nonce="nonce" :slug="slug" />
                <LicenseActions
                  v-if="row && row.trialAvailable && trialUrl"
                  kind="trial"
                  :url="trialUrl"
                  :nonce="nonce"
                  :slug="slug"
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
                <a v-if="safeStorefrontUrl" :href="safeStorefrontUrl" target="_blank"
                   rel="noreferrer noopener" class="fwmc-plan-link">
                  <div class="fwmc-plan" :class="{ 'fwmc-plan--highlight': plan.highlight }">
                    <div class="fwmc-plan-name">{{ planName(plan) }}</div>
                    <div class="fwmc-plan-price">
                      {{ amount(plan.price_eur) }} € / {{ intervalLabel(plan.interval) }}
                    </div>
                    <div class="fwmc-meta" v-if="plan.max_users">
                      {{ translate('FeinwerkMarketplaceConnector_MaxUsers', plan.max_users) }}
                    </div>
                    <div class="fwmc-meta" v-if="planNote(plan)">{{ planNote(plan) }}</div>
                  </div>
                </a>
                <div v-else class="fwmc-plan" :class="{ 'fwmc-plan--highlight': plan.highlight }">
                  <div class="fwmc-plan-name">{{ planName(plan) }}</div>
                  <div class="fwmc-plan-price">
                    {{ amount(plan.price_eur) }} € / {{ intervalLabel(plan.interval) }}
                  </div>
                  <div class="fwmc-meta" v-if="plan.max_users">
                    {{ translate('FeinwerkMarketplaceConnector_MaxUsers', plan.max_users) }}
                  </div>
                  <div class="fwmc-meta" v-if="planNote(plan)">{{ planNote(plan) }}</div>
                </div>
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
            <template v-else-if="cta === 'buyhere'">
              <LicenseActions kind="buy" :url="buyUrl" :nonce="nonce" :slug="slug" />
              <LicenseActions
                v-if="row && row.trialAvailable && trialUrl"
                kind="trial"
                :url="trialUrl"
                :nonce="nonce"
                :slug="slug"
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
import intervalLabel from '../intervals';
import amount from '../amount';
import proxied from '../proxied';
import activateLink from '../activate';
import BugReportForm from '../BugReportForm/BugReportForm.vue';
import InstallForm from '../InstallForm/InstallForm.vue';
import LicenseActions from '../LicenseActions/LicenseActions.vue';
import trialLabel, { trialRunning } from '../trial';
import failureText from '../failure';
import {
  tierLabel, categoryLabel, channelLabel, formatDay, inAdminLanguage,
} from '../labels';

export default defineComponent({
  components: {
    MatomoDialog, BugReportForm, InstallForm, LicenseActions,
  },
  // Forms in this window tell the server to reopen it after their action (actionContext.ts).
  provide: { fwmcInWindow: true },
  emits: ['close'],
  props: {
    slug: { type: String, default: null },
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
    // The window starts at the plugin's name (UI review 2026-11-23, F2): Matomo's modalConfirm
    // focuses the first link of an open modal once it has opened, which scrolled the window into
    // its text. Focus the heading and scroll to the top after rendering and once the open
    // animation is over (Materialize: 250 ms).
    showTop() {
      const run = () => {
        const heading = this.$refs.heading as HTMLElement|undefined;
        if (!heading) return;
        heading.focus({ preventScroll: true });
        const modal = heading.closest('.modal') as HTMLElement|null;
        if (modal) modal.scrollTop = 0;
      };
      this.$nextTick(run);
      window.setTimeout(run, 400);
    },
    planName(plan: any): string {
      return inAdminLanguage(plan.name_en, plan.name_de);
    },
    planNote(plan: any): string|null {
      return inAdminLanguage(plan.note_en, plan.note_de);
    },
    intervalLabel,
    amount,
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
