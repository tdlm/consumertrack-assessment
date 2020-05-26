<?php

/**
 * Plugin Name: MicroTheme Core
 * Plugin Script: plugin.php
 * Plugin URI: http://localhost/
 * Description: MicroTheme Core Plugin
 * Author: Scott Weaver
 * Author URI: https://scottmw.com
 * Version: 0.0.1
 *
 * @package    WordPress
 * @subpackage MicroTheme
 */

namespace MicroTheme\Core;

use WP_CLI;

/**
 * Plugin version (increment when you update this).
 */
const PLUGIN_VERSION = '0.0.1';

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once __DIR__ . '/inc/class-cli.php';
	WP_CLI::add_command( 'movies', __NAMESPACE__ . '\\CLI' );
}
