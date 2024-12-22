<?php

require_once plugin_dir_path( dirname(__FILE__) ) . 'models/Base_Model.php';

final class DPLR_Form_Model extends DPLR_Base_Model {

  static $primary_key = 'id';

  static function init() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $tablemame = self::_table();

    if($wpdb->get_var("SHOW TABLES LIKE '$tablemame'") != $tablemame) {
      $sql = "CREATE TABLE ". $tablemame . "("
  		 . "id mediumint(9) NOT NULL AUTO_INCREMENT,"
       . "title tinytext NOT NULL,"
       . "description text NULL,"
       . "list_id INT(15) NOT NULL,"
       . "name tinytext NOT NULL,"
  		 . "PRIMARY KEY  (id)"
  		. ") $charset_collate;";

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta( $sql );

    }

    self::initSettings();

    $eventTableName = self::_eventTable();
    if($wpdb->get_var("SHOW TABLES LIKE '$eventTableName'") != $eventTableName) {
      $sql = "CREATE TABLE ". $eventTableName . "("
  		 . "id mediumint(9) NOT NULL AUTO_INCREMENT,"
      . "parent_id mediumint(9) NOT NULL,"
      . "event_type TINYINT NOT NULL,"
      . "event_date DATETIME NOT NULL,"
      . "data TEXT NULL,"
      . "PRIMARY KEY (id),"
      . "FOREIGN KEY (parent_id) REFERENCES " . self::_table() . "(".self::$primary_key.") ON DELETE CASCADE"
      . ") $charset_collate;";

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta( $sql );

    }

  }
}

 ?>