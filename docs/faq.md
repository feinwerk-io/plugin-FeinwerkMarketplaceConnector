## FAQ

__What does it cost?__

The connector is free, and so are the free Feinwerk plugins: they need no license. Paid plugins show their price on
their card and their plans in the plugin window, and need a license for this Matomo instance. A license covers one
production instance and up to three test or staging instances in the same account. You manage your licenses and
instances in your customer account on plugins.feinwerk.io.

__Does the connector replace the Matomo Marketplace?__

No. It adds the screen **Administration › Feinwerk Plugins** for the Plugins for Matomo by Feinwerk. The Matomo
Marketplace and its plugins keep working as before.

__Is it safe to install plugins this way?__

Every plugin comes over HTTPS from plugins.feinwerk.io and is checked against its SHA-256 checksum before Matomo's own
plugin installer puts it in place. Only super users can install, update or buy licenses. Updates that change the
database are never installed automatically, and you are only offered versions that fit your Matomo and PHP version.
As with every plugin, keep a backup before large updates.

__What data leaves my server?__

Nothing before you connect, except the request for the public plugin list when you click Show Feinwerk plugins. After
connecting: once a day the instance token, the Matomo and PHP version, the address of this Matomo, the number of users
and the installed Feinwerk plugins with their versions; when you use the screen, the plugin concerned. Never visitor
data or reports. The plugin description lists it in full.

__Can I look around before I create an account?__

Yes. Click **Show Feinwerk plugins** on **Administration › Feinwerk Plugins**. You see every plugin with description and
price, and which ones are not yet available for your Matomo version, without an account and without data about your
websites or users.

__Do I need an account?__

Only to install: a free account on plugins.feinwerk.io connects your Matomo. Click **Connect with Feinwerk account**,
log in or sign up, pick the instance, and you are back in Matomo.

__Does the connector slow down Matomo?__

No. It checks for updates once a day in Matomo's scheduled tasks and sends nothing beyond the cases listed in the
description. The Feinwerk Plugins screen loads only when an administrator opens it, and the connector does not touch
tracking.

__What happens when a license runs out?__

A license runs as long as its subscription. After a cancellation it stays valid until the end of the paid term. After
that, installed versions keep running technically, there are no more updates, and licensed features may switch off.
The Licenses tab and the Tasks list tell you 30 days before a license ends.

__Some Feinwerk plugins are also on the Matomo Marketplace. Which way should I install them?__

Either way: it is the same plugin. The Feinwerk Plugins screen recognises a plugin installed from the Matomo
Marketplace and offers its updates as well; new versions usually reach the Feinwerk Plugins screen first.

__How do I connect my Matomo?__

Open **Administration › Feinwerk Plugins** and click **Connect with Feinwerk account**. You log in on
plugins.feinwerk.io, pick or create the instance for this Matomo and come back connected. If your browser cannot reach
plugins.feinwerk.io, copy the instance token from your account into **General settings › Instance token**.

__My Matomo server is behind a firewall. What must it be allowed to reach?__

One address: outgoing HTTPS (port 443) from the Matomo server to `plugins.feinwerk.io`. The plugin list, updates,
licenses, plugin downloads and plugin images all come from there; the connector contacts no other server. Allow the
host name rather than an IP address, the address can change. Some Feinwerk plugins talk to further services (for
example Search Report to Google and Bing); the [connector guide](https://plugins.feinwerk.io/en/docs/connector#firewall)
lists them.

If your Matomo reaches the internet through a proxy, enter it under `[proxy]` in `config/config.ini.php`: the
connector uses Matomo's own HTTP client and its proxy settings.

The browsers of your administrators open `plugins.feinwerk.io` too, to connect this Matomo and to buy licenses. When
the server cannot reach it at all, see "Without internet access" in the
[connector guide](https://plugins.feinwerk.io/en/docs/connector#without-internet-access).

__Can I install and update the plugins on the command line?__

Yes. `./console feinwerkmarketplaceconnector:update --all` updates every Feinwerk plugin with an update,
`./console feinwerkmarketplaceconnector:install <plugin>` installs one, `./console feinwerkmarketplaceconnector:status`
shows the state. On large Matomo instances this is the recommended way, together with `./console core:update` for
database updates (or `--core-update`). All commands and options: the
[connector guide](https://plugins.feinwerk.io/en/docs/connector#console).

__What happens when I update Matomo to a new major version?__

Before the update, the connector tells you which of your Feinwerk plugins already exist for that version; make a full
backup of files and database first. After the update, it installs the builds of your Feinwerk plugins for the new
version and switches on again what was active before. Plugins without a build yet follow as soon as they are released.

__An update says it needs a database update. What do I do?__

Run it on the Matomo update page that opens, or on large instances on the console with the command the connector
shows. Updates that change the database are never installed automatically.

__How do I update the connector?__

Either way: Matomo shows the update in its Marketplace like for any other plugin, and the Feinwerk Plugins screen offers
it with one click. Both install the same version; once one has updated it, the other shows it as installed. The
connector never updates itself automatically.

__How do I uninstall the connector?__

Deactivate and uninstall `FeinwerkMarketplaceConnector` under **Administration › Plugins**. The Feinwerk plugins
you installed stay installed. Paid Feinwerk plugins check their license through the connector, so keep it while you use
them.

__Who is behind Feinwerk?__

Feinwerk is Peter Boehlke's plugin studio in Unterschleißheim near Munich, Germany. We build Plugins for Matomo,
nothing else. Write to support@feinwerk.io in English or German, or read the
[connector guide](https://plugins.feinwerk.io/en/docs/connector).
