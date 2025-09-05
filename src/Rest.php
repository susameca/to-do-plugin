<?php
namespace To_Do;

use To_Do\Database;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WP_Error;

defined( 'ABSPATH' ) || exit;

class Rest {
	public const ENDPOINT = 'tparushev/v1';

	public static function can_edit() {
		return is_user_logged_in() && current_user_can( 'manage_options' );
	}

	public static function register_routes() {
		register_rest_route( self::ENDPOINT, '/todos', [
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ __CLASS__, 'list_todos' ],
				'permission_callback' => '__return_true',
			],
			[
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => [ __CLASS__, 'create_todo' ],
				'permission_callback' => [ __CLASS__, 'can_edit' ],
			],
		] );

		register_rest_route( self::ENDPOINT, '/todos/(?P<id>\d+)', [
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ __CLASS__, 'get_todo' ],
				'permission_callback' => '__return_true',
				'args'                => [ 'id' => [ 'type' => 'integer', 'required' => true ] ],
			],
			[
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => [ __CLASS__, 'update_todo' ],
				'permission_callback' => [ __CLASS__, 'can_edit' ],
				'args'                => [ 'id' => [ 'type' => 'integer', 'required' => true ] ],
			],
			[
				'methods'             => WP_REST_Server::DELETABLE,
				'callback'            => [ __CLASS__, 'delete_todo' ],
				'permission_callback' => [ __CLASS__, 'can_edit' ],
				'args'                => [ 'id' => [ 'type' => 'integer', 'required' => true ] ],
			],
		] );
	}

	private static function validate_params( $params ) {
		if ( 
			empty( $params['title'] ) || 
			empty( $params['description'] ) || 
			empty( $params['category'] )
		) {
			 return new WP_Error( 'todo_list_invalid', __( 'Fields "title", "description" and "category" are required.', 'to-do' ), [ 'status' => 400 ] );
		}

        return [
        	'title' => sanitize_text_field( $params['title'] ),
        	'description' => sanitize_text_field( $params['description'] ),
        	'category' => sanitize_text_field( $params['category'] ),
        ];
    }

	public static function list_todos( WP_REST_Request $request ) {
		$params = $request->get_params();
		$database = new Database();
		
		return new WP_REST_Response( $database->get_all( $params ) );
	}

	public static function create_todo( WP_REST_Request $request ) {
		$params = $request->get_params();
        $data = self::validate_params( $params );

        if ( is_wp_error( $data ) ) {
            return $data;
        }

		$database = new Database();
        $id = $database->insert( $data );
        $response = $database->get( $id );
        $response['message'] = __('Todo is successfully added!', 'to-do' );

        return new WP_REST_Response( $response, 200 );
    }

    public static function get_todo( WP_REST_Request $request ) {
       	$id = (int) $request['id'];
		$database = new Database();
		$response = $database->get( $id );

		if ( !$response ) {
			return new WP_Error( 'todo_list_invalid', __( 'No todo is found with this value.', 'to-do' ), [ 'status' => 400 ] );
		}

        return new WP_REST_Response( $database->get( $id ), 200 );
    }

    public static function update_todo( WP_REST_Request $request ) {
		$params = $request->get_params();
		$database = new Database();
        $data = self::validate_params( $params );
       	$id = (int) $request['id'];

        if ( is_wp_error( $data ) ) {
            return $data;
        }

        $founded = $database->get( $id );

		if ( !$founded ) {
			return new WP_Error( 'todo_list_invalid', __( 'No todo is found with this value.', 'to-do' ), [ 'status' => 400 ] );
		}

		$database = new Database();
        $database->update( $id, $data );

        $response = $database->get( $id );
        $response['message'] = __('Todo is successfully updated!', 'to-do' );

        return new WP_REST_Response( $response, 200 );
    }

    public static function delete_todo( WP_REST_Request $request ) {
       	$id = (int) $request['id'];
		$database = new Database();
		$founded = $database->get( $id );

		if ( !$founded ) {
			return new WP_Error( 'todo_list_invalid', __( 'No todo is found with this value.', 'to-do' ), [ 'status' => 400 ] );
		}

		$database->delete( $id );

        return new WP_REST_Response( [ 'message' => __( 'Deleted successfully!', 'to-do' ) ], 200 );
    }
}
