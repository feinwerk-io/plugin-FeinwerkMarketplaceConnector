// Which rows "update several at once" may take (BatchUpdate): an update the admin could also
// install with a click, never the connector itself (its update replaces the code that runs the
// batch), a development checkout, a plugin a Matomo switch is still handling or one without a
// license.

interface BatchRow {
  slug: string;
  status: string;
  latestVersion: string|null;
  needsLicense: boolean;
  license: unknown;
  devCheckout?: boolean;
  switching?: boolean;
}

export default function canBatch(row: BatchRow, selfSlug: string): boolean {
  return row.status === 'update_available' && !!row.latestVersion && row.slug !== selfSlug
    && !row.devCheckout && !row.switching && !(row.needsLicense && !row.license);
}
