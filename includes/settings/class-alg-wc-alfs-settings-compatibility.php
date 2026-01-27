<?php
/**
 * Cost of Goods for WooCommerce - Compatibility Settings.
 *
 * @version 2.5.1
 * @since   2.5.1
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Cost_of_Goods_Settings_Compatibility' ) ) :

	class Alg_WC_Left_To_Free_Shipping_Settings_Compatibility extends Alg_WC_Left_To_Free_Shipping_Settings_Section {

		/**
		 * Constructor.
		 *
		 * @version 2.5.1
		 * @since   2.5.1
		 */
		function __construct() {
			$this->id   = 'compatibility';
			$this->desc = __( 'Compatibility', 'amount-left-free-shipping-woocommerce' );
			parent::__construct();
		}

		/**
		 * get_settings.
		 *
		 * @version 2.5.1
		 * @since   2.5.1
		 */
		function get_settings() {

			$wbw_product_table_opts = array(
				$this->get_default_compatibility_title_option( array(
					'title' => __( 'PW WooCommerce Exclude Free Shipping', 'amount-left-free-shipping-woocommerce' ),
					'link'  => 'https://wordpress.org/plugins/pw-woocommerce-exclude-free-shipping/',
					'type'  => 'plugin',
					'id'    => 'alg_wc_left_to_free_shipping_comp_pwwcefs_opts',
				) ),
				array(
					'title'             => __( 'Disable Free Shipping check', 'amount-left-free-shipping-woocommerce' ),
					'desc'              => sprintf( __( 'Disable Free Shipping check if a product marked as %s is in the cart', 'amount-left-free-shipping-woocommerce' ), '<strong>' . __( 'Exclude Free Shipping', 'amount-left-free-shipping-woocommerce' ) . '</strong>' ),
					'desc_tip'          => '',
					'type'              => 'checkbox',
					'id'                => 'alg_wc_left_to_free_shipping_pwwcefs_disable_free_shipping_check',
					'custom_attributes' => apply_filters( 'alg_wc_left_to_free_shipping_settings', array( 'disabled' => 'disabled' ) ),
					'default'           => 'no',
				),
				array(
					//'title'           => __( 'Wrap template', 'amount-left-free-shipping-woocommerce' ),
					'type'            => 'checkbox',
					'desc'            => __( 'Display notice', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip'        => sprintf(__( 'Display notice if there are products in the cart marked as %s', 'amount-left-free-shipping-woocommerce' ),'<strong>' . __( 'Exclude Free Shipping', 'amount-left-free-shipping-woocommerce' ) . '</strong>'),
					'id'              => 'alg_wc_left_to_free_shipping_pwwcefs_disable_free_shipping_check_display_msg',
					'default'         => 'no',
				),
				array(
					'type'            => 'text',
					'desc'            => sprintf( __( 'Available placeholders: %s.', 'amount-left-free-shipping-woocommerce' ), '<code>' . implode( '</code>, <code>', array(
							'%pw_efs_products%',
						) ) . '</code>' ),
					'id'              => 'alg_wc_left_to_free_shipping_comp_pwwcefs_disable_free_shipping_check_msg',
					'default'         => __( 'Free shipping is not available while the following items are in the cart: %pw_efs_products%.', 'amount-left-free-shipping-woocommerce' ),
					'css'             => 'width:600px',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_left_to_free_shipping_comp_pwwcefs_opts',
				),
			);

			return array_merge(
				$wbw_product_table_opts,
			);

		}

		/**
		 * get_default_compatibility_title_option.
		 *
		 * @version 3.2.4
		 * @since   3.2.4
		 *
		 * @param $args
		 *
		 * @return array
		 */
		function get_default_compatibility_title_option( $args = null ) {
			$args = wp_parse_args( $args, array(
				'link'  => '',
				'title' => '',
				'type'  => 'plugin', // plugin | theme
				'id'    => '',
			) );

			$product_type = 'plugin' === $args['type'] ? __( 'plugin', 'amount-left-free-shipping-woocommerce' ) : __( 'theme', 'amount-left-free-shipping-woocommerce' );

			return array(
				'title' => $args['title'],
				'type'  => 'title',
				'desc'  => sprintf(
					__( 'Compatibility with %s %s.', 'amount-left-free-shipping-woocommerce' ),
					'<a href="' . esc_url( $args['link'] ) . '" target="_blank">' . esc_html( $args['title'] ) . '</a>',
					$product_type
				),
				'id'    => $args['id']
			);
		}


	}

endif;

return new Alg_WC_Left_To_Free_Shipping_Settings_Compatibility();
