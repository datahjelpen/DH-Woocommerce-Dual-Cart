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

	add_action('init', 'session_start_dh_woocommerce_dual_cart', 1);
	add_action('wp_logout', 'session_end_dh_woocommerce_dual_cart');
	add_action('wp_login', 'session_end_dh_woocommerce_dual_cart');

	add_action( 'wp_ajax_add_to_request_list_dh_woocommerce_dual_cart', 'add_to_request_list_dh_woocommerce_dual_cart' );
	add_action( 'wp_ajax_nopriv_add_to_request_list_dh_woocommerce_dual_cart', 'add_to_request_list_dh_woocommerce_dual_cart' );
	// Cart page HTML
	add_filter('woocommerce_before_cart_table', 'woocommerce_before_cart_table_dh_woocommerce_dual_cart', 11);
	add_filter('woocommerce_after_cart_table', 'woocommerce_after_cart_table_dh_woocommerce_dual_cart', 11);
}
run_dh_woocommerce_dual_cart();

function session_start_dh_woocommerce_dual_cart() {
	if(!session_id()) {
		session_start();
	}

	if (!isset($_SESSION['dh_woocommerce_dual_cart_request_list'])) {
		$_SESSION['dh_woocommerce_dual_cart_request_list'] = [];
	}
}

function session_end_dh_woocommerce_dual_cart() {
	session_destroy();
}

function add_to_request_list_dh_woocommerce_dual_cart() {
	// Retrive POST data
	$product_id = intval( htmlspecialchars($_POST['product_id']) );
	$product_count = intval( htmlspecialchars($_POST['count']) );

	// Temp store the request list
	$request_list = $_SESSION['dh_woocommerce_dual_cart_request_list'];
	$product_was_in_list = false;

	// Make the product object that we put in the list
	$_product = new stdClass;
	$_product->id = $product_id;
	$_product->count = $product_count;

	// Let's first loop through our list to make sure we don't already have the product in it.
	// If our product is in the list, just up the counter
	foreach ($request_list as $item) {
		if ($_product->id == $item->id) {
			$product_was_in_list = true;
			$item->count += $_product->count;
			continue;
		}
	}

	// Product was not in the list, add it
	if (!$product_was_in_list) {
		array_push($_SESSION['dh_woocommerce_dual_cart_request_list'], $_product);
	}

	// Make a notice for the front-end that the product was added to the list
	add_to_request_list_notice_dh_woocommerce_dual_cart($_product->id, $_product->count);

	// Return true as everything went well
	echo true;

	wp_die(); // this is required to terminate immediately and return a proper response
}

function add_to_request_list_notice_dh_woocommerce_dual_cart($product_id = null, $product_count = 1) {
	global $woocommerce;

	$titles[] = get_the_title( $product_id );

	$titles = array_filter( $titles );
	$added_text = sprintf( _n( '%s has been added to your request list.', '%s have been added to your request list.', $product_count, 'dh-woocommerce-dual-cart' ), wc_format_list_of_items( $titles ) );
	$amount_text = sprintf( _n( '', '%s x ', $product_count, 'dh-woocommerce-dual-cart'), esc_html($product_count) );

	// Example: 2 x ProductName have been added ...
	//      or: ProductName has been added ...
	$message = sprintf( '%s %s <a href="%s" class="button">%s</a>',
									esc_html( $amount_text ),
									esc_html( $added_text ),
									esc_url( wc_get_page_permalink( 'cart' ) ),
									esc_html__( 'View request list', 'woocommerce' ));

	wc_add_notice( $message, 'success' );
}

function woocommerce_before_cart_table_dh_woocommerce_dual_cart() {
	require plugin_dir_path( __FILE__ ) . 'templates/cart_before.php';
}
function woocommerce_after_cart_table_dh_woocommerce_dual_cart() {
	require plugin_dir_path( __FILE__ ) . 'templates/cart.php';
}