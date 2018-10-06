<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://datahjelpen.no/
 * @since      1.0.0
 *
 * @package    Dh_Woocommerce_Dual_Cart
 * @subpackage Dh_Woocommerce_Dual_Cart/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Dh_Woocommerce_Dual_Cart
 * @subpackage Dh_Woocommerce_Dual_Cart/public
 * @author     Datahjelpen AS <post@datahjelpen.no>
 */
class Dh_Woocommerce_Dual_Cart_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action( 'wp_ajax_my_action', 'my_action' );
		add_action( 'wp_ajax_nopriv_my_action', 'my_action' );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dh_Woocommerce_Dual_Cart_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dh_Woocommerce_Dual_Cart_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dh-woocommerce-dual-cart-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dh_Woocommerce_Dual_Cart_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dh_Woocommerce_Dual_Cart_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dh-woocommerce-dual-cart-public.js', array( 'jquery' ), $this->version, false );
			wp_localize_script( $this->plugin_name, 'ajax_object', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'product_id' => get_the_ID()
			));
	}
}
