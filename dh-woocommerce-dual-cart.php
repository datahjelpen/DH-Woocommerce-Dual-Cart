<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://datahjelpen.no/
 * @since             1.0.0
 * @package           Dh_Woocommerce_Dual_Cart
 *
 * @wordpress-plugin
 * Plugin Name:       DH Woocommerce Dual Cart
 * Plugin URI:        https://datahjelpen.no/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Datahjelpen AS
 * Author URI:        https://datahjelpen.no/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dh-woocommerce-dual-cart
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dh-woocommerce-dual-cart-activator.php
 */
function activate_dh_woocommerce_dual_cart() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dh-woocommerce-dual-cart-activator.php';
	Dh_Woocommerce_Dual_Cart_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dh-woocommerce-dual-cart-deactivator.php
 */
function deactivate_dh_woocommerce_dual_cart() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dh-woocommerce-dual-cart-deactivator.php';
	Dh_Woocommerce_Dual_Cart_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dh_woocommerce_dual_cart' );
register_deactivation_hook( __FILE__, 'deactivate_dh_woocommerce_dual_cart' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dh-woocommerce-dual-cart.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dh_woocommerce_dual_cart() {

	$plugin = new Dh_Woocommerce_Dual_Cart();
	$plugin->run();

	add_action( 'wp_ajax_add_to_request_list_dh_woocommerce_dual_cart', 'add_to_request_list_dh_woocommerce_dual_cart' );
	add_action( 'wp_ajax_nopriv_add_to_request_list_dh_woocommerce_dual_cart', 'add_to_request_list_dh_woocommerce_dual_cart' );
}
run_dh_woocommerce_dual_cart();


function add_to_request_list_dh_woocommerce_dual_cart() {
	global $wpdb; // this is how you get access to the database

	$product_id = intval( $_POST['product_id'] );

	add_to_request_list_notice_dh_woocommerce_dual_cart($product_id);

	echo true;

	wp_die(); // this is required to terminate immediately and return a proper response
}

function add_to_request_list_notice_dh_woocommerce_dual_cart($product_id = null) {
	global $woocommerce;

	$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );

	$titles[] = get_the_title( $product_id );

	$titles = array_filter( $titles );
	$added_text = sprintf( _n( '%s has been added to your request list.', '%s have been added to your request list.', sizeof( $titles ), 'woocommerce' ), wc_format_list_of_items( $titles ) );

	$message = sprintf( '%s <a href="%s" class="button">%s</a> <a href="%s" class="button">%s</a>',
									esc_html( $added_text ),
									esc_url( wc_get_page_permalink( 'checkout' ) ),
									esc_html__( 'Checkout', 'woocommerce' ),
									esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ),
									esc_html__( 'Continue shopping', 'dh_woocommerce_dual_cart' ));

	wc_add_notice( $message, 'success' );
}
