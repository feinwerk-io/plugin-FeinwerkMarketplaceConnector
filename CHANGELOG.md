# Changelog

## 5.4.0

- Console commands: `feinwerkmarketplaceconnector:status`, `refresh`, `update`, `install` and `connect`. They update and
  install Feinwerk plugins with the same checks as the buttons on the Feinwerk Plugins screen, for example on large
  Matomo instances or in scripts.
- The feedback question at the end of the screen is one quiet row under a fine line, with the support link at its end.

## 5.3.1

- A plugin category without a text in this connector shows its name instead of a technical message on the card
  (Tracking Router showed "The string ... was not loaded").
- New category text "Tracking".

## 5.3.0

- Before you connect, "Show Feinwerk plugins" lists all Feinwerk plugins with description and price, and notes the ones
  not yet available for your Matomo version. The list comes from plugins.feinwerk.io without an account and without data
  about your websites or users; a card opens the plugin's page there.
- Requests to plugins.feinwerk.io no longer carry your browser's user agent.
- Prices follow the number format of your Matomo language (for example 1.299,00 € in German).
- Documentation: which address a firewall must allow (only plugins.feinwerk.io, HTTPS) and how a proxy is used.

## 5.2.3

- Description, documentation and screenshots for the Matomo Marketplace, including which data the connector sends to
  plugins.feinwerk.io and when. Before you connect this Matomo, the connector sends nothing, plugin images included.
- The feedback question now takes a single compact line.
- After the connector is activated, a short notice shows where to connect this Matomo with your Feinwerk account.
- The settings section is now called "Feinwerk Connector", the name on the Matomo Marketplace.
- Uninstalling the connector now also removes the data it stored in Matomo; deactivating it keeps everything.
- The connector now speaks French, Spanish and Italian as well.

## 5.2.2

- Fixed: on some Matomo installations the plugins updated with "Update all" could stay in the list until the page was
  loaded again, and updating them a second time ended with an error. The list now takes the installed version from the
  plugin's own files, and an update of a plugin that is already at that version is reported as done.
- After a Matomo major update, the notice no longer calls a plugin "not available yet" once the refreshed plugin list
  offers its new version; the connector switches it with its next run or with "Switch now".

## 5.2.1

- Fixed: after a Matomo major update, a plugin that could not be switched to its new build because its license had
  lapsed (or because its install failed several times) stayed switched off once you had renewed the license and
  updated it on the Feinwerk Plugins screen. If it was active before the Matomo update, the update now switches it on
  again, as the connector does for the plugins it switches itself, and its reports for the days it was off (at most the
  last 31) are rebuilt by the next archiving run.
- While the connector switches your plugins after a Matomo update, their status on Manage and on the Discover cards
  says "Switch pending" instead of "Update available".
- The buttons "Buy a license" and "Use an existing license" keep their whole label on narrower screens.
- The help text of "Show notes on other Feinwerk offers" says that it applies to the other Feinwerk plugins; the
  recommendations of this plugin have their own setting.

## 5.2.0

- New setting "Show notes on other Feinwerk offers" under General settings: Feinwerk plugins may show administrators a
  short note on other Feinwerk offers, and unticking the setting switches these notes off in all Feinwerk plugins at
  once. `promotions = 0` in the section `[FeinwerkMarketplaceConnector]` of config.ini.php does the same and takes
  precedence over the setting; it also hides the recommendations of this plugin (Administration home and Discover).

## 5.1.4

- In a German Matomo, the note a plugin's author gives about its database update is shown in German when the plugin
  provides a German text (the update dialog on Manage and in the plugin window).
- Wide tables (Manage, Licenses and the versions in the plugin window) scroll sideways inside their box on narrow
  screens instead of running past it.

## 5.1.3

- Discover shows the whole description on every card; since 5.1.0 they were cut off after three lines. The same for the
  text of the recommendation.
- Recommendations can be switched off right in the banner: "Turn off recommendations" does it with one click, and the
  setting "Show recommendations" under General settings switches them on again.
