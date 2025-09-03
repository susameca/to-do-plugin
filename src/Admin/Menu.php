<?php
namespace To_Do\Admin;

use To_Do\Fragment;

defined( 'ABSPATH' ) || exit;

class Menu {
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
	}

	/**
	 * Add menu items.
	 */
	public static function admin_menu() {
		add_menu_page( 
			__( 'To do list', 'to-do' ), 
			__( 'To do list', 'to-do' ), 
			'manage_options', 
			'to-do-list',
			array( __CLASS__, 'page_content' ),
			'dashicons-forms',
		);
	}

	public static function page_content() {
		Fragment::render( 'admin/list' );
	}
}
