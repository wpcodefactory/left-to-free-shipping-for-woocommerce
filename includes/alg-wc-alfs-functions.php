<?php
/**
 * Amount Left for Free Shipping for WooCommerce - Functions
 *
 * @version 1.8.0
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'alg_wc_get_left_to_free_shipping' ) ) {
	/*
	 * alg_wc_get_left_to_free_shipping.
	 *
	 * @version 1.8.0
	 * @since   1.3.0
	 */
	function alg_wc_get_left_to_free_shipping( $content, $multiply_by = 1, $min_free_shipping_amount = 0, $free_delivery_text = false, $is_ajax_response = false ) {
		if ( function_exists( 'alg_wc_left_to_free_shipping' ) ) {
			return alg_wc_left_to_free_shipping()->core->get_left_to_free_shipping(
				$content,
				$multiply_by,
				$min_free_shipping_amount,
				$free_delivery_text,
				$is_ajax_response
			);
		}
	}
}
