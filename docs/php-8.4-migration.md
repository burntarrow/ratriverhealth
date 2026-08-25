# Rat River Health: PHP 8.4 migration

This branch is an isolated modernization of the archived WordPress site. Do not deploy it directly over the working PHP 5.6 archive.

## Changes performed on the branch

- Replace the WordPress 3.8-era core with the current stable WordPress core while preserving `wp-content`, `wp-config.php`, uploads, and site-specific files.
- Refresh plugins and themes only when their directory slug maps to a valid package from WordPress.org. Custom and commercial packages are not overwritten by that job.
- Convert straightforward PHP curly-brace string/array offsets to bracket offsets because curly-brace offsets are not valid on PHP 8.
- Run every tracked PHP file through `php -l` using PHP 8.4.
- Run PHPCompatibilityWP against themes, plugins, and must-use plugins and retain the complete report as a workflow artifact.

## Required staging sequence

1. Back up the database and the complete files directory.
2. Clone the production archive to a separate staging hostname and database.
3. Deploy the compatibility branch to staging.
4. Confirm that PHP 8.4 has MySQLi, mbstring, cURL, DOM, XML, ZIP, GD or Imagick, and Intl enabled.
5. Visit `/wp-admin/upgrade.php` once as a network administrator so WordPress can perform its database upgrades.
6. Review the PHP 8.4 validation workflow. Update, replace, or disable any commercial/custom extension that still produces fatal or compatibility errors.
7. Test multisite domain mapping, network login, media, forms, email, search, menus, scheduled tasks, redirects, and every public template.
8. Keep the original PHP 5.6 archive and database backup until the staging copy has passed acceptance testing.

## Rollback

A code rollback alone is not sufficient after WordPress has upgraded the database. Restore both the pre-upgrade files and the matching pre-upgrade database snapshot.