- The Feinwerk Plugins screen stays current: when you come back to a page that was left open and something has changed
  in the meantime, for example a database update that ran on the server, it loads itself again. If a plugin window, a
  dialog or a search is open, a note offers to reload instead.
- Fixed: the notice asking for the database update stayed after the update of the Feinwerk plugin had run, as long as
  an update of Matomo itself or of another plugin was still pending.
- An automatic update that was held back because it changes the database is forgotten once you have installed that
  version yourself.

## 5.1.2

- Fixed: a plugin installed but not activated yet (for example installed for activation on the server) still showed
  "Install". It now shows as installed with the badge "Not activated" and an "Activate" button, which uses Matomo's own
  activation. The same applies to a plugin you switched off.
- An automatic update keeps a plugin switched on or off as it was. It used to switch on a plugin you had switched off.

## 5.1.1

- The notices after a switch to a new Matomo major version name the new place of the Feinwerk Plugins screen
  (Administration, Feinwerk Plugins, Manage).

## 5.1.0

- New: the connector has its own section "Feinwerk Plugins" in Matomo's administration with Tasks, Discover, Manage and
  Licenses. The links you used so far keep working.
- New: Tasks lists everything that needs you, each with its button: updates, an update held back because it changes the
  database, a pending database update, a license that is missing or ends within 30 days, an unused license, and a store
  that cannot be reached or rejects the instance token. The menu entry shows how many there are.
- New: update several plugins at once. On Manage you can select updates or update all of them, on Tasks update all.
  The plugins are updated one after the other; if one fails, the others still run, and a summary follows at the end.
  An update that changes the database comes last, and Matomo's database update follows as usual.
- New: Licenses shows which paid Feinwerk plugins are licensed for this Matomo, with the buttons to use an existing
  license or buy one, and a link to your customer account for subscriptions and invoices.
- New: a recommendation for a Feinwerk plugin that this Matomo does not have yet, on the Administration home page and on
  Discover. Nothing is sent for it. You can close each recommendation, and the setting "Show recommendations" under
  General settings turns them off.
- Discover: the search also covers categories, plugins can be sorted by name or by the latest release, the number of
  hits is shown, and an empty result offers to reset the search. The cards work with the keyboard.
- The browser's Back button closes an open plugin window.
- An update no longer switches on a plugin that you had switched off.
- Fixed: a plugin in the list of automatic updates that the connector handed over to you after a Matomo major update was
  missing from the "Updates available" notice.

## 5.0.0

- New version numbering: the connector now carries the number of the Matomo version it is built on, so 5.0.0 follows
  1.2.1. It still runs on Matomo 5 and on the next Matomo major version. Feinwerk plugins switch to the same numbering
  with their next release (the major version of the Matomo they are built for); the connector offers these as ordinary
  updates.
- New: under the Feinwerk Plugins screen a short question with thumbs up and down, one for each tab, and a link to the
  Feinwerk support. Your Matomo server sends the answer to Feinwerk with the connector and Matomo version, the browser
  family and version and the interface language, nothing about you, your instance or your visitors. To switch it off,
  add feedback = 0 to the section [FeinwerkMarketplaceConnector] of config/config.ini.php. With
  enable_internet_features = 0 in Matomo's section [General] nothing is sent either.
- New: on a large Matomo instance, the page that asks for the database update on the command line now also offers
  "Run in the browser anyway" to super users without access to the command line. After a confirmation the update runs
  once in the browser, as in Matomo itself. A second click while it runs starts no second update.
- New setting "Show information notices" under General settings, on by default. Switched off, the notices that only
  inform no longer show: which Feinwerk plugins already exist for the next Matomo version, with the reminder to back up
  before that update, and which updates are available (they still show in the menu and on the Manage tab). Warnings and everything you need to act on always show.
- When the security check of an installation or update fails, for example because the page was open for too long, you
  now stay on the Feinwerk Plugins screen with a notice to reload the page, instead of Matomo's error page. Nothing is
  installed in that case.
- The connector's settings under General settings are shown in the language of your Matomo.

## 1.2.1

