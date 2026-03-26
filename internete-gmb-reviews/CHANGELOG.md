# Changelog

All notable changes to the Internete GMB Reviews plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.2.0] - 2025-12-13

### Added
- **NEW**: Horizontal scrolling layout with touch/swipe support on mobile
- **NEW**: Carousel layout with auto-rotation, navigation arrows, and dot indicators
- **NEW**: Compact layout designed for sidebars and footers
- **NEW**: Card style options (`default`, `minimal`, `detailed`)
- **NEW**: Column control for grid layout (1-6 columns via `columns` parameter)
- **NEW**: `max_text_length` parameter to control text truncation
- **NEW**: Carousel-specific settings (`autoplay`, `autoplay_speed`, `show_navigation`, `show_dots`)
- **NEW**: Keyboard navigation for carousel (arrow keys)
- **NEW**: Pause on hover for carousel autoplay
- **NEW**: `source` database column to track review origin
- **NEW**: `last_seen` database column for tracking sync history
- JavaScript carousel functionality (`assets/js/carousel.js`)

### Changed
- **BREAKING**: Reviews now accumulate instead of being replaced on each sync
- Improved duplicate detection using author_name + timestamp combination
- Enhanced responsive grid with better breakpoints
- Updated version to 2.2.0
- Improved CSS organization with layout-specific sections

### Fixed
- Reviews no longer lost when syncing (removed TRUNCATE behavior)
- Better handling of existing reviews during sync

### Examples
```
// Horizontal scrolling
[internete_gmb_reviews layout="horizontal" limit="10"]

// Auto-rotating carousel
[internete_gmb_reviews layout="carousel" autoplay="yes" autoplay_speed="4000"]

// Compact sidebar widget
[internete_gmb_reviews layout="compact" limit="4" show_badge="no"]

// 4-column grid with minimal style
[internete_gmb_reviews layout="grid" columns="4" card_style="minimal"]
```

---

## [2.1.4] - 2024-10-29

### Fixed
- Bug fixes and stability improvements

---

## [2.1.3] - 2024-10-29

### Added
- **NEW**: `badge_layout` shortcode parameter for choosing between horizontal and vertical badge layouts
- **NEW**: Vertical badge layout option perfect for sidebars, widget areas, and narrow spaces
- Enhanced badge styling with improved responsive design
- Comprehensive CSS styles for vertical badge display
- Documentation for new badge layout options in README and shortcode examples

### Changed
- Badge now supports both horizontal (default) and vertical layout modes
- Improved mobile responsiveness for both badge layouts
- Enhanced visual hierarchy in vertical badge layout

### Examples
```
// Default horizontal badge
[internete_gmb_reviews show_reviews="no"]

// NEW - Vertical badge for sidebars
[internete_gmb_reviews show_reviews="no" badge_layout="vertical"]
```

---

## [2.1.2] - 2024-10-29

### Added
- `show_see_all` parameter to control "See All Reviews" link visibility
- Enhanced flexibility for customizing review display

### Changed
- "See All Reviews" link can now be hidden using shortcode parameter
- Improved documentation for new parameter

---

## [2.1.1] - 2024-10-29

### Fixed
- Fixed "Read more" button functionality on reviews
- Improved JavaScript for expanding review text with proper line breaks

### Changed
- Updated "Write a review" button to open Google's native review writing interface
- Changed review URL to use direct Google review writing endpoint
- Better line break preservation in review text

---

## [2.1.0] - 2024-10-29

### Added
- New compact button-style badge design with centered layout
- "See All Reviews" link at the bottom of reviews
- Verified checkmark to individual review stars

### Changed
- Enhanced visual styling with stronger shadows for better depth
- Improved mobile responsiveness
- Updated badge to be more inline with modern Google design patterns

---

## [2.0.0] - 2024-10-28

### Added
- Complete rewrite for performance optimization
- Local database storage for instant loading
- New admin interface with one-click sync
- Multiple layout options (grid/list)
- SEO optimizations with structured data

### Changed
- Reviews now stored locally in WordPress database
- No API calls on every page load
- Responsive design matching Google's official style

---

## [1.0.0] - Initial Release

### Added
- Basic Google My Business review display functionality
- Google Places API integration
- Simple shortcode implementation

---

## Version Comparison

| Version | Key Feature |
|---------|-------------|
| 2.2.0 | Horizontal, carousel, compact layouts + review accumulation |
| 2.1.4 | Bug fixes and stability |
| 2.1.3 | Vertical badge layout option |
| 2.1.2 | Control "See All Reviews" link visibility |
| 2.1.1 | Fixed review text expansion |
| 2.1.0 | Compact badge design |
| 2.0.0 | Performance rewrite with local storage |
| 1.0.0 | Initial release |
