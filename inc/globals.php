<?php
/**
 * BP Handbook Globals.
 *
 * @package bp-handbook\inc
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register plugin globals.
 *
 * @since 1.0.0
 */
function bp_handbook_set_globals() {
	$bph = bp_handbook();

	$bph->version = '1.0.0-beta2';

	// Path.
	$plugin_dir = plugin_dir_path( dirname( __FILE__ ) );
	$bph->dir   = $plugin_dir;

	// URL.
	$plugin_url = plugins_url( '', dirname( __FILE__ ) );
	$bph->url   = $plugin_url;
}
add_action( 'bp_loaded', 'bp_handbook_set_globals', 1 );
