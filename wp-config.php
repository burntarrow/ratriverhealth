<?php
/**
 * Rat River Health WordPress bootstrap configuration.
 *
 * Secrets are intentionally loaded from an ignored local file or environment
 * variables. Copy wp-config.local.example.php to wp-config.local.php on the
 * server and populate it before deploying this branch.
 */

$rrh_local_config = __DIR__ . '/wp-config.local.php';
if (is_readable($rrh_local_config)) {
    require $rrh_local_config;
}

/**
 * Define a configuration constant from an environment variable when it has not
 * already been supplied by wp-config.local.php.
 *
 * @param string $name    Constant/environment variable name.
 * @param mixed  $default Fallback value.
 */
function rrh_define_config(string $name, $default = ''): void
{
    if (defined($name)) {
        return;
    }

    $value = getenv($name);
    define($name, false !== $value && '' !== $value ? $value : $default);
}

rrh_define_config('DB_NAME');
rrh_define_config('DB_USER');
rrh_define_config('DB_PASSWORD');
rrh_define_config('DB_HOST', 'localhost');
rrh_define_config('DB_CHARSET', 'utf8mb4');
rrh_define_config('DB_COLLATE', '');

$rrh_secret_constants = array(
    'AUTH_KEY',
    'SECURE_AUTH_KEY',
    'LOGGED_IN_KEY',
    'NONCE_KEY',
    'AUTH_SALT',
    'SECURE_AUTH_SALT',
    'LOGGED_IN_SALT',
    'NONCE_SALT',
);

foreach ($rrh_secret_constants as $rrh_secret_constant) {
    rrh_define_config($rrh_secret_constant);
}

$rrh_missing_configuration = array();
foreach (array_merge(array('DB_NAME', 'DB_USER', 'DB_PASSWORD'), $rrh_secret_constants) as $rrh_required_constant) {
    if ('' === (string) constant($rrh_required_constant)) {
        $rrh_missing_configuration[] = $rrh_required_constant;
    }
}

if ($rrh_missing_configuration) {
    $rrh_message = 'Rat River Health configuration is incomplete. Copy wp-config.local.example.php to wp-config.local.php and populate the required values.';

    if ('cli' === PHP_SAPI) {
        throw new RuntimeException($rrh_message);
    }

    http_response_code(503);
    exit($rrh_message);
}

unset(
    $rrh_local_config,
    $rrh_secret_constants,
    $rrh_secret_constant,
    $rrh_missing_configuration,
    $rrh_required_constant,
    $rrh_message
);

$table_prefix = 'wp_';

rrh_define_config('WP_ENVIRONMENT_TYPE', 'production');

if (!defined('WP_HOME')) {
    define('WP_HOME', 'https://archive.ratriverhealth.ca');
}
if (!defined('WP_SITEURL')) {
    define('WP_SITEURL', 'https://archive.ratriverhealth.ca');
}

if (!defined('WP_DEBUG')) {
    define('WP_DEBUG', 'production' !== WP_ENVIRONMENT_TYPE);
}
if (!defined('WP_DEBUG_LOG')) {
    define('WP_DEBUG_LOG', WP_DEBUG);
}
if (!defined('WP_DEBUG_DISPLAY')) {
    define('WP_DEBUG_DISPLAY', false);
}
@ini_set('display_errors', WP_DEBUG_DISPLAY ? '1' : '0');

if (!defined('WP_MEMORY_LIMIT')) {
    define('WP_MEMORY_LIMIT', '1024M');
}

if (!defined('WP_ALLOW_MULTISITE')) {
    define('WP_ALLOW_MULTISITE', true);
}
if (!defined('MULTISITE')) {
    define('MULTISITE', true);
}
if (!defined('SUBDOMAIN_INSTALL')) {
    define('SUBDOMAIN_INSTALL', false);
}
if (!defined('DOMAIN_CURRENT_SITE')) {
    define('DOMAIN_CURRENT_SITE', 'archive.ratriverhealth.ca');
}
if (!defined('PATH_CURRENT_SITE')) {
    define('PATH_CURRENT_SITE', '/');
}
if (!defined('SITE_ID_CURRENT_SITE')) {
    define('SITE_ID_CURRENT_SITE', 1);
}
if (!defined('BLOG_ID_CURRENT_SITE')) {
    define('BLOG_ID_CURRENT_SITE', 1);
}

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

require_once ABSPATH . 'wp-settings.php';
