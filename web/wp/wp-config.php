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
define( 'DB_NAME', 'groupedmm_preprod' );

/** Database username */
define( 'DB_USER', 'groupdmm' );

/** Database password */
define( 'DB_PASSWORD', '-J$7ccTF=oc`Jd' );

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
define( 'AUTH_KEY',         ',K02vl`!N )EG3f0*LQT--fm:+k*m5N:Pc4F]h;o1>I$fB94)oqux*<tZ2oz3Y6w' );
define( 'SECURE_AUTH_KEY',  '/wV%!+B Bd er}=Qn}XIP29ECUjyM|dPKrU%U8(~9+0o{zkh>(J?h+1Hf5s.v}b,' );
define( 'LOGGED_IN_KEY',    '/OfGIS4Dp}$/ze?MT[ HQv.i?Rt^~ePm;kGx| JE/;ljFd{(;Xh`u6N]Xsi3#;YD' );
define( 'NONCE_KEY',        '7vzk~n]!9=`;1AQq!z]<f-_FS%[KMPrhxf[;)<3dNPOz/|gf@.qx%~lcpgi9074a' );
define( 'AUTH_SALT',        'oyd*}P3za%_!04}zGmaCa<cP{q>Eim/x5;}p5]<,iNR|`+LgRoOU+jNjL:ofh^=D' );
define( 'SECURE_AUTH_SALT', 'QNH~<4je=nZHc#1,!pMPd^>/H[^,L$CWL(`]t+c{Y]9hgcZj(E>YEV5{-eWyhw;M' );
define( 'LOGGED_IN_SALT',   '<HqeS3BTIc8(?1mYh@`]p}Uwy,be{%[w7&YU w~yah=bUCDg:l3[[w_q[cRbYFlh' );
define( 'NONCE_SALT',       '6e`<*&J}m*EBk8~]bpj=OhsX[U%rZf%)/n]30pt)i//X`;fa~4WFe[etWn}>_1a1' );

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
$table_prefix = 'dmm_';

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
