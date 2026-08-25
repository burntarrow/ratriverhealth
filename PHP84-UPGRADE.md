# Rat River Health: PHP 8.4 deployment guide

This repository has been upgraded to a WordPress and custom-code baseline that can be statically checked on PHP 8.4. Complete the following deployment steps on a staging copy before changing production.

## 1. Create recoverable backups

- Export the complete multisite database.
- Back up `wp-content/uploads`, any server-only plugins or themes, and the existing `wp-config.php`.
- Record the current PHP version and active network/site plugins.
- Confirm that the backup can be restored before proceeding.

## 2. Rotate exposed credentials

The previous repository history contained database credentials and WordPress authentication salts. Treat those historical values as compromised even though the current branch no longer contains them.

- Change the database user's password in the hosting control panel.
- Generate eight new WordPress authentication keys and salts.
- Expect all existing WordPress sessions to be logged out when the salts change.
- Do not commit the replacement values.

## 3. Create the local configuration

On the server, copy the example file and restrict its permissions:

```bash
cp wp-config.local.example.php wp-config.local.php
chmod 600 wp-config.local.php
```

Populate `wp-config.local.php` with the new database password and new WordPress keys/salts. The file is ignored by Git.

The same values can instead be supplied as environment variables named `DB_NAME`, `DB_USER`, `DB_PASSWORD`, `DB_HOST`, `AUTH_KEY`, `SECURE_AUTH_KEY`, `LOGGED_IN_KEY`, `NONCE_KEY`, `AUTH_SALT`, `SECURE_AUTH_SALT`, `LOGGED_IN_SALT`, and `NONCE_SALT`.

## 4. Deploy code and PHP together on staging

Do not load this upgraded code while the site is still running PHP 5.6. WordPress 7.1, the updated plugins, and the compatibility configuration target a modern PHP runtime.

On a staging clone:

1. Prevent public traffic or enable a maintenance page that does not bootstrap WordPress.
2. Deploy the repository and populated `wp-config.local.php` without overwriting `wp-content/uploads` or the database.
3. Switch the staging site to PHP 8.4 before loading WordPress.
4. Confirm that the host has the required PHP extensions, including MySQLi, cURL, DOM, EXIF, fileinfo, GD or Imagick, intl, mbstring, OpenSSL, and ZIP where available.

The code deployment and PHP switch should be treated as one cutover. A temporary mixed state—new code on PHP 5.6 or old code on PHP 8.4—is not a supported operating state.

## 5. Run the WordPress database upgrade

From the WordPress root with WP-CLI:

```bash
wp core update-db --network
wp cache flush
```

Without WP-CLI, sign in as a super administrator and complete the Network Upgrade screen when WordPress prompts for it.

## 6. Replace the Slider Revolution compatibility guard

The repository previously contained Slider Revolution 4.5.95. That package has syntax and removed-API failures on PHP 8.4, so it was replaced with a safe compatibility guard. Slider output remains intentionally disabled until a current licensed Slider Revolution package is installed.

- Obtain the current plugin package from the site's valid Slider Revolution licence/account.
- Replace `wp-content/plugins/revslider` with that package.
- Network-activate or site-activate it in the same scope used previously.
- Verify every page template and shortcode that uses a revolution slider.
- The existing slider records remain in the database, but make a database backup before allowing the new plugin to migrate them.

## 7. Complete staging verification

Review the PHP error log while checking:

- `https://archive.ratriverhealth.ca` or the staging equivalent;
- the home page and representative inner pages;
- English and French versions;
- Network Admin and individual site dashboards;
- Pages, Posts, Media, Widgets, Menus, and plugin screens;
- existing widget assignments and sidebar output;
- forms, donation links, media, search, redirects, scheduled tasks, permalinks, and logout/login;
- every site in the multisite network.

Keep `WP_DEBUG_DISPLAY` disabled. Temporarily enable `WP_DEBUG_LOG` only on staging when investigating an error, then remove the generated log.

## 8. Production cutover and rollback

After staging passes:

1. Put production into a maintenance window that does not load WordPress.
2. Take a fresh database backup.
3. Deploy the tested code and populated local configuration.
4. Switch production to PHP 8.4 before loading the upgraded WordPress code.
5. Run the network database upgrade and flush caches.
6. Complete a focused smoke test and inspect the server error log.

If a fatal error or data migration problem occurs, restore the previous PHP version and code together. Restore the database backup as well if the WordPress schema or plugin data was changed.

## Automated compatibility check

`.github/workflows/php84-compat.yml` runs PHP 8.4 syntax and compile-diagnostic checks across the custom theme and all committed plugins. It also blocks removed PHP APIs and legacy short tags, and runs PHPCompatibilityWP as an additional advisory scan.

A passing workflow establishes a static compatibility baseline; it does not replace a staging test against the production database, uploads, web-server configuration, and server-only extensions.
