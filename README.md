# Feinwerk Connector

## Description

Feinwerk Connector brings the Plugins for Matomo by Feinwerk into your Matomo administration. You find, install, update
and license them on one screen, **Administration › Feinwerk Plugins**, next to the Matomo Marketplace, which keeps
working as before.

- **Tasks:** everything that needs you, in one list: updates, database updates, licenses that run out.
- **Discover:** all Feinwerk plugins with description, screenshots and price.
- **Manage:** install and update with one click, or update several plugins at once.
- **Licenses:** the license of each paid plugin; use an existing license or buy one.
- **Automatic updates** for the plugins you choose. Updates that change the database are never installed
  automatically, and on large Matomo instances the connector shows the console command for the database update.
- **Matomo major updates:** after Matomo moves to a new major version, the connector switches your Feinwerk plugins
  to their builds for that version.

Available in English, German, French, Spanish and Italian.

### What is sent to Feinwerk, and when

Nothing is sent before you connect this Matomo with your account on plugins.feinwerk.io, with the button
"Connect with Feinwerk account" or by entering the instance token under General settings, or ask to see the plugins
first.

When you click "Show Feinwerk plugins" (no account needed): a request for the public list of Feinwerk plugins with the
interface language, and the plugin images afterwards. No instance token and no data about your websites or users.

When you click "Connect with Feinwerk account", your browser opens plugins.feinwerk.io with the address of this Matomo,
the interface language and whether the connector came from the Matomo Marketplace or from the Feinwerk store.

Once connected, the connector talks to plugins.feinwerk.io:

- **Once a day**, to check for updates and licenses: the instance token, the Matomo and PHP version, the address of this
  Matomo, the number of Matomo users (for license seats) and the installed Feinwerk plugins with their versions.
- **When you use the Feinwerk Plugins screen** (open it, refresh the list, open a plugin, install, update, use or buy a
  license): the instance token, the Matomo and PHP version and the plugin concerned.
- **After a Matomo major update**: the installed Feinwerk plugins, to find their builds for the new version.
- **When you answer the feedback question or send a bug report**: only what the form shows. A feedback vote carries
  no instance token.

No visitor data and no reports are ever sent. Every request carries a fixed user agent instead of your browser's;
Matomo's HTTP client adds the Matomo version to it. Recommendations and notices are built from the plugin list stored in this
Matomo and send nothing.

## Requirements

- Matomo 5.0 or newer
- PHP 8.1 or newer
- An account on [plugins.feinwerk.io](https://plugins.feinwerk.io) (free plugins need no license)
- Outgoing HTTPS (port 443) from the Matomo server to `plugins.feinwerk.io`, the only server the connector contacts.
  Behind a firewall, allow that host name; a proxy is taken from `[proxy]` in Matomo's `config/config.ini.php`.
  Details: [Firewall and proxy](https://plugins.feinwerk.io/en/docs/connector#firewall).

## Installation

1. In Matomo, open **Administration › Plugins › Marketplace**, search for Feinwerk Connector, install and activate it.
   Without the Matomo Marketplace, upload the ZIP from
   [plugins.feinwerk.io/connector/download](https://plugins.feinwerk.io/connector/download) under
   **Administration › Plugins › Upload a plugin**.
2. Open **Administration › Feinwerk Plugins** and click **Connect with Feinwerk account**.

Details: [the connector guide](https://plugins.feinwerk.io/en/docs/connector).

## Support

support@feinwerk.io

## License

GPL v3 or later, see the LICENSE file.

## Author

Peter Boehlke, [Feinwerk.io](https://feinwerk.io)
