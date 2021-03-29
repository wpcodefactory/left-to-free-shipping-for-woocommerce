<?php
/**
 * Amount Left for Free Shipping for WooCommerce - General Section Settings
 *
 * @version 2.0.0
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
		 * @version 2.0.0
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
					'title'    => __( 'Minimum cart amount', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip' => __( 'Minimum cart amount required for displaying the amount left for free shipping.', 'amount-left-free-shipping-woocommerce' ) . '<br />'
					              . __( 'Use 0 or leave it empty to disable it.', 'amount-left-free-shipping-woocommerce' ),
					'id'       => 'alg_wc_left_to_free_shipping_min_cart_amount',
					'default'  => 0,
					'type'     => 'number',
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
				array(
					'title'    => __( 'Calculation', 'amount-left-free-shipping-woocommerce' ),
					'type'     => 'title',
					'id'       => 'alg_wc_left_to_free_shipping_calculation',
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
					'title'    => __( 'Cart total method', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip' => __( 'The method used to get the total amount from cart.', 'amount-left-free-shipping-woocommerce' ),
					'id'       => 'alg_wc_left_to_free_shipping_cart_total_method',
					'default'  => 'get_displayed_subtotal',
					'type'     => 'select',
					'options'  => array(
						'get_displayed_subtotal'  => sprintf( __( 'Displayed subtotal - %s', 'amount-left-free-shipping-woocommerce' ), 'WC_Cart::get_displayed_subtotal()' ),
						'get_cart_contents_total' => sprintf( __( 'Cart contents total - %s', 'amount-left-free-shipping-woocommerce' ), 'WC_Cart::get_cart_contents_total()' ),
					),
					'class'    => 'chosen_select',
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'alg_wc_left_to_free_shipping_calculation',
				),
				array(
					'title'    => __( 'Hide by category', 'amount-left-free-shipping-woocommerce' ),
					'type'     => 'title',
					'id'       => 'alg_wc_left_to_free_shipping_hide_by_category_options',
				),
				array(
					'title'             => __( 'Hide by category', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip'          => __( 'Hides the notification if a product from a specific category has been added to cart.', 'amount-left-free-shipping-woocommerce' ).'<br />'.
					                       __( 'Leave it empty to disable.', 'amount-left-free-shipping-woocommerce' ),
					'id'                => 'alg_wc_left_to_free_hide_by_category',
					'class'             => 'chosen_select',
					'custom_attributes' => apply_filters( 'alg_wc_left_to_free_shipping_settings', array( 'disabled' => 'disabled' ) ),
					'default'           => array(),
					'options'           => wp_list_pluck( get_terms( array(
						'taxonomy'   => "product_cat",
						'hide_empty' => false,
					) ), 'name', 'term_id' ),
					'type'              => 'multiselect',
				),
				array(
					'desc'              => __( 'Check children categories', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip'          => sprintf( __( 'Checks for children categories in cart so you can use just the parent categories in the %s option.', 'amount-left-free-shipping-woocommerce' ), '<strong>' . __( 'Hide by category', 'amount-left-free-shipping-woocommerce' ) . '</strong>' ),
					'id'                => 'alg_wc_left_to_free_hide_by_category_check_children',
					'default'           => 'no',
					'type'              => 'checkbox',
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'alg_wc_left_to_free_shipping_hide_by_category_options',
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
					'title'    => __( 'Added to cart event without AJAX', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'Enable', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip' => sprintf( __( 'Fires a custom event %s in case a product has been added to cart without AJAX.', 'amount-left-free-shipping-woocommerce' ), '<code>"alg_wc_alfs_added_to_cart"</code>' ) . '<br />' .
					              __( 'Enable it if the Store notice is not getting displayed on single product pages and if you have the "Hide" option enabled in "Store notice".', 'amount-left-free-shipping-woocommerce' ),
					'type'     => 'checkbox',
					'id'       => 'alg_wc_left_to_free_shipping_ajax_added_to_cart_no_ajax',
					'default'  => 'no',
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
					'title'    => __( 'Check shipping cost', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'Show free shipping message if cart shipping costs are not present, i.e., when shipping total is zero', 'amount-left-free-shipping-woocommerce' ),
					'id'       => 'alg_wc_left_to_free_check_cart_free_shipping',
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'title'    => __( 'Check for alternative free shipping', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'Check for already available alternative (i.e. not requiring minimum order amount) free shipping methods.', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip' => __( 'No text will be outputted in this case.', 'amount-left-free-shipping-woocommerce' ),
					'id'       => 'alg_wc_left_to_free_shipping_check_free_shipping',
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'title'    => __( 'Virtual products', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'Do not display any text if cart consists of virtual products only', 'amount-left-free-shipping-woocommerce' ),
					'id'       => 'alg_wc_left_to_free_shipping_check_virtual',
					'default'  => 'no',
					'checkboxgroup' => 'start',
					'type'     => 'checkbox',
				),
				array(
					'desc'              => __( 'Ignore virtual products in cart to reach the min amount', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip'          => __( 'The free shipping method will also ignore virtual products in cart.', 'amount-left-free-shipping-woocommerce' ).apply_filters( 'alg_wc_left_to_free_shipping_settings', '<br />'.$this->pro_desc ),
					'id'                => 'alg_wc_left_to_free_shipping_ignore_virtual_products',
					'default'           => 'no',
					'type'              => 'checkbox',
					'checkboxgroup'     => 'end',
					'custom_attributes' => apply_filters( 'alg_wc_left_to_free_shipping_settings', array( 'disabled' => 'disabled' ) ),
				),
				array(
					'title'    => __( 'Clear notices', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'Wipe notices when a product has been added to cart', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip' => __( 'Enable if you have problems with duplicated messages.', 'amount-left-free-shipping-woocommerce' ),
					'id'       => 'alg_wc_left_to_free_shipping_clear_notices',
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
					              '<code>echo alg_wc_get_left_to_free_shipping( array( "content" => "%amount_left_for_free_shipping% left for free shipping" ) );</code>' .
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
