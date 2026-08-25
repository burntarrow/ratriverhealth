# Rat River Health: PHP 8.4 deployment guide

This repository has been upgraded to a WordPress and custom-code baseline that can be statically checked on PHP 8.4. Complete the following deployment steps on a staging copy before changing the production PHP version.

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

## 4. Deploy the repository on the existing PHP version

Deploy the code before changing PHP. Confirm that:

- `https://archive.ratriverhealth.ca` loads.
- Network Admin and individual site dashboards load.
- English and French pages resolve correctly.
- Existing widgets remain assigned to their sidebars.
- Forms, donation links, media, menus, search, and permalinks work.

Do not overwrite `wp-content/uploads` or the production database.

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

## 7. Switch staging to PHP 8.4

Enable PHP 8.4 on staging, then repeat the functional checks above. Also review the PHP error log while loading:

- the home page and representative inner pages;
- English and French versions;
- Network Admin, Pages, Posts, Media, Widgets, Menus, and plugin screens;
- search, forms, redirects, scheduled tasks, and logout/login;
- all sites in the multisite network.

Keep `WP_DEBUG_DISPLAY` disabled. Temporarily enable `WP_DEBUG_LOG` only on staging when investigating an error, then remove the generated log.

## 8. Production cutover and rollback

After staging passes:

1. Put the production site into a brief maintenance window.
2. Take a fresh database backup.
3. Deploy the tested code and local configuration.
4. Run the network database upgrade.
5. Switch production to PHP 8.4.
6. Complete a focused smoke test and inspect the server error log.

If a fatal error or data migration problem occurs, revert the PHP version, restore the previous code, and restore the database backup if the schema or plugin data changed.

## Automated compatibility check

`.github/workflows/php84-compat.yml` runs PHP 8.4 syntax and compile-diagnostic checks across the custom theme and all committed plugins. It also blocks removed PHP APIs and legacy short tags, and runs PHPCompatibilityWP as an additional advisory scan.

A passing workflow establishes a static compatibility baseline; it does not replace a staging test against the production database, uploads, web-server configuration, and server-only extensions.
