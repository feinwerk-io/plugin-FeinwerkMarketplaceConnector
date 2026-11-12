/**
 * A euro amount from the store ("1299.00") in the admin's number format ("1.299,00" in
 * German), through Matomo's own global NumberFormatter (5.0 to 6; the admin pages set no
 * html lang). Without it: the plain amount.
 */
export default function amount(value: unknown): string {
  if (value === null || value === undefined) {
    return '';
  }
  const n = Number(value);
  if (value === '' || !Number.isFinite(n)) {
    return String(value);
  }
  try {
    const formatter = (window as any).NumberFormatter;
    if (formatter && typeof formatter.formatNumber === 'function') {
      const formatted = formatter.formatNumber(n, 2, 2);
      if (typeof formatted === 'string' && formatted !== '') {
        return formatted;
      }
    }
  } catch (e) {
    // fall back to the plain amount
  }
  return n.toFixed(2);
}
