<?php

class DPLR_Base_Model {

  static $primary_key = 'id';

  public static function _table() {
    global $wpdb;
    $tablename = strtolower( get_called_class() );
    $tablename = str_replace( '_model', '', $tablename );
    return $wpdb->prefix . $tablename;
  }

  public static function _eventTable() {
    global $wpdb;
    $tablename = strtolower( get_called_class() );
    $tablename = str_replace( '_model', '_events', $tablename );
    return $wpdb->prefix . $tablename;
  }

  private static function _settings_table() {
    global $wpdb;
    $tablename = strtolower( get_called_class() );
    $tablename = str_replace( '_model', '_settings', $tablename );
    return $wpdb->prefix . $tablename;
  }

  public static function getBy($conditions , $order_by = null, $with_settings = false) {
    global $wpdb;

    $cache_key = md5( 'getby_' . self::_table() . serialize( $conditions ) . serialize( $order_by ) . serialize( $with_settings ) );
    $cached_result = wp_cache_get( $cache_key, self::_table() );

    if ( false !== $cached_result ) {
        return $cached_result;
    }


    $where_clauses = [];
    $where_values = [];
    foreach ($conditions as $key => $value) {
      $where_clauses[] = "`" . esc_sql($key) . "` = %s";
      $where_values[] = $value;
    }
    $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);

    $order_by_sql = '';
    if ($order_by !== null && is_array($order_by)) {
        $order_by_safe = array_map('esc_sql', $order_by);
        $order_by_sql = ' ORDER BY ' . implode(", ", $order_by_safe) . ' ASC';
    }

