# Feinwerk Connector

## Description

**Get the Plugins for Matomo by Feinwerk with one click: discover, install, update and license them right in your
Matomo administration. No file upload, no FTP, no config changes.**

Feinwerk Connector is free. It adds one screen, **Administration › Feinwerk Plugins**, next to the Matomo Marketplace,
which keeps working as before.

### Three reasons to install it

- **Install with one click instead of uploading ZIP files.** Open a plugin and click Install. Update one plugin or
  several at once, or let the plugins you choose update themselves. Updates that change the database never install by
  themselves, and on large Matomo instances the connector shows the console command for them.
- **Always current, also across Matomo versions.** Tasks lists everything that needs you: updates, database updates,
  licenses that run out. You are only offered versions that fit your Matomo and PHP version, and after a Matomo major
  update the connector switches your Feinwerk plugins to their builds for the new version and activates again what was
  active before.
- **Licenses without paperwork.** Free plugins need no license. For a paid plugin, buy a license or use one you already
  have, right in Matomo. A license covers one production instance and up to three test or staging instances in the
  same account.

### What you get right after connecting

The Feinwerk catalogue inside your Matomo: free plugins, free plugins with a paid Pro add-on, and paid plugins, each
with description, screenshots and plans. For example:

- *Data quality and monitoring:* Sentinel (free) and Sentinel Pro, Campaign Score (free), Tracking Spam Score,
  Delete Visits Filter (free)
- *Reports and analysis:* Search Report for Google Search Console and Bing, Visit Journey, Data Exploration (free),
  Page Filter Report (free), Channel Grouping
- *Dashboards and email:* Dashboard Segment (free), Dashboard Sharing (free), Email Reports Pro
- *Integrations and security:* Conversions API, Tracking Router, Auth SAML

### Get started in 3 steps

1. In your Matomo, open **Administration › Platform › Marketplace**, search for "Feinwerk", and install and activate
   **Feinwerk Marketplace Connector**.
2. Open **Administration › Feinwerk Plugins**. Click **Show Feinwerk plugins** to look around without an account, or
   **Connect with Feinwerk account** to connect this Matomo with a free account on plugins.feinwerk.io.
3. Open a plugin on **Discover** and click **Install**.

Every step with pictures: the [connector guide](https://plugins.feinwerk.io/en/docs/connector).

### Why administrators trust it

- **You decide what leaves your server.** Nothing is sent before you connect or ask to see the plugins, and never any
  visitor data or reports. The full list is below.
- **Safe installs.** Every plugin comes over HTTPS from plugins.feinwerk.io, is checked against its SHA-256 checksum
  and is installed with Matomo's own plugin installer. Only super users can install, update or license.
- **One address for your firewall.** The connector talks only to plugins.feinwerk.io and uses Matomo's proxy settings.
- **Works with Matomo 5 and Matomo 6.** One connector for both.
- **Support from the people who build the plugins.** Write to support@feinwerk.io in English or German. The screen
  speaks English, German, French, Spanish and Italian.

**Try it now:** install Feinwerk Connector and click **Show Feinwerk plugins**. Looking around needs no account and
sends no data about your websites or users.

### What is sent to Feinwerk, and when

Nothing is sent until you either connect this Matomo with your account on plugins.feinwerk.io (the button "Connect
with Feinwerk account", or the instance token under General settings) or click "Show Feinwerk plugins" to look at the
plugins first.

When you click "Show Feinwerk plugins" (no account needed): a request for the public list of Feinwerk plugins with the
interface language, and the plugin images afterwards. No instance token and no data about your websites or users.

When you click "Connect with Feinwerk account", your browser opens plugins.feinwerk.io with the address of this Matomo,
the interface language and whether the connector came from the Matomo Marketplace or from the Feinwerk store.

Once connected, the connector talks to plugins.feinwerk.io:

- **Once a day**, to check for updates and licenses: the instance token, the Matomo and PHP version, the address of this
  Matomo, the number of Matomo users (for license seats) and the installed Feinwerk plugins with their versions.
- **When you use the Feinwerk Plugins screen or its console commands** (open it, refresh the list, open a plugin,
  install, update, use or buy a license, start a free trial): the instance token, the Matomo and PHP version and the
  plugin concerned.
- **After a Matomo major update**: the installed Feinwerk plugins, to find their builds for the new version.
- **When you answer the feedback question or send a bug report**: only what the form shows. A feedback vote carries
  no instance token.

No visitor data and no reports are ever sent. Every request carries a fixed user agent instead of your browser's;
Matomo's HTTP client adds the Matomo version to it. Once connected, requests also name the version of the connector.
Recommendations and notices are built from the plugin list stored in this Matomo and send nothing.

## Requirements

- Matomo 5.0 or newer
- PHP 8.1 or newer
- An account on [plugins.feinwerk.io](https://plugins.feinwerk.io) (free plugins need no license)
- Outgoing HTTPS (port 443) from the Matomo server to `plugins.feinwerk.io`, the only server the connector contacts.
  Behind a firewall, allow that host name; a proxy is taken from `[proxy]` in Matomo's `config/config.ini.php`.
  Details: [Firewall and proxy](https://plugins.feinwerk.io/en/docs/connector#firewall).

## Installation

1. In Matomo, open **Administration › Platform › Marketplace**, search for "Feinwerk", and install and activate
   Feinwerk Marketplace Connector.
   This is the easiest way: no file upload, no FTP, no change to config files.
   Without the Matomo Marketplace (for example when the Matomo server has no internet access to it), upload the ZIP from
   [plugins.feinwerk.io/connector/download](https://plugins.feinwerk.io/connector/download) under
   **Administration › Plugins › Upload a plugin**.
2. Open **Administration › Feinwerk Plugins** and click **Connect with Feinwerk account**.

Updates of the connector arrive both ways: in the Matomo Marketplace like any other plugin, and on the Feinwerk
Plugins screen with one click. Both install the same version, so use whichever comes first.

Details: [the connector guide](https://plugins.feinwerk.io/en/docs/connector).

## Console commands

Run them like Matomo's own commands, for example `./console feinwerkmarketplaceconnector:status`:

- `feinwerkmarketplaceconnector:status [--json]`: installed and latest version, status and license of each Feinwerk plugin.
- `feinwerkmarketplaceconnector:refresh`: loads the plugin list from plugins.feinwerk.io again.
- `feinwerkmarketplaceconnector:update <plugin>... | --all`: updates plugins with the same checks as the Update button.
  `--all` leaves out the connector itself; name it to update it. `--core-update` also runs Matomo's database update.
- `feinwerkmarketplaceconnector:install <plugin>... [--no-activate]`: installs and activates plugins.
- `feinwerkmarketplaceconnector:connect --token-file=<file>`: connects this Matomo with the instance token from your
  account (`--token-file=-` reads it from standard input). The token is never shown.

Plugins are named by their technical name, for example `FeinwerkSearchReport`. The commands end with exit code 1 when
something needs your attention.

## Support

support@feinwerk.io

## License

GPL v3 or later, see the LICENSE file.

## Author

Peter Boehlke, [Feinwerk.io](https://feinwerk.io)
