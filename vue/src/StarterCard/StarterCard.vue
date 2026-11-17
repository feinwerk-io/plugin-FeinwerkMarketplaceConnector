<template>
  <!-- First step right after Connect (StarterCard, Peter 2026-11-16): the plugin the store's
       `starter` names, installed with one click. Super users only, every off switch, closed for
       good with ×. Nothing is sent: the data comes from the cached store list. -->
  <aside v-if="!closed" class="fwmc-promo fwmc-starter" :aria-label="tr('StarterLabel')">
    <div class="fwmc-promo-art">
      <img
        v-if="starter.artworkUrl && !artworkFailed"
        :src="proxied(starter.artworkUrl)"
        alt=""
        @error="artworkFailed = true"
      />
      <span v-else class="fwmc-avatar fwmc-avatar--lg" aria-hidden="true">{{ monogram }}</span>
    </div>
    <div class="fwmc-promo-body">
      <span class="fwmc-promo-label">{{ tr('StarterLabel') }}</span>
      <h3 class="fwmc-promo-title">{{ starter.name }}</h3>
      <p class="fwmc-promo-text">{{ starter.line }}</p>
      <div class="fwmc-promo-actions">
        <InstallForm
          :action-url="installUrl"
          :nonce="nonce"
          :slug="starter.slug"
          :version="starter.version"
          :label="tr('StarterInstall')"
          :plugin-name="starter.name"
          then="starter"
        />
        <FeinwerkLogo class="fwmc-promo-logo" />
      </div>
    </div>
    <button
      type="button"
      class="fwmc-promo-close"
      :aria-label="tr('StarterClose')"
      :title="tr('StarterClose')"
      @click="close"
    ><span aria-hidden="true">×</span></button>
  </aside>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import { translate } from 'CoreHome';
import InstallForm from '../InstallForm/InstallForm.vue';
import FeinwerkLogo from '../FeinwerkLogo/FeinwerkLogo.vue';
import proxied from '../proxied';

interface Starter {
  slug: string;
  name: string;
  line: string;
  version: string;
  artworkUrl: string|null;
  closeUrl: string;
  closeNonce: string;
}

export default defineComponent({
  components: { InstallForm, FeinwerkLogo },
  props: {
    starter: { type: Object as PropType<Starter>, required: true },
    installUrl: { type: String, required: true },
    nonce: { type: String, required: true },
  },
  data() {
    return { closed: false, artworkFailed: false };
  },
  computed: {
    monogram(): string {
      return (this.starter.name || '?').trim().charAt(0).toUpperCase();
    },
  },
  methods: {
    proxied,
    tr(key: string): string {
      return translate(`FeinwerkMarketplaceConnector_${key}`);
    },
    // Closed at once; the server forgets the card (StarterCard::close), so it does not come back.
    async close() {
      this.closed = true;
      try {
        await fetch(this.starter.closeUrl, {
          method: 'POST',
          credentials: 'same-origin',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            Accept: 'application/json',
          },
          body: new URLSearchParams({ nonce: this.starter.closeNonce }),
        });
      } catch (e) {
        // no answer: the card may show again on the next visit
      }
    },
  },
});
</script>
