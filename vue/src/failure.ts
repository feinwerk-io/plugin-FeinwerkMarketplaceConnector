import { translate } from 'CoreHome';

// One readable sentence for a request that failed in the browser (UI review 2026-11-23, F10),
// instead of the browser's own exception text: a timeout, an answer that is no JSON (logged
// out, or the page is outdated) or a broken connection.
export default function failureText(error: unknown, status?: number): string {
  const name = error && typeof error === 'object' && 'name' in error ? String((error as Error).name) : '';
  if (name === 'AbortError') {
    return translate('FeinwerkMarketplaceConnector_FailureTimeout');
  }
  if (name === 'SyntaxError' || (status !== undefined && status > 0)) {
    return translate('FeinwerkMarketplaceConnector_FailureReload');
  }
  return translate('FeinwerkMarketplaceConnector_FailureConnection');
}
