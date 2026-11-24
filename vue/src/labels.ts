import { translate, Matomo } from 'CoreHome';

// Store codes shown to people (UI review 2026-11-23, F3): the tier, category and release channel
// arrive as codes ("paid", "seo", "beta") and are translated here, never shown raw.

// Only keys in Matomo's table are translated: translate() answers an unknown key with "The
// string ... was not loaded" (5.0 to 6).
function known(key: string): boolean {
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  const table = (window as any).piwik_translations || {};
  return typeof table[key] === 'string';
}

export function tierLabel(tier: string|null|undefined): string {
  return translate(tier === 'paid'
    ? 'FeinwerkMarketplaceConnector_TierPaid' : 'FeinwerkMarketplaceConnector_TierFree');
}

export function categoryLabel(code: string|null|undefined): string {
  if (!code) return '';
  const key = `FeinwerkMarketplaceConnector_Category_${String(code).toLowerCase()}`;
  if (known(key)) {
    return translate(key);
  }
  const text = String(code);
  return text.charAt(0).toUpperCase() + text.slice(1);
}

export function channelLabel(channel: string|null|undefined): string {
  return translate(channel === 'beta'
    ? 'FeinwerkMarketplaceConnector_Beta' : 'FeinwerkMarketplaceConnector_ChannelStable');
}

// A day (YYYY-MM-DD, or the date part of an ISO timestamp) in the user's language (F5): the
// page names it since the review, so Intl no longer falls back to the browser's language.
// "2026-11-24 00:36" from the server as date and time in the user's Matomo language, the same
// wall-clock time (UI review 2026-11-23, W4: the list time was shown raw).
export function formatMoment(value: string|null|undefined): string {
  const m = /^(\d{4})-(\d{2})-(\d{2})[ T](\d{2}):(\d{2})/.exec(String(value || ''));
  if (!m) {
    return String(value || '');
  }
  try {
    const at = new Date(+m[1], +m[2] - 1, +m[3], +m[4], +m[5]);
    return at.toLocaleString(Matomo.language || undefined, {
      year: 'numeric', month: 'numeric', day: 'numeric', hour: '2-digit', minute: '2-digit',
    });
  } catch (e) {
    return String(value);
  }
}

// A store text in the admin's language: the German field in a German Matomo when the store
// sends one, else the English one (UI review 2026-11-23, W1).
export function inAdminLanguage<T>(english: T, german: T|null|undefined): T {
  return (Matomo.language || '').slice(0, 2) === 'de' && german ? german : english;
}

export function formatDay(value: string|null|undefined): string {
  const day = String(value || '').slice(0, 10);
  if (!/^\d{4}-\d{2}-\d{2}$/.test(day)) {
    return String(value || '');
  }
  try {
    return new Date(`${day}T12:00:00`).toLocaleDateString(Matomo.language || undefined);
  } catch (e) {
    return day;
  }
}
