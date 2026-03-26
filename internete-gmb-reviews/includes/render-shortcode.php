<?php
/**
 * Shortcode rendering for displaying reviews
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the shortcode
 */
add_shortcode('internete_gmb_reviews', 'internete_gmb_reviews_shortcode');

/**
 * Render the reviews shortcode
 */
function internete_gmb_reviews_shortcode($atts) {
    // Parse attributes
    $atts = shortcode_atts(array(
        'limit' => 3,
        'min_rating' => 0,
        'show_badge' => 'yes',
        'show_reviews' => 'yes',
        'show_see_all' => 'yes',
        'layout' => 'grid',
        'badge_layout' => 'horizontal',
        'columns' => 3,
        'card_style' => 'default',
        'max_text_length' => 200,
        'per_page' => 3,
        'perpage' => 0,  // Alias without underscore for Elementor compatibility
        'show_date' => 'yes',
    ), $atts);

    $limit = intval($atts['limit']);
    $min_rating = intval($atts['min_rating']);
    $show_badge = $atts['show_badge'] === 'yes';
    $show_reviews = $atts['show_reviews'] === 'yes';
    $show_see_all = $atts['show_see_all'] === 'yes';
    $show_date = $atts['show_date'] === 'yes';
    $layout = sanitize_text_field($atts['layout']);
    $badge_layout = sanitize_text_field($atts['badge_layout']);
    $columns = intval($atts['columns']);
    $card_style = sanitize_text_field($atts['card_style']);
    $max_text_length = intval($atts['max_text_length']);
    // Support both per_page and perpage (alias without underscore for Elementor compatibility)
    $per_page = intval($atts['perpage']) > 0 ? intval($atts['perpage']) : intval($atts['per_page']);
    
    // Get stats and reviews
    $stats = internete_gmb_get_stats();
    $reviews = internete_gmb_get_reviews($limit, $min_rating);
    
    // Get Place ID for review writing
    $place_id = internete_gmb_get_setting('place_id');
    $review_url = !empty($place_id) 
        ? 'https://search.google.com/local/writereview?placeid=' . urlencode($place_id)
        : $stats['google_maps_url'];
    
    // Start output buffering
    ob_start();
    ?>
    
    <div class="internete-gmb-container">
        
        <?php if ($show_badge && $stats['total_reviews'] > 0): ?>
        <!-- Google Reviews Badge -->
        <div class="internete-gmb-badge internete-gmb-badge-<?php echo esc_attr($badge_layout); ?>">
            <div class="gmb-badge-header">
                <svg class="google-logo" viewBox="0 0 272 92" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#EA4335" d="M115.75 47.18c0 12.77-9.99 22.18-22.25 22.18s-22.25-9.41-22.25-22.18C71.25 34.32 81.24 25 93.5 25s22.25 9.32 22.25 22.18zm-9.74 0c0-7.98-5.79-13.44-12.51-13.44S80.99 39.2 80.99 47.18c0 7.9 5.79 13.44 12.51 13.44s12.51-5.55 12.51-13.44z"/>
                    <path fill="#FBBC05" d="M163.75 47.18c0 12.77-9.99 22.18-22.25 22.18s-22.25-9.41-22.25-22.18c0-12.85 9.99-22.18 22.25-22.18s22.25 9.32 22.25 22.18zm-9.74 0c0-7.98-5.79-13.44-12.51-13.44s-12.51 5.46-12.51 13.44c0 7.9 5.79 13.44 12.51 13.44s12.51-5.55 12.51-13.44z"/>
                    <path fill="#4285F4" d="M209.75 26.34v39.82c0 16.38-9.66 23.07-21.08 23.07-10.75 0-17.22-7.19-19.66-13.07l8.48-3.53c1.51 3.61 5.21 7.87 11.17 7.87 7.31 0 11.84-4.51 11.84-13v-3.19h-.34c-2.18 2.69-6.38 5.04-11.68 5.04-11.09 0-21.25-9.66-21.25-22.09 0-12.52 10.16-22.26 21.25-22.26 5.29 0 9.49 2.35 11.68 4.96h.34v-3.61h9.25zm-8.56 20.92c0-7.81-5.21-13.52-11.84-13.52-6.72 0-12.35 5.71-12.35 13.52 0 7.73 5.63 13.36 12.35 13.36 6.63 0 11.84-5.63 11.84-13.36z"/>
                    <path fill="#34A853" d="M225 3v65h-9.5V3h9.5z"/>
                    <path fill="#EA4335" d="M262.02 54.48l7.56 5.04c-2.44 3.61-8.32 9.83-18.48 9.83-12.6 0-22.01-9.74-22.01-22.18 0-13.19 9.49-22.18 20.92-22.18 11.51 0 17.14 9.16 18.98 14.11l1.01 2.52-29.65 12.28c2.27 4.45 5.8 6.72 10.75 6.72 4.96 0 8.4-2.44 10.92-6.14zm-23.27-7.98l19.82-8.23c-1.09-2.77-4.37-4.7-8.23-4.7-4.95 0-11.84 4.37-11.59 12.93z"/>
                    <path fill="#4285F4" d="M35.29 41.41V32H67c.31 1.64.47 3.58.47 5.68 0 7.06-1.93 15.79-8.15 22.01-6.05 6.3-13.78 9.66-24.02 9.66C16.32 69.35.36 53.89.36 34.91.36 15.93 16.32.47 35.3.47c10.5 0 17.98 4.12 23.6 9.49l-6.64 6.64c-4.03-3.78-9.49-6.72-16.97-6.72-13.86 0-24.7 11.17-24.7 25.03 0 13.86 10.84 25.03 24.7 25.03 8.99 0 14.11-3.61 17.39-6.89 2.66-2.66 4.41-6.46 5.1-11.65l-22.49.01z"/>
                </svg>
                <span class="gmb-reviews-text">Reviews</span>
            </div>
            
            <div class="gmb-badge-content">
                <div class="gmb-rating-stars">
                    <?php echo internete_gmb_render_stars($stats['average_rating']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>

                <div class="gmb-rating-score">
                    <?php echo esc_html( number_format($stats['average_rating'], 1) ); ?>
                </div>

                <div class="gmb-review-count">
                    <?php echo esc_html( number_format($stats['total_reviews']) ); ?> reviews
                </div>
                
                <?php if (!empty($review_url)): ?>
                <a href="<?php echo esc_url($review_url); ?>" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="gmb-write-review-btn">
                    Write a review
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if ($show_reviews && !empty($reviews)): ?>
        <!-- Reviews List -->
        <?php
        $layout_classes = 'layout-' . $layout;
        if ($layout === 'grid' || $layout === 'paginated') {
            $layout_classes .= ' columns-' . $columns;
        }
        if ($card_style !== 'default') {
            $layout_classes .= ' style-' . $card_style;
        }

        // Generate unique ID for this instance
        $instance_id = 'gmb-' . wp_rand(1000, 9999);
        $reviews_array = array_values((array) $reviews);
        $total_reviews = count($reviews_array);
        $total_pages = ceil($total_reviews / $per_page);
        ?>

        <?php if ($layout === 'paginated'): ?>
        <!-- Paginated Layout -->
        <style>
        #<?php echo esc_attr($instance_id); ?> { position: relative; }
        #<?php echo esc_attr($instance_id); ?> .gmb-paginated-content { display: flex; align-items: center; gap: 15px; }
        #<?php echo esc_attr($instance_id); ?> .gmb-page { display: grid; grid-template-columns: repeat(<?php echo esc_attr($columns); ?>, 1fr); gap: 20px; flex: 1; }
        #<?php echo esc_attr($instance_id); ?> .gmb-nav-btn { width: 44px; height: 44px; min-width: 44px; border-radius: 50%; background: #fff; border: 1px solid #dadce0; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.15); transition: all 0.2s; }
        #<?php echo esc_attr($instance_id); ?> .gmb-nav-btn:hover { background: #f8f9fa; box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
        #<?php echo esc_attr($instance_id); ?> .gmb-nav-btn:disabled { opacity: 0.3; cursor: not-allowed; }
        #<?php echo esc_attr($instance_id); ?> .gmb-nav-btn svg { width: 20px; height: 20px; stroke: #5f6368; }
        #<?php echo esc_attr($instance_id); ?> .gmb-pagination-dots { display: flex; justify-content: center; gap: 8px; margin-top: 20px; }
        #<?php echo esc_attr($instance_id); ?> .gmb-dot { width: 10px; height: 10px; border-radius: 50%; background: #dadce0; border: none; cursor: pointer; padding: 0; transition: all 0.2s; }
        #<?php echo esc_attr($instance_id); ?> .gmb-dot:hover { background: #9aa0a6; }
        #<?php echo esc_attr($instance_id); ?> .gmb-dot.active { background: #1a73e8; transform: scale(1.2); }
        @media (max-width: 768px) { #<?php echo esc_attr($instance_id); ?> .gmb-page { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 480px) { #<?php echo esc_attr($instance_id); ?> .gmb-page { grid-template-columns: 1fr; } #<?php echo esc_attr($instance_id); ?> .gmb-nav-btn { width: 36px; height: 36px; min-width: 36px; } }
        </style>
        <div class="internete-gmb-reviews layout-paginated columns-<?php echo esc_attr($columns); ?><?php echo $card_style !== 'default' ? ' style-' . esc_attr($card_style) : ''; ?>" id="<?php echo esc_attr($instance_id); ?>">
            <div class="gmb-paginated-content">
                <?php if ($total_pages > 1): ?>
                <button class="gmb-nav-btn gmb-nav-prev" onclick="gmbNav('<?php echo esc_attr($instance_id); ?>', -1)" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
                <?php endif; ?>

                <div class="gmb-paginated-wrapper" style="flex: 1;">
                    <?php
                    $pages = array_chunk($reviews_array, $per_page);
                    foreach ($pages as $page_index => $page_reviews):
                    ?>
                    <div class="gmb-page" data-page="<?php echo (int) $page_index; ?>" style="display:<?php echo $page_index === 0 ? 'grid' : 'none'; ?>;">
                    <?php foreach ($page_reviews as $review):
                        $review = (object) $review;
                    ?>
                    <div class="gmb-review-card">
                        <div class="gmb-review-header">
                            <div class="gmb-reviewer-info">
                                <?php if (!empty($review->profile_photo_url)): ?>
                                <img src="<?php echo esc_url($review->profile_photo_url); ?>"
                                     alt="<?php echo esc_attr($review->author_name); ?>"
                                     class="gmb-reviewer-photo"
                                     loading="lazy">
                                <?php else: ?>
                                <div class="gmb-reviewer-photo gmb-reviewer-initial">
                                    <?php echo esc_html(strtoupper(substr($review->author_name, 0, 1))); ?>
                                </div>
                                <?php endif; ?>

                                <div class="gmb-reviewer-meta">
                                    <div class="gmb-reviewer-name"><?php echo esc_html($review->author_name); ?></div>
                                    <?php if ($show_date): ?><div class="gmb-review-time"><?php echo esc_html($review->relative_time); ?></div><?php endif; ?>
                                </div>
                            </div>

                            <div class="gmb-google-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                            </div>
                        </div>

                        <div class="gmb-review-stars">
                            <?php echo internete_gmb_render_stars($review->rating); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </div>

                        <?php if (!empty($review->text)): ?>
                        <div class="gmb-review-text">
                            <?php
                            $text = esc_html($review->text);
                            $should_truncate = strlen($text) > $max_text_length && $max_text_length > 0;
                            if ($should_truncate) {
                                $display_text = substr($text, 0, $max_text_length) . '...';
                                $full_text = $text;
                                ?>
                                <span class="gmb-review-text-content"><?php echo nl2br($display_text); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                                <button class="gmb-read-more" onclick="this.previousElementSibling.innerHTML='<?php echo str_replace(array("\r", "\n"), array('', '<br>'), esc_js($full_text)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>';this.style.display='none';">Read more</button>
                            <?php } else {
                                echo nl2br($text); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            } ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
                </div>

                <?php if ($total_pages > 1): ?>
                <button class="gmb-nav-btn gmb-nav-next" onclick="gmbNav('<?php echo esc_attr($instance_id); ?>', 1)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
                <?php endif; ?>
            </div>

            <?php if ($total_pages > 1): ?>
            <div class="gmb-pagination-dots">
                <?php for ($i = 0; $i < $total_pages; $i++): ?>
                <button class="gmb-dot<?php echo $i === 0 ? ' active' : ''; ?>" onclick="gmbGoTo('<?php echo esc_attr($instance_id); ?>', <?php echo (int) $i; ?>)"></button>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>

        <?php if ($total_pages > 1): ?>
        <script>
        window.gmbState = window.gmbState || {};
        window.gmbState['<?php echo esc_js($instance_id); ?>'] = {page: 0, total: <?php echo (int) $total_pages; ?>};

        function gmbNav(id, dir) {
            var s = window.gmbState[id];
            gmbGoTo(id, s.page + dir);
        }

        function gmbGoTo(id, page) {
            var s = window.gmbState[id];
            if (page < 0 || page >= s.total) return;
            s.page = page;

            var el = document.getElementById(id);
            var pages = el.querySelectorAll('.gmb-page');
            var dots = el.querySelectorAll('.gmb-dot');
            var prev = el.querySelector('.gmb-nav-prev');
            var next = el.querySelector('.gmb-nav-next');

            for (var i = 0; i < pages.length; i++) { pages[i].style.display = i === page ? 'grid' : 'none'; }
            for (var i = 0; i < dots.length; i++) { dots[i].className = i === page ? 'gmb-dot active' : 'gmb-dot'; }
            prev.disabled = page === 0;
            next.disabled = page === s.total - 1;
        }
        </script>
        <?php endif; ?>
        <?php else: ?>
        <!-- Standard Layouts (grid, list) -->
        <div class="internete-gmb-reviews <?php echo esc_attr($layout_classes); ?>">
            <?php foreach ($reviews as $review): ?>
            <div class="gmb-review-card">
                <div class="gmb-review-header">
                    <div class="gmb-reviewer-info">
                        <?php if (!empty($review->profile_photo_url)): ?>
                        <img src="<?php echo esc_url($review->profile_photo_url); ?>"
                             alt="<?php echo esc_attr($review->author_name); ?>"
                             class="gmb-reviewer-photo"
                             loading="lazy">
                        <?php else: ?>
                        <div class="gmb-reviewer-photo gmb-reviewer-initial">
                            <?php echo esc_html(strtoupper(substr($review->author_name, 0, 1))); ?>
                        </div>
                        <?php endif; ?>

                        <div class="gmb-reviewer-meta">
                            <div class="gmb-reviewer-name">
                                <?php echo esc_html($review->author_name); ?>
                            </div>
                            <?php if ($show_date): ?>
                            <div class="gmb-review-time">
                                <?php echo esc_html($review->relative_time); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="gmb-google-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                    </div>
                </div>

                <div class="gmb-review-stars">
                    <?php echo internete_gmb_render_stars($review->rating); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>

                <?php if (!empty($review->text)): ?>
                <div class="gmb-review-text">
                    <?php
                    $text = esc_html($review->text);
                    $should_truncate = strlen($text) > $max_text_length && $max_text_length > 0;
                    if ($should_truncate) {
                        $display_text = substr($text, 0, $max_text_length) . '...';
                        $full_text = $text;
                        ?>
                        <span class="gmb-review-text-content"><?php echo nl2br($display_text); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                        <button class="gmb-read-more" onclick="
                            var content = this.previousElementSibling;
                            content.innerHTML = '<?php echo str_replace(array("\r", "\n"), array('', '<br>'), esc_js($full_text)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>';
                            this.style.display = 'none';
                        ">
                            Read more
                        </button>
                    <?php } else {
                        echo nl2br($text); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    } ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ($show_see_all && !empty($stats['google_maps_url'])): ?>
        <!-- See All Reviews Link -->
        <div class="gmb-see-all-reviews">
            <a href="<?php echo esc_url($stats['google_maps_url']); ?>" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="gmb-see-all-link">
                See All Reviews
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                    <polyline points="15 3 21 3 21 9"></polyline>
                    <line x1="10" y1="14" x2="21" y2="3"></line>
                </svg>
            </a>
        </div>
        <?php endif; ?>
        <?php endif; ?>
        
    </div>
    
    <?php
    return ob_get_clean();
}

/**
 * Render star rating HTML
 */
function internete_gmb_render_stars($rating) {
    $rating = floatval($rating);
    $full_stars = floor($rating);
    $half_star = ($rating - $full_stars) >= 0.5;
    $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);
    
    $html = '<div class="gmb-stars">';
    
    // Full stars
    for ($i = 0; $i < $full_stars; $i++) {
        $html .= '<span class="gmb-star gmb-star-full">★</span>';
    }
    
    // Half star
    if ($half_star) {
        $html .= '<span class="gmb-star gmb-star-half">★</span>';
    }
    
    // Empty stars
    for ($i = 0; $i < $empty_stars; $i++) {
        $html .= '<span class="gmb-star gmb-star-empty">★</span>';
    }
    
    $html .= '</div>';
    
    return $html;
}
