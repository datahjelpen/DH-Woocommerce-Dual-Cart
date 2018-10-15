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

	// Session storage
	add_action('init', 'session_start_dh_woocommerce_dual_cart', 1);
	add_action('wp_logout', 'session_end_dh_woocommerce_dual_cart');
	add_action('wp_login', 'session_end_dh_woocommerce_dual_cart');

	// Add to request list
	add_action( 'wp_ajax_add_to_request_list_dh_woocommerce_dual_cart', 'add_to_request_list_dh_woocommerce_dual_cart' );
	add_action( 'wp_ajax_nopriv_add_to_request_list_dh_woocommerce_dual_cart', 'add_to_request_list_dh_woocommerce_dual_cart' );

	// Remove from request list
	add_action( 'wp_ajax_remove_from_request_list_dh_woocommerce_dual_cart', 'remove_from_request_list_dh_woocommerce_dual_cart' );
	add_action( 'wp_ajax_nopriv_remove_from_request_list_dh_woocommerce_dual_cart', 'remove_from_request_list_dh_woocommerce_dual_cart' );

	// Update request list
	add_action( 'wp_ajax_update_request_list_dh_woocommerce_dual_cart', 'update_request_list_dh_woocommerce_dual_cart' );
	add_action( 'wp_ajax_nopriv_update_request_list_dh_woocommerce_dual_cart', 'update_request_list_dh_woocommerce_dual_cart' );

	// Cart page HTML
	add_filter('woocommerce_before_cart_table', 'woocommerce_before_cart_table_dh_woocommerce_dual_cart', 11);

	// Custom template for request list page
	add_filter('theme_page_templates', 'custom_template');
	add_filter('template_include', 'dhwcdc_load_template');

	// Add some custom body classes
	add_filter('body_class', 'dhwcdc_add_body_classes');
}
run_dh_woocommerce_dual_cart();

function session_start_dh_woocommerce_dual_cart() {
	if(!session_id()) {
		session_start();
	}

	if (!isset($_SESSION['dh_woocommerce_dual_cart_request_list'])) {
		$_SESSION['dh_woocommerce_dual_cart_request_list'] = [];
		$_SESSION['dh_woocommerce_dual_cart_request_list_count'] = 0;
	}
}

function session_end_dh_woocommerce_dual_cart() {
	session_destroy();
}

// Notice: item(s) added
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
									'/foresporsel-liste',
									esc_html__( 'View request list', 'dh-woocommerce-dual-cart' ));

	wc_add_notice( $message, 'success' );
}

// Notice: item(s) removed
function remove_from_request_list_notice_dh_woocommerce_dual_cart($product_id = null, $product_count = 1) {
	global $woocommerce;

	$titles[] = get_the_title( $product_id );

	$titles = array_filter( $titles );
	$added_text = sprintf( _n( '%s has been removed from your request list.', '%s have been removed from your request list.', $product_count, 'dh-woocommerce-dual-cart' ), wc_format_list_of_items( $titles ) );
	$amount_text = sprintf( _n( '', '%s x ', $product_count, 'dh-woocommerce-dual-cart'), esc_html($product_count) );

	// Example: 2 x ProductName have been remove ...
	//      or: ProductName has been remove ...
	$message = sprintf( '%s %s', esc_html( $amount_text ), esc_html( $added_text ));

	wc_add_notice( $message, 'success' );
}

// Notice: List updated
function updated_request_list_notice_dh_woocommerce_dual_cart($product_id = null, $product_count = 1) {
	global $woocommerce;
	$message = __('Your request list has been updated.', 'dh-woocommerce-dual-cart');
	wc_add_notice( $message, 'success' );
}

// Remove item from list
function remove_from_request_list_dh_woocommerce_dual_cart() {
	$product_id = intval( htmlspecialchars($_POST['product_id']) );

	if (isset($_SESSION['dh_woocommerce_dual_cart_request_list'][$product_id])) {
		remove_from_request_list_notice_dh_woocommerce_dual_cart($product_id, $_SESSION['dh_woocommerce_dual_cart_request_list'][$product_id]);
		$_SESSION['dh_woocommerce_dual_cart_request_list_count'] -= $_SESSION['dh_woocommerce_dual_cart_request_list'][$product_id];
		unset($_SESSION['dh_woocommerce_dual_cart_request_list'][$product_id]);
		echo true;
		wp_die();
	}

	echo false;
	wp_die();
}

