<?php
namespace To_Do;

use To_Do\Fragment;

defined( 'ABSPATH' ) || exit;

class Shortcodes {
	public static function init() {
		add_shortcode( 'to-do', [ __CLASS__, 'todo_shortcode' ] );
	}

	public static function todo_shortcode( $atts, $content ) {
		ob_start();

		if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
			Fragment::render( 'shortcode' );
		}

		return ob_get_clean();
	}
}
