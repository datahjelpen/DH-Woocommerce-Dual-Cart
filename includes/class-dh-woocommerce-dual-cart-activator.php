<?php

/**
 * Fired during plugin activation
 *
 * @link       https://datahjelpen.no/
 * @since      1.0.0
 *
 * @package    Dh_Woocommerce_Dual_Cart
 * @subpackage Dh_Woocommerce_Dual_Cart/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Dh_Woocommerce_Dual_Cart
 * @subpackage Dh_Woocommerce_Dual_Cart/includes
 * @author     Datahjelpen AS <post@datahjelpen.no>
 */
class Dh_Woocommerce_Dual_Cart_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		if( !class_exists( 'WooCommerce' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( __( 'Please install and Activate WooCommerce.', 'dh-woocommerce-dual-cart' ), 'Plugin dependency check', array( 'back_link' => true ) );
		}
	}

}
