<template>
  <div class="fwmc-browse">
    <div class="fwmc-filters">
      <input
        type="search"
        v-model="search"
        :placeholder="translate('FeinwerkMarketplaceConnector_SearchPlaceholder')"
        :aria-label="translate('FeinwerkMarketplaceConnector_SearchPlaceholder')"
        class="fwmc-search"
      />
      <select
        v-model="category"
        class="fwmc-filter"
        :aria-label="translate('FeinwerkMarketplaceConnector_FilterCategory')"
      >
        <option value="">{{ translate('FeinwerkMarketplaceConnector_AllCategories') }}</option>
        <option v-for="c in categories" :key="c" :value="c">{{ categoryLabel(c) }}</option>
      </select>
      <select
        v-model="tier"
        class="fwmc-filter"
        :aria-label="translate('FeinwerkMarketplaceConnector_FilterTier')"
      >
        <option value="">{{ translate('FeinwerkMarketplaceConnector_AllTiers') }}</option>
        <option value="free">{{ translate('FeinwerkMarketplaceConnector_TierFree') }}</option>
        <option value="paid">{{ translate('FeinwerkMarketplaceConnector_TierPaid') }}</option>
      </select>
      <select
        v-model="sort"
        class="fwmc-filter"
        :aria-label="translate('FeinwerkMarketplaceConnector_SortLabel')"
      >
        <option value="">{{ translate('FeinwerkMarketplaceConnector_SortRecommended') }}</option>
        <option value="name">{{ translate('FeinwerkMarketplaceConnector_SortName') }}</option>
        <option value="recent">{{ translate('FeinwerkMarketplaceConnector_SortRecent') }}</option>
      </select>
    </div>
    <p class="fwmc-count" aria-live="polite">{{ countText }}</p>
    <div v-if="filtered.length" class="fwmc-grid">
      <PluginCard
        v-for="row in filtered"
        :key="row.slug"
        :row="row"
        :category-label="categoryLabel(row.category)"
        @open="$emit('open', row.slug)"
      />
    </div>
    <div v-else class="fwmc-empty">
      <p>{{ translate('FeinwerkMarketplaceConnector_NoResults') }}</p>
      <button type="button" class="btn btn-flat" @click="reset">
        {{ translate('FeinwerkMarketplaceConnector_ResetFilters') }}
      </button>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { translate } from 'CoreHome';
import PluginCard from '../PluginCard/PluginCard.vue';

export default defineComponent({
  components: { PluginCard },
  emits: ['open'],
  props: {
    rows: { type: Array as () => any[], required: true },
  },
  data() {
    return {
      search: '', category: '', tier: '', sort: '',
    };
  },
  computed: {
    categories(): string[] {
      return [...new Set(this.rows.map((r) => r.category).filter(Boolean))]
        .sort((a, b) => this.categoryLabel(a).localeCompare(this.categoryLabel(b)));
    },
    filtered(): any[] {
      const q = this.search.trim().toLowerCase();
      const rows = this.rows.filter((r) => {
        if (this.category && r.category !== this.category) return false;
        if (this.tier && r.tier !== this.tier) return false;
        if (!q) return true;
        const haystack = [r.displayName, r.description, r.slug, r.category,
          r.category ? this.categoryLabel(r.category) : ''].join(' ').toLowerCase();
        return haystack.includes(q);
      });
      if (this.sort === 'name') {
        return [...rows].sort((a, b) => String(a.displayName).localeCompare(String(b.displayName)));
      }
      if (this.sort === 'recent') {
        // ISO 8601 dates sort as strings; plugins without a date go last.
        return [...rows].sort(
          (a, b) => String(b.releasedAt || '').localeCompare(String(a.releasedAt || '')),
        );
      }
      return rows;
    },
    countText(): string {
      const n = this.filtered.length;
      return n === 1
        ? translate('FeinwerkMarketplaceConnector_ResultCountOne')
        : translate('FeinwerkMarketplaceConnector_ResultCount', String(n));
    },
  },
  methods: {
    translate,
    // The store sends category codes (analytics, monitoring, ...): show the translation, else
    // the code. Matomo's translate() answers an unknown key with "The string ... was not
    // loaded", so only keys in Matomo's table (piwik_translations, 5.0 to 6) are translated.
    categoryLabel(code: string): string {
      if (!code) return '';
      const key = `FeinwerkMarketplaceConnector_Category_${String(code).toLowerCase()}`;
      const known = (window as any).piwik_translations || {};
      if (typeof known[key] === 'string') {
        return translate(key);
      }
      const text = String(code);
      return text.charAt(0).toUpperCase() + text.slice(1);
    },
    reset() {
      this.search = '';
      this.category = '';
      this.tier = '';
    },
  },
});
</script>