- Fixed: the warning after an installation that names the database update (core:update) or the activation on the
  command line stayed until it was closed by hand. It now disappears by itself on the next page once the update has run
  or the plugin is active. After the database update a short notice confirms it and links back to the Feinwerk Plugins
  screen.
- Fixed: from the second plugin window you opened on, an empty grey button appeared at the bottom right of the
  window. Matomo turned the screenshot thumbnails of the previous plugin into empty buttons. The thumbnails are now
  real buttons, which also work with the keyboard.
- After an installation, an update, a license action or "Refresh" you are back where you started: on the same tab, and
  in the plugin's window when you clicked there.
- Buttons that install, update or act on a license show "Please wait" once clicked. A second click, also on another
  button, sends nothing, so a double click no longer ends in Matomo's security check error. Coming back to the screen
  with the browser's back button loads it again.
- If the Feinwerk store answers with something that is not a plugin list (for example a maintenance page in between),
  the screen keeps showing the last good list instead of an empty one. The same applies to the plugin window.

## 1.2.0

- New: on a large Matomo instance the database update no longer starts in the browser while an update of a Feinwerk
  plugin is pending, also not for new or changed columns of the visit data tables. Matomo's update page still shows,
  but its button leads to a page with the command for the server (./console core:update) and "Check again". That
  avoids the "Bad Gateway" error of a long update in the browser. A Matomo update pending at the same time also runs
  on the server then. Matomo's Marketplace, its one-click update and the check for new Matomo versions stay as they
  are.
- Without access to the command line you can switch this off with large_instance = 0 in the section
  [FeinwerkMarketplaceConnector] of config/config.ini.php. Updates of Matomo itself or of other vendors' plugins
  alone are not affected.
- The connector no longer recommends enable_auto_update = 0 in config/config.ini.php. Besides the database update in
  the browser, this switch also turns off installing and updating plugins through Matomo's own Marketplace, Matomo's
  one-click update and the check for new Matomo versions. The lasting notice on large instances is gone, and so is the
  sentence after an installation. If you set the switch only because of this notice and miss these functions, you can
  remove it again.
- On a large instance the warning before an installation or update still shows the way over the command line
  (./console core:update) and asks you to confirm it. It now also says not to start the update on Matomo's own update
  page that opens after the installation.
- Plugin names are written with spaces wherever you read them: in the notices, on the Feinwerk Plugins screen and in
  the list of automatic updates under General settings, also for a plugin installed as a development checkout. The
  connector uses the name the Feinwerk store shows; without one it writes the technical name as words, for example
  "Feinwerk Search Report".
- Under General settings the connector's section is headed "Feinwerk Marketplace Connector" instead of its technical
  name.

## 1.1.0

- New: "Use an existing license". If your Feinwerk account has a license for a plugin that is not in use yet, the
  plugin's row and its window on the Feinwerk Plugins screen offer it. One click and the license covers this Matomo.
- New: "Buy a license". For a paid plugin without a license, the button opens the Feinwerk store, signed in with the
  account this Matomo is connected to (sign in first if needed). After the payment, "Back to Matomo" brings you here,
  the license covers this Matomo and the plugin window offers the installation.
- The store now assigns a license by itself when it is clear which Matomo is meant, for example your only production
  instance or the only one that has the plugin installed. The License column also shows licenses assigned in the
  customer account.
- Test and staging instances: mark a Matomo as a test instance when you connect it or in your customer account. It
  then uses the licenses of your account without a seat of its own, for up to three test instances per account.
- If this server cannot reach the store at all, the screen says so and links to the guide for installing plugins as
  ZIP files from the customer account. Other errors still show the note about cached data.
- The new buttons appear only with a store that offers them; with an older store the screen works as before.

## 1.0.1

- New: on a large Matomo instance that still runs database updates in the browser, the plugin pages (Feinwerk
  Plugins, Manage Plugins, Marketplace) show a lasting notice: set enable_auto_update = 0 in config/config.ini.php so
  Matomo does not start a database update by accident in the browser, where it can fail with "Bad Gateway", and run
  database updates, also those of Matomo itself, on the command line. The notice disappears once the switch is set.
