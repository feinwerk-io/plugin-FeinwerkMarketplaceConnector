import { translate } from 'CoreHome';

/** Human label for a plan interval (monthly | yearly | two_years | once). */
export default function intervalLabel(interval: string): string {
  const map: Record<string, string> = {
    monthly: 'FeinwerkMarketplaceConnector_IntervalMonthly',
    yearly: 'FeinwerkMarketplaceConnector_IntervalYearly',
    two_years: 'FeinwerkMarketplaceConnector_IntervalTwoYears',
    once: 'FeinwerkMarketplaceConnector_IntervalOnce',
  };
  return translate(map[interval] || 'FeinwerkMarketplaceConnector_IntervalMonthly');
}
