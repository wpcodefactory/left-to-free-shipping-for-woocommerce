<?php
/**
 * Amount Left for Free Shipping for WooCommerce - Manual Min Amount Section Settings
 *
 * @version 2.0.5
 * @since   1.9.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Left_To_Free_Shipping_Settings_Manual_Min_Amount' ) ) :

class Alg_WC_Left_To_Free_Shipping_Settings_Manual_Min_Amount extends Alg_WC_Left_To_Free_Shipping_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.9.2
	 * @since   1.9.0
	 */
	function __construct() {
		$this->id   = 'manual_min_amount';
		$this->desc = __( 'Manual min amount', 'amount-left-free-shipping-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_user_roles_options.
	 *
	 * @version 1.9.0
	 * @since   1.4.5
	 */
	function get_user_roles_options() {
		global $wp_roles;
		$roles = apply_filters( 'editable_roles', ( ( isset( $wp_roles ) && is_object( $wp_roles ) ) ? $wp_roles->roles : array() ) );
		return array_merge( array( 'guest' => __( 'Guest', 'amount-left-free-shipping-woocommerce' ) ), wp_list_pluck( $roles, 'name' ) );
	}

	/**
	 * get_shipping_zones.
	 *
	 * @version 1.9.0
	 * @since   1.9.0
	 */
	function get_shipping_zones( $include_empty_zone = true ) {
		$zones = WC_Shipping_Zones::get_zones();
		if ( $include_empty_zone ) {
			$zone                                                = new WC_Shipping_Zone( 0 );
			$zones[ $zone->get_id() ]                            = $zone->get_data();
			$zones[ $zone->get_id() ]['zone_id']                 = $zone->get_id();
			$zones[ $zone->get_id() ]['formatted_zone_location'] = $zone->get_formatted_location();
			$zones[ $zone->get_id() ]['shipping_methods']        = $zone->get_shipping_methods();
		}
		return $zones;
	}

	/**
	 * get_shipping_zones_options.
	 *
	 * @version 1.9.0
	 * @since   1.9.0
	 */
	function get_shipping_zones_options( $include_empty_zone = true ) {
		$zones = array();
		foreach ( $this->get_shipping_zones( $include_empty_zone ) as $zone_id => $zone_data ) {
			$zones[ 'z' . $zone_id ] = $zone_data['formatted_zone_location'];
		}
		return $zones;
	}

	/**
	 * get_amount_atts.
	 *
	 * @version 1.9.0
	 * @since   1.9.0
	 * @todo    [maybe] `'min' => -1` (i.e. no min amount)
	 */
	function get_amount_atts() {
		return array( 'min' => 0, 'step' => 0.000001 );
	}

	/**
	 * get_settings.
	 *
	 * @version 2.0.5
	 * @since   1.9.0
	 * @todo    [next] currency: conversion (i.e. exchange rates) (manual and automatic)
	 * @todo    [next] rename `alg_wc_left_to_free_shipping_mma_roles_val` to e.g. `alg_wc_left_to_free_shipping_mma_amounts`
	 * @todo    [next] `alg_wc_left_to_free_shipping_mma_enabled`: default to `no`
	 * @todo    [maybe] "Extra Options": rename?
	 * @todo    [maybe] better desc
	 */
	function get_settings() {

		$general_settings = array(
			array(
				'title'    => __( 'Advanced', 'amount-left-free-shipping-woocommerce' ) . ': ' . __( 'Manual Min Amount', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Generally the plugin will retrieve the minimum free shipping order amount automatically, however, if you are using <strong>non-standard</strong> shipping methods for free shipping (i.e. not the original "Free shipping" method from WooCommerce), you need to set the amount manually here.', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_left_to_free_shipping_mma_options',
			),
			array(
				'title'    => __( 'Manual min amount', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable section', 'amount-left-free-shipping-woocommerce' ) . '</strong>',
				'id'       => 'alg_wc_left_to_free_shipping_mma_enabled',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_left_to_free_shipping_mma_options',
			),
			array(
				'title'    => __( 'Manual Min Amount', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Ignored if set to zero.', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_left_to_free_shipping_mma_general_options',
			),
			array(
				'title'    => __( 'Manual min amount', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_manual_min_amount',
				'default'  => 0,
				'type'     => 'number',
				'custom_attributes' => $this->get_amount_atts(),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_left_to_free_shipping_mma_general_options',
			),
		);

		$all_values = array(
			'roles'      => $this->get_user_roles_options(),
			'currencies' => get_woocommerce_currencies(),
			'zones'      => $this->get_shipping_zones_options(),
		);
		$extra_settings = array(
			array(
				'title'    => __( 'Extra Options', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Here you can optionally select user roles, currencies and/or shipping zones to set individual manual min amounts for.', 'amount-left-free-shipping-woocommerce' ) . ' ' .
					__( 'Select values and "Save changes" for new option fields.', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_left_to_free_shipping_mma_extra_options',
			),
		);
		foreach ( alg_wc_left_to_free_shipping()->core->get_manual_min_amount_types() as $type => $title ) {
			$extra_settings = array_merge( $extra_settings, array(
				array(
					'title'    => $title,
					'id'       => "alg_wc_left_to_free_shipping_mma_{$type}",
					'default'  => array(),
					'type'     => 'multiselect',
					'class'    => 'chosen_select',
					'options'  => $all_values[ $type ],
				),
			) );
		}
		$extra_settings = array_merge( $extra_settings, array(
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_left_to_free_shipping_mma_extra_options',
			),
		) );

		$extra_amounts_settings = array();
		$types                  = array();
		$do_display             = false;
		foreach ( alg_wc_left_to_free_shipping()->core->get_manual_min_amount_types() as $type => $title ) {
			$values = get_option( "alg_wc_left_to_free_shipping_mma_{$type}", array() );
			if ( ! empty( $values ) ) {
				$types[ $type ] = $values;
				$do_display     = true;
			} else {
				$types[ $type ] = array( '' );
			}
		}
		if ( $do_display ) {
			$extra_amounts_settings = array(
				array(
					'title'    => __( 'Extra Options', 'amount-left-free-shipping-woocommerce' ) . ': ' . __( 'Amounts', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'Ignored if set to zero.', 'amount-left-free-shipping-woocommerce' ),
					'type'     => 'title',
					'id'       => 'alg_wc_left_to_free_shipping_mma_extra_amounts_options',
				),
			);
			foreach ( $types['roles'] as $value_roles ) {
				foreach ( $types['currencies'] as $value_currencies ) {
					foreach ( $types['zones'] as $value_zones ) {
						// Get title and ID
						$extra_amount_title = array();
						$extra_amount_id    = array();
						foreach ( alg_wc_left_to_free_shipping()->core->get_manual_min_amount_types() as $type => $title ) {
							$value = 'value_' . $type;
							$value = $$value;
							if ( '' !== $value ) {
								$extra_amount_title[] = ( isset( $all_values[ $type ][ $value ] ) ? $all_values[ $type ][ $value ] : $value );
								$extra_amount_id[]    = ( 'roles' === $type ? '' : $type . ':' ) . $value;
							}
						}
						$extra_amount_title = implode( ' | ', $extra_amount_title );
						$extra_amount_id    = implode( '|',   $extra_amount_id );
						// Add settings field
						$extra_amounts_settings = array_merge( $extra_amounts_settings, array(
							array(
								'title'    => $extra_amount_title,
								'id'       => "alg_wc_left_to_free_shipping_mma_roles_val[{$extra_amount_id}]", // mislabeled, should be e.g. `alg_wc_left_to_free_shipping_mma_amounts`
								'default'  => 0,
								'type'     => 'number',
								'custom_attributes' => $this->get_amount_atts(),
							),
						) );
					}
				}
			}
			$extra_amounts_settings = array_merge( $extra_amounts_settings, array(
				array(
					'type'     => 'sectionend',
					'id'       => 'alg_wc_left_to_free_shipping_mma_extra_amounts_options',
				),
			) );
		}

		$compatibility_settings = array(
			array(
				'title'    => __( 'Compatibility', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Compatibility with 3rd party plugins or solutions.', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_left_to_free_shipping_mma_compatibility_options',
			),
			array(
				'title'    => __( 'WooCommerce Currency Switcher (realmag777)', 'emails-verification-for-woocommerce' ),
				'desc'     => sprintf( __( 'Convert manual min amount value to current currency when using "<a target="_blank" href="%s">WooCommerce Currency Switcher</a>" plugin made by author <a href="%s" target="_blank">realmag777</a>', 'emails-verification-for-woocommerce' ), 'https://currency-switcher.com/', 'https://pluginus.net/' ),
				'desc_tip' => empty( apply_filters( 'alg_wc_left_to_free_shipping_settings', true ) ) ? __( 'Extra options will also be converted except currencies values.', 'emails-verification-for-woocommerce' ) : '',
				'id'       => 'alg_wc_left_to_free_shipping_mma_compatibility_woocs',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_left_to_free_shipping_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_left_to_free_shipping_mma_compatibility_options',
			),
		);

		return array_merge( $general_settings, $extra_settings, $extra_amounts_settings, $compatibility_settings );
	}

}

endif;

return new Alg_WC_Left_To_Free_Shipping_Settings_Manual_Min_Amount();
