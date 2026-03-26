<?php
/**
 * Pro upsell — admin notice, plugin row links, dismissal handler.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ---------------------------------------------------------------------------
// Plugin row links  (Settings | Go Pro)
// ---------------------------------------------------------------------------
add_filter( 'plugin_action_links_internete-gmb-reviews/internete-gmb-reviews.php', 'internete_gmb_plugin_action_links' );

function internete_gmb_plugin_action_links( $links ) {
    $settings_link = sprintf(
        '<a href="%s">%s</a>',
        esc_url( admin_url( 'options-general.php?page=internete-gmb-reviews' ) ),
        esc_html__( 'Settings', 'internete-gmb-reviews' )
    );
    $pro_link = sprintf(
        '<a href="%s" target="_blank" style="color:#2271b1;font-weight:600;">%s</a>',
        esc_url( 'https://internete.net/gmb-reviews-pro' ),
        esc_html__( 'Go Pro ↗', 'internete-gmb-reviews' )
    );
    array_unshift( $links, $settings_link );
    $links[] = $pro_link;
    return $links;
}

// ---------------------------------------------------------------------------
// One-time activation notice
// ---------------------------------------------------------------------------
add_action( 'admin_notices', 'internete_gmb_activation_notice' );

function internete_gmb_activation_notice() {
    // Only show to admins who haven't dismissed it
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    if ( get_user_meta( get_current_user_id(), 'internete_gmb_notice_dismissed', true ) ) {
        return;
    }
    // Only show if plugin was just activated (flag set in main file)
    if ( ! get_option( 'internete_gmb_show_welcome_notice' ) ) {
        return;
    }
    ?>
    <div class="notice notice-info is-dismissible internete-gmb-welcome-notice"
         data-nonce="<?php echo esc_attr( wp_create_nonce( 'internete_gmb_dismiss_notice' ) ); ?>">
        <p>
            <strong><?php esc_html_e( 'Thanks for installing Internete GMB Reviews!', 'internete-gmb-reviews' ); ?></strong>
            <?php esc_html_e( 'Want automatic GBP sync (no API key needed), multi-location support, and the ability to reply to Google reviews right from WordPress?', 'internete-gmb-reviews' ); ?>
            &nbsp;<a href="<?php echo esc_url( 'https://internete.net/gmb-reviews-pro' ); ?>" target="_blank">
                <?php esc_html_e( 'Check out GMB Reviews Pro &rarr;', 'internete-gmb-reviews' ); ?>
            </a>
        </p>
    </div>
    <script>
    (function($){
        $(document).on('click', '.internete-gmb-welcome-notice .notice-dismiss', function(){
            $.post(ajaxurl, {
                action: 'internete_gmb_dismiss_notice',
                nonce:  $(this).closest('.internete-gmb-welcome-notice').data('nonce')
            });
        });
    })(jQuery);
    </script>
    <?php
}

// ---------------------------------------------------------------------------
// AJAX: dismiss notice
// ---------------------------------------------------------------------------
add_action( 'wp_ajax_internete_gmb_dismiss_notice', 'internete_gmb_handle_dismiss_notice' );

function internete_gmb_handle_dismiss_notice() {
    check_ajax_referer( 'internete_gmb_dismiss_notice', 'nonce' );
    update_user_meta( get_current_user_id(), 'internete_gmb_notice_dismissed', 1 );
    delete_option( 'internete_gmb_show_welcome_notice' );
    wp_send_json_success();
}
