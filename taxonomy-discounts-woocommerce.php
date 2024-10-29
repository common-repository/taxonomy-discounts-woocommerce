<?php
/**
 * Plugin Name: Taxonomy/Term and Role based Discounts for WooCommerce
 * Plugin URI: https://www.webdados.pt/wordpress/plugins/taxonomy-term-based-discounts-for-woocommerce/
 * Description: "Taxonomy/Term based Discounts for WooCommerce" lets you configure discount/pricing rules for products based on any product taxonomy terms and WordPress user roles
 * Version: 5.0
 * Text Domain: taxonomy-discounts-woocommerce
 * Domain Path: /languages
 * Requires at least: 5.4
 * Requires PHP: 7.0
 * WC requires at least: 5.0
 * WC tested up to: 9.3
 * Requires Plugins: woocommerce
**/

/* Partially WooCommerce CRUD ready - Term metas are still fetched from the database using WP_Query for filtering and performance reasons */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/* Our own order class and the main classes */
add_action( 'plugins_loaded', 'wctd_init', 1 );
function wctd_init() {
	if ( class_exists( 'WooCommerce' ) && defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '5.0', '>=' ) ) {
		if ( ! function_exists( 'get_plugin_data' ) ){
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		$plugin_data = get_plugin_data( __FILE__ );
		define( 'WCTD_FREE_PLUGIN_VERSION', $plugin_data['Version'] );
		require_once( 'includes/class-wc-taxonomy-discounts-webdados.php' );
		require_once( 'includes/helpers.php' );
		$GLOBALS['WC_Taxonomy_Discounts_Webdados'] = WC_Taxonomy_Discounts_Webdados();
	} else {
		add_action( 'admin_notices', 'wctd_init_no_woocommerce' );
	}
}

/* Main class */
function WC_Taxonomy_Discounts_Webdados() {
	return WC_Taxonomy_Discounts_Webdados::instance();
}

function wctd_init_no_woocommerce() {
	?>
	<div id="message" class="error">
		<p><?php
			_e( '<strong>Taxonomy/Term and Role based Discounts for WooCommerce</strong> is enabled but not effective. It requires <strong>WooCommerce</strong> in order to work.',  'taxonomy-discounts-woocommerce' );
		?></p>
	</div>
	<?php
}

/* HPOS Compatible */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
	}
} );

/* If you're reading this you must know what you're doing ;-) Greetings from sunny Portugal! */
