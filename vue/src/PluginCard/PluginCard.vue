<template>
  <!-- The title button opens the plugin window; its ::after covers the whole card. -->
  <div class="fwmc-card">
    <div class="fwmc-card-media">
      <img
        v-if="row.artworkUrl && !artworkFailed"
        :src="proxied(row.artworkUrl)"
        alt=""
        loading="lazy"
        @error="artworkFailed = true"
      />
      <span v-else class="fwmc-avatar fwmc-avatar--lg" aria-hidden="true">{{ monogram }}</span>
    </div>
    <div class="fwmc-card-body">
      <h3 class="fwmc-card-title">
        <button type="button" class="fwmc-card-link" @click="$emit('open')">
          {{ row.displayName }}
        </button>
        <span class="fwmc-badge" :class="'fwmc-badge--' + row.tier">{{ tierLabel }}</span>
      </h3>
      <p class="fwmc-card-desc" v-if="row.description">{{ row.description }}</p>
      <div class="fwmc-card-foot">
        <span class="fwmc-meta" v-if="row.category">{{ categoryLabel || row.category }}</span>
        <span class="fwmc-price" v-if="row.priceFrom">
          {{ translate(
            'FeinwerkMarketplaceConnector_PriceFrom',
            amount(row.priceFrom.amount_eur),
            intervalLabel(row.priceFrom.interval),
          ) }}
        </span>
        <span class="fwmc-meta fwmc-card-na" v-if="row.availableHere === false">
          {{ translate('FeinwerkMarketplaceConnector_NotAvailableHere') }}
        </span>
        <span class="fwmc-badge fwmc-status--installed" v-if="row.status === 'installed'">
          {{ translate('FeinwerkMarketplaceConnector_StatusInstalled') }}
        </span>
        <!-- After a Matomo major update the connector installs this build itself (rc1 RC-F2). -->
        <span
          class="fwmc-badge fwmc-status--not_installed"
          v-else-if="row.switching"
          :title="translate('FeinwerkMarketplaceConnector_SwitchingHint')"
        >
          {{ translate('FeinwerkMarketplaceConnector_Switching') }}
        </span>
        <span
          class="fwmc-badge fwmc-status--update_available"
          v-else-if="row.status === 'update_available'"
        >
          {{ translate('FeinwerkMarketplaceConnector_StatusUpdate') }}
        </span>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { translate } from 'CoreHome';
import intervalLabel from '../intervals';
import amount from '../amount';
import proxied from '../proxied';

export default defineComponent({
  emits: ['open'],
  props: {
    row: { type: Object as () => any, required: true },
    categoryLabel: { type: String, default: '' },
  },
  data() {
    return { artworkFailed: false };
  },
  computed: {
    monogram(): string {
      return (this.row.displayName || '?').trim().charAt(0).toUpperCase();
    },
    tierLabel(): string {
      return translate(this.row.tier === 'paid'
        ? 'FeinwerkMarketplaceConnector_TierPaid' : 'FeinwerkMarketplaceConnector_TierFree');
    },
  },
  methods: {
    translate,
    intervalLabel,
    amount,
    proxied,
  },
});
</script>
