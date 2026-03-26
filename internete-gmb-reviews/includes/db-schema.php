<?php
/**
 * Database schema for storing Google reviews
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create plugin database tables
 */
function internete_gmb_create_tables() {
    global $wpdb;
    
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'internete_gmb_reviews';
    
    // Reviews table
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        author_name varchar(255) NOT NULL,
        author_photo varchar(500) DEFAULT NULL,
        rating tinyint(1) NOT NULL,
        text text,
        time bigint(20) NOT NULL,
        relative_time varchar(100) DEFAULT NULL,
        language varchar(10) DEFAULT NULL,
        profile_photo_url varchar(500) DEFAULT NULL,
        source varchar(50) DEFAULT 'places_api',
        last_seen datetime DEFAULT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        KEY rating (rating),
        KEY time (time),
        KEY source (source)
    ) $charset_collate;";
    
    // Settings table
    $settings_table = $wpdb->prefix . 'internete_gmb_settings';
    $sql2 = "CREATE TABLE IF NOT EXISTS $settings_table (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        setting_key varchar(100) NOT NULL,
        setting_value text,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        UNIQUE KEY setting_key (setting_key)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);  // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
    dbDelta($sql2); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
    
    // Set default values
    internete_gmb_set_default_settings();
}

/**
 * Set default plugin settings
 */
function internete_gmb_set_default_settings() {
    global $wpdb;
    $settings_table = $wpdb->prefix . 'internete_gmb_settings';
    
    $defaults = array(
        'place_id' => '',
        'api_key' => '',
        'average_rating' => '0',
        'total_reviews' => '0',
        'last_sync' => '',
        'business_name' => '',
        'google_maps_url' => ''
    );
    
    foreach ($defaults as $key => $value) {
        $wpdb->query($wpdb->prepare(
            "INSERT IGNORE INTO $settings_table (setting_key, setting_value) VALUES (%s, %s)", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
            $key,
            $value
        ));
    }
}

/**
 * Get a setting value
 */
function internete_gmb_get_setting($key, $default = '') {
    global $wpdb;
    $settings_table = $wpdb->prefix . 'internete_gmb_settings';
    
    $value = $wpdb->get_var($wpdb->prepare(
        "SELECT setting_value FROM $settings_table WHERE setting_key = %s", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $key
    ));
    
    return $value !== null ? $value : $default;
}

/**
 * Update a setting value
 */
function internete_gmb_update_setting($key, $value) {
    global $wpdb;
    $settings_table = $wpdb->prefix . 'internete_gmb_settings';
    
    // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
    $wpdb->query($wpdb->prepare(
        "INSERT INTO $settings_table (setting_key, setting_value) VALUES (%s, %s)
        ON DUPLICATE KEY UPDATE setting_value = %s, updated_at = CURRENT_TIMESTAMP",
        $key,
        $value,
        $value
    ));
    // phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
}
