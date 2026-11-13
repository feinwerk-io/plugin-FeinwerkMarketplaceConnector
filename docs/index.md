## Getting started

1. Install and activate Feinwerk Connector from the Matomo Marketplace (**Administration › Plugins › Marketplace**).
2. Open **Administration › Feinwerk Plugins** and click **Connect with Feinwerk account**. Log in on
   plugins.feinwerk.io, pick or create the instance for this Matomo, and you are back in Matomo, connected.
3. On **Discover**, open a plugin to read about it, then install it there or on **Manage**.

Want to look around before you create an account? Click **Show Feinwerk plugins** on the same screen: it lists all
Feinwerk plugins with description and price, and notes the ones not yet available for your Matomo version. A card opens
the plugin's page on plugins.feinwerk.io. To install, connect.

The four tabs of the Feinwerk Plugins screen:

- **Tasks** lists what needs you: updates, database updates, licenses that run out, a plugin that waits for its license.
- **Discover** shows all Feinwerk plugins. Click a card for the description, screenshots, versions and plans.
- **Manage** lists the Feinwerk plugins with their installed and latest version. Install or update one, or select
  several and update them at once.
- **Licenses** shows the license of each paid plugin. Use an existing license of your account, or buy one.

## Settings

Under **Administration › System › General settings › Feinwerk Connector**:

- **Instance token**: set by "Connect with Feinwerk account"; you can also paste it from your account.
- **Auto-apply updates for these plugins**: updated by the daily task. Updates that change the database are held back
  and shown on Tasks.
- **Show information notices**, **Show recommendations**, **Show notes on other Feinwerk offers**: switch these notes
  off if you do not want them.

In config.ini.php, section `[FeinwerkMarketplaceConnector]`: `feedback = 0` hides the feedback question,
`promotions = 0` hides all notes on other Feinwerk offers.

## Console

The same on the command line, for example on a large Matomo or in a script:

- `./console feinwerkmarketplaceconnector:status` lists the Feinwerk plugins (`--json` for scripts).
- `./console feinwerkmarketplaceconnector:update --all` updates every Feinwerk plugin with an update;
  `--core-update` runs Matomo's database update right after, when one is needed.
- `./console feinwerkmarketplaceconnector:install FeinwerkSearchReport` installs and activates a plugin.
- `./console feinwerkmarketplaceconnector:refresh` loads the plugin list again.
- `./console feinwerkmarketplaceconnector:connect --token-file=token.txt` connects this Matomo with the instance token
  from your account.

## Data

Before you connect, the connector sends nothing. What it sends afterwards, and when, is listed in the plugin
description under "What is sent to Feinwerk, and when".
