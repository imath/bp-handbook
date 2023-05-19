<?php
/**
 * BP Handbook Funcions.
 *
 * @package bp-handbook\inc
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load translation.
 *
 * @since 1.0.0
 */
function bp_handbook_load_translation() {
	$bph = bp_handbook();

	// Load translations.
	load_plugin_textdomain( 'bp-handbook', false, trailingslashit( basename( $bph->dir ) ) . 'languages' );
}
add_action( 'bp_loaded', 'bp_handbook_load_translation' );

function bp_handbook_init() {
	BP_Handbook_Addons_Importer::instance()->init();
}
add_action( 'bp_loaded', 'bp_handbook_init', 11 );