    $table = self::_table();
    // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
    $sql = "SELECT *, NULL AS settings FROM {$table} " . $where_sql . $order_by_sql;
    $result = $wpdb->get_results( $wpdb->prepare( $sql, $where_values ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
    
    if ($with_settings) self::groupSettings($result);

    wp_cache_set( $cache_key, $result, self::_table() );

    return $result;
  }

  static function get( $value, $with_settings = false ) {
    global $wpdb;
    if(empty($value)) return false;

    $cache_key = md5( 'get_' . self::_table() . serialize( $value ) . serialize( $with_settings ) );
    $cached_result = wp_cache_get( $cache_key, self::_table() );

    if ( false !== $cached_result ) {
        return $cached_result;
    }

    $table = self::_table();
    $primary_key = static::$primary_key;
    // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
    $sql = "SELECT *, NULL AS settings FROM {$table} WHERE `{$primary_key}` = %s";
    $result = $wpdb->get_row($wpdb->prepare( $sql, $value )); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

    if($with_settings) {
      self::groupSettings($result);
    }

    wp_cache_set( $cache_key, $result, self::_table() );

    return $result;
  }

  static function insert( $data ) {
    global $wpdb;
    $wpdb->insert( self::_table(), $data );
    wp_cache_flush_group( self::_table() );
    return $wpdb->insert_id;
  }
  
  static function update( $id, $data ) {
    global $wpdb;
    wp_cache_flush_group( self::_table() );
    $wpdb->update( self::_table(), $data, [self::$primary_key => $id] );
  }
  
  static function delete( $value ) {
    global $wpdb;
    if(empty($value)) return false;

    $table = self::_table();
    $primary_key = static::$primary_key;
    // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
    $sql = "DELETE FROM {$table} WHERE `{$primary_key}` = %d";
    $result = $wpdb->query($wpdb->prepare( $sql, array($value) )); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
    wp_cache_flush_group( self::_table() );

    return $result;
  }

  static function deleteWhere( $condition ) {
    global $wpdb;
    if ( empty( $condition ) || ! is_array( $condition ) ) {
        return false;
    }

    $table = self::_table();
    $where_clauses = [];
    $where_values = [];

    foreach ( $condition as $column => $value ) {
        $where_clauses[] = '`' . esc_sql( $column ) . '` = %s';
        $where_values[] = $value;
    }

    $where_sql = implode( ' AND ', $where_clauses );
    // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
    $sql = "DELETE FROM {$table} WHERE {$where_sql}";
    wp_cache_flush_group( self::_table() );
    return $wpdb->query($wpdb->prepare( $sql, $where_values )); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
  }

  static function getAll($with_settings = false, $order_by = null, $with_events = false) {
    global $wpdb;

    $cache_key = md5( 'getAll_' . self::_table() . serialize( $with_settings ) . serialize( $order_by ) . serialize( $with_events ) );
    $cached_result = wp_cache_get( $cache_key, self::_table() );

    if ( false !== $cached_result ) {
        return $cached_result;
    }

    $order_by_sql = '';
    if ($order_by !== null && is_array($order_by)) {
        $order_by_safe = array_map('esc_sql', $order_by);
        $order_by_sql = ' ORDER BY ' . implode(", ", $order_by_safe) . ' ASC';
    }

    $table = self::_table();
    // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
    $sql = "SELECT *, NULL AS settings, NULL AS events FROM {$table} {$order_by_sql}";
    $result = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

    if($with_settings) {
      self::groupSettings($result);
    }

    if ($with_events) 
    {
      self::groupEvents($result);
    }

    wp_cache_set( $cache_key, $result, self::_table() );

    return $result;
  }

  static function insert_id() {
    global $wpdb;
    return $wpdb->insert_id;
  }
  static function time_to_date( $time ) {
    return gmdate( 'Y-m-d H:i:s', $time );
  }
  static function now() {
    return self::time_to_date( time() );
  }
  static function date_to_time( $date ) {
    return strtotime( $date . ' GMT' );
  }

  static function setSettings($id, $settings) {
    global $wpdb;

    $wpdb->delete( self::_settings_table(), ['parent_id' => $id] );
    foreach ($settings as $key => $value) {
      wp_cache_flush_group( self::_table() );
      $row = ['parent_id' => $id, 'setting_key' => $key, 'value' => $value];
      $wpdb->insert( self::_settings_table(), $row );
    }

  }

  private static function groupSettings(& $rows) {
    if ($rows == NULL) return;
    global $wpdb;
    is_object($rows)? $elements = [$rows] : $elements = $rows;
    
    foreach ($elements as $to_attach) {
        
        $table = self::_settings_table();
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $sql = "SELECT setting_key, value FROM {$table} WHERE parent_id = %d";
        $settings_result = $wpdb->get_results( $wpdb->prepare($sql, array($to_attach->id)), 'ARRAY_N'); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

        foreach ($settings_result as $setting_result) {
          $to_attach->settings[$setting_result[0]] = $setting_result[1];
        }
    }
  }

  private static function groupEvents(& $rows) {
    if ($rows == NULL) return;
    global $wpdb;
    
    is_object($rows)? $elements = [$rows] : $elements = $rows;
    
    foreach ($elements as $to_attach) {
        
        $table = self::_eventTable();
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $sql = "SELECT event_type, COUNT(1) FROM {$table} WHERE parent_id = %d GROUP BY event_type";
        $events_result = $wpdb->get_results( $wpdb->prepare($sql, array($to_attach->id)), 'ARRAY_N'); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
      
        $result = [];

        foreach ($events_result as $event_result) {
            list($eventType, $count) = $event_result;

            switch ($eventType) {
                case EventType::DISPLAY:
                    $result['Display'] = $count;
                    break;
                case EventType::SUBMIT:
                    $result['Submit'] = $count;
                    break;
            }
        }

        $to_attach->events = $result;
    }
  }

  protected static function initSettings() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $tablemame = self::_settings_table();

    if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $tablemame ) ) !== $tablemame ) {
      $sql = "CREATE TABLE ". $tablemame . "("
      . "id mediumint(11) NOT NULL AUTO_INCREMENT,"
      . "parent_id mediumint(9) NOT NULL,"
      . "setting_key TEXT NOT NULL,"
      . "value TEXT NOT NULL,"
      . "PRIMARY KEY (id),"
      . "FOREIGN KEY (parent_id) REFERENCES " . self::_table() . "(".self::$primary_key.") ON DELETE CASCADE"
      . ") $charset_collate;";

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

      dbDelta( $sql );
    }
  }

  protected static function initEventsTable() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $tablemame = self::_eventTable();

    if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $tablemame ) ) !== $tablemame ) {
      $sql = "CREATE TABLE ". $tablemame . "("
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

  static function insertEvent( $data ) {
    global $wpdb;
    wp_cache_flush_group( self::_table() );
    $wpdb->insert( self::_eventTable(), $data );
    return $wpdb->insert_id;
  }
}
?>
