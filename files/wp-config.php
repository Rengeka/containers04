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
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'wordpress' );

/** Database password */
define( 'DB_PASSWORD', 'wordpress' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         'Jf[>m*cU%<SCChw%XMoh p7vTgA_S9Jmi{$i~j:a-S(Naxj#_j(WPi-G6lRLca{d' );
define( 'SECURE_AUTH_KEY',  'cs/rz(PO2iRpdu%. &t>cMhLQ?0{q};xi&0{Dl(lT2X)M-A|o! `f5]1vsf)$4R{' );
define( 'LOGGED_IN_KEY',    '+#!ckJ=2#d0}Q%g<m_WpbA1c9*^}{HP6X+>(Umd7<~SW@X~f~@Oj=nz9A*O_ex+L' );
define( 'NONCE_KEY',        'ec+HI!Ev)au1VIG,rjpnlMNQ;zR,Ro>RY>}q.m[J!t~I37CnKo3qvmg3mKAfmLS(' );
define( 'AUTH_SALT',        'iIrm[or$T}ibkkJGUPm$Qh*p^ lp[?V?~`/-jzfudP/1/^S[ohr-jCq9k+Unad`d' );
define( 'SECURE_AUTH_SALT', '1bb3qbSel.a)u+:AP4x+C:>:rRRWp_@#!LHN:ZC)lKs41$0cyR2-M*0tfDhZTCsr' );
define( 'LOGGED_IN_SALT',   'Mw`BDf7+F:eoVRJfe87Cyzjx>WOWDyZQtt[zv]A/s,=SDe,y,P;{Dh;pi;Mk3Sms' );
define( 'NONCE_SALT',       '4OvhaU7Iw:s}owB^%b(qM4fm.,)ifN/i-@/VKnE4)rG[2MoF:n)p1|U7S&}n#|0X' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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
