<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://datahjelpen.no/
 * @since      1.0.0
 *
 * @package    Dh_Woocommerce_Dual_Cart
 * @subpackage Dh_Woocommerce_Dual_Cart/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Dh_Woocommerce_Dual_Cart
 * @subpackage Dh_Woocommerce_Dual_Cart/includes
 * @author     Datahjelpen AS <post@datahjelpen.no>
 */
class Dh_Woocommerce_Dual_Cart_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'dh-woocommerce-dual-cart',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
