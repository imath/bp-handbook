<?php
/**
 * BP Handbook plugin.
 *
 * @package   bp-handbook
 * @author    The BuddyPress Community
 * @license   GPL-2.0+
 * @link      https://buddypress.org
 *
 * @buddypress-plugin
 * Plugin Name:       BP Handbook
 * Plugin URI:        https://github.com/buddypress/bp-handbook
 * Description:       BuddyPress Handbook plugin.
 * Version:           1.0.0-alpha
 * Author:            The BuddyPress Community
 * Author URI:        https://buddypress.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages/
 * Text Domain:       bp-handbook
 * GitHub Plugin URI: https://github.com/buddypress/bp-handbook
 * Requires Plugins:  buddypress, handbook
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Class
 *
 * @since 1.0.0
 */
final class BP_Handbook {
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Used to store dynamic properties.
	 *
	 * @var array
	 */
	private $data = array();

	/**
	 * Initialize the plugin.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		// Autoload Classes.
		spl_autoload_register( array( $this, 'autoload' ) );

		// Load Globals & Functions.
		$inc_path = plugin_dir_path( __FILE__ ) . 'inc/';

		require $inc_path . 'globals.php';
		require $inc_path . 'functions.php';
	}

	/**
	 * Magic method for checking the existence of a plugin global variable.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Key to check the set status for.
	 * @return bool
	 */
	public function __isset( $key ) {
		return isset( $this->data[ $key ] );
	}

	/**
	 * Magic method for getting a plugin global variable.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Key to return the value for.
	 * @return mixed
	 */
	public function __get( $key ) {
		$retval = null;
		if ( isset( $this->data[ $key ] ) ) {
			$retval = $this->data[ $key ];
		}

		return $retval;
	}

	/**
	 * Magic method for setting a plugin global variable.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key   Key to set a value for.
	 * @param mixed  $value Value to set.
	 */
	public function __set( $key, $value ) {
		$this->data[ $key ] = $value;
	}

	/**
	 * Magic method for unsetting a plugin global variable.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Key to unset a value for.
	 */
	public function __unset( $key ) {
		if ( isset( $this->data[ $key ] ) ) {
			unset( $this->data[ $key ] );
		}
	}

	/**
	 * Checks whether BuddyPress is active.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean True if BuddyPress is active. False otherwise.
	 */
	public static function is_buddypress_active() {
		$bp_plugin_basename   = 'buddypress/bp-loader.php';
		$is_buddypress_active = false;
		$sitewide_plugins     = (array) get_site_option( 'active_sitewide_plugins', array() );

		if ( $sitewide_plugins ) {
			$is_buddypress_active = isset( $sitewide_plugins[ $bp_plugin_basename ] );
		}

		if ( ! $is_buddypress_active ) {
			$plugins              = (array) get_option( 'active_plugins', array() );
			$is_buddypress_active = in_array( $bp_plugin_basename, $plugins, true );
		}

		return $is_buddypress_active;
	}

	/**
	 * Checks whether Handbook is active.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean True if Handbook is active. False otherwise.
	 */
	public static function is_handbook_active() {
		return class_exists( 'WPorg_Handbook_Init', false );
	}

	/**
	 * Displays an admin notice to explain how to install BP Rewrites.
	 *
	 * @since 1.0.0
	 */
	public static function admin_notice() {
		$plugins = array();

		if ( ! self::is_buddypress_active() ) {
			$plugins[] = sprintf( '<a href="%s">BuddyPress</a>', esc_url( _x( 'https://wordpress.org/plugins/buddypress', 'BuddyPress WP plugin directory URL', 'bp-handbook' ) ) );
		}

		if ( ! self::is_handbook_active() ) {
			$plugins[] = sprintf( '<a href="%s">Handbook</a>', esc_url( _x( 'https://meta.trac.wordpress.org/browser/sites/trunk/wordpress.org/public_html/wp-content/plugins/handbook', 'Handbook SVN repository URL', 'bp-handbook' ) ) );
		}

		if ( ! $plugins ) {
			return;
		}

		$notices = array();
		foreach ( $plugins as $plugin ) {
			$notices[] = sprintf(
				/* translators: 1. is the link to the BuddyPress plugin on the WordPress.org plugin directory. */
				esc_html__( 'BP Handbook requires the %1$s plugin to be active. Please deactivate BP Handbook, activate %1$s and only then, reactivate BP Handbook.', 'bp-handbook' ),
				$plugin // phpcs:ignore
			);
		}

		printf(
			'<div class="notice notice-error is-dismissible">%s</div>',
			'<p>' . implode( '</p><p>', $notices ) . '</p>'
		);
	}

	/**
	 * Class Autoload function
	 *
	 * @since  1.0.0
	 *
	 * @param  string $class The class name.
	 */
	public function autoload( $class ) {
		$name = str_replace( '_', '-', strtolower( $class ) );

		if ( 0 !== strpos( $name, 'bp-handbook' ) ) {
			return;
		}

		$path = plugin_dir_path( __FILE__ ) . "inc/classes/class-{$name}.php";

		// Sanity check.
		if ( ! file_exists( $path ) ) {
			return;
		}

		require $path;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 */
	public static function start() {
		// This plugin is only usable with BuddyPress.
		if ( ! self::is_buddypress_active() ) {
			return false;
		}

		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

/**
 * Start plugin.
 *
 * @since 1.0.0
 *
 * @return BP_Handbook The main instance of the plugin.
 */
function bp_handbook() {
	return BP_Handbook::start();
}
add_action( 'bp_loaded', 'bp_handbook', -1 );

// Displays a notice to inform BP Rewrites needs to be activated after BuddyPress.
add_action( 'admin_notices', array( 'BP_Handbook', 'admin_notice' ) );
