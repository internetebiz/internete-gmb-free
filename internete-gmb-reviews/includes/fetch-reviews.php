<?php
/**
 * Fetch reviews from Google Places API
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Fetch reviews from Google Places API and store in database
 */
function internete_gmb_fetch_reviews() {
    $place_id = internete_gmb_get_setting('place_id');
    $api_key = internete_gmb_get_setting('api_key');
    
    if (empty($place_id) || empty($api_key)) {
        return array(
            'success' => false,
            'message' => 'Please configure both Place ID and API Key in settings.'
        );
    }
    
    // Build API URL
    $api_url = add_query_arg(array(
        'place_id' => $place_id,
        'fields' => 'name,rating,user_ratings_total,reviews,url',
        'key' => $api_key
    ), 'https://maps.googleapis.com/maps/api/place/details/json');
    
    // Make API request
    $response = wp_remote_get($api_url, array(
        'timeout' => 15,
        'sslverify' => true
    ));
    
    if (is_wp_error($response)) {
        return array(
            'success' => false,
            'message' => 'API request failed: ' . $response->get_error_message()
        );
    }
    
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    
    if (empty($data['result'])) {
        return array(
            'success' => false,
            'message' => 'No results returned from Google Places API. Please check your Place ID.'
        );
    }
    
    $result = $data['result'];
    
    // Update business info
    internete_gmb_update_setting('business_name', isset($result['name']) ? $result['name'] : '');
    internete_gmb_update_setting('average_rating', isset($result['rating']) ? $result['rating'] : '0');
    internete_gmb_update_setting('total_reviews', isset($result['user_ratings_total']) ? $result['user_ratings_total'] : '0');
    internete_gmb_update_setting('google_maps_url', isset($result['url']) ? $result['url'] : '');
    
    // Store reviews (accumulate, don't truncate)
    if (!empty($result['reviews']) && is_array($result['reviews'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'internete_gmb_reviews';

        $inserted = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($result['reviews'] as $review) {
            $author_name = isset($review['author_name']) ? $review['author_name'] : '';
            $review_time = isset($review['time']) ? $review['time'] : time();

            // Check for existing review by author_name + time (unique identifier)
            $existing_id = $wpdb->get_var($wpdb->prepare(
                "SELECT id FROM $table_name WHERE author_name = %s AND time = %d", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                $author_name,
                $review_time
            ));

            $review_data = array(
                'author_name' => $author_name,
                'author_photo' => isset($review['profile_photo_url']) ? $review['profile_photo_url'] : '',
                'rating' => isset($review['rating']) ? intval($review['rating']) : 5,
                'text' => isset($review['text']) ? $review['text'] : '',
                'time' => $review_time,
                'relative_time' => isset($review['relative_time_description']) ? $review['relative_time_description'] : '',
                'language' => isset($review['language']) ? $review['language'] : 'en',
                'profile_photo_url' => isset($review['profile_photo_url']) ? $review['profile_photo_url'] : '',
                'source' => 'places_api',
                'last_seen' => current_time('mysql')
            );

            $data_format = array('%s', '%s', '%d', '%s', '%d', '%s', '%s', '%s', '%s', '%s');

            if ($existing_id) {
                // Update existing review (in case text or relative_time changed)
                $wpdb->update(
                    $table_name,
                    $review_data,
                    array('id' => $existing_id),
                    $data_format,
                    array('%d')
                );
                $updated++;
            } else {
                // Insert new review
                $wpdb->insert($table_name, $review_data, $data_format);
                $inserted++;
            }
        }

        // Update last sync time
        internete_gmb_update_setting('last_sync', current_time('mysql'));

        $message = sprintf(
            'Sync complete: %d new reviews added, %d existing reviews updated.',
            $inserted,
            $updated
        );

        return array(
            'success' => true,
            'message' => $message,
            'inserted' => $inserted,
            'updated' => $updated
        );
    }
    
    return array(
        'success' => false,
        'message' => 'No reviews found for this location.'
    );
}

/**
 * Get reviews from database
 */
function internete_gmb_get_reviews($limit = 10, $min_rating = 0) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'internete_gmb_reviews';
    
    $query = $wpdb->prepare(
        "SELECT * FROM $table_name WHERE rating >= %d ORDER BY time DESC LIMIT %d", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $min_rating,
        $limit
    );

    return $wpdb->get_results($query); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
}

/**
 * Get review statistics
 */
function internete_gmb_get_stats() {
    return array(
        'average_rating' => floatval(internete_gmb_get_setting('average_rating', '0')),
        'total_reviews' => intval(internete_gmb_get_setting('total_reviews', '0')),
        'business_name' => internete_gmb_get_setting('business_name', ''),
        'google_maps_url' => internete_gmb_get_setting('google_maps_url', ''),
        'last_sync' => internete_gmb_get_setting('last_sync', '')
    );
}
