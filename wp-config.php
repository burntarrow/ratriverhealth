<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */
 
/**define(‘WP_TEMP_DIR’, ABSPATH . ‘wp-content/’);** //

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'ratriverhealth_website');

/** MySQL database username */
define('DB_USER', 'ratriverhealth_archive-user');

/** MySQL database password */
define('DB_PASSWORD', 'wzF=*r51zMDM6m,3');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Lar??|fX0Ya[H}`efKmmy=O03*q-]6{g|>y3(Xj7|.KTFPdMWB?F~i0`0zP<+z<(');
define('SECURE_AUTH_KEY',  ',Z|,TlEV+Jx^IYhJRst1L!K>}2yiY;y(I>lGYME1fZR7@];+Q).#6@iB,^Z9w!1S');
define('LOGGED_IN_KEY',    'w{@=$M+[4~1at~%7%qS@EH0aTa~rMtw+4l4fj+yoR3xlg.!WM4oM(~YvGqFd_y6%');
define('NONCE_KEY',        'V1mwY`7-?Mzf1yK|WGmZHK4S$=;+aHA*0~IY0Og/PXjTvgI^rNRkQ#I7+0-I9iIO');
define('AUTH_SALT',        ' jO/,A+e@%xlM^XY3j-B-<UeW=Se= c-qORzb^ qXv2`.>/I)d=hop(@3@?ucFb;');
define('SECURE_AUTH_SALT', '{?UN)^]iEc<Ega]bdC|:O_.?6KjafyT3y4!rjYi{Z-u^J<yMimelYw-Y%2ZxP?V(');
define('LOGGED_IN_SALT',   '-tCXOn#HNQ{AaiX|{o]y*&-8>4XbUu;#cl^+ DNk7t7s3`CZ/f6I|0/$9@ft{0v_');
define('NONCE_SALT',       'N{9eq?Jq+l[9$|% jJ2ThPJ*iDN~>XN!iZllv>28}Q1|2[X}P3l3%^cf+N+hu;X!');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
 
define('WP_HOME','https://archive.ratriverhealth.ca');
define('WP_SITEURL','https://archive.ratriverhealth.ca');


define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', true );

define ('WP_MEMORY_LIMIT', '1024M');

/* Multisite */
define( 'WP_ALLOW_MULTISITE', true );

define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'archive.ratriverhealth.ca');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
