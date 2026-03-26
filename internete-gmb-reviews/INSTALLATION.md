# Internete GMB Reviews - Installation & Setup Guide

## 📦 Installation

### Method 1: Upload via WordPress Admin (Recommended)

1. Download the `internete-gmb-reviews.zip` file
2. Log in to your WordPress admin panel
3. Navigate to **Plugins → Add New**
4. Click **Upload Plugin** button at the top
5. Click **Choose File** and select the ZIP file
6. Click **Install Now**
7. Click **Activate Plugin**

### Method 2: Manual FTP Installation

1. Extract the `internete-gmb-reviews.zip` file
2. Upload the `internete-gmb-reviews` folder to `/wp-content/plugins/`
3. Log in to WordPress admin
4. Navigate to **Plugins → Installed Plugins**
5. Find "Internete GMB Reviews" and click **Activate**

---

## ⚙️ Setup & Configuration

### Step 1: Get Your Google Place ID

1. Visit the [Place ID Finder](https://developers.google.com/maps/documentation/javascript/examples/places-placeid-finder)
2. Search for your business name or address
3. Click on your business marker on the map
4. Copy the **Place ID** (starts with "ChIJ...")

**Example Place ID:** `ChIJN1t_tDeuEmsRUsoyG83frY4`

### Step 2: Get Your Google API Key

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Click **Enable APIs and Services**
4. Search for and enable **"Places API (New)"**
5. Go to **Credentials** in the left menu
6. Click **+ CREATE CREDENTIALS → API Key**
7. Copy your new API key
8. **(Important)** Click **Restrict Key**:
   - Under "API restrictions", select "Restrict key"
   - Check only "Places API"
   - Save

**Security Note:** Always restrict your API key to only the Places API to prevent unauthorized usage.

### Step 3: Configure the Plugin

1. In WordPress admin, go to **Settings → GMB Reviews**
2. Paste your **Place ID** in the first field
3. Paste your **API Key** in the second field
4. Click **Save Settings**

### Step 4: Fetch Your Reviews

1. After saving settings, click **Fetch Reviews Now**
2. Wait a few seconds (you should see a success message)
3. Your reviews are now stored locally in your WordPress database

---

## 📝 Displaying Reviews on Your Site

### Basic Usage

Add this shortcode to any page, post, or widget:

```
[internete_gmb_reviews]
```

### Shortcode Examples

**Show 5 reviews:**
```
[internete_gmb_reviews limit="5"]
```

**Show only 5-star reviews:**
```
[internete_gmb_reviews min_rating="5"]
```

**List layout instead of grid:**
```
[internete_gmb_reviews layout="list"]
```

**Show only badge (no individual reviews):**
```
[internete_gmb_reviews show_reviews="no"]
```

**Show only reviews (no badge):**
```
[internete_gmb_reviews show_badge="no"]
```

**Combine parameters:**
```
[internete_gmb_reviews limit="10" min_rating="4" layout="list"]
```

---

## 🎨 Customizing the Design

The plugin uses standard CSS classes that you can override in your theme's Custom CSS (Appearance → Customize → Additional CSS):

### Example Customizations

**Change badge background color:**
```css
.internete-gmb-badge {
    background: #f0f9ff;
}
```

**Adjust star color:**
```css
.gmb-star-full {
    color: #ff9500;
}
```

**Change button style:**
```css
.gmb-write-review-btn {
    background: #4285F4;
    color: white;
    border: none;
}
```

**Make review cards have shadow always:**
```css
.gmb-review-card {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
```

---

## 🔄 Updating Reviews

### Manual Update (Recommended)

1. Go to **Settings → GMB Reviews**
2. Click **Fetch Reviews Now**
3. Reviews will refresh from Google

**When to update:**
- After receiving new reviews
- Weekly or monthly (your preference)

### Automatic Updates (Advanced)

You can set up a WordPress cron job to auto-sync reviews:

Add this to your theme's `functions.php`:

```php
// Schedule daily review sync
if (!wp_next_scheduled('internete_gmb_auto_sync')) {
    wp_schedule_event(time(), 'daily', 'internete_gmb_auto_sync');
}

add_action('internete_gmb_auto_sync', 'internete_gmb_auto_fetch');
function internete_gmb_auto_fetch() {
    if (function_exists('internete_gmb_fetch_reviews')) {
        internete_gmb_fetch_reviews();
    }
}
```

---

## 🐛 Troubleshooting

### "API request failed" error

**Cause:** Invalid API key or Places API not enabled

**Solution:**
1. Verify your API key is correct
2. Ensure "Places API (New)" is enabled in Google Cloud Console
3. Check that your API key restrictions allow Places API

### "No results returned" error

**Cause:** Invalid Place ID

**Solution:**
1. Double-check your Place ID using the Place ID Finder
2. Make sure you copied the complete Place ID (starts with "ChIJ")

### Reviews not displaying

**Cause:** Shortcode not added correctly

**Solution:**
1. Ensure you're using `[internete_gmb_reviews]` (with square brackets)
2. Check if reviews were fetched successfully in Settings → GMB Reviews
3. Try adding the shortcode to a new test page

### Badge shows 0 reviews

**Cause:** Reviews haven't been fetched yet

**Solution:**
1. Go to Settings → GMB Reviews
2. Click "Fetch Reviews Now"
3. Refresh the page where you added the shortcode

---

## 📊 Plugin Requirements

- **WordPress:** 5.0 or higher
- **PHP:** 7.4 or higher
- **MySQL:** 5.6 or higher
- **Google Places API:** Active and enabled

---

## 🔒 Security Best Practices

1. **Restrict Your API Key:**
   - Always limit to Places API only
   - Consider IP restrictions if your server has a static IP

2. **Regular Updates:**
   - Keep WordPress, PHP, and this plugin updated

3. **Monitor API Usage:**
   - Check your Google Cloud Console for unusual activity
   - Set up billing alerts to avoid surprise charges

---

## 💡 Tips for Best Results

1. **Respond to Reviews:** Encourage customers to leave reviews by responding to them on Google
2. **Showcase Your Best:** Use `min_rating="4"` to show only great reviews
3. **Keep It Fresh:** Update reviews monthly to show current customer sentiment
4. **Mobile First:** Test how reviews look on mobile devices
5. **Strategic Placement:** Put reviews on high-traffic pages (homepage, contact, about)

---

## 📞 Support

Need help? Visit: https://internete.net/support

---

## 🚀 What's Next?

Now that your reviews are displaying:

1. Monitor your Google Business Profile for new reviews
2. Respond to reviews on Google to encourage more feedback
3. Test different placements and parameters to maximize impact
4. Consider adding reviews to landing pages for better conversion

---

**Enjoy showcasing your Google reviews! 🌟**