- The notice after an installation that needs a database update names this switch too on large instances.

## 1.0.0

The connector is now considered stable for production use.

- New: before an installation or update that changes the database, a warning window opens. It names the database
  update and any new columns in Matomo's visit data tables, says that this can take a long time on a large database
  and asks for a database backup. Only the button in this window installs.
- New: on a large Matomo instance the warning explains how to run the database update on the command line
  (./console core:update) and how to keep Matomo from starting it in the browser (enable_auto_update = 0), where it
  can fail with "Bad Gateway". It has to be confirmed with a tick. A new plugin that adds columns to the visit data
  tables is then installed without being activated, to be activated on the command line.
- After an installation that needs a database update, the notice names the command line way on large instances.
- Automatic updates now also hold back updates that add columns to the visit data tables, not only updates with
  database update files.
- The Manage tab marks installations and updates that change the database with "Database update".

## 0.8.4

- Fixed: since 0.8.3 the license check of Conversions API, AuthSaml, SearchReport and TrackingSpamScore could no
  longer read the marketplace address from the connector. The settings page of Conversions API showed an error, and
  the other plugins treated the connector as not set up. The address is available to them again.

## 0.8.3

- New: "Connect with Feinwerk account". On a Matomo that is not connected yet, the Feinwerk Plugins screen shows this
  button. You sign in at plugins.feinwerk.io, choose the instance for this Matomo (the store suggests the one that
  matches its address) or create a new one, and you are back in Matomo, connected. No token to copy.
- When the marketplace rejects the stored token, the notice on the screen offers "Connect again".
- The address of the Feinwerk Plugins marketplace is now built into the connector. In the settings only the
  instance token is left, for Matomo installations whose browser cannot reach plugins.feinwerk.io.

## 0.8.2

- After "Refresh", the plugin details window shows a new release right away instead of up to an hour later.
- The daily update check and the switch after a Matomo major update also work on Matomo installations without a
  stored Matomo URL (for example on localhost). Before, the marketplace rejected these requests from scheduled tasks
  without any message.
- When the daily update check, an automatic update or the switch fails, the output of the archiving run shows a notice
  with the reason (and the Matomo log, if its log level is INFO or DEBUG). It is not logged as a warning, so the
  archiving cron job does not report an error because of it.
- The notice after the switch to a new Matomo major version says when a plugin was left switched off because it was
  already off before the update, instead of saying that this is not known.
- A plugin without a valid license for this Matomo instance is no longer retried during the switch. The notice names
  the missing license and where to renew it (plugins.feinwerk.io); after that you update the plugin on the Feinwerk
  Plugins screen.
- Right after an update to a new Matomo major version, Matomo no longer shows its own red error that a Feinwerk plugin
  "could not be loaded as it has missing dependencies" while the connector switches that plugin, whatever language you
  use Matomo in. The connector's notice explains the switch. Messages about other plugins stay.
- The notice about Feinwerk plugins for the next Matomo major version now asks for a full backup of the files and the
  database before the update: it is the only way back to the current version.
- After the switch, the daily reports of the plugins it turned back on are rebuilt for the days since the Matomo update
  (at most 31 days) by the next archiving run, unless re-archiving is switched off in Matomo. Because of a current
  Matomo limitation, the week, month and year that are still running can miss these days again; daily reports and
  completed weeks and months are correct. For plugins you turn on yourself later, run
  `./console core:invalidate-report-data --dates=<update day>,<today> --sites=all` and then `./console core:archive`.
- Right after an update to a new Matomo major version, the Feinwerk Plugins screen no longer offers updates for the plugins
  the connector is still switching (the offer could still be the version for the previous Matomo); their row says
  "Switch pending". Plugins the connector hands over to you (no valid license, or switching failed several times) keep
  their update button.
- Success and error messages after installing or updating a plugin are shown in the language of your Matomo and name the
  plugin. When the marketplace refuses the download, the message asks you to check the license and the instance token.

## 0.8.1

- After an update to a new Matomo major version, the connector keeps switching the plugins that are not switched yet:
  every hour on the first day, then once a day. This now also holds while the marketplace cannot be reached; before,
  the connector kept trying every hour.
