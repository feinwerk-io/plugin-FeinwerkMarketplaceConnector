import { translate } from 'CoreHome';

// A trial licence (store field license.trial, 2026-11-16): "Trial until <date>, N days left";
// '' for every other licence. The store sends the end as a date (or a full timestamp).

interface TrialLicense {
  valid_until?: string|null;
  trial?: boolean;
}

export default function trialLabel(
  license: TrialLicense|null|undefined,
  formatDate: (day: string) => string,
): string {
  if (!license || license.trial !== true || !license.valid_until) {
    return '';
  }
  const day = String(license.valid_until).slice(0, 10);
  const end = Date.parse(`${day}T00:00:00Z`);
  if (Number.isNaN(end)) {
    return '';
  }
  const days = Math.max(0, Math.ceil((end - Date.now()) / 86400000));
  return days <= 1
    ? translate('FeinwerkMarketplaceConnector_TrialLastDay', formatDate(day))
    : translate('FeinwerkMarketplaceConnector_TrialUntil', formatDate(day), String(days));
}

// A free trial runs (Peter, 2026-11-17: "3. ja"): "Buy a license" is offered during it too,
// never for a bought licence.
export function trialRunning(license: TrialLicense|null|undefined): boolean {
  return !!license && license.trial === true;
}
