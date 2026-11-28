<template>
  <!-- One plan of the plugin window: spans only, so the card can sit inside a link or a
       button (the window's three ways: checkout, store page, plain information). -->
  <span
    class="fwmc-plan"
    :class="{ 'fwmc-plan--highlight': plan.highlight, 'fwmc-plan--busy': busy }"
  >
    <span class="fwmc-plan-name">{{ name }}</span>
    <span class="fwmc-plan-price">
      {{ amount(shownPrice) }} € / {{ intervalLabel(plan.interval) }}
    </span>
    <span class="fwmc-meta" v-if="vatLine">{{ vatLine }}</span>
    <span class="fwmc-meta" v-if="plan.max_users">
      {{ translate('FeinwerkMarketplaceConnector_MaxUsers', plan.max_users) }}
    </span>
    <span class="fwmc-meta" v-if="note">{{ note }}</span>
    <span v-if="buy" class="fwmc-plan-buy-cue">
      {{ busy
        ? translate('FeinwerkMarketplaceConnector_Working')
        : translate('FeinwerkMarketplaceConnector_PlanBuy') }}
    </span>
  </span>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { translate } from 'CoreHome';
import intervalLabel from '../intervals';
import amount from '../amount';
import { inAdminLanguage } from '../labels';
import { vatRate, percent } from '../vat';

export default defineComponent({
  props: {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    plan: { type: Object as () => any, required: true },
    // A click opens this plan's checkout (Peter, 2026-11-24): the card says so.
    buy: { type: Boolean, default: false },
    busy: { type: Boolean, default: false },
  },
  computed: {
    name(): string {
      return inAdminLanguage(this.plan.name_en, this.plan.name_de);
    },
    note(): string|null {
      return inAdminLanguage(this.plan.note_en, this.plan.note_de);
    },
    // With the store's rate and net price the card shows the net price large and the gross small,
    // as the store does (Peter, 2026-11-27: "Netto groß, brutto klein").
    netShown(): boolean {
      const net = this.plan.price_net_eur;
      return vatRate(this.plan) !== null && net !== null && net !== undefined && net !== ''
        && Number.isFinite(Number(net));
    },
    shownPrice(): unknown {
      return this.netShown ? this.plan.price_net_eur : this.plan.price_eur;
    },
    // "plus VAT · 70,21 € incl. 19 % VAT"
    vatLine(): string {
      const rate = vatRate(this.plan);
      if (!this.netShown || rate === null) {
        return '';
      }
      return translate('FeinwerkMarketplaceConnector_PlanGross', amount(this.plan.price_eur), percent(rate));
    },
  },
  methods: {
    translate,
    intervalLabel,
    amount,
  },
});
</script>
