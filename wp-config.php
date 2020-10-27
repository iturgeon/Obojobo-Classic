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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */

/** Enable W3 Total Cache */
define('WP_CACHE', true);

// OBOJOBO configuration
include_once(__DIR__.'/internal/config/cfgLocal.php'); // local config
if ( ! class_exists('AppCfg')) exit('Error: Obojobo cfgLocal invalid or missing.');

define('DB_NAME', AppCfg::DB_WP_NAME);

/** MySQL database username */
define('DB_USER', AppCfg::DB_WP_USER);

/** MySQL database password */
define('DB_PASSWORD', AppCfg::DB_WP_PASS);

/** MySQL hostname */
define('DB_HOST', AppCfg::DB_WP_HOST);

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

// Block outbound communication from wordpress
define( 'WP_HTTP_BLOCK_EXTERNAL', TRUE );

// disable the wp cron - we really don't need it
define('DISABLE_WP_CRON', true);

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         AppCfg::WP_AUTH_KEY);
define('SECURE_AUTH_KEY',  AppCfg::WP_SECURE_AUTH_KEY);
define('LOGGED_IN_KEY',    AppCfg::WP_LOGGED_IN_KEY);
define('NONCE_KEY',        AppCfg::WP_NONCE_KEY);
define('AUTH_SALT',        AppCfg::WP_AUTH_SALT);
define('SECURE_AUTH_SALT', AppCfg::WP_SECURE_AUTH_SALT);
define('LOGGED_IN_SALT',   AppCfg::WP_LOGGED_IN_SALT);
define('NONCE_SALT',       AppCfg::WP_NONCE_SALT);

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/wp/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

// when docker is used and the request is coming from webpack, don't redirect to siteurl
if (AppCfg::IS_DEV_DOCKER === true &&  $_SERVER['HTTP_X_USE_WEBPACK'] === 'true')
{
	remove_filter('template_redirect','redirect_canonical');
}