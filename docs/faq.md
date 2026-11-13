## FAQ

__Does the connector replace the Matomo Marketplace?__

No. It adds the screen **Administration › Feinwerk Plugins** for the Plugins for Matomo by Feinwerk. The Matomo
Marketplace and its plugins keep working as before.

__How do I connect my Matomo?__

Open **Administration › Feinwerk Plugins** and click **Connect with Feinwerk account**. You log in on
plugins.feinwerk.io, pick or create the instance for this Matomo and come back connected. If your browser cannot reach
plugins.feinwerk.io, copy the instance token from your account into **General settings › Instance token**.

__Can I see the plugins before I create an account?__

Yes. Click **Show Feinwerk plugins** on **Administration › Feinwerk Plugins**. The connector loads the public list from
plugins.feinwerk.io, without an account and without data about your websites or users.

__What is sent to Feinwerk?__

Nothing before you connect, except the request for the public list when you click Show Feinwerk plugins. Afterwards: once a day the instance token, the Matomo and PHP version, the address of this
Matomo, the number of users and the installed Feinwerk plugins with their versions; when you use the screen, the plugin
concerned. No visitor data and no reports. The plugin description lists it in full.

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
database updates (or `--core-update`).

__Do I need a license?__

Free Feinwerk plugins need none. Paid plugins need a license for this Matomo instance; the **Licenses** tab shows it,
and you can use an existing license of your account or buy one there.

__What happens when I update Matomo to a new major version?__

Before the update, the connector tells you which of your Feinwerk plugins already exist for that version; make a full
backup of files and database first. After the update, it installs the builds of your Feinwerk plugins for the new
version and switches on again what was active before. Plugins without a build yet follow as soon as they are released.

__An update says it needs a database update. What do I do?__

Run it on the Matomo update page that opens, or on large instances on the console with the command the connector
shows. Updates that change the database are never installed automatically.

__How do I uninstall the connector?__

Deactivate and uninstall `FeinwerkMarketplaceConnector` under **Administration › Plugins**. The Feinwerk plugins
you installed stay installed. Paid Feinwerk plugins check their license through the connector, so keep it while you use
them.

__Where do I get help?__

Write to support@feinwerk.io or read [the connector guide](https://plugins.feinwerk.io/en/docs/connector).
