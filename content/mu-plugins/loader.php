<?php
/**
 * Plugin Name: MU Plugin Loader
 * Description: Loads the MU plugins required to run the site
 * Author: Scott Weaver
 * Author URI: https://scottmw.com
 * Version: 0.0.1
 *
 * @package    WordPress
 * @subpackage Micronaut
 */

if ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) {
	return;
}

$active_mu_plugins = array(
	'micronaut-core/plugin.php',
);

foreach ( $active_mu_plugins as $file ) {
	if ( file_exists( WPMU_PLUGIN_DIR . '/' . $file ) ) {
		require_once WPMU_PLUGIN_DIR . '/' . $file;
	}
}
unset( $file );