- The switch no longer starts a second time when an admin page is opened at the moment it finishes.
- The notice after the switch names every plugin only once, with its latest status.
- The notice that shows which Feinwerk plugins are already available for the next Matomo major version stays visible
  when the marketplace is briefly unreachable, checks the current plugin list instead of the one from the day before, and
  leaves out plugins installed as a git checkout or symlink (the connector never switches those).
- Empty cells on the Manage tab show "-", like Matomo does.

## 0.8.0

- Works with Matomo 5 and the next Matomo major version. Install this version before you update Matomo to a new major
  version. Older connector versions are switched off by the Matomo update.
- After an update to a new Matomo major version, the connector switches your Feinwerk plugins to their versions for the
  new Matomo by itself and turns back on the ones that were active before. Plugins that are not available yet follow
  as soon as they are released; "Switch now" starts it right away. If the connector cannot tell which plugins were
  active (because an older connector was installed during the Matomo update), it installs the new versions and leaves
  switching them on to you.
- If a plugin cannot be switched after several attempts, a notice asks you to update it on the Feinwerk Plugins screen.
- A notice shows which of your Feinwerk plugins are already available for the next Matomo major version.

## 0.7.4

- The plugin pictures on the cards are shown in full (8:5) instead of being cropped at the top and bottom.

## 0.7.3

- The "Feinwerk Plugins" menu entry moved from Administration → System to Administration → Plugins,
  next to Matomo's own plugin management and Marketplace.

## 0.7.2

- Automatic updates never change the database any more: an update that brings a database change is held
  back and shown as a warning (notice on the plugin pages and a "Database update" badge on the Manage tab),
  so an admin installs it and Matomo runs the database update in a controlled way.

## 0.7.1

- The plugin pictures now fill their card instead of sitting as a small strip with empty bars beside them,
  in light and dark mode; the detail window shows them in their own format.

## 0.7.0

- New: the connector can update itself. When a new version is available, the Feinwerk Plugins screen shows
  a notice with an update button. It never updates itself automatically.
- New: the plugins for automatic updates are now picked from a list instead of typed in.
- Safety: plugins installed as a git checkout or symlink (development setups) are never updated or
  overwritten through the connector, neither by a click nor automatically.

## 0.6.6

- New: the Feinwerk Plugins screen, plugin details and screenshot view support Matomo's dark mode.

## 0.6.5

- Fixed: a failed refresh of the plugin list was not shown. The screen now says when it shows cached data.
- New: when the marketplace rejects the instance token, the screen and the plugin details say so and point
  to the plugin settings, instead of reporting the marketplace as unreachable.
- Faster: a refresh against an unreachable marketplace no longer waits for a second timeout.

## 0.6.4

- Fixed: the notice about available plugin updates never appeared. It now shows on the Feinwerk Plugins,
  Plugins and Marketplace admin pages for super users (plugins set to update automatically are left out).

## 0.6.3

- Fixed: enlarged screenshots in the plugin details were cut off by the dialog; they now open full-screen.
- Fixed: pressing Escape in the enlarged screenshot view closed the whole plugin dialog instead of just the screenshot.

## 0.6.2

- Removed: the automatic takeover from the old `SonoraMarketplaceConnector`. Upgrading from the old
  connector now requires re-entering the marketplace URL and instance token in the plugin settings.
- Cleaned up internal diagnostics from security-check error messages.

## 0.6.1

- Fixed: switching from the previous connector could fail with an SQL error on Matomo databases without a table prefix.

## 0.6.0

- The connector is now published by Feinwerk.io and has been renamed from `SonoraMarketplaceConnector` to
  `FeinwerkMarketplaceConnector`; the admin screen is now called "Feinwerk Plugins".
- Upgrading: download the connector from your Feinwerk Plugins account, unzip it into Matomo's `plugins/`
  directory and activate `FeinwerkMarketplaceConnector`. It keeps the marketplace URL, the instance token and
  the cached plugin list, and removes the old connector automatically.
