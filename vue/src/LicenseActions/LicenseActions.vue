<template>
  <form method="post" :action="url" class="fwmc-license-action" @submit="onSubmit">
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

// "Use an existing license" (kind claim) and "Buy a license" (kind buy): one POST form to the
// connector's claimLicense or buy action. The server decides; the nonce is checked there.
export default defineComponent({
  props: {
    kind: { type: String as () => 'claim'|'buy', required: true },
    url: { type: String, required: true },
    nonce: { type: String, required: true },
    slug: { type: String, required: true },
  },
  inject: { inWindow: { from: 'fwmcInWindow', default: false } },
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
      return translate(this.kind === 'claim'
        ? 'FeinwerkMarketplaceConnector_ClaimLicense'
        : 'FeinwerkMarketplaceConnector_BuyLicense');
    },
    hint(): string {
      return translate(this.kind === 'claim'
        ? 'FeinwerkMarketplaceConnector_ClaimLicenseHint'
        : 'FeinwerkMarketplaceConnector_BuyLicenseHint');
    },
  },
});
</script>
