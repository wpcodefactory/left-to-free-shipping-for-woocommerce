<?php
/**
 * Amount Left for Free Shipping for WooCommerce - General Section Settings
 *
 * @version 1.9.1
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Left_To_Free_Shipping_Settings_General' ) ) :

class Alg_WC_Left_To_Free_Shipping_Settings_General extends Alg_WC_Left_To_Free_Shipping_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'amount-left-free-shipping-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.9.1
	 * @since   1.0.0
	 * @todo    [next] `alg_wc_left_to_free_shipping_check_free_shipping`: default to `yes`
	 * @todo    [next] `alg_wc_left_to_free_shipping_check_virtual`: default to `yes`
	 * @todo    [next] `alg_wc_left_to_free_shipping_ajax_enabled`: better description
	 * @todo    [next] [maybe] add new sections: "AJAX", "Advanced"?
	 * @todo    [maybe] Message on free shipping reached: add checkbox (similar as it's in "Message on empty cart" option)
	 */
	function get_settings() {

		$general_settings = array(
			array(
				'title'    => __( 'General Options', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_left_to_free_shipping_general_options',
			),
			array(
				'title'    => __( 'Amount Left for Free Shipping', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable plugin', 'amount-left-free-shipping-woocommerce' ) . '</strong>',
				'id'       => 'alg_wc_left_to_free_shipping_enabled',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Include discounts', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Include', 'amount-left-free-shipping-woocommerce' ),
				'desc_tip' => __( 'Include discounts when calculating cart total.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_include_discounts',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Message on free shipping reached', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => $this->get_placeholders_desc(),
				'desc_tip' => __( 'Outputted when min free shipping amount is reached.', 'amount-left-free-shipping-woocommerce' ) . ' ' .
					__( 'You can use HTML and/or shortcodes here.', 'amount-left-free-shipping-woocommerce' ) . ' ' .
					__( 'To disable - set it empty.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_content_reached',
				'default'  => __( 'You have free delivery!', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'textarea',
				'css'      => 'width:100%;height:100px;',
				'alg_wc_alfs_raw' => true,
			),
			array(
				'title'    => __( 'Message on empty cart', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Custom message', 'amount-left-free-shipping-woocommerce' ),
				'desc_tip' => __( 'If disabled, then standard content is outputted.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_custom_empty_cart',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'desc_tip' => sprintf( __( 'Ignored unless "%s" checkbox is enabled.', 'amount-left-free-shipping-woocommerce' ),
					__( 'Message on empty cart', 'amount-left-free-shipping-woocommerce' ) . ' > ' . __( 'Custom message', 'amount-left-free-shipping-woocommerce' ) ),
				'desc'     => sprintf( __( 'E.g.: %s', 'amount-left-free-shipping-woocommerce' ),
					'<code>' . __( 'Free shipping on orders over %free_shipping_min_amount%.', 'amount-left-free-shipping-woocommerce' ) . '</code>' ) . '<br>' .
					$this->get_placeholders_desc(),
				'id'       => 'alg_wc_left_to_free_shipping_info_content_empty_cart',
				'default'  => '',
				'type'     => 'textarea',
				'css'      => 'width:100%;height:100px;',
				'alg_wc_alfs_raw' => true,
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_left_to_free_shipping_general_options',
			),
		);

		$ajax_settings = array(
			array(
				'title'    => __( 'AJAX Options', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Enable this section if you want the amount to be updated via AJAX, e.g. when using plugin\'s "Store notice" section, or WooCommerce "Enable AJAX add to cart buttons on archives" option, or for some positions in cart, when customer updates items quantities and the amount text is positioned outside of automatically refreshed area.', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_left_to_free_shipping_ajax_options',
			),
			array(
				'title'    => __( 'Enable AJAX', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Enable', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_left_to_free_shipping_ajax_enabled',
				'default'  => 'no',
				'desc_tip' => apply_filters( 'alg_wc_left_to_free_shipping_settings', $this->pro_desc ),
				'custom_attributes' => apply_filters( 'alg_wc_left_to_free_shipping_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'    => __( 'Additional events', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Additional JavaScript events to update the amount text on. Leave empty if unsure.', 'amount-left-free-shipping-woocommerce' ) . '<br>' .
					sprintf( __( 'These events are always included: %s', 'amount-left-free-shipping-woocommerce' ),
						'<code>' . implode( ' ', alg_wc_left_to_free_shipping()->core->get_default_ajax_events() ) . '</code>' ),
				'type'     => 'text',
				'id'       => 'alg_wc_left_to_free_shipping_ajax_events',
				'default'  => '',
				'css'      => 'width:100%;',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_left_to_free_shipping_ajax_options',
			),
		);

		$advanced_settings = array(
			array(
				'title'    => __( 'Advanced Options', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_left_to_free_shipping_advanced_options',
			),
			array(
				'title'    => __( 'Check for free shipping', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Enable', 'amount-left-free-shipping-woocommerce' ),
				'desc_tip' => __( 'Check for already available alternative (i.e. not requiring minimum order amount) free shipping methods.', 'amount-left-free-shipping-woocommerce' ) . ' ' .
					__( 'No text will be outputted in this case.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_check_free_shipping',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Check for virtual cart', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Enable', 'amount-left-free-shipping-woocommerce' ),
				'desc_tip' => __( 'Check if cart consists of virtual products only.', 'amount-left-free-shipping-woocommerce' ) . ' ' .
					__( 'No text will be outputted in this case.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_check_virtual',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_left_to_free_shipping_advanced_options',
			),
		);

		$info = array(
			array(
				'title'    => __( 'Info', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_left_to_free_shipping_info',
				'desc'     => '<div style="background-color:white;color:black;padding:10px;">' .
					'<strong>' . __( 'You can also use:', 'amount-left-free-shipping-woocommerce' ) . '</strong>' .
					'<ol>' .
						'<li>' .
								'<span style="text-decoration:underline;">' . __( 'Widget', 'amount-left-free-shipping-woocommerce' ) . '</span>' . ': ' .
								'<em>' . __( 'Amount Left for Free Shipping', 'amount-left-free-shipping-woocommerce' ) . '</em>' .
						'</li>' .
						'<li>' .
								'<span style="text-decoration:underline;">' . __( 'Shortcode', 'amount-left-free-shipping-woocommerce' ) . '</span>' . ': ' .
								'<code>[alg_wc_left_to_free_shipping content="%amount_left_for_free_shipping% left for free shipping"]</code>' .
						'</li>' .
						'<li>' .
								'<span style="text-decoration:underline;">' . __( 'PHP function', 'amount-left-free-shipping-woocommerce' ) . '</span>' . ': ' .
								'<code>echo alg_wc_get_left_to_free_shipping( "%amount_left_for_free_shipping% left for free shipping" );</code>' .
						'</li>' .
					'</ol>' .
				'</div>',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_left_to_free_shipping_info',
			),
		);

		return array_merge( $general_settings, $ajax_settings, $advanced_settings, $info );
	}

}

endif;

return new Alg_WC_Left_To_Free_Shipping_Settings_General();
