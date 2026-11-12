/** Rewrite a marketplace image URL through the CSP-safe connector proxy. */
export default function proxied(url: string|null|undefined): string|null {
  if (!url) return null;
  return `index.php?module=FeinwerkMarketplaceConnector&action=image&src=${encodeURIComponent(url)}`;
}
