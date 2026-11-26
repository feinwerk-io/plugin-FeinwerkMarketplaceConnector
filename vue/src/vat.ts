import { translate } from 'CoreHome';
import amount from './amount';
import intervalLabel from './intervals';

// The store's prices are German gross; with its VAT rate it also sends the net amount (Peter,
// 2026-11-26: "Netto und brutto"). Without a rate (an older store) the texts stay as before.

interface Price {
  amount_eur?: unknown;
  interval?: unknown;
  vat_rate?: unknown;
}

export function vatRate(price: unknown): number|null {
  const rate = Number((price as { vat_rate?: unknown }|null)?.vat_rate);
  return Number.isFinite(rate) && rate > 0 && rate < 100 ? rate : null;
}

// "19 %" in the user's Matomo language, through Matomo's global NumberFormatter (5.0 to 6).
export function percent(rate: number): string {
  try {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    const formatter = (window as any).NumberFormatter;
    if (formatter && typeof formatter.formatPercent === 'function') {
      const formatted = formatter.formatPercent(rate);
      if (typeof formatted === 'string' && formatted !== '') {
        // no line break inside "19 %"
        return formatted.replace(/ /g, '\u00a0');
      }
    }
  } catch (e) {
    // fall back to the plain rate
  }
  return `${rate}\u00a0%`;
}

// "from 70,21 € / month" or, with the store's VAT rate, "... incl. VAT".
export function priceFromText(price: Price|null|undefined): string {
  if (!price) {
    return '';
  }
  return translate(
    vatRate(price) !== null
      ? 'FeinwerkMarketplaceConnector_PriceFromVat'
      : 'FeinwerkMarketplaceConnector_PriceFrom',
    amount(price.amount_eur),
    intervalLabel(String(price.interval || '')),
  );
}
