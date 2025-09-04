<?php
namespace To_Do;

defined( 'ABSPATH' ) || exit;

class Database {
	private $wpdb, $table_name;

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->table_name = $this->wpdb->prefix . 'to_do_list';
	}

	public function get_table_name() {
		return $this->table_name;
	}
	
	public function exists() {
		$sql = $this->wpdb->prepare('SHOW TABLES LIKE %s', $this->table_name);
		$found = $this->wpdb->get_var($sql);
		return $found === $this->table_name;
	}

	public function create_table() {
		if (!function_exists('dbDelta')) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		$table = $this->table_name;
		$collate = $this->wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table} (
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			title VARCHAR(255) NOT NULL,
			description TEXT NULL,
			category VARCHAR(255) NOT NULL,
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (id)
		) {$collate};";

		dbDelta( $sql );
	}

	public function get( $id ) {
		$sql = $this->wpdb->prepare(
			"SELECT id, title, description, category, created_at, updated_at FROM {$this->table_name} WHERE id = %d LIMIT 1",
			$id
		);

		return $this->wpdb->get_row( $sql, ARRAY_A );
	}

	public function insert( $data ) {
        $this->wpdb->insert( $this->table_name, $data );

        return $this->wpdb->insert_id;
    }

    public function update( $id, $data ) {
        return $this->wpdb->update( $this->table_name, $data, ['id' => $id], null, ['%d'] );
    }

    public function delete( $id ) {
        return $this->wpdb->delete( $this->table_name, ['id' => $id], ['%d'] );
    }
}