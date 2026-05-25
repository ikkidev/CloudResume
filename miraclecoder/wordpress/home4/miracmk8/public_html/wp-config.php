<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'miracmk8_WP6ZY');

/** MySQL database username */
define('DB_USER', 'miracmk8_WP6ZY');

/** MySQL database password */
define('DB_PASSWORD', 'j7?=_&BRC%){kbIS[');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'e968694219bbb00983940c9e145c1650cf90798cdee9c25d111a1499cb9a50b9');
define('SECURE_AUTH_KEY', '4bb4bb35b47cc8a2b7a85c5719abd2b87859adcb0b7ca14dd5e608dd141884ad');
define('LOGGED_IN_KEY', '7fb87c977f7c3c17a9d22597c7afa07d5b8bf05a45caedf0d4673dc5e6b571a4');
define('NONCE_KEY', '80a6005273d1ad9473bc800e84dcde2f0bf66053aff0cf165ea6b9e2112781a8');
define('AUTH_SALT', '7a280bbfea3d7f75ecac29b2c80fd6b17e551ce4b89d1b2225a05a4366a273e8');
define('SECURE_AUTH_SALT', 'cf3fcbcb78f1b2992e1488fcd8f589543e1c25b92aa91a7eea3e98d92b89db8f');
define('LOGGED_IN_SALT', '6d598ee435a60a73dfa3d2675c5501d2965c08c757f711fba339ba6f129798ec');
define('NONCE_SALT', 'b410cc775748ea9d1a55516784469d5d2afe216b8b8d709a5382c414f0024ebb');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'vi6_';
define('WP_CRON_LOCK_TIMEOUT', 120);
define('AUTOSAVE_INTERVAL', 300);
define('WP_POST_REVISIONS', 5);
define('EMPTY_TRASH_DAYS', 7);
define('WP_AUTO_UPDATE_CORE', true);

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
