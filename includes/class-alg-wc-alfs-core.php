<?php
/**
 * Amount Left for Free Shipping for WooCommerce - Core Class
 *
 * @version 2.0.9
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Left_To_Free_Shipping_Core' ) ) :

class Alg_WC_Left_To_Free_Shipping_Core {

	/**
	 * Constructor.
	 *
	 * @version 2.0.7
	 * @since   1.0.0
	 * @todo    [maybe] move shortcodes inside `alg_wc_left_to_free_shipping_enabled` (or maybe remove `alg_wc_left_to_free_shipping_enabled` completely?)
	 */
	function __construct() {
		if ( 'yes' === get_option( 'alg_wc_left_to_free_shipping_enabled', 'yes' ) ) {
			// Cart
			if ( 'yes' === get_option( 'alg_wc_left_to_free_shipping_info_enabled_cart', 'no' ) ) {
				add_action(
					get_option( 'alg_wc_left_to_free_shipping_info_position_cart', 'woocommerce_after_cart_totals' ),
					array( $this, 'show_left_to_free_shipping_info_cart' ),
					get_option( 'alg_wc_left_to_free_shipping_info_priority_cart', 10 )
				);
			}
		}
		// Shortcodes
		add_shortcode( 'alg_get_left_to_free_shipping',          array( $this, 'get_left_to_free_shipping_shortcode' ) ); // deprecated
		add_shortcode( 'alg_wc_left_to_free_shipping',           array( $this, 'get_left_to_free_shipping_shortcode' ) );
		add_shortcode( 'alg_wc_left_to_free_shipping_translate', array( $this, 'translate_shortcode' ) );
		// Default notice
		add_action( 'wp',                                             array( $this, 'create_default_notice' ) );
		add_action( 'woocommerce_add_to_cart_validation',             array( $this, 'clear_notices' ), 1 );
		// Core loaded
		do_action( 'alg_wc_left_to_free_shipping_core_loaded', $this );
		// Min amount extra options
		add_filter( 'alg_wc_left_to_free_shipping_manual_min_amount_available_types', array( $this, 'get_user_role_available_on_min_amount' ), 9, 2 );
		add_filter( 'alg_wc_left_to_free_shipping_manual_min_amount_available_types', array( $this, 'get_currency_available_on_min_amount' ), 10, 2 );
		add_filter( 'alg_wc_left_to_free_shipping_manual_min_amount_available_types', array( $this, 'get_shipping_zone_available_on_min_amount' ), 11, 2 );
	}

	/**
	 * clear_notices.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 *
	 * @param $passed
	 *
	 * @return boolean
	 */
	function clear_notices( $passed ) {
		if ( $passed && 'yes' === get_option( 'alg_wc_left_to_free_shipping_clear_notices', 'no' ) ) {
			wc_clear_notices();
		}
		return $passed;
	}

	/**
	 * can_display_default_notice_by_function.
	 *
	 * @version 1.9.6
	 * @since   1.9.6
	 *
	 * @param $functions
	 *
	 * @return bool
	 */
	function can_display_default_notice_by_function( $functions ) {
		foreach ( $functions as $function ) {
			if ( function_exists( $function ) && call_user_func( $function ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * create_default_notice.
	 *
	 * @version 1.9.6
	 * @since   1.9.6
	 */
	function create_default_notice() {
		if (
			'no' == get_option( 'alg_wc_left_to_free_shipping_default_notice_enabled', 'no' )
			|| is_admin()
			|| wp_doing_ajax()
			||
			(
				! empty( $functions = get_option( 'alg_wc_left_to_free_shipping_default_notice_display_by_function', '' ) )
				&& ! $this->can_display_default_notice_by_function( $functions )
			)
		) {
			return;
		}
		$message = $this->get_left_to_free_shipping( array(
			'content'          => get_option( 'alg_wc_left_to_free_shipping_default_notice_content', $this->get_default_content() ),
			'is_ajax_response' => true
		) );
		wc_add_notice( $message, get_option( 'alg_wc_left_to_free_shipping_default_notice_type', 'notice' ) );
	}

	/**
	 * add_to_log.
	 *
	 * @version 1.7.0
	 * @since   1.7.0
	 */
	function add_to_log( $message ) {
		if ( function_exists( 'wc_get_logger' ) && ( $log = wc_get_logger() ) ) {
			$log->log( 'info', $message, array( 'source' => 'alg-wc-alfs' ) );
		}
	}

	/*
	 * get_left_to_free_shipping_shortcode.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function get_left_to_free_shipping_shortcode( $atts, $content ) {
		$atts = shortcode_atts( array(
			'content'                  => '',
			'template'                 => '{content}',
			'multiply_by'              => 1,
			'min_free_shipping_amount' => 0,
			'free_delivery_text'       => false,
			'min_cart_amount'          => get_option( 'alg_wc_left_to_free_shipping_min_cart_amount', 0 )
		), $atts, 'alg_wc_left_to_free_shipping' );
		return $this->get_left_to_free_shipping( $atts );
	}

	/**
	 * translate_shortcode.
	 *
	 * @version 1.8.0
	 * @since   1.3.0
	 */
	function translate_shortcode( $atts, $content = '' ) {
		// E.g.: `[alg_wc_left_to_free_shipping_translate lang="DE" lang_text="%amount_left_for_free_shipping% für kostenlosen Versand" not_lang_text="%amount_left_for_free_shipping% left for free shipping"]`
		if ( isset( $atts['lang_text'] ) && isset( $atts['not_lang_text'] ) && ! empty( $atts['lang'] ) ) {
			return ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) ) ?
				$atts['not_lang_text'] : $atts['lang_text'];
		}
		// E.g.: `[alg_wc_left_to_free_shipping_translate lang="DE"]%amount_left_for_free_shipping% für kostenlosen Versand[/alg_wc_left_to_free_shipping_translate][alg_wc_left_to_free_shipping_translate not_lang="DE"]%amount_left_for_free_shipping% left for free shipping[/alg_wc_left_to_free_shipping_translate]`
		return (
			( ! empty( $atts['lang'] )     && ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) ) ) ||
			( ! empty( $atts['not_lang'] ) &&     defined( 'ICL_LANGUAGE_CODE' ) &&   in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['not_lang'] ) ) ) ) )
		) ? '' : $content;
	}

	/**
	 * show_left_to_free_shipping_info_cart.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function show_left_to_free_shipping_info_cart() {
		$args          = array( 'content' => get_option( 'alg_wc_left_to_free_shipping_info_content_cart', $this->get_default_content() ) );
		$wrap_template = get_option( 'alg_wc_left_to_free_shipping_cart_wrap_template', '<tr><th></th><td>{content}</td></tr>' );
		if (
			'smart' === ( $wrap_method = get_option( 'alg_wc_left_to_free_shipping_cart_wrap_method', 'ignore' ) )
			&& in_array( current_filter(), array(
				'woocommerce_cart_totals_before_shipping',
				'woocommerce_cart_totals_after_shipping',
				'woocommerce_cart_totals_before_order_total',
				'woocommerce_cart_totals_after_order_total'
			) ) ) {
			$args['template'] = $wrap_template;
		} elseif ( 'force' === $wrap_method ) {
			$args['template'] = $wrap_template;
		}
		echo $this->get_left_to_free_shipping( $args );
	}

	/*
	 * get_cart_total.
	 *
	 * @version 2.0.9
	 * @since   1.4.0
	 * @see     `WC_Shipping_Free_Shipping::is_available()` (/woocommerce/includes/shipping/free-shipping/class-wc-shipping-free-shipping.php)
	 */
	function get_cart_total() {
		if ( ! function_exists( 'WC' ) || ! isset( WC()->cart ) ) {
			return 0;
		}
		$cart_total_params   = array(
			'get_total' => array( 'raw' )
		);
		$cart_total_function = get_option( 'alg_wc_left_to_free_shipping_cart_total_method', 'get_displayed_subtotal' );
		$cart_total_params   = isset( $cart_total_params[ $cart_total_function ] ) ? $cart_total_params[ $cart_total_function ] : array();
		$total               = call_user_func_array( array( WC()->cart, $cart_total_function ), $cart_total_params );
		if ( 'yes' === get_option( 'alg_wc_left_to_free_shipping_include_discounts', 'yes' ) ) {
			if ( WC()->cart->display_prices_including_tax() ) {
				$total = round( $total - ( WC()->cart->get_discount_total() + WC()->cart->get_discount_tax() ), wc_get_price_decimals() );
			} else {
				$total = round( $total - WC()->cart->get_discount_total(), wc_get_price_decimals() );
			}
		}
		return apply_filters( 'alg_wc_left_to_free_shipping_cart_total', $total );
	}

	/**
	 * is_cart_virtual.
	 *
	 * @version 1.8.0
	 * @since   1.6.0
	 */
	function is_cart_virtual() {
		if ( 'yes' === get_option( 'alg_wc_left_to_free_shipping_check_virtual', 'no' ) ) {
			if ( function_exists( 'WC' ) && isset( WC()->cart ) && ! WC()->cart->is_empty() ) {
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					if ( $cart_item['data'] instanceof WC_Product ) {
						if ( ! $cart_item['data']->is_virtual() ) {
							return false;
						}
					}
				}
				return true;
			}
		}
		return false;
	}

	/**
	 * is_equal.
	 *
	 * @version 1.8.0
	 * @since   1.4.2
	 * @todo    [maybe] better epsilon value
	 */
	function is_equal( $float1, $float2 ) {
		$epsilon = ( defined( 'PHP_FLOAT_EPSILON' ) ? PHP_FLOAT_EPSILON : 0.000000001 );
		return ( abs( $float1 - $float2 ) < $epsilon );
	}

	/**
	 * get_default_content.
	 *
	 * @version 1.8.0
	 * @since   1.6.0
	 */
	function get_default_content() {
		return sprintf( __( '%s left for free shipping', 'amount-left-free-shipping-woocommerce' ), '%amount_left_for_free_shipping%' );
	}

	/*
	 * get_manual_min_amount_types.
	 *
	 * @version 2.0.7
	 * @since   1.9.0
	 */
	function get_manual_min_amount_types() {
		return array(
			'roles'            => __( 'User roles', 'amount-left-free-shipping-woocommerce' ),
			'currencies'       => __( 'Currencies', 'amount-left-free-shipping-woocommerce' ),
			'zones'            => __( 'Shipping zones', 'amount-left-free-shipping-woocommerce' ),
			'shipping_methods' => __( 'Shipping methods', 'amount-left-free-shipping-woocommerce' ),
		);
	}

	/**
	 * get_user_role_available_on_min_amount.
	 *
	 * @version 2.0.7
	 * @since   2.0.7
	 *
	 * @param $id_array
	 * @param $args
	 *
	 * @return array
	 */
	function get_user_role_available_on_min_amount( $id_array, $args ) {
		$types = $args['types'];
		if ( isset( $types['roles'] ) ) {
			$user          = ( function_exists( 'wp_get_current_user' ) ? wp_get_current_user() : false );
			$current_roles = ( ! empty( $user->roles ) ? $user->roles : array( 'guest' ) );
			foreach ( $current_roles as $current_role ) {
				if ( '' == $current_role ) {
					$current_role = 'guest';
				}
				if ( in_array( $current_role, $types['roles'] ) ) {
					$id_array[] = $current_role;
				}
			}
		}
		return $id_array;
	}

	/**
	 * get_currency_available_on_min_amount.
	 *
	 * @version 2.0.7
	 * @since   2.0.7
	 *
	 * @param $id_array
	 * @param $args
	 *
	 * @return array
	 */
	function get_currency_available_on_min_amount( $id_array, $args ) {
		$types = $args['types'];
		if ( isset( $types['currencies'] ) ) {
			$current_currency = get_woocommerce_currency();
			if ( in_array( $current_currency, $types['currencies'] ) ) {
				$id_array[] = 'currencies:' . $current_currency;
			}
		}
		return $id_array;
	}

	/**
	 * get_shipping_zone_available_on_min_amount.
	 *
	 * @version 2.0.7
	 * @since   2.0.7
	 *
	 * @param $id_array
	 * @param $args
	 *
	 * @return array
	 */
	function get_shipping_zone_available_on_min_amount( $id_array, $args ) {
		$types = $args['types'];
		if ( isset( $types['zones'] ) ) {
			if ( function_exists( 'WC' ) && ( $wc_cart = WC()->cart ) ) {
				$packages = $wc_cart->get_shipping_packages();
				foreach ( $packages as $package ) {
					$shipping_zone = WC_Shipping_Zones::get_zone_matching_package( $package );
					$current_zone  = 'z' . $shipping_zone->get_id();
					if ( in_array( $current_zone, $types['zones'] ) ) {
						$id_array[] = 'zones:' . $current_zone;
					}
				}
			}
		}
		return $id_array;
	}

	/*
	 * get_manual_min_amount.
	 *
	 * @version 2.0.7
	 * @since   1.9.0
	 * @todo    [maybe] pre-check `function_exists( 'WC' ) && ( $wc_cart = WC()->cart )`
	 */
	function get_manual_min_amount() {
		if ( 'yes' !== get_option( 'alg_wc_left_to_free_shipping_mma_enabled', 'yes' ) ) {
			return false;
		}
		// Extra options
		$amounts = get_option( 'alg_wc_left_to_free_shipping_mma_roles_val', array() );
		if ( ! empty( $amounts ) ) {
			$types = array();
			foreach ( $this->get_manual_min_amount_types() as $type => $title ) {
				$values = get_option( "alg_wc_left_to_free_shipping_mma_{$type}", array() );
				if ( ! empty( $values ) ) {
					$types[ $type ] = $values;
				}
			}
			if ( ! empty( $types ) ) {
				$id  = apply_filters( 'alg_wc_left_to_free_shipping_manual_min_amount_available_types', array(), array( 'types' => $types ) );
				$_id = implode( '|', $id );
				if ( ! empty( $_id ) && ! empty( $amounts[ $_id ] ) ) {
					return apply_filters( 'alg_wc_left_to_free_shipping_manual_min_amount', array( 'amount' => $amounts[ $_id ], 'is_available' => false ), array(
						'id'      => $_id,
						'amounts' => $amounts,
					) );
				}
			}
		}
		// General manual min amount
		if ( 0 != ( $manual_min_amount = get_option( 'alg_wc_left_to_free_shipping_manual_min_amount', 0 ) ) ) {
			return apply_filters( 'alg_wc_left_to_free_shipping_manual_min_amount', array( 'amount' => $manual_min_amount, 'is_available' => false ), array( 'amounts' => $amounts ) );
		}
		// No manual min amount
		return false;
	}

	/*
	 * get_min_free_shipping_amount.
	 *
	 * @version 1.9.0
	 * @since   1.4.0
	 * @todo    [next] `either`: should check for coupon (e.g. coupon not applied: "Or a coupon";            coupon applied: "You have free delivery!")
	 * @todo    [next] `both`:   should check for coupon (e.g. coupon not applied: "You also need a coupon"; coupon applied: "You have free delivery!")
	 * @todo    [maybe] split into smaller functions
	 * @todo    [maybe] Flat rate + zero rate (both by shipping class or no class)
	 */
	function get_min_free_shipping_amount() {
		$is_available = false;
		// Manual min amount
		if ( false !== ( $manual_min_amount_data = $this->get_manual_min_amount() ) ) {
			return $manual_min_amount_data;
		}
		// Automatic min amount
		$min_free_shipping_amount = 0;
		$current_wc_version       = get_option( 'woocommerce_version', null );
		if ( version_compare( $current_wc_version, '2.6.0', '<' ) ) {
			$free_shipping = new WC_Shipping_Free_Shipping();
			if ( in_array( $free_shipping->requires, array( 'min_amount', 'either', 'both' ) ) ) {
				$min_free_shipping_amount = $free_shipping->min_amount;
			}
		} else {
			$legacy_free_shipping = new WC_Shipping_Legacy_Free_Shipping();
			if ( 'yes' === $legacy_free_shipping->enabled ) {
				if ( in_array( $legacy_free_shipping->requires, array( 'min_amount', 'either', 'both' ) ) ) {
					$min_free_shipping_amount = $legacy_free_shipping->min_amount;
				}
			}
			$do_check_for_available_free_shipping = ( 'yes' === get_option( 'alg_wc_left_to_free_shipping_check_free_shipping', 'no' ) );
			if (
				0 == $min_free_shipping_amount &&
				function_exists( 'WC' ) && ( $wc_shipping = WC()->shipping ) && ( $wc_cart = WC()->cart ) &&
				$wc_shipping->enabled &&
				( $packages = $wc_cart->get_shipping_packages() )
			) {
				$shipping_methods = $wc_shipping->load_shipping_methods( $packages[0] );
				foreach ( $shipping_methods as $shipping_method ) {
					if (
						'yes' === $shipping_method->enabled && 0 != $shipping_method->instance_id &&
						$shipping_method instanceof WC_Shipping_Free_Shipping
					) {
						if ( in_array( $shipping_method->requires, array( 'min_amount', 'either', 'both' ) ) ) {
							if ( $shipping_method->is_available( $packages[0] ) ) {
								$is_available = true;
							}
							$min_free_shipping_amount = $shipping_method->min_amount;
							if ( ! $do_check_for_available_free_shipping ) {
								break;
							}
						} elseif ( $do_check_for_available_free_shipping ) {
							$is_available = true;
							$min_free_shipping_amount = 0;
							break;
						}
					}
				}
			}
		}
		return array( 'amount' => $min_free_shipping_amount, 'is_available' => $is_available );
	}

	/**
	 * are_there_available_shipping_methods.
	 *
	 * @version 1.9.6
	 * @since   1.9.6
	 *
	 * @return bool
	 */
	function are_there_available_shipping_methods() {
		$rates    = array();
		$packages = WC()->shipping()->get_packages();
		foreach ( $packages as $i => $package ) {
			if ( ! empty( $package['rates'] ) ) {
				$rates[] = $package['rates'];
			}
		}
		return count( $rates ) > 0 ? true : false;
	}

	/**
	 * check_cart_free_shipping.
	 *
	 * @version 1.9.6
	 * @since   1.9.6
	 *
	 * @return bool
	 */
	function check_cart_free_shipping() {
		if (
			'yes' == get_option( 'alg_wc_left_to_free_check_cart_free_shipping', 'no' )
			&& WC()->cart
			&& WC()->cart->get_shipping_total() == 0
			&& $this->are_there_available_shipping_methods()
		) {
			return true;
		}
		return false;
	}

	/*
	 * get_left_to_free_shipping.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 * @return  string
	 * @todo    [next] `$empty_cart_text` as function optional param (similar as `$free_delivery_text`)
	 * @todo    [maybe] add `notice_type` param (i.e. `cart`, `mini-cart`, `checkout`, `store-notice` etc.)
	 */
	function get_left_to_free_shipping( $args = null ) {
		$args = wp_parse_args( $args, array(
			'content'                  => '',
			'multiply_by'              => 1,
			'min_free_shipping_amount' => 0,
			'free_delivery_text'       => false,
			'template'                 => '{content}',
			'is_ajax_response'         => false,
			'min_cart_amount'          => get_option( 'alg_wc_left_to_free_shipping_min_cart_amount', 0 )
		) );

		if ( 0 == $args['min_free_shipping_amount'] ) {
			$min_free_shipping_amount_data = $this->get_min_free_shipping_amount();
			$args['min_free_shipping_amount']      = $min_free_shipping_amount_data['amount'];
		}
		if (
			apply_filters( 'alg_wc_get_left_to_free_shipping_validation', true )
			&& 0 != $args['min_free_shipping_amount']
			&& ! $this->is_cart_virtual()
			&& (
				$args['min_cart_amount'] == 0
				|| ( $args['min_cart_amount'] > 0 && $this->get_cart_total() > $args['min_cart_amount'] )
			)
		) {
			$total = $this->get_cart_total();
			// Placeholders
			$amount_left_for_free_shipping = ( $args['min_free_shipping_amount'] - $total ) * $args['multiply_by'];
			$free_shipping_min_amount      = ( $args['min_free_shipping_amount'] )          * $args['multiply_by'];
			$current_cart_total            = ( $total )                             * $args['multiply_by'];
			$placeholders = array(
				'%amount_left_for_free_shipping%'     => wc_price( $amount_left_for_free_shipping ),
				'%free_shipping_min_amount%'          => wc_price( $free_shipping_min_amount ),
				'%current_cart_total%'                => wc_price( $current_cart_total ),
				'%amount_left_for_free_shipping_raw%' => $amount_left_for_free_shipping,
				'%free_shipping_min_amount_raw%'      => $free_shipping_min_amount,
				'%current_cart_total_raw%'            => $current_cart_total,
			);
			// Content
			if (
				$min_free_shipping_amount_data['is_available']
				|| $total > $args['min_free_shipping_amount']
				|| $this->is_equal( $total, $args['min_free_shipping_amount'] )
				|| $this->check_cart_free_shipping()
			) {
				if ( false === $args['free_delivery_text'] ) {
					$args['free_delivery_text'] = get_option( 'alg_wc_left_to_free_shipping_info_content_reached',
						__( 'You have free delivery!', 'amount-left-free-shipping-woocommerce' ) );
				}
				$result = $args['free_delivery_text'];
			} else {
				if (
					'yes' === get_option( 'alg_wc_left_to_free_shipping_custom_empty_cart', 'no' ) &&
					function_exists( 'WC' ) && isset( WC()->cart ) && WC()->cart->is_empty()
				) {
					$result = get_option( 'alg_wc_left_to_free_shipping_info_content_empty_cart', '' );
				} else {
					if ( '' == $args['content'] ) {
						$args['content'] = $this->get_default_content();
					}
					$result = $args['content'];
				}
			}
			if ( '' === $result ) {
				return null;
			}
			$result = str_replace( array_keys( $placeholders ), $placeholders, $result );
			$result = do_shortcode( $result );
			$args['original_result'] = $result;
			$result = str_replace( '{content}', $result, $args['template'] );
			// Result
			return apply_filters( 'alg_wc_get_left_to_free_shipping', $result, $args );
		}
	}

	/*
	 * get_default_ajax_events.
	 *
	 * @version 1.9.1
	 * @since   1.9.1
	 * @todo    [next] [maybe] add `wc_fragments_loaded updated_shipping_method applied_coupon removed_coupon wc_fragments_refreshed`
	 */
	function get_default_ajax_events() {
		return array(
			'updated_cart_totals',
			'added_to_cart',
			'removed_from_cart',
			'wc_fragment_refresh',
			'updated_checkout',
			'wc_cart_emptied',
		);
	}

}

endif;

return new Alg_WC_Left_To_Free_Shipping_Core();
