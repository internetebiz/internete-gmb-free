<?php
/**
 * Handle settings form submissions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Save settings
 */
add_action('admin_post_internete_gmb_save_settings', 'internete_gmb_handle_save_settings');

function internete_gmb_handle_save_settings() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized access');
    }
    
    // Verify nonce
    $nonce = isset( $_POST['internete_gmb_nonce'] ) ? sanitize_key( wp_unslash( $_POST['internete_gmb_nonce'] ) ) : '';
    if ( ! wp_verify_nonce( $nonce, 'internete_gmb_save_settings' ) ) {
        wp_die( 'Security check failed' );
    }

    // Sanitize and save settings
    $place_id = isset( $_POST['place_id'] ) ? sanitize_text_field( wp_unslash( $_POST['place_id'] ) ) : '';
    $api_key  = isset( $_POST['api_key'] )  ? sanitize_text_field( wp_unslash( $_POST['api_key'] ) )  : '';
    
    internete_gmb_update_setting('place_id', $place_id);
    internete_gmb_update_setting('api_key', $api_key);
    
    // Redirect back with success message
    wp_redirect(add_query_arg(array(
        'page' => 'internete-gmb-reviews',
        'message' => urlencode('Settings saved successfully!'),
        'type' => 'success'
    ), admin_url('options-general.php')));
    exit;
}

/**
 * Fetch reviews
 */
add_action('admin_post_internete_gmb_fetch_reviews', 'internete_gmb_handle_fetch_reviews');

function internete_gmb_handle_fetch_reviews() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized access');
    }
    
    // Verify nonce
    $nonce = isset( $_POST['internete_gmb_nonce'] ) ? sanitize_key( wp_unslash( $_POST['internete_gmb_nonce'] ) ) : '';
    if ( ! wp_verify_nonce( $nonce, 'internete_gmb_fetch_reviews' ) ) {
        wp_die( 'Security check failed' );
    }
    
    // Fetch reviews
    $result = internete_gmb_fetch_reviews();
    
    // Redirect back with result message
    wp_redirect(add_query_arg(array(
        'page' => 'internete-gmb-reviews',
        'message' => urlencode($result['message']),
        'type' => $result['success'] ? 'success' : 'error'
    ), admin_url('options-general.php')));
    exit;
}
