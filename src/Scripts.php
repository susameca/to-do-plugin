<?php
namespace To_Do;

defined( 'ABSPATH' ) || exit;

class Scripts {
	public static function admin_scripts() {
		wp_enqueue_script(
			'todo-js-admin',
			TODO_PLUGIN_URL . 'assets/js/admin.js',
			array( 'jquery' ), // deps
			null, // version -- this is handled by the bundle manifest
			true // in footer
		);

		wp_localize_script( 'todo-js-admin', 'toDo', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		) );

		wp_enqueue_style(
			'todo-css-bundle',
			TODO_PLUGIN_URL . 'assets/js/admin.css',
		);
	}
}
