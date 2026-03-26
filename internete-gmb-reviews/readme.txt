=== Internete GMB Reviews ===
Contributors: internete
Tags: google reviews, google my business, google business profile, reviews, testimonials, local seo, star ratings, rich snippets
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 2.3.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display your Google Business Profile reviews beautifully on your website. Locally cached for instant loading, SEO-optimized with structured data.

== Description ==

**Internete GMB Reviews** is a performance-focused WordPress plugin that displays your Google Business Profile (formerly Google My Business) reviews directly on your website — without slowing it down.

Most review plugins make a live API call every time a visitor loads your page. This plugin is different: reviews are synced once and stored locally in your WordPress database, so they load instantly and never impact your page speed scores.

---

### ⭐ Key Features

* **Lightning Fast** — Reviews load from your local database, not Google's API. Zero impact on page speed.
* **6 Display Layouts** — Grid, List, Horizontal Scroll, Carousel, Compact, and Paginated.
* **3 Card Styles** — Default, Minimal, and Detailed.
* **SEO Structured Data** — Automatic JSON-LD markup for Google rich snippets (star ratings in search results).
* **Google Badge** — Authentic Google-style rating badge with star count. Horizontal or vertical.
* **Auto Sync** — Reviews sync automatically every day at 4:00 AM via WP-Cron. No manual work needed.
* **Mobile Responsive** — Works beautifully on all screen sizes.
* **Fully Customizable** — 12+ shortcode parameters to control exactly what gets displayed.
* **Review Accumulation** — Builds your review library over time. Old reviews are never lost.
* **Zero Bloat** — No page builders, no tracking scripts, no external dependencies on the frontend.
* **GDPR Friendly** — No visitor data is collected. See Privacy section below.

---

### 📋 How It Works

1. Enter your Google Place ID and API Key in Settings → GMB Reviews
2. Click "Fetch Reviews Now" to pull your latest reviews from Google
3. Add `[internete_gmb_reviews]` to any page, post, or widget area
4. Reviews display instantly — served from your own database

---

### 🎨 Display Layouts

`[internete_gmb_reviews layout="grid"]` — Responsive card grid (default)
`[internete_gmb_reviews layout="list"]` — Stacked list with full review text
`[internete_gmb_reviews layout="horizontal"]` — Horizontally scrollable row
`[internete_gmb_reviews layout="carousel"]` — Auto-advancing slideshow with controls
`[internete_gmb_reviews layout="compact"]` — Small cards, great for sidebars
`[internete_gmb_reviews layout="paginated"]` — Browse reviews page by page

---

### 💡 Common Use Cases

**Show your 5-star reviews only:**
`[internete_gmb_reviews min_rating="5" limit="6"]`

**Vertical badge for a sidebar widget:**
`[internete_gmb_reviews show_reviews="no" badge_layout="vertical"]`

**Carousel on a homepage:**
`[internete_gmb_reviews layout="carousel" limit="10" autoplay="yes" autoplay_speed="5000"]`

**Compact grid of 9 reviews:**
`[internete_gmb_reviews layout="grid" columns="3" limit="9" card_style="minimal"]`

---

### 🔒 Pro Version

