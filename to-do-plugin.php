<?php
/*
Plugin Name:  To-do list
Description:  To-do list plugin with admin page and Gutenberg block
Author:       Tihomir Parushev
Version:      1.0.0
Author URI:   https://wordpress.org/plugins/bulgarisation-for-woocommerce/
Requires PHP: 8.2
Text Domain:  to-do
License:      GPLv3 or later
*/

defined( 'ABSPATH' ) || exit;

// Start the plugin
add_action( 'plugins_loaded', 'to_do_plugin_init', 1, 0 );

define( 'TODO_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'TODO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * @return \To_Do\Plugin
 */
function to_do_plugin_init() {
	load_plugin_textdomain( 'to-do', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	if ( file_exists( TODO_PLUGIN_DIR . '/vendor/autoload.php' ) ) {
		require_once TODO_PLUGIN_DIR . '/vendor/autoload.php';
	} else {
		add_action( 'admin_notices', function() {
			$message = sprintf( esc_html__( 'Please run `composer install` in the plugin folder `to-do-plugin`', 'to-do' ) );
			echo wp_kses_post( sprintf( '<div class="error">%s</div>', wpautop( $message ) ) );
		} );

		return;
	}

	require_once TODO_PLUGIN_DIR . '/src/functions.php';

	add_action( 'admin_enqueue_scripts', [ 'To_Do\Scripts', 'admin_scripts' ] );
}