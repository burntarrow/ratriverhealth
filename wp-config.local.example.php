<?php
/**
 * Local secrets for Rat River Health.
 *
 * Copy this file to wp-config.local.php on the server. The copied file is
 * ignored by Git and must never be committed.
 */

define('DB_NAME', 'replace-with-database-name');
define('DB_USER', 'replace-with-database-user');
define('DB_PASSWORD', 'replace-with-database-password');
define('DB_HOST', 'localhost');

/* Generate fresh values at https://api.wordpress.org/secret-key/1.1/salt/ */
define('AUTH_KEY',         'replace-with-a-unique-random-value');
define('SECURE_AUTH_KEY',  'replace-with-a-unique-random-value');
define('LOGGED_IN_KEY',    'replace-with-a-unique-random-value');
define('NONCE_KEY',        'replace-with-a-unique-random-value');
define('AUTH_SALT',        'replace-with-a-unique-random-value');
define('SECURE_AUTH_SALT', 'replace-with-a-unique-random-value');
define('LOGGED_IN_SALT',   'replace-with-a-unique-random-value');
define('NONCE_SALT',       'replace-with-a-unique-random-value');

/* Optional local overrides. */
// define('WP_ENVIRONMENT_TYPE', 'development');
// define('WP_DEBUG', true);
// define('WP_DEBUG_LOG', true);
// define('WP_DEBUG_DISPLAY', false);
