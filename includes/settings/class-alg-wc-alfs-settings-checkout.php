<?php
/**
 * Amount Left for Free Shipping for WooCommerce - Checkout Section Settings
 *
 * @version 1.8.0
 * @since   1.6.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Left_To_Free_Shipping_Settings_Checkout' ) ) :

class Alg_WC_Left_To_Free_Shipping_Settings_Checkout extends Alg_WC_Left_To_Free_Shipping_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 */
	function __construct() {
		$this->id   = 'checkout';
		$this->desc = __( 'Checkout', 'amount-left-free-shipping-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.8.0
	 * @since   1.6.0
	 */
	function get_settings() {
		return array(
			array(
				'title'    => __( 'Checkout Options', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_left_to_free_shipping_checkout_options',
				'desc'     => __( 'Outputted on the checkout page.', 'amount-left-free-shipping-woocommerce' ),
			),
			array(
				'title'    => __( 'Enable/Disable', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Enable section', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_enabled_checkout',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_left_to_free_shipping_settings', array( 'disabled' => 'disabled' ) ),
				'desc_tip' => apply_filters( 'alg_wc_left_to_free_shipping_settings', $this->pro_desc ),
			),
			array(
				'title'    => __( 'Content', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => $this->get_placeholders_desc(),
				'desc_tip' => __( 'You can use HTML and/or shortcodes here.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_content_checkout',
				'default'  => alg_wc_left_to_free_shipping()->core->get_default_content(),
				'type'     => 'textarea',
				'css'      => 'width:100%;height:100px;',
				'alg_wc_alfs_raw' => true,
			),
			array(
				'title'    => __( 'Position', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_position_checkout',
				'default'  => 'woocommerce_checkout_after_order_review',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'woocommerce_before_checkout_form'              => __( 'Before checkout form', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_checkout_before_customer_details'  => __( 'Before customer details', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_checkout_billing'                  => __( 'Billing', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_checkout_shipping'                 => __( 'Shipping', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_checkout_after_customer_details'   => __( 'After customer details', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_checkout_before_order_review'      => __( 'Before order review', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_checkout_order_review'             => __( 'Order review', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_review_order_before_shipping'      => __( 'Order review: Before shipping', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_review_order_after_shipping'       => __( 'Order review: After shipping', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_review_order_before_submit'        => __( 'Order review: Payment: Before submit button', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_review_order_after_submit'         => __( 'Order review: Payment: After submit button', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_checkout_after_order_review'       => __( 'After order review', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_after_checkout_form'               => __( 'After checkout form', 'amount-left-free-shipping-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Position order (priority)', 'amount-left-free-shipping-woocommerce' ),
				'desc_tip' => __( 'Here you can move the info inside the Position selected above.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_priority_checkout',
				'default'  => 10,
				'type'     => 'number',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_left_to_free_shipping_checkout_options',
			),
		);
	}

}

endif;

return new Alg_WC_Left_To_Free_Shipping_Settings_Checkout();
