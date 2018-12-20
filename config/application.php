<?php

/** @var string Directory containing all of the site's files */
$root_dir = dirname(__DIR__);

/** @var string Document Root */
$webroot_dir = $root_dir . '/web';

/**
 * Expose global env() function from oscarotero/env
 */
Env::init();

/**
 * Use Dotenv to set required environment variables and load .env file in root
 */
$dotenv = new Dotenv\Dotenv($root_dir);
if (file_exists($root_dir . '/.env')) {
    $dotenv->load();
    $dotenv->required(['DB_NAME', 'DB_USER', 'DB_PASSWORD', 'WP_HOME', 'WP_SITEURL']);
}

/**
 * Set up our global environment constant and load its config first
 * Default: production
 */
define('WP_ENV', env('WP_ENV') ?: 'production');

$env_config = __DIR__ . '/environments/' . WP_ENV . '.php';

if (file_exists($env_config)) {
    require_once $env_config;
}

/**
 * URLs
 */
define('WP_HOME', env('WP_HOME'));
define('WP_SITEURL', env('WP_SITEURL'));

/**
 * Custom Content Directory
 */
define('CONTENT_DIR', '/app');
define('WP_CONTENT_DIR', $webroot_dir . CONTENT_DIR);
define('WP_CONTENT_URL', WP_HOME . CONTENT_DIR);

/**
 * DB settings
 */
define('DB_NAME', env('DB_NAME'));
define('DB_USER', env('DB_USER'));
define('DB_PASSWORD', env('DB_PASSWORD'));
define('DB_HOST', env('DB_HOST') ?: 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');
$table_prefix = env('DB_PREFIX') ?: 'wp_';

/**
 * Authentication Unique Keys and Salts
 */
define('AUTH_KEY', env('BIv`7vKbT=|+s~?*Y0/h@Xo+a{l<8-8iF>wUuNXx qa;c?RJom@&f{<m`7yYsD<v'));
define('SECURE_AUTH_KEY', env('3O.P*J;|]g(plBqXs-m]jB;Vh&w@rBH.M_>%D@.Q`|-V|@R4G+L3YH[gx-P+4o)6'));
define('LOGGED_IN_KEY', env('8[U90f 3_6o7AVJY_^ys~o $a4/|HJM.w_|&t@/.aT]i}JG 2;.=T(0={,1di)6V''));
define('NONCE_KEY', env('S-mHW?i`vDW344#Wqmmh}kG/WPY|vAK;t-3J=ea3Skr6]Y1`x*As{.Pv0/9&saki'));
define('AUTH_SALT', env('`>97t6iV#|_+b2gJ+NpTU&:LtUD5m?ao[HO*D*A<wT0G+3ZKmH*(?_f|n&Fs&_nQ'));
define('SECURE_AUTH_SALT', env('?+57]d6V8{nu4Scl^*<7w@1OkvQyQFb=| E.9TU+&X/1(=g|HKje{3SzMO{G(E>/'));
define('LOGGED_IN_SALT', env('c!{~OfS,7&g1mC<7+42_,f53ief/kZ~[-D@Rj~2D5 8>suJyF{/H_-_?x03VxU@b'));
define('NONCE_SALT', env('@fv7,@>j%L^XC,[t/ADNdW+96O-MwyE8f-CeQk|6~9hYW-HzR9l50.tMd~Sp#WVi'));

/**
 * Custom Settings
 */
define('AUTOMATIC_UPDATER_DISABLED', true);
define('DISABLE_WP_CRON', env('DISABLE_WP_CRON') ?: false);
define('DISALLOW_FILE_EDIT', true);

/**
 * Bootstrap WordPress
 */
if (!defined('ABSPATH')) {
    define('ABSPATH', $webroot_dir . '/wp/');
}

/*
Plugin Name: PostgreSQL for WordPress (PG4WP)
Plugin URI: http://www.hawkix.net
Description: PG4WP is a special 'plugin' enabling WordPress to use a PostgreSQL database.
Version: 1.3.1
Author: Hawk__
Author URI: http://www.hawkix.net
License: GPLv2 or newer.
*/

if( !defined('PG4WP_ROOT')) {
// You can choose the driver to load here
    define('DB_DRIVER', 'pgsql'); // 'pgsql' or 'mysql' are supported for now

// Set this to 'true' and check that `pg4wp` is writable if you want debug logs to be written
    define( 'PG4WP_DEBUG', false);
// If you just want to log queries that generate errors, leave PG4WP_DEBUG to "false"
// and set this to true
    define( 'PG4WP_LOG_ERRORS', false);

// If you want to allow insecure configuration (from the author point of view) to work with PG4WP,
// change this to true
    define( 'PG4WP_INSECURE', true);

// This defines the directory where PG4WP files are loaded from
    define( 'PG4WP_ROOT', WP_CONTENT_DIR.'/plugins/postgresql-for-wordpress/pg4wp');

    define( 'WP_USE_EXT_MYSQL', true);
} // Protection against multiple loading
