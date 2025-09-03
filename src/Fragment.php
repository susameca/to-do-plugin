<?php
namespace To_Do;

defined( 'ABSPATH' ) || exit;

class Fragment {
	public static function render( $fragment, $atts = array() ) {
		$fragment_file = TODO_PLUGIN_DIR . DIRECTORY_SEPARATOR ."fragments" . DIRECTORY_SEPARATOR . $fragment . ".php";

		if ( ! is_readable( $fragment_file ) ) {
			return;
		}

		extract( $atts );

		include( $fragment_file );
	}
}