// Add items to list
function add_to_request_list_dh_woocommerce_dual_cart()
{
	// Retrive POST data
	$product_id = intval(htmlspecialchars($_POST['product_id']));
	$product_count = intval(htmlspecialchars($_POST['count']));

	// Temp store the request list
	$request_list = $_SESSION['dh_woocommerce_dual_cart_request_list'];
	$product_was_in_list = false;

	// If our product is in the list, just up the counter
	if (isset($_SESSION['dh_woocommerce_dual_cart_request_list'][$product_id])) {
		$_SESSION['dh_woocommerce_dual_cart_request_list_count'] -= $_SESSION['dh_woocommerce_dual_cart_request_list'][$product_id];
		$product_count += $_SESSION['dh_woocommerce_dual_cart_request_list'][$product_id];
	}

	$_SESSION['dh_woocommerce_dual_cart_request_list'][$product_id] = $product_count;
	$_SESSION['dh_woocommerce_dual_cart_request_list_count'] += $product_count;


	// Make a notice for the front-end that the product was added to the list
	add_to_request_list_notice_dh_woocommerce_dual_cart($product_id, $product_count);

	// Return true as everything went well
	echo true;

	wp_die();
}

// Update item count in list
function update_request_list_dh_woocommerce_dual_cart() {
	$updated_list = $_POST['updated_list'];

	$_SESSION['dh_woocommerce_dual_cart_request_list_count'] = 0;
	updated_request_list_notice_dh_woocommerce_dual_cart();

	foreach ($updated_list as $key => $value) {
		if (isset($_SESSION['dh_woocommerce_dual_cart_request_list'][$key])) {
			$_SESSION['dh_woocommerce_dual_cart_request_list'][$key] = $value;
			$_SESSION['dh_woocommerce_dual_cart_request_list_count'] += $value;

			if ($value == 0) {
				$_SESSION['dh_woocommerce_dual_cart_request_list_count'] -= $_SESSION['dh_woocommerce_dual_cart_request_list'][$key];
				unset($_SESSION['dh_woocommerce_dual_cart_request_list'][$key]);
			}
		} else {
			echo false;
			wp_die();
		}
	}

	echo true;
	wp_die();
}

function woocommerce_before_cart_table_dh_woocommerce_dual_cart() {
	require plugin_dir_path( __FILE__ ) . 'templates/cart_before.php';
}
// function woocommerce_after_cart_table_dh_woocommerce_dual_cart() {
// 	require plugin_dir_path( __FILE__ ) . 'templates/cart.php';
// }

function custom_template($templates) {
	$custom_templates = [
		'templates/cart.php' => 'Forespørsel liste'
	];

	$templates = array_merge($templates, $custom_templates);

	return $templates;
}

function dhwcdc_load_template($template) {
	global $post;

	if (!$post) {
		return $template;
	}

	$template_name = get_post_meta($post->ID, '_wp_page_template', true);

	// if (!isset($this->templates[$template_name])) {
	// 	return $template;
	// }

	$file = plugin_dir_path(__FILE__) . $template_name;
	if (file_exists($file)) {
		return $file;
	}

	return $template;
}

function dhwcdc_add_body_classes($classes)
{
	global $post;
	$template_name = get_post_meta($post->ID, '_wp_page_template', true);

	// Add classes when we have items in our request list
	if ($_SESSION['dh_woocommerce_dual_cart_request_list_count'] > 0) {
		$classes[] .= 'request_list-has-items';
	} else {
		$classes[] .= 'request_list-empty';
	}

	// Add classes when we are on our request list template page
	if ($template_name == 'templates/cart.php') {
		$classes[] .= 'request_list-page woocommerce-page woocommerce-cart page-template-default';
	}

	return $classes;
}
