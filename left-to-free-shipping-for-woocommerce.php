<?php
/*
Plugin Name: Amount Left for Free Shipping for WooCommerce
Plugin URI: https://wpfactory.com/item/amount-left-free-shipping-woocommerce/
Description: Show your customers the amount left for free shipping in WooCommerce.
Version: 1.9.6
Author: WPFactory
Author URI: https://wpfactory.com
Text Domain: amount-left-free-shipping-woocommerce
Domain Path: /langs
Copyright: © 2021 WPFactory
WC tested up to: 4.9
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Left_To_Free_Shipping' ) ) :

/**
 * Main Alg_WC_Left_To_Free_Shipping Class
 *
 * @class   Alg_WC_Left_To_Free_Shipping
 * @version 1.6.0
 * @since   1.0.0
 */
final class Alg_WC_Left_To_Free_Shipping {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = '1.9.6-dev-20210106-0028';

	/**
	 * @var   Alg_WC_Left_To_Free_Shipping The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_WC_Left_To_Free_Shipping Instance
	 *
	 * Ensures only one instance of Alg_WC_Left_To_Free_Shipping is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @static
	 * @return  Alg_WC_Left_To_Free_Shipping - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_WC_Left_To_Free_Shipping Constructor.
	 *
	 * @version 1.6.0
	 * @since   1.0.0
	 * @access  public
	 */
	function __construct() {

		// Check for active plugins
		if (
			! $this->is_plugin_active( 'woocommerce/woocommerce.php' ) ||
			( 'left-to-free-shipping-for-woocommerce.php' === basename( __FILE__ ) && $this->is_plugin_active( 'left-to-free-shipping-for-woocommerce-pro/left-to-free-shipping-for-woocommerce-pro.php' ) )
		) {
			return;
		}

		// Set up localisation
		load_plugin_textdomain( 'amount-left-free-shipping-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

		// Pro
		if ( 'left-to-free-shipping-for-woocommerce-pro.php' === basename( __FILE__ ) ) {
			require_once( 'includes/pro/class-alg-wc-alfs-pro.php' );
		}

		// Include required files
		$this->includes();

		// Admin
		if ( is_admin() ) {
			$this->admin();
		}
	}

	/*
	 * is_plugin_active.
	 *
	 * @version 1.4.1
	 * @since   1.4.1
	 */
	function is_plugin_active( $plugin ) {
		return ( function_exists( 'is_plugin_active' ) ? is_plugin_active( $plugin ) :
			(
				in_array( $plugin, apply_filters( 'active_plugins', (array) get_option( 'active_plugins', array() ) ) ) ||
				( is_multisite() && array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) ) )
			)
		);
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @version 1.6.0
	 * @since   1.0.0
	 */
	function includes() {
		// Functions
		require_once( 'includes/alg-wc-alfs-functions.php' );
		// Widget
		require_once( 'includes/class-alg-wc-widget-alfs.php' );
		// Core
		$this->core = require_once( 'includes/class-alg-wc-alfs-core.php' );
	}

	/**
	 * admin.
	 *
	 * @version 1.4.4
	 * @since   1.3.0
	 */
	function admin() {
		// Action links
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		// Settings
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
		// Version update
		if ( get_option( 'alg_wc_left_to_free_shipping_version', '' ) !== $this->version ) {
			add_action( 'admin_init', array( $this, 'version_updated' ) );
		}
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @version 1.4.7
	 * @since   1.0.0
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_left_to_free_shipping' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
		if ( 'left-to-free-shipping-for-woocommerce.php' === basename( __FILE__ ) ) {
			$custom_links[] = '<a target="_blank" style="font-weight: bold; color: green;" href="https://wpfactory.com/item/amount-left-free-shipping-woocommerce/">' .
				__( 'Go Pro', 'amount-left-free-shipping-woocommerce' ) . '</a>';
		}
		return array_merge( $custom_links, $links );
	}

	/**
	 * Add Amount Left for Free Shipping settings tab to WooCommerce settings.
	 *
	 * @version 1.6.0
	 * @since   1.0.0
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = require_once( 'includes/settings/class-alg-wc-settings-alfs.php' );
		return $settings;
	}

	/**
	 * version_updated.
	 *
	 * @version 1.3.0
	 * @since   1.2.0
	 */
	function version_updated() {
		// Update version
		update_option( 'alg_wc_left_to_free_shipping_version', $this->version );
	}

	/**
	 * Get the plugin url.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

}

endif;

if ( ! function_exists( 'alg_wc_left_to_free_shipping' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Left_To_Free_Shipping to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  Alg_WC_Left_To_Free_Shipping
	 */
	function alg_wc_left_to_free_shipping() {
		return Alg_WC_Left_To_Free_Shipping::instance();
	}
}

add_action( 'plugins_loaded', 'alg_wc_left_to_free_shipping' );
