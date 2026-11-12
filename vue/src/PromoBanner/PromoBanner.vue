<template>
  <!-- Recommendation banner (backlog 1): a Feinwerk plugin this Matomo does not have yet
       (Core/Promotion). Labelled as a recommendation, closable for 30 days per plugin, switched
       off with one click here (backlog 12) or under General settings. Nothing is sent: the data
       comes from the cached store list. -->
  <p v-if="switchedOff" class="fwmc-promo-offnote" aria-live="polite">
    {{ tr('PromoOffDone') }}
    <a :href="settingsUrl">{{ tr('PromoOffUndo') }}</a>
  </p>
  <aside
    v-else-if="promo && promo.slug && !hidden"
    class="fwmc-promo"
    :aria-label="tr('PromoLabel')"
  >
    <div class="fwmc-promo-art">
      <img
        v-if="promo.artworkUrl && !artworkFailed"
        :src="proxied(promo.artworkUrl)"
        alt=""
        @error="artworkFailed = true"
      />
      <span v-else class="fwmc-avatar fwmc-avatar--lg" aria-hidden="true">{{ monogram }}</span>
    </div>
    <div class="fwmc-promo-body">
      <span class="fwmc-promo-label">{{ tr('PromoLabel') }}</span>
      <h3 class="fwmc-promo-title">{{ promo.name }}</h3>
      <p v-if="promo.description" class="fwmc-promo-text">{{ promo.description }}</p>
      <div class="fwmc-promo-actions">
        <button
          v-if="inline"
          type="button"
          class="btn fwmc-promo-cta"
          @click="$emit('open', promo.slug)"
        >{{ tr('PromoCta') }} <span aria-hidden="true">→</span></button>
        <a v-else class="btn fwmc-promo-cta" :href="screenUrl">
          {{ tr('PromoCta') }} <span aria-hidden="true">→</span>
        </a>
        <span v-if="price" class="fwmc-promo-price">{{ price }}</span>
        <button
          v-if="promo.offUrl && promo.offNonce"
          type="button"
          class="fwmc-promo-off"
          :disabled="switching"
          @click="switchOff"
        >{{ switching ? tr('Working') : tr('PromoSettings') }}</button>
        <a v-else class="fwmc-promo-off" :href="settingsUrl">{{ tr('PromoSettings') }}</a>
      </div>
    </div>
    <button
      type="button"
      class="fwmc-promo-close"
      :aria-label="tr('PromoClose')"
      :title="tr('PromoClose')"
      @click="dismiss"
    ><span aria-hidden="true">×</span></button>
  </aside>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import { translate } from 'CoreHome';
import intervalLabel from '../intervals';
import amount from '../amount';
import proxied from '../proxied';

interface Promo {
  slug: string;
  name: string;
  description: string;
  artworkUrl: string|null;
  tier: string;
  priceFrom: { amount_eur: string, interval: string }|null;
  // "Switch recommendations off" right here (Recommendation::current, backlog 12).
  offUrl?: string;
  offNonce?: string;
}

const HIDE_DAYS = 30;

function storageKey(slug: string): string {
  return `fwmc.promo.hidden.${slug}`;
}

export default defineComponent({
  emits: ['open'],
  props: {
    promo: { type: Object as PropType<Promo|null>, default: null },
    // On the Discover tab the button opens the plugin window right there.
    inline: { type: Boolean, default: false },
  },
  data() {
    let hidden = false;
    try {
      const at = Number(window.localStorage.getItem(storageKey(this.promo?.slug || '')) || 0);
      hidden = at > 0 && Date.now() - at < HIDE_DAYS * 86400000;
    } catch (e) {
      // blocked storage: the banner shows
    }
    return {
      hidden, artworkFailed: false, switching: false, switchedOff: false,
    };
  },
  computed: {
    monogram(): string {
      return (this.promo?.name || '?').trim().charAt(0).toUpperCase();
    },
    price(): string {
      const from = this.promo?.priceFrom;
      return from
        ? translate(
          'FeinwerkMarketplaceConnector_PriceFrom',
          amount(from.amount_eur),
          intervalLabel(from.interval),
        )
        : '';
    },
    screenUrl(): string {
      const slug = encodeURIComponent(this.promo?.slug || '');
      return `index.php?module=FeinwerkMarketplaceConnector&action=index&fwmc_open=${slug}`;
    },
    settingsUrl(): string {
      return 'index.php?module=CoreAdminHome&action=generalSettings#/FeinwerkMarketplaceConnector';
    },
  },
  methods: {
    proxied,
    tr(key: string): string {
      return translate(`FeinwerkMarketplaceConnector_${key}`);
    },
    // One click switches all recommendations off (the setting "Show recommendations"); General
    // settings switch them on again. If that fails, the settings page opens instead.
    async switchOff() {
      const url = this.promo?.offUrl || '';
      if (this.switching || !url) {
        return;
      }
      this.switching = true;
      try {
        const response = await fetch(url, {
          method: 'POST',
          credentials: 'same-origin',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded', Accept: 'application/json' },
          body: new URLSearchParams({ nonce: this.promo?.offNonce || '' }),
        });
        const result = await response.json();
        if (result && result.off) {
          this.switchedOff = true;
          return;
        }
      } catch (e) {
        // no answer of ours: fall back to the settings page
      }
      window.location.href = this.settingsUrl;
    },
    dismiss() {
      this.hidden = true;
      try {
        window.localStorage.setItem(storageKey(this.promo?.slug || ''), String(Date.now()));
      } catch (e) {
        // blocked storage: hidden until the page reloads
      }
    },
  },
});
</script>
