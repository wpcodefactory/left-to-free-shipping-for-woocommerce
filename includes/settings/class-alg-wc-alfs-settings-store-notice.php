<?php
/**
 * Amount Left for Free Shipping for WooCommerce - Store Notice Section Settings
 *
 * @version 1.8.0
 * @since   1.6.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Left_To_Free_Shipping_Settings_Store_Notice' ) ) :

class Alg_WC_Left_To_Free_Shipping_Settings_Store_Notice extends Alg_WC_Left_To_Free_Shipping_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 */
	function __construct() {
		$this->id   = 'store_notice';
		$this->desc = __( 'Store notice', 'amount-left-free-shipping-woocommerce' );
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
				'title'    => __( 'Store Notice Options', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Site-wide notice.', 'amount-left-free-shipping-woocommerce' ) . ' ' .
					__( 'You may also want to enable "General > AJAX" option for this.', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_left_to_free_shipping_store_notice_options',
			),
			array(
				'title'    => __( 'Enable/Disable', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Enable section', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_enabled_store_notice',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_left_to_free_shipping_settings', array( 'disabled' => 'disabled' ) ),
				'desc_tip' => apply_filters( 'alg_wc_left_to_free_shipping_settings', $this->pro_desc ),
			),
			array(
				'title'    => __( 'Content', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => $this->get_placeholders_desc(),
				'desc_tip' => __( 'You can use HTML and/or shortcodes here.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_content_store_notice',
				'default'  => alg_wc_left_to_free_shipping()->core->get_default_content(),
				'type'     => 'textarea',
				'css'      => 'width:100%;height:100px;',
				'alg_wc_alfs_raw' => true,
			),
			array(
				'title'    => __( 'Hide', 'amount-left-free-shipping-woocommerce' ),
				'desc_tip' => __( 'Set to zero to show the notice permanently.', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'milliseconds', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_store_notice_hide',
				'default'  => 0,
				'type'     => 'number',
				'custom_attributes' => array( 'min' => 0 ),
			),
			array(
				'title'    => __( 'Position', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_position_store_notice',
				'default'  => 'bottom',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'bottom' => __( 'Bottom', 'amount-left-free-shipping-woocommerce' ),
					'top'    => __( 'Top', 'amount-left-free-shipping-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Background color', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_back_color_store_notice',
				'default'  => '#3d9cd2',
				'type'     => 'color',
			),
			array(
				'title'    => __( 'Text color', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_text_color_store_notice',
				'default'  => '#fff',
				'type'     => 'color',
			),
			array(
				'title'    => __( 'Text alignment', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_text_align_store_notice',
				'default'  => 'left',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'left'    => __( 'Left', 'amount-left-free-shipping-woocommerce' ),
					'right'   => __( 'Right', 'amount-left-free-shipping-woocommerce' ),
					'center'  => __( 'Center', 'amount-left-free-shipping-woocommerce' ),
					'justify' => __( 'Justify', 'amount-left-free-shipping-woocommerce' ),
				),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_left_to_free_shipping_store_notice_options',
			),
		);
	}

}

endif;

return new Alg_WC_Left_To_Free_Shipping_Settings_Store_Notice();
