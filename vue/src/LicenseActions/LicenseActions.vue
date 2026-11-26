<template>
  <form
    v-if="!hiddenInWordPress"
    method="post"
    :action="url"
    class="fwmc-license-action"
    @submit="onSubmit"
  >
    <input type="hidden" name="nonce" :value="nonce" />
    <input type="hidden" name="slug" :value="slug" />
    <button
      type="submit"
      :class="quiet ? 'btn btn-outline' : 'btn'"
      :title="hint"
      :disabled="busy"
      :aria-busy="busy"
    >
      {{ busy ? translate('FeinwerkMarketplaceConnector_Working') : label }}
    </button>
  </form>
  <!-- Matomo for WordPress, no WordPress version: say why instead of an empty cell (E2E run 3,
       2026-11-17). Once per row, in place of the buy button. -->
  <span
    v-else-if="kind === 'buy'"
    class="fwmc-badge fwmc-status--not_installed fwmc-wp-action"
    :title="translate(never
      ? 'FeinwerkMarketplaceConnector_WordPressNeverHint'
      : 'FeinwerkMarketplaceConnector_WordPressNotYetHint')"
  >{{ translate(never
    ? 'FeinwerkMarketplaceConnector_WordPressNever'
    : 'FeinwerkMarketplaceConnector_WordPressNotYet') }}</span>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { translate } from 'CoreHome';
import { addActionContext, claimSubmit } from '../actionContext';

// "Use an existing license" (kind claim), "Buy a license" (kind buy) and "Try it free"
// (kind trial): one POST form to the connector's claimLicense, buy or startTrial action. The
// server decides; the nonce is checked there.
export default defineComponent({
  props: {
    kind: { type: String as () => 'claim'|'buy'|'trial', required: true },
    url: { type: String, required: true },
    nonce: { type: String, required: true },
    slug: { type: String, required: true },
    // The second of two actions: an outline button (the window's buy next to its trial).
    quiet: { type: Boolean, default: false },
  },
  inject: {
    inWindow: { from: 'fwmcInWindow', default: false },
    // Matomo for WordPress: buy and trial only for the plugins that install through WordPress
    // here (WordPress mode); the others could not be installed there.
    wordpress: { from: 'fwmcWordPress', default: false },
    wordpressAvailable: { from: 'fwmcWordPressAvailable', default: () => [] },
    wordpressNever: { from: 'fwmcWordPressNever', default: () => [] },
  },
  data() {
    return { busy: false };
  },
  methods: {
    translate,
    // Sent once: a double click cannot spend the nonce twice. The server returns to this tab
    // and window.
    onSubmit(event: Event) {
      if (this.busy || !claimSubmit()) {
        event.preventDefault();
        return;
      }
      const { inWindow } = this as unknown as { inWindow: boolean };
      addActionContext(event.target as HTMLFormElement, inWindow ? this.slug : '');
      this.busy = true;
    },
  },
  computed: {
    hiddenInWordPress(): boolean {
      return !!this.wordpress && this.kind !== 'claim'
        && !(this.wordpressAvailable as string[]).includes(this.slug);
    },
    never(): boolean {
      return (this.wordpressNever as string[]).includes(this.slug);
    },
    label(): string {
      return translate({
        claim: 'FeinwerkMarketplaceConnector_ClaimLicense',
        buy: 'FeinwerkMarketplaceConnector_BuyLicense',
        trial: 'FeinwerkMarketplaceConnector_TrialStart',
      }[this.kind]);
    },
    hint(): string {
      return translate({
        claim: 'FeinwerkMarketplaceConnector_ClaimLicenseHint',
        buy: 'FeinwerkMarketplaceConnector_BuyLicenseHint',
        trial: 'FeinwerkMarketplaceConnector_TrialStartHint',
      }[this.kind]);
    },
  },
});
</script>
