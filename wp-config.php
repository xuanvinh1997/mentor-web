<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'mentor_db' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1:8889' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '^5u/=AiMOA53;bf$7&<B}^/.gK3NL)g0*dy#,MN@Fv!KRO>+_K(#7s^WrB)8r6Th' );
define( 'SECURE_AUTH_KEY',  'd:VQ~pckc6.4><v~/%<>*5`pr0a_:nE$PJImkABJr`!JV26Ib8XS3Bo5$+=f7TOc' );
define( 'LOGGED_IN_KEY',    'j3,*1{3J!+v]87)+i3@>P|BS=,e~hjMkz:5(Z9h&:4Arh<YHv2>&C`L!pyjaGOP9' );
define( 'NONCE_KEY',        ',J%84N;!QZ_3e3q^nv)f]@(WEK,V&z9y7*[H4x@laAs=_1JSKiBY]k^2WzgvOmL ' );
define( 'AUTH_SALT',        '9Od6Wt+`cDm7G2F2im]O)^5W7;v(E9P~TVKra7Q?3nu !%s?Vi-Xju5Raw/)%c t' );
define( 'SECURE_AUTH_SALT', 'WCsBX!GBfaVx T?X3F1Mp%Qz^`bnOK3@b+3frBRzSu^O!1vmI^OLZ[gK[mGW2]J[' );
define( 'LOGGED_IN_SALT',   '|-^3i,;rdbu$aYtl<jguN<e|(45>>d-l~BR(f$Ag4j[r-Hg!q%+y8pk6$t)pj3Hm' );
define( 'NONCE_SALT',       '6 p4uufOSgH8exHb=S[fK,0/^LQ,3lmC`QX6kJwtk1MUlMiZ%<y_w=v<vK=-[ZcK' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'mentor_';

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
