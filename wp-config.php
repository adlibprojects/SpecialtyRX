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
/*
 * Unified variables
 */
$db_name = 'specialtyrx';
$hostname = 'localhost';
$charset = 'UTF-8';
$collate = '';
/*
 * Check for the current environment
 */
if ($_SERVER["HTTP_HOST"] === 'specialtyrx.com') {
  $user_name = 'root';
  $password = 'root';
} else if ($_SERVER["HTTP_HOST"] === 'localhost') {
  $user_name = 'root';
  $password = 'root';
}

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', $db_name);

/** MySQL database username */
define('DB_USER', $user_name);

/** MySQL database password */
define('DB_PASSWORD', $password);

/** MySQL hostname */
define('DB_HOST', $hostname);

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', $chartset);

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', $collate);

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '=K|V3yL?-KpkCtWfxGq0=]8!vb^3SKlO1H_6*OEb5r[-ejwFX. -;ju^|nQPfN6{');
define('SECURE_AUTH_KEY',  'cmwQaB?90X>nobjgn qsPR(W;>QUt<b,-[M5KJ3-)0F~GoCpm&+b-F}MpV+la3MC');
define('LOGGED_IN_KEY',    'bkR&0&&Gg-U0~ErRo@]p)3A6aprXjlh]{7ne%ff7iXO+<t-/`aI$.KjV_Xghp.wi');
define('NONCE_KEY',        '(2kAJYa6(v57l)(+=MmI/M]WOa-2<-d)-X^!C`S+,n;#WG}a+1#+AXNlcWMLCnDk');
define('AUTH_SALT',        'X1j`GH.UGr=,=1o`sJ0*i-:K2f:xCIX+ Ed]Mso-ce|bWg ztk!jYz+eVbw7J|WP');
define('SECURE_AUTH_SALT', 'tykSA*g:O= +)P@9t<#1C/jjc+:Bj@CkQXr`w377:7U(/$$I.A}D}DqfiejjFf~q');
define('LOGGED_IN_SALT',   '1Cs@UF#@GBer~IK/-4M3yw7e&+:Pr2`>+^W4L+[CNsjJ^rm7T+(&h>|,H&vsyeR4');
define('NONCE_SALT',       ')x^3_+3+A5YQCP/=0us`dZK@0Ml$r!-l>]`5ROJ7m;a<>zxu--W>~|83:r6Ljlt,');

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
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
