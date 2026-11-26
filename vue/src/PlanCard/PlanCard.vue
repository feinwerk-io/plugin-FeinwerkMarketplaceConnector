<template>
  <!-- One plan of the plugin window: spans only, so the card can sit inside a link or a
       button (the window's three ways: checkout, store page, plain information). -->
  <span
    class="fwmc-plan"
    :class="{ 'fwmc-plan--highlight': plan.highlight, 'fwmc-plan--busy': busy }"
  >
    <span class="fwmc-plan-name">{{ name }}</span>
    <span class="fwmc-plan-price">
      {{ amount(plan.price_eur) }} € / {{ intervalLabel(plan.interval) }}
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
    // "incl. 19 % VAT · 59,00 € excl. VAT" when the store sends its rate and the net price.
    vatLine(): string {
      const rate = vatRate(this.plan);
      const net = Number(this.plan.price_net_eur);
      if (rate === null || this.plan.price_net_eur === '' || !Number.isFinite(net)) {
        return '';
      }
      return translate('FeinwerkMarketplaceConnector_PlanVat', percent(rate), amount(net));
    },
  },
  methods: {
    translate,
    intervalLabel,
    amount,
  },
});
</script>
