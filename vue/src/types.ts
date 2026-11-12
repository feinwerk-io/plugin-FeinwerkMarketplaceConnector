// The shapes the server hands to the screen (Controller::index): a plugin row
// (PluginStatusRow) and an item of the "Aufgaben" tab (Core/ActionItems).

export interface PluginRow {
  slug: string;
  displayName: string;
  tier: string;
  installedVersion: string|null;
  latestVersion: string|null;
  status: string;
  needsLicense: boolean;
  license: { status: string, valid_until: string|null }|null;
  licenseClaimable: boolean;
  url?: string|null;
  dbChange?: Record<string, unknown>|null;
  inactive?: boolean;
}

export interface ActionItem {
  kind: string;
  severity: string;
  slug: string|null;
  date: string|null;
  components: string[];
}