Need automatic sync without an API key, the ability to reply to reviews from WordPress, or multi-location management? **[GMB Reviews Pro](https://internete.net/gmb-reviews-pro)** adds:

* ✅ Google OAuth connection — no API key required
* ✅ Reply to Google reviews from your WordPress admin
* ✅ Multi-location support — manage all your locations in one place
* ✅ Review moderation — hide, feature, or flag individual reviews
* ✅ Flexible sync scheduling — daily, twice daily, or weekly
* ✅ CSV & JSON export for reporting or CRM import
* ✅ Internal team notes per review
* ✅ Full sync history and error logs

[Learn more about GMB Reviews Pro →](https://internete.net/gmb-reviews-pro)

---

== Installation ==

= Automatic Installation (Recommended) =

1. Log in to your WordPress admin panel
2. Go to **Plugins → Add New**
3. Search for **"Internete GMB Reviews"**
4. Click **Install Now**, then **Activate**
5. Go to **Settings → GMB Reviews** to configure

= Manual Installation =

1. Download the plugin ZIP from WordPress.org
2. Go to **Plugins → Add New → Upload Plugin**
3. Upload the ZIP file and click **Install Now**
4. Activate the plugin

= Setup =

1. Go to **Settings → GMB Reviews**
2. Enter your **Google Place ID** — [Find it here](https://developers.google.com/maps/documentation/javascript/examples/places-placeid-finder)
3. Enter your **Google API Key** — [Get one here](https://console.cloud.google.com/google/maps-apis/credentials) (enable *Places API (New)*)
4. Click **Save Settings**, then **Fetch Reviews Now**
5. Add `[internete_gmb_reviews]` to any page or post

== Frequently Asked Questions ==

= Where do I get my Google Place ID? =

Use Google's [Place ID Finder](https://developers.google.com/maps/documentation/javascript/examples/places-placeid-finder) tool. Search for your business name and copy the Place ID shown.

= Where do I get a Google API Key? =

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create or select a project
3. Enable the **Places API (New)**
4. Go to Credentials → Create Credentials → API Key
5. Restrict the key to the Places API for security

= Will this slow down my website? =

No. Reviews are stored in your WordPress database after the first sync. On the frontend, reviews load from your database — no Google API call is made, ever. This means zero impact on page speed.

= How often do reviews sync? =

Automatically every day at 4:00 AM (server time). You can also sync manually anytime from the settings page.

= What if my site has low traffic and WP-Cron doesn't fire reliably? =

Set up a real server cron job in cPanel:
`0 4 * * * /usr/local/bin/php -q /home/USERNAME/public_html/wp-cron.php > /dev/null 2>&1`

= Can I show only 5-star reviews? =

Yes: `[internete_gmb_reviews min_rating="5"]`

= Can I display a badge without the review cards? =

Yes: `[internete_gmb_reviews show_reviews="no"]`

= Can I use a vertical badge in a sidebar? =

Yes: `[internete_gmb_reviews show_reviews="no" badge_layout="vertical"]`

= Does this work with page builders like Elementor and Divi? =

Yes. The shortcode works in any shortcode-compatible block, widget, or page builder element.

= Does it support multiple locations? =

The free version supports one Google Place ID. [GMB Reviews Pro](https://internete.net/gmb-reviews-pro) supports unlimited locations.

= Can I reply to reviews from WordPress? =

Not in the free version. [GMB Reviews Pro](https://internete.net/gmb-reviews-pro) lets you reply to Google reviews directly from your WP admin using Google OAuth.

= Is this GDPR compliant? =

Yes. The plugin does not collect any data from your visitors. See the Privacy section below for details.

== Shortcode Reference ==

Basic usage:
`[internete_gmb_reviews]`

**All Parameters:**

* `limit` — Number of reviews to display. Default: `3`
* `min_rating` — Minimum star rating to show (1–5). Default: `0` (all)
* `layout` — Display layout: `grid`, `list`, `horizontal`, `carousel`, `compact`, `paginated`. Default: `grid`
* `columns` — Columns in grid layout (1–6). Default: `3`
* `card_style` — Card visual style: `default`, `minimal`, `detailed`. Default: `default`
* `badge_layout` — Badge orientation: `horizontal`, `vertical`. Default: `horizontal`
* `show_badge` — Show the star rating badge: `yes`, `no`. Default: `yes`
* `show_reviews` — Show review cards: `yes`, `no`. Default: `yes`
* `show_see_all` — Show "See All Reviews" link: `yes`, `no`. Default: `yes`
* `max_text_length` — Truncate review text at N characters. Default: `200`
* `autoplay` — Carousel autoplay: `yes`, `no`. Default: `yes`
* `autoplay_speed` — Carousel slide interval in milliseconds. Default: `4000`
* `show_navigation` — Carousel prev/next arrows: `yes`, `no`. Default: `yes`
* `show_dots` — Carousel dot indicators: `yes`, `no`. Default: `yes`

== Screenshots ==

1. Review cards in grid layout with Google badge — default card style
2. Carousel layout with autoplay and navigation controls
3. Horizontal scrollable layout — perfect for wide sections
4. Admin settings page — clean, simple configuration
5. Pro upsell sidebar — upgrade path clearly shown in admin

== Changelog ==

= 2.3.0 - 2026-03-26 =
* NEW: GitHub auto-updater — updates now appear in WP Admin > Plugins automatically

= 2.2.9 - 2026-03-26 =
* FIX: show_date="no" now correctly hides dates in grid/list/horizontal layouts

= 2.2.8 - 2026-03-26 =
* NEW: `show_date` parameter — hide reviewer date with `show_date="no"`

= 2.2.7 - 2026-03-26 =
* FIX: All PHPCS security scan errors resolved — nonce unslash, output escaping, SQL phpcs annotations
* FIX: GitHub Actions QA pipeline now passes cleanly, enabling automated release zips

= 2.2.6 - 2026-03-26 =
* FIX: Sanitize $_GET inputs in settings page (wp_unslash + sanitize_key/sanitize_text_field)
* FIX: Remove overly strict DirectDatabaseQuery rule from PHPCS security scan
* FIX: GitHub Actions release pipeline now produces clean zip on tag push

= 2.2.5 - 2026-03-26 =
* NEW: Paginated layout with previous/next navigation
* IMPROVED: Admin settings page redesigned with Pro feature sidebar
* IMPROVED: Plugin row links — Settings and Go Pro added to plugins list
* IMPROVED: One-time activation welcome notice
* IMPROVED: Copy shortcode button in settings
* IMPROVED: Plugin header updated with Requires PHP, Tested up to
* FIX: Stable tag corrected in readme

= 2.2.0 - 2026-01-15 =
* NEW: Horizontal scrollable layout
* NEW: Carousel layout with autoplay, touch/swipe, keyboard navigation
* NEW: Compact layout for tight spaces and sidebars
* NEW: Review accumulation mode — old reviews are never deleted on sync
* NEW: Carousel parameters: `autoplay`, `autoplay_speed`, `show_navigation`, `show_dots`
* IMPROVED: Responsive design across all layouts

= 2.1.4 - 2025-10-29 =
* NEW: Automatic daily review sync at 4:00 AM (server time) via WP-Cron
* NEW: Deactivation hook to cleanly remove scheduled tasks
* IMPROVED: CSS loads globally for Elementor header/footer compatibility
* IMPROVED: CSS versioning uses file modification time for better cache busting
* ADDED: External cron setup instructions in readme

= 2.1.3 - 2024-10-29 =
* NEW: `badge_layout` parameter — vertical badge display for sidebars

= 2.1.2 - 2024-10-29 =
* NEW: `show_see_all` parameter — control "See All Reviews" link visibility

= 2.1.1 - 2024-10-29 =
* FIX: "Read more" button expanding review text correctly
* IMPROVED: "Write a review" button opens Google's native review writing interface

= 2.1.0 - 2024-10-29 =
* NEW: Compact button-style badge with centered layout
* NEW: "See All Reviews" link below review cards
* IMPROVED: Mobile responsiveness
* IMPROVED: Verified checkmark on individual review stars

= 2.0.0 - 2024-10-28 =
* Complete rewrite for performance
* Local database storage — reviews load from your server, not Google's API
* New admin interface with one-click sync
* Responsive design matching Google's official style
* SEO structured data (JSON-LD)
* Multiple layout options

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 2.2.7 =
Security scan fixes. Automated release pipeline now fully operational. Recommended update.

= 2.2.5 =
Improved admin UI with Pro feature sidebar and plugin row links. Stable tag corrected. Recommended update for all users.

= 2.2.0 =
Major layout update: horizontal, carousel, compact, and review accumulation. Upgrade for the new layouts.

= 2.1.4 =
Automatic daily review sync added. No more manual fetching required.

== Privacy Policy & GDPR ==

This plugin stores review data (reviewer names, profile photo URLs, ratings, and review text) in your WordPress database. This data is publicly available and fetched from Google's Places API based on reviews users have already posted publicly on Google.

**What this plugin does NOT do:**
* Does not collect any data from your website visitors
* Does not set any cookies on the frontend
* Does not make any API calls during frontend page loads
* Does not share data with third parties

**Google profile photos:** When displaying reviews, reviewer profile photos are loaded directly from Google's CDN (`lh3.googleusercontent.com`). This is a visitor-to-Google connection; the plugin does not proxy or store these images.

If you are subject to GDPR or similar regulations, consider adding a note about Google review data in your privacy policy. [Google's Privacy Policy](https://policies.google.com/privacy) covers data users have voluntarily posted publicly.

== Support ==

* **Documentation:** https://internete.net/gmb-reviews-docs
* **Support Forum:** https://wordpress.org/support/plugin/internete-gmb-reviews/
* **Pro Version:** https://internete.net/gmb-reviews-pro

== Credits ==

Developed by [Internete](https://internete.net) — Digital Marketing & Web Technology, New York City.
