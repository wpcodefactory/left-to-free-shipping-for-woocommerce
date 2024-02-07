<?php
/**
 * Amount Left for Free Shipping for WooCommerce - Progress Bar Section Settings.
 *
 * @version 2.3.4
 * @since   2.3.4
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Left_To_Free_Shipping_Settings_Progress_Bar' ) ) :

class Alg_WC_Left_To_Free_Shipping_Settings_Progress_Bar extends Alg_WC_Left_To_Free_Shipping_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 2.3.4
	 * @since   2.3.4
	 */
	function __construct() {
		$this->id   = 'progress_bar';
		$this->desc = __( 'Progress Bar', 'amount-left-free-shipping-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 2.3.4
	 * @since   2.3.4
	 * @todo    [maybe] "notice" as "position" (same in "Checkout" section)
	 * @todo    [maybe] multiple positions (same in "Mini-cart" and "Checkout" sections)
	 */
	function get_settings() {
		$cart_options = array(
			array(
				'title'    => __( 'Progress Bar Options', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_left_to_free_shipping_progressbar_options',
				'desc'     => __( 'Please use the placeholder <code>%progress_bar%</code> within any content textarea in the "cart," "mini-cart," "checkout," and "Store notice" sections.', 'amount-left-free-shipping-woocommerce' ),
			),
			array(
				'title'    => __( 'Enable/Disable', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Enable progress bar', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_progressbar_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_left_to_free_shipping_progressbar_options',
			),
		);

		$style = array(
			array(
				'title'    => __( 'Custom style', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_left_to_free_shipping_progressbar_style',
			),
			array(
				'title'    => __( 'Animation', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Enable animation', 'amount-left-free-shipping-woocommerce' ),
				'custom_attributes' => apply_filters( 'alg_wc_left_to_free_shipping_settings', array( 'disabled' => 'disabled' ) ),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_left_to_free_shipping_progressbar_animation_enabled',
				'default'  => 'no',
			),
			array(
				'title'    => __( 'Background color', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_progressbar_background_color',
				'default'  => '#f0f0f0',
				'type'     => 'color',
			),
			array(
				'title'    => __( 'Foreground color', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_progressbar_foreground_color',
				'default'  => '#007bff',
				'type'     => 'color',
			),
			array(
				'title'    => __( 'Height', 'amount-left-free-shipping-woocommerce' ),
				'desc_tip' => __( 'Height in pixels.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_progressbar_height',
				'default'  => '20',
				'type'     => 'number',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_left_to_free_shipping_progressbar_style',
			),
		);

		return array_merge( $cart_options, $style );


	}

}

endif;

return new Alg_WC_Left_To_Free_Shipping_Settings_Progress_Bar();
