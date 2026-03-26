# Internete GMB Reviews - WordPress Plugin

[![Version](https://img.shields.io/badge/version-2.2.0-blue.svg)](https://github.com/internetebiz/internete-gmb/releases)
[![WordPress](https://img.shields.io/badge/wordpress-5.0%2B-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/php-7.4%2B-purple.svg)](https://php.net/)
[![License](https://img.shields.io/badge/license-GPL%20v2-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

Display Google My Business reviews beautifully on your WordPress website with a modern, lightweight plugin that improves SEO without slowing down your site.

## Features

- **Lightweight & Fast** - No bloat, minimal JavaScript, optimized for performance
- **Local Storage** - Reviews cached locally, no API calls on every page load
- **Review Accumulation** - Build up reviews over time (doesn't overwrite existing reviews)
- **Multiple Layouts** - Grid, List, Horizontal, Carousel, and Compact
- **SEO Optimized** - Structured data markup for rich snippets
- **Beautiful Design** - Professional Google-style badge and review cards
- **Mobile Responsive** - Looks perfect on all devices
- **Customizable** - Control columns, card styles, text length, and more
- **Native Review Interface** - "Write a review" opens Google's official review popup

## Installation

### Manual Installation

1. Download the [latest release](https://github.com/internetebiz/internete-gmb/releases/latest)
2. Extract the files to `/wp-content/plugins/internete-gmb-reviews/`
3. Activate the plugin through the 'Plugins' menu in WordPress

### From GitHub
```bash
cd /path/to/wordpress/wp-content/plugins/
git clone https://github.com/internetebiz/internete-gmb.git internete-gmb-reviews
```

## Configuration

1. Go to **Settings → GMB Reviews**
2. Enter your **Google Place ID** ([Find it here](https://developers.google.com/maps/documentation/javascript/examples/places-placeid-finder))
3. Enter your **Google API Key** ([Get one here](https://console.cloud.google.com/google/maps-apis/credentials))
4. Click **"Fetch Reviews Now"** to sync your reviews
5. Add the shortcode `[internete_gmb_reviews]` to any page

### API Key Setup (Important!)

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create or select a project
3. Enable **"Places API"** (NOT "Places API New")
4. Create an API key under Credentials
5. Set **Application restrictions** to "None" (or IP addresses for your server)
6. Set **API restrictions** to include "Places API"
7. Enable billing on your project

## Usage

### Basic Shortcode
```
[internete_gmb_reviews]
```

### Shortcode Parameters

| Parameter | Description | Default | Values |
|-----------|-------------|---------|--------|
| `limit` | Number of reviews to display | 3 | 1-100 |
| `min_rating` | Minimum star rating to show | 0 | 0-5 |
| `layout` | Display layout | grid | grid, list, horizontal, carousel, compact |
| `columns` | Columns for grid layout | 3 | 1-6 |
| `card_style` | Card design style | default | default, minimal, detailed |
| `show_badge` | Show Google rating badge | yes | yes, no |
| `show_reviews` | Show review cards | yes | yes, no |
| `show_see_all` | Show "See All Reviews" link | yes | yes, no |
| `badge_layout` | Badge orientation | horizontal | horizontal, vertical |
| `max_text_length` | Characters before "Read more" | 200 | number |
| `autoplay` | Auto-rotate carousel | yes | yes, no |
| `autoplay_speed` | Carousel rotation speed (ms) | 5000 | milliseconds |
| `show_navigation` | Show carousel arrows | yes | yes, no |
| `show_dots` | Show carousel dots | yes | yes, no |

### Examples

**3-column grid:**
```
[internete_gmb_reviews layout="grid" columns="3" limit="6"]
```

**Horizontal scrolling:**
```
[internete_gmb_reviews layout="horizontal" limit="10"]
```

**Auto-rotating carousel:**
```
[internete_gmb_reviews layout="carousel" autoplay="yes" autoplay_speed="4000"]
```

**Compact sidebar widget:**
```
[internete_gmb_reviews layout="compact" limit="4" show_badge="no"]
```

**Minimal style list:**
```
[internete_gmb_reviews layout="list" card_style="minimal" limit="5"]
```

**Badge only (no reviews):**
```
[internete_gmb_reviews show_reviews="no"]
```

**5-star reviews only:**
```
[internete_gmb_reviews min_rating="5"]
```

**Vertical badge for sidebar:**
```
[internete_gmb_reviews show_reviews="no" badge_layout="vertical"]
```

## Layouts

### Grid
Displays reviews in a responsive grid. Use `columns` attribute to control the number of columns (1-6). Automatically adjusts for smaller screens.

### List
Vertical stack of review cards, ideal for narrow containers or full-width sections.

### Horizontal
Horizontally scrollable row of reviews with smooth scrolling, touch/swipe support on mobile, and a custom scrollbar.

### Carousel
One review at a time with:
- Auto-rotation (configurable speed)
- Previous/Next navigation arrows
- Dot indicators
- Pause on hover
- Touch/swipe support
- Keyboard navigation (arrow keys)

### Compact
Smaller cards designed for sidebars, footers, and space-constrained areas.

## Card Styles

### Default
Standard cards with shadows and hover effects. Professional look suitable for most sites.

### Minimal
Borderless cards with divider lines. Clean, modern aesthetic.

### Detailed
Larger cards with bigger photos and text. Ideal for testimonial sections.

## API Limitations & Review Accumulation

Google Places API returns a maximum of **5 reviews per request**. However, this plugin **accumulates reviews over time**:

- Each sync adds new reviews without deleting existing ones
- Duplicate detection by author name + timestamp
- Reviews are automatically synced daily at 4 AM
- Manual sync available in settings

**Tip:** Run manual syncs periodically to capture new reviews as they appear in Google's rotation.

## Troubleshooting

### "No results returned from Google Places API"

1. **Verify Place ID** - Use [Place ID Finder](https://developers.google.com/maps/documentation/javascript/examples/places-placeid-finder)
2. **Check API Enabled** - Enable "Places API" (NOT "Places API New")
3. **API Key Restrictions** - Set Application restrictions to "None"
4. **Billing** - Ensure billing is enabled on your Google Cloud project
5. **Wait** - Changes can take up to 5 minutes to propagate

### Reviews not displaying

1. Check that reviews exist in Settings → GMB Reviews
2. Verify shortcode syntax is correct
3. Check browser console for JavaScript errors (carousel layout)
4. Clear any caching plugins

### Carousel not working

1. Ensure JavaScript is loading (check browser console)
2. Verify layout="carousel" is set correctly
3. Check for JavaScript conflicts with other plugins

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- Google Places API Key
- Google Place ID for your business

## Changelog

### v2.2.0 - December 2025
- **NEW**: Horizontal scrolling layout with touch/swipe support
- **NEW**: Carousel layout with autoplay, navigation, and dots
- **NEW**: Compact layout for sidebars and footers
- **NEW**: Card style options (default, minimal, detailed)
- **NEW**: Column control for grid layout (1-6 columns)
- **NEW**: `max_text_length` parameter for text truncation
- **NEW**: Carousel settings (autoplay, speed, navigation, dots)
- **IMPROVED**: Reviews now accumulate instead of being replaced
- **IMPROVED**: Added `source` and `last_seen` tracking fields
- **IMPROVED**: Better duplicate detection for reviews
- **IMPROVED**: Responsive grid breakpoints
- Various CSS improvements and bug fixes

### v2.1.4 - October 2024
- Bug fixes and stability improvements

### v2.1.3 - October 2024
- Added `badge_layout` parameter for vertical badge display
- Vertical badge layout for sidebars

### v2.1.2 - October 2024
- Added `show_see_all` parameter

### v2.1.1 - October 2024
- Fixed "Read more" button functionality
- Updated "Write a review" to open Google's review popup

### v2.1.0 - October 2024
- New compact button-style badge design
- Added "See All Reviews" link
- Enhanced shadows and visual styling

### v2.0.0 - October 2024
- Complete rewrite for performance
- Local database storage
- New admin interface

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details.

## Support

- **Website**: [https://internete.net/support](https://internete.net/support)
- **Issues**: [GitHub Issues](https://github.com/internetebiz/internete-gmb/issues)

## Developer

**Internete**
- Website: [https://internete.net](https://internete.net)
- GitHub: [@internetebiz](https://github.com/internetebiz)

---

**Made with care for the WordPress community**

*Last updated: December 2025*
