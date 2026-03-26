/**
 * Internete GMB Reviews - Carousel JavaScript
 * Version 2.2.0
 */

(function() {
    'use strict';

    // Initialize all carousels on the page
    function initCarousels() {
        const carousels = document.querySelectorAll('.internete-gmb-reviews.layout-carousel');

        carousels.forEach(function(carousel) {
            new InternetReviewsCarousel(carousel);
        });
    }

    // Carousel Class
    function InternetReviewsCarousel(container) {
        this.container = container;
        this.cards = container.querySelectorAll('.gmb-review-card');
        this.prevBtn = container.querySelector('.gmb-carousel-prev');
        this.nextBtn = container.querySelector('.gmb-carousel-next');
        this.dots = container.querySelectorAll('.gmb-carousel-dot');
        this.currentIndex = 0;
        this.autoplayInterval = null;

        // Get settings from data attributes
        this.autoplay = container.dataset.autoplay === 'true';
        this.speed = parseInt(container.dataset.speed, 10) || 5000;
        this.showNavigation = container.dataset.navigation === 'true';
        this.showDots = container.dataset.dots === 'true';

        this.init();
    }

    InternetReviewsCarousel.prototype.init = function() {
        if (this.cards.length === 0) return;

        // Show first card
        this.showCard(0);

        // Bind navigation events
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', this.prev.bind(this));
        }
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', this.next.bind(this));
        }

        // Bind dot events
        this.dots.forEach(function(dot, index) {
            dot.addEventListener('click', function() {
                this.goTo(index);
            }.bind(this));
        }.bind(this));

        // Start autoplay
        if (this.autoplay) {
            this.startAutoplay();

            // Pause on hover
            this.container.addEventListener('mouseenter', this.stopAutoplay.bind(this));
            this.container.addEventListener('mouseleave', this.startAutoplay.bind(this));
        }

        // Touch/swipe support
        this.initTouchEvents();

        // Keyboard navigation
        this.container.setAttribute('tabindex', '0');
        this.container.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                this.prev();
            } else if (e.key === 'ArrowRight') {
                this.next();
            }
        }.bind(this));
    };

    InternetReviewsCarousel.prototype.showCard = function(index) {
        // Hide all cards
        this.cards.forEach(function(card) {
            card.classList.remove('active');
        });

        // Show target card
        if (this.cards[index]) {
            this.cards[index].classList.add('active');
        }

        // Update dots
        this.dots.forEach(function(dot, i) {
            dot.classList.toggle('active', i === index);
        });

        this.currentIndex = index;
    };

    InternetReviewsCarousel.prototype.next = function() {
        var nextIndex = (this.currentIndex + 1) % this.cards.length;
        this.showCard(nextIndex);
    };

    InternetReviewsCarousel.prototype.prev = function() {
        var prevIndex = (this.currentIndex - 1 + this.cards.length) % this.cards.length;
        this.showCard(prevIndex);
    };

    InternetReviewsCarousel.prototype.goTo = function(index) {
        this.showCard(index);
    };

    InternetReviewsCarousel.prototype.startAutoplay = function() {
        if (this.autoplayInterval) return;

        this.autoplayInterval = setInterval(function() {
            this.next();
        }.bind(this), this.speed);
    };

    InternetReviewsCarousel.prototype.stopAutoplay = function() {
        if (this.autoplayInterval) {
            clearInterval(this.autoplayInterval);
            this.autoplayInterval = null;
        }
    };

    InternetReviewsCarousel.prototype.initTouchEvents = function() {
        var startX = 0;
        var endX = 0;
        var threshold = 50;

        this.container.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
        }, { passive: true });

        this.container.addEventListener('touchend', function(e) {
            endX = e.changedTouches[0].clientX;
            var diff = startX - endX;

            if (Math.abs(diff) > threshold) {
                if (diff > 0) {
                    this.next();
                } else {
                    this.prev();
                }
            }
        }.bind(this), { passive: true });
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCarousels);
    } else {
        initCarousels();
    }

    // Also handle dynamically loaded content (e.g., AJAX)
    window.initInternetReviewsCarousels = initCarousels;

})();
