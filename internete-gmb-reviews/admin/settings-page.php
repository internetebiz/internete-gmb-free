<?php
/**
 * Admin settings page
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'admin_menu', 'internete_gmb_add_admin_menu' );

function internete_gmb_add_admin_menu() {
    add_options_page(
        'GMB Reviews Settings',
        'GMB Reviews',
        'manage_options',
        'internete-gmb-reviews',
        'internete_gmb_settings_page'
    );
}

function internete_gmb_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $place_id = internete_gmb_get_setting( 'place_id' );
    $api_key  = internete_gmb_get_setting( 'api_key' );
    $stats    = internete_gmb_get_stats();

    global $wpdb;
    $table_name   = $wpdb->prefix . 'internete_gmb_reviews';
    $review_count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.PreparedSQL.InterpolatedNotPrepared
    $pro_active   = defined( 'INTERNETE_GMB_PRO_VERSION' );
    $pro_url      = 'https://internete.net/gmb-reviews-pro';
    ?>
    <div class="wrap internete-gmb-settings">

        <h1>
            <svg width="28" height="28" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:10px;">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            <?php esc_html_e( 'GMB Reviews Settings', 'internete-gmb-reviews' ); ?>
            <?php if ( $pro_active ) : ?>
                <span class="internete-gmb-pro-badge"><?php esc_html_e( 'Pro Active', 'internete-gmb-reviews' ); ?></span>
            <?php endif; ?>
        </h1>

        <?php if ( isset( $_GET['message'] ) ) : ?>
            <?php
            $notice_type    = isset( $_GET['type'] ) ? sanitize_key( wp_unslash( $_GET['type'] ) ) : 'success';
            $raw_message    = isset( $_GET['message'] ) ? wp_unslash( $_GET['message'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            $notice_message = sanitize_text_field( urldecode( $raw_message ) );
            ?>
            <div class="notice notice-<?php echo esc_attr( $notice_type ); ?> is-dismissible">
                <p><?php echo esc_html( $notice_message ); ?></p>
            </div>
        <?php endif; ?>

        <div class="internete-gmb-layout">

            <!-- ============================================================
                 MAIN COLUMN
            ============================================================ -->
            <div class="internete-gmb-main-col">

                <!-- API Configuration -->
                <div class="internete-gmb-card">
                    <h2><?php esc_html_e( 'API Configuration', 'internete-gmb-reviews' ); ?></h2>
                    <p><?php esc_html_e( 'Connect to the Google Places API to fetch your business reviews.', 'internete-gmb-reviews' ); ?></p>

                    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                        <?php wp_nonce_field( 'internete_gmb_save_settings', 'internete_gmb_nonce' ); ?>
                        <input type="hidden" name="action" value="internete_gmb_save_settings">

                        <table class="form-table" role="presentation">
                            <tr>
                                <th scope="row">
                                    <label for="place_id"><?php esc_html_e( 'Google Place ID', 'internete-gmb-reviews' ); ?></label>
                                </th>
                                <td>
                                    <input type="text"
                                           id="place_id"
                                           name="place_id"
                                           value="<?php echo esc_attr( $place_id ); ?>"
                                           class="regular-text"
                                           placeholder="ChIJN1t_tDeuEmsRUsoyG83frY4">
                                    <p class="description">
                                        <?php esc_html_e( 'Find your Place ID:', 'internete-gmb-reviews' ); ?>
                                        <a href="https://developers.google.com/maps/documentation/javascript/examples/places-placeid-finder" target="_blank" rel="noopener">
                                            <?php esc_html_e( 'Place ID Finder Tool ↗', 'internete-gmb-reviews' ); ?>
                                        </a>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="api_key"><?php esc_html_e( 'Google API Key', 'internete-gmb-reviews' ); ?></label>
                                </th>
                                <td>
                                    <input type="text"
                                           id="api_key"
                                           name="api_key"
                                           value="<?php echo esc_attr( $api_key ); ?>"
                                           class="regular-text"
                                           placeholder="AIzaSyD...">
                                    <p class="description">
                                        <?php esc_html_e( 'Required API: Places API (New).', 'internete-gmb-reviews' ); ?>
                                        <a href="https://console.cloud.google.com/google/maps-apis/credentials" target="_blank" rel="noopener">
                                            <?php esc_html_e( 'Google Cloud Console ↗', 'internete-gmb-reviews' ); ?>
                                        </a>
                                    </p>
                                </td>
                            </tr>
                        </table>

                        <p class="submit">
                            <button type="submit" class="button button-primary button-large">
                                <span class="dashicons dashicons-saved" style="vertical-align:middle;margin-right:4px;"></span>
                                <?php esc_html_e( 'Save Settings', 'internete-gmb-reviews' ); ?>
                            </button>
                        </p>
                    </form>
                </div>

                <!-- Sync Status & Manual Fetch -->
                <div class="internete-gmb-card">
                    <h2><?php esc_html_e( 'Review Sync', 'internete-gmb-reviews' ); ?></h2>
                    <p><?php esc_html_e( 'Reviews sync automatically every day at 4:00 AM. Manually sync anytime below.', 'internete-gmb-reviews' ); ?></p>

                    <?php if ( ! empty( $stats['last_sync'] ) ) : ?>
                    <div class="internete-gmb-stats">
                        <div class="stat-item">
                            <span class="stat-label"><?php esc_html_e( 'Last Synced', 'internete-gmb-reviews' ); ?></span>
                            <span class="stat-value"><?php echo esc_html( date( 'M j, Y g:i A', strtotime( $stats['last_sync'] ) ) ); ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label"><?php esc_html_e( 'Average Rating', 'internete-gmb-reviews' ); ?></span>
                            <span class="stat-value"><?php echo esc_html( number_format( (float) $stats['average_rating'], 1 ) ); ?> ★</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label"><?php esc_html_e( 'Total Reviews (Google)', 'internete-gmb-reviews' ); ?></span>
                            <span class="stat-value"><?php echo esc_html( number_format( (int) $stats['total_reviews'] ) ); ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label"><?php esc_html_e( 'Stored Locally', 'internete-gmb-reviews' ); ?></span>
                            <span class="stat-value"><?php echo esc_html( number_format( $review_count ) ); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                        <?php wp_nonce_field( 'internete_gmb_fetch_reviews', 'internete_gmb_nonce' ); ?>
                        <input type="hidden" name="action" value="internete_gmb_fetch_reviews">
                        <button type="submit" class="button button-secondary button-large"
                                <?php echo ( empty( $place_id ) || empty( $api_key ) ) ? 'disabled' : ''; ?>>
                            <span class="dashicons dashicons-update" style="vertical-align:middle;margin-right:4px;"></span>
                            <?php esc_html_e( 'Fetch Reviews Now', 'internete-gmb-reviews' ); ?>
                        </button>
                        <?php if ( empty( $place_id ) || empty( $api_key ) ) : ?>
                            <p class="description" style="margin-top:8px;color:#d63638;">
                                <?php esc_html_e( '⚠ Save your API credentials above before fetching reviews.', 'internete-gmb-reviews' ); ?>
                            </p>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Shortcode Reference -->
                <div class="internete-gmb-card">
                    <h2><?php esc_html_e( 'Display Reviews', 'internete-gmb-reviews' ); ?></h2>
                    <p><?php esc_html_e( 'Add this shortcode to any page, post, or widget area:', 'internete-gmb-reviews' ); ?></p>

                    <div class="internete-gmb-shortcode-box">
                        <code>[internete_gmb_reviews limit="5"]</code>
                        <button type="button" class="button button-small internete-gmb-copy-btn"
                                data-copy="[internete_gmb_reviews limit=&quot;5&quot;]">
                            <?php esc_html_e( 'Copy', 'internete-gmb-reviews' ); ?>
                        </button>
                    </div>

                    <h3><?php esc_html_e( 'Shortcode Parameters', 'internete-gmb-reviews' ); ?></h3>
                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th><?php esc_html_e( 'Parameter', 'internete-gmb-reviews' ); ?></th>
                                <th><?php esc_html_e( 'Default', 'internete-gmb-reviews' ); ?></th>
                                <th><?php esc_html_e( 'Options', 'internete-gmb-reviews' ); ?></th>
                                <th><?php esc_html_e( 'Description', 'internete-gmb-reviews' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td><code>limit</code></td><td>3</td><td><?php esc_html_e( 'Any number', 'internete-gmb-reviews' ); ?></td><td><?php esc_html_e( 'Number of reviews to display', 'internete-gmb-reviews' ); ?></td></tr>
                            <tr><td><code>min_rating</code></td><td>0</td><td>0 – 5</td><td><?php esc_html_e( 'Only show reviews at this rating or above', 'internete-gmb-reviews' ); ?></td></tr>
                            <tr><td><code>layout</code></td><td>grid</td><td>grid, list, horizontal, carousel, compact, paginated</td><td><?php esc_html_e( 'Display layout', 'internete-gmb-reviews' ); ?></td></tr>
                            <tr><td><code>columns</code></td><td>3</td><td>1 – 6</td><td><?php esc_html_e( 'Columns in grid layout', 'internete-gmb-reviews' ); ?></td></tr>
                            <tr><td><code>card_style</code></td><td>default</td><td>default, minimal, detailed</td><td><?php esc_html_e( 'Card visual style', 'internete-gmb-reviews' ); ?></td></tr>
                            <tr><td><code>badge_layout</code></td><td>horizontal</td><td>horizontal, vertical</td><td><?php esc_html_e( 'Badge orientation', 'internete-gmb-reviews' ); ?></td></tr>
                            <tr><td><code>show_badge</code></td><td>yes</td><td>yes, no</td><td><?php esc_html_e( 'Show/hide the star rating badge', 'internete-gmb-reviews' ); ?></td></tr>
                            <tr><td><code>show_reviews</code></td><td>yes</td><td>yes, no</td><td><?php esc_html_e( 'Show/hide review cards', 'internete-gmb-reviews' ); ?></td></tr>
                            <tr><td><code>show_see_all</code></td><td>yes</td><td>yes, no</td><td><?php esc_html_e( '"See All Reviews" link', 'internete-gmb-reviews' ); ?></td></tr>
                            <tr><td><code>max_text_length</code></td><td>200</td><td><?php esc_html_e( 'Any number', 'internete-gmb-reviews' ); ?></td><td><?php esc_html_e( 'Truncate review text at N characters', 'internete-gmb-reviews' ); ?></td></tr>
                            <tr><td><code>autoplay</code></td><td>yes</td><td>yes, no</td><td><?php esc_html_e( 'Carousel autoplay', 'internete-gmb-reviews' ); ?></td></tr>
                            <tr><td><code>autoplay_speed</code></td><td>4000</td><td><?php esc_html_e( 'Milliseconds', 'internete-gmb-reviews' ); ?></td><td><?php esc_html_e( 'Carousel slide interval', 'internete-gmb-reviews' ); ?></td></tr>
                        </tbody>
                    </table>
                </div>

            </div><!-- /.internete-gmb-main-col -->

            <!-- ============================================================
                 PRO SIDEBAR
            ============================================================ -->
            <?php if ( ! $pro_active ) : ?>
            <div class="internete-gmb-sidebar-col">

                <div class="internete-gmb-card internete-gmb-pro-sidebar">
                    <div class="internete-gmb-pro-sidebar-header">
                        <span class="internete-gmb-pro-label"><?php esc_html_e( 'PRO', 'internete-gmb-reviews' ); ?></span>
                        <h3><?php esc_html_e( 'Unlock the Full Power of GMB Reviews', 'internete-gmb-reviews' ); ?></h3>
                        <p><?php esc_html_e( 'Upgrade to Pro and take full control of your Google reputation — without ever leaving WordPress.', 'internete-gmb-reviews' ); ?></p>
                    </div>

                    <ul class="internete-gmb-pro-features">
                        <li>
                            <span class="internete-gmb-pro-check">✓</span>
                            <div>
                                <strong><?php esc_html_e( 'Automatic GBP OAuth Sync', 'internete-gmb-reviews' ); ?></strong>
                                <span><?php esc_html_e( 'No API key needed. Connect with one click via Google OAuth.', 'internete-gmb-reviews' ); ?></span>
                            </div>
                        </li>
                        <li>
                            <span class="internete-gmb-pro-check">✓</span>
                            <div>
                                <strong><?php esc_html_e( 'Reply to Reviews', 'internete-gmb-reviews' ); ?></strong>
                                <span><?php esc_html_e( 'Respond to Google reviews directly from your WP admin.', 'internete-gmb-reviews' ); ?></span>
                            </div>
                        </li>
                        <li>
                            <span class="internete-gmb-pro-check">✓</span>
                            <div>
                                <strong><?php esc_html_e( 'Multi-Location Management', 'internete-gmb-reviews' ); ?></strong>
                                <span><?php esc_html_e( 'Manage reviews across all your locations from one dashboard.', 'internete-gmb-reviews' ); ?></span>
                            </div>
                        </li>
                        <li>
                            <span class="internete-gmb-pro-check">✓</span>
                            <div>
                                <strong><?php esc_html_e( 'Review Moderation', 'internete-gmb-reviews' ); ?></strong>
                                <span><?php esc_html_e( 'Hide, feature, or flag reviews. Add internal team notes.', 'internete-gmb-reviews' ); ?></span>
                            </div>
                        </li>
                        <li>
                            <span class="internete-gmb-pro-check">✓</span>
                            <div>
                                <strong><?php esc_html_e( 'Flexible Sync Scheduling', 'internete-gmb-reviews' ); ?></strong>
                                <span><?php esc_html_e( 'Daily, twice daily, or weekly auto-sync with full error logging.', 'internete-gmb-reviews' ); ?></span>
                            </div>
                        </li>
                        <li>
                            <span class="internete-gmb-pro-check">✓</span>
                            <div>
                                <strong><?php esc_html_e( 'CSV & JSON Export', 'internete-gmb-reviews' ); ?></strong>
                                <span><?php esc_html_e( 'Export all your reviews for reporting or CRM import.', 'internete-gmb-reviews' ); ?></span>
                            </div>
                        </li>
                        <li>
                            <span class="internete-gmb-pro-check">✓</span>
                            <div>
                                <strong><?php esc_html_e( 'Sync History & Error Logs', 'internete-gmb-reviews' ); ?></strong>
                                <span><?php esc_html_e( 'Full audit trail of every sync event.', 'internete-gmb-reviews' ); ?></span>
                            </div>
                        </li>
                    </ul>

                    <a href="<?php echo esc_url( $pro_url ); ?>" target="_blank" rel="noopener"
                       class="button button-primary internete-gmb-pro-cta">
                        <?php esc_html_e( 'Get GMB Reviews Pro →', 'internete-gmb-reviews' ); ?>
                    </a>
                    <p class="internete-gmb-pro-sub">
                        <?php esc_html_e( 'Instant download. Works alongside this plugin.', 'internete-gmb-reviews' ); ?>
                    </p>
                </div>

                <!-- Need Help? -->
                <div class="internete-gmb-card internete-gmb-help-card">
                    <h3><?php esc_html_e( 'Need Help?', 'internete-gmb-reviews' ); ?></h3>
                    <ul>
                        <li><a href="https://internete.net/gmb-reviews-docs" target="_blank" rel="noopener"><?php esc_html_e( 'Documentation ↗', 'internete-gmb-reviews' ); ?></a></li>
                        <li><a href="https://wordpress.org/support/plugin/internete-gmb-reviews/" target="_blank" rel="noopener"><?php esc_html_e( 'Support Forum ↗', 'internete-gmb-reviews' ); ?></a></li>
                        <li><a href="https://wordpress.org/support/plugin/internete-gmb-reviews/reviews/#new-post" target="_blank" rel="noopener"><?php esc_html_e( 'Leave a Review ↗', 'internete-gmb-reviews' ); ?></a></li>
                    </ul>
                </div>

            </div><!-- /.internete-gmb-sidebar-col -->
            <?php endif; ?>

        </div><!-- /.internete-gmb-layout -->

    </div><!-- /.wrap -->

    <script>
    (function(){
        var btn = document.querySelector('.internete-gmb-copy-btn');
        if ( ! btn ) return;
        btn.addEventListener('click', function(){
            navigator.clipboard.writeText( this.dataset.copy ).then(function(){
                btn.textContent = '<?php echo esc_js( __( 'Copied!', 'internete-gmb-reviews' ) ); ?>';
                setTimeout(function(){ btn.textContent = '<?php echo esc_js( __( 'Copy', 'internete-gmb-reviews' ) ); ?>'; }, 2000);
            });
        });
    })();
    </script>
    <?php
}
