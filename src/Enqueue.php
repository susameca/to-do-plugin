<?php
namespace To_Do;

use To_Do\Rest;
defined( 'ABSPATH' ) || exit;

class Enqueue {
	public static function admin_scripts() {
		wp_enqueue_script(
			'todo-js-admin',
			TODO_PLUGIN_URL . 'assets/js/admin.js',
			array( 'jquery' ), // deps
			null, // version -- this is handled by the bundle manifest
			true // in footer
		);

		wp_localize_script( 'todo-js-admin', 'toDo', array(
			'restUrl' => get_rest_url( null, Rest::ENDPOINT ),
			'restNonce' => wp_create_nonce( 'wp_rest' ),
		) );

		wp_enqueue_style(
			'todo-css-bundle',
			TODO_PLUGIN_URL . 'assets/css/admin.css',
		);
	}
}
