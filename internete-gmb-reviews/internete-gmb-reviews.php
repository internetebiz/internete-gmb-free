<?php
/**
 * Plugin Name: Internete GMB Reviews
 * Plugin URI: https://internete.net/gmb-reviews
 * Description: Lightweight Google My Business reviews plugin that displays your Google reviews beautifully and improves SEO without slowing down your site.
 * Version: 2.3.0
 * Author: Internete
 * Author URI: https://internete.net
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: internete-gmb-reviews
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Tested up to: 6.7
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('INTERNETE_GMB_VERSION', '2.3.0');
define('INTERNETE_GMB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('INTERNETE_GMB_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once INTERNETE_GMB_PLUGIN_DIR . 'includes/db-schema.php';
require_once INTERNETE_GMB_PLUGIN_DIR . 'includes/fetch-reviews.php';
require_once INTERNETE_GMB_PLUGIN_DIR . 'includes/render-shortcode.php';

// Admin area
if (is_admin()) {
    require_once INTERNETE_GMB_PLUGIN_DIR . 'admin/settings-page.php';
    require_once INTERNETE_GMB_PLUGIN_DIR . 'admin/settings-handler.php';
    require_once INTERNETE_GMB_PLUGIN_DIR . 'admin/upsell.php';
    require_once INTERNETE_GMB_PLUGIN_DIR . 'includes/updater.php';
    new Internete_GMB_Updater( __FILE__ );
}

// Activation hook
register_activation_hook(__FILE__, 'internete_gmb_activate');

function internete_gmb_activate() {
    internete_gmb_create_tables();
    add_option('internete_gmb_show_welcome_notice', 1);
}

// Enqueue frontend styles and scripts globally so badge loads in headers/footers too
function internete_gmb_enqueue_assets() {
    $css_path = INTERNETE_GMB_PLUGIN_DIR . 'assets/css/style.css';
    $css_url  = INTERNETE_GMB_PLUGIN_URL . 'assets/css/style.css';
    $js_path = INTERNETE_GMB_PLUGIN_DIR . 'assets/js/carousel.js';
    $js_url  = INTERNETE_GMB_PLUGIN_URL . 'assets/js/carousel.js';

    if ( file_exists( $css_path ) ) {
        wp_enqueue_style(
            'internete-gmb-reviews',
            $css_url,
            array(),
            filemtime( $css_path )
        );
    }

    if ( file_exists( $js_path ) ) {
        wp_enqueue_script(
            'internete-gmb-carousel',
            $js_url,
            array(),
            filemtime( $js_path ),
            true // Load in footer
        );
    }
}
add_action('wp_enqueue_scripts', 'internete_gmb_enqueue_assets');

// Admin styles
function internete_gmb_admin_enqueue_assets($hook) {
    if ($hook !== 'settings_page_internete-gmb-reviews') {
        return;
    }
    wp_enqueue_style(
        'internete-gmb-admin',
        INTERNETE_GMB_PLUGIN_URL . 'assets/css/admin-style.css',
        array(),
        INTERNETE_GMB_VERSION
    );
}
add_action('admin_enqueue_scripts', 'internete_gmb_admin_enqueue_assets');

// Schedule daily review sync
function internete_gmb_schedule_cron() {
    if ( ! wp_next_scheduled( 'internete_gmb_daily_review_sync' ) ) {
        $first_run = strtotime( 'tomorrow 4am' );
        wp_schedule_event( $first_run, 'daily', 'internete_gmb_daily_review_sync' );
    }
}
add_action( 'wp', 'internete_gmb_schedule_cron' );

// Cron job callback
add_action( 'internete_gmb_daily_review_sync', 'internete_gmb_cron_update_reviews' );

function internete_gmb_cron_update_reviews() {
    if ( function_exists( 'internete_gmb_fetch_reviews' ) ) {
        internete_gmb_fetch_reviews();
    }
}

// Unschedule on deactivation
function internete_gmb_clear_cron() {
    $timestamp = wp_next_scheduled( 'internete_gmb_daily_review_sync' );
    if ( $timestamp ) {
        wp_unschedule_event( $timestamp, 'internete_gmb_daily_review_sync' );
    }
}
register_deactivation_hook( __FILE__, 'internete_gmb_clear_cron' );

