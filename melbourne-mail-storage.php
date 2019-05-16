<?php
/**
 * Plugin Name: Melbourne mail storage
 * GitHub Plugin URI: https://github.com/ivanBobrov/melbourneMailPlugin
 * Version: 1.0.2
 */
defined( 'ABSPATH' ) or die( 'Wrong script execution' );

//require( plugin_dir_path( __FILE__ ) . 'db-storage.php' );

class MelbourneMailStorage {
	const DB_VERSION_ID = '2';
	const DB_VERSION_OPTION_NAME = 'melbourne_mail_storage_plugin_db_version';
	const DB_TABLE_NAME = 'melbourne_mail';
	const DB_TABLE_TEMP_NAME = 'melbourne_mail_temp';
	
	var $tableName;
	var $tempTableName;
	var $wpdb;
	
	public function __construct($wpdb) {
		$this->wpdb = $wpdb;
		$this->tableName = $wpdb->prefix . self::DB_TABLE_NAME;
		$this->tempTableName = $wpdb->prefix . self::DB_TABLE_TEMP_NAME;
	}
	
	public function setDatabase() {
		if (!$this->tableExists($this->tableName)) {
			$this->createTable();
		}
		
		update_option( self::DB_VERSION_OPTION_NAME, self::DB_VERSION_ID );
	}
	
	public function addMail($mail) {
		$this->wpdb->insert($this->tableName, array('email' => $mail));
	}
	
	function createTable() {
		$this->wpdb->query("
			CREATE TABLE $this->tableName (
				id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				email varchar(50) NOT NULL,
				insert_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
			);
		");
	}
	
	function tableExists($table) {
		return $this->wpdb->get_var("SELECT 1 FROM $table LIMIT 1");
	}
}

global $wpdb;

$melbourneMailStorage = new MelbourneMailStorage($wpdb);
register_activation_hook(__FILE__, array($melbourneMailStorage, 'setDatabase'));

?>