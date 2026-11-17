<template>
  <form
    v-if="!(wordpress && kind !== 'claim')"
    method="post"
    :action="url"
    class="fwmc-license-action"
    @submit="onSubmit"
  >
    <input type="hidden" name="nonce" :value="nonce" />
    <input type="hidden" name="slug" :value="slug" />
    <button type="submit" class="btn" :title="hint" :disabled="busy" :aria-busy="busy">
      {{ busy ? translate('FeinwerkMarketplaceConnector_Working') : label }}
    </button>
  </form>
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
  },
  inject: {
    inWindow: { from: 'fwmcInWindow', default: false },
    // Matomo for WordPress: a plugin bought or tried here could not be installed there yet.
    wordpress: { from: 'fwmcWordPress', default: false },
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
