# Alumni Module Assets - Quick Reference Card

## 📁 File Locations

```
assets/
├── css/
│   ├── style.css      → Main frontend styles
│   └── admin.css      → Admin panel styles
├── js/
│   └── alumni.js      → Frontend JavaScript
└── images/
    ├── default-avatar.png    → Profile placeholder
    ├── default-event.png     → Event placeholder
    ├── logo.png              → Module logo
    ├── iconsmall.png         → 16x16 icon
    ├── iconbig.png           → 32x32 icon
    └── icons/                → Admin icons
```

## 🎨 CSS Quick Reference

### Custom Properties
```css
--alumni-primary: #0d6efd
--alumni-success: #28a745
--alumni-danger: #dc3545
--alumni-warning: #ffc107
```

### Key Classes
```css
.profile-card          → Alumni profile cards
.event-card            → Event cards
.connection-badge      → Connection status
.rsvp-status          → RSVP indicators
.skill-tag            → Skill badges
.dashboard-widget     → Dashboard components
.view-toggle          → Grid/list toggle
.directory-grid       → Grid view layout
.directory-list       → List view layout
```

### Responsive Breakpoints
- **Desktop:** Default
- **Tablet:** max-width: 768px
- **Mobile:** max-width: 576px

## ⚡ JavaScript Quick Reference

### AJAX Endpoints
```javascript
// RSVP to event
fetch(XOOPS_URL + '/modules/alumni/ajax.php', {
    method: 'POST',
    body: 'action=rsvp&event_id=123&status=going'
});

// Send connection request
fetch(XOOPS_URL + '/modules/alumni/ajax.php', {
    method: 'POST',
    body: 'action=send_connection&alumni_id=456'
});

// Search autocomplete
fetch(XOOPS_URL + '/modules/alumni/ajax.php?action=search_autocomplete&q=john');
```

### Key Functions
```javascript
initViewToggle()          → Grid/list view switching
initRSVPHandlers()        → Event RSVP management
initConnectionHandlers()  → Connection requests
initImageUploadPreview()  → Image preview
initSkillTagManagement()  → Tag management
initFormValidation()      → Form validation
initSearchAutocomplete()  → Search suggestions
```

### Data Attributes
```html
<button class="btn-rsvp" data-event-id="123" data-status="going">RSVP</button>
<button class="btn-connect" data-alumni-id="456">Connect</button>
<button class="view-toggle-btn" data-view="grid">Grid</button>
<input type="file" data-preview="preview-img" data-allowed-types="jpg,png">
```

## 🖼️ Image Constants

### In PHP
```php
define('ALUMNI_ASSETS_URL', XOOPS_URL . '/modules/alumni/assets');
define('ALUMNI_DEFAULT_AVATAR', ALUMNI_ASSETS_URL . '/images/default-avatar.png');
define('ALUMNI_DEFAULT_EVENT', ALUMNI_ASSETS_URL . '/images/default-event.png');
```

### In Smarty
```smarty
<img src="<{$profile.photo|default:$xoops_url}/modules/alumni/assets/images/default-avatar.png">
<img src="<{$event.banner|default:$xoops_url}/modules/alumni/assets/images/default-event.png">
```

## 🔧 Common Tasks

### Include Assets in Template
```smarty
<{* CSS *}>
<link rel="stylesheet" href="<{$xoops_url}>/modules/alumni/assets/css/style.css">

<{* JavaScript *}>
<script src="<{$xoops_url}>/modules/alumni/assets/js/alumni.js"></script>
```

### Include via Preload
```php
class AlumniCorePreload extends XoopsPreloadItem {
    public static function eventCoreHeaderAddmeta($args) {
        $GLOBALS['xoTheme']->addStylesheet(
            XOOPS_URL . '/modules/alumni/assets/css/style.css'
        );
        $GLOBALS['xoTheme']->addScript(
            XOOPS_URL . '/modules/alumni/assets/js/alumni.js'
        );
    }
}
```

### Regenerate Images
```bash
cd modules/alumni/assets/images
php generate_placeholders.php
```

### Clear Cache After Changes
```bash
rm -rf xoops_data/caches/xoops_cache/*
rm -rf xoops_data/caches/smarty_cache/*
rm -rf xoops_data/caches/smarty_compile/*
```

## 🎯 HTML Examples

### Profile Card
```html
<div class="profile-card profile-verified">
    <img src="photo.jpg" class="profile-photo" alt="Name">
    <h3 class="profile-name"><a href="#">John Doe</a></h3>
    <p class="profile-degree">B.Sc. Computer Science, 2020</p>
    <div class="profile-meta">
        <span><i class="fa fa-map-marker"></i> New York</span>
        <span><i class="fa fa-briefcase"></i> Software Engineer</span>
    </div>
    <div class="profile-skills">
        <span class="badge bg-primary">PHP</span>
        <span class="badge bg-primary">JavaScript</span>
    </div>
</div>
```

### Event Card
```html
<div class="event-card event-featured">
    <div class="position-relative">
        <img src="banner.jpg" class="event-banner" alt="Event">
        <div class="event-date-badge">
            <div class="event-date-day">25</div>
            <div class="event-date-month">Feb</div>
        </div>
    </div>
    <div class="card-body">
        <h3 class="event-title"><a href="#">Alumni Reunion</a></h3>
        <div class="event-meta">
            <span><i class="fa fa-clock"></i> 6:00 PM</span>
            <span><i class="fa fa-map-marker"></i> Campus Hall</span>
        </div>
        <button class="btn btn-primary btn-rsvp" data-event-id="123" data-status="going">
            RSVP
        </button>
    </div>
</div>
```

### Connection Badge
```html
<span class="connection-badge connection-connected">
    <i class="fa fa-check"></i> Connected
</span>
<span class="connection-badge connection-pending">
    <i class="fa fa-clock"></i> Pending
</span>
```

### RSVP Status
```html
<span class="rsvp-status rsvp-going">Going</span>
<span class="rsvp-status rsvp-maybe">Maybe</span>
<span class="rsvp-status rsvp-declined">Can't Go</span>
```

### View Toggle
```html
<div class="view-toggle">
    <button class="view-toggle-btn active" data-view="grid">
        <i class="fa fa-th"></i> Grid
    </button>
    <button class="view-toggle-btn" data-view="list">
        <i class="fa fa-list"></i> List
    </button>
</div>
```

### Skill Tags
```html
<div class="skill-tag-input">
    <input type="text" id="skill-input" placeholder="Add skill...">
    <div id="skills-container">
        <span class="skill-tag">
            PHP
            <span class="skill-tag-remove" data-skill="PHP">&times;</span>
        </span>
    </div>
    <input type="hidden" id="skills-hidden" name="skills">
</div>
```

## 🔍 Troubleshooting

### CSS Not Loading
1. Clear XOOPS cache
2. Check file permissions (644)
3. Verify file path in browser
4. Check browser console for 404 errors

### JavaScript Not Working
1. Check browser console for errors
2. Verify XOOPS_URL is defined
3. Ensure no jQuery conflicts
4. Test with modern browser

### Images Not Showing
1. Verify file exists
2. Check file permissions (644)
3. Clear browser cache
4. Regenerate with PHP script

### AJAX Not Working
1. Check browser network tab
2. Verify ajax.php exists
3. Check CSRF token
4. Test with browser dev tools

## 📊 Status Indicators

### Connection Status
- **Connected** → Green badge
- **Pending** → Yellow badge
- **Available** → Blue badge

### RSVP Status
- **Going** → Green
- **Maybe** → Yellow
- **Declined** → Gray

### Event Status
- **Upcoming** → Green
- **Ongoing** → Blue
- **Past** → Gray
- **Cancelled** → Red

## 🌐 Browser Support

### ✅ Fully Supported
- Chrome 60+
- Firefox 60+
- Safari 12+
- Edge 79+

### ⚠️ Partial Support
- IE11 (CSS only, no JS)

### ❌ Not Supported
- IE10 and below

## 📦 File Sizes

| File | Size | Lines |
|------|------|-------|
| style.css | ~25KB | 880 |
| admin.css | ~10KB | 544 |
| alumni.js | ~18KB | 584 |
| Images | ~8KB | - |
| **Total** | **~61KB** | **2,008** |

## 🚀 Performance Tips

1. **Minify for Production**
   - Use CSS/JS minifiers
   - Reduce size by ~35%

2. **Optimize Images**
   - Convert to WebP format
   - Lazy load images

3. **Enable Caching**
   - Set appropriate cache headers
   - Use CDN if available

4. **Combine Files**
   - Merge multiple CSS files
   - Merge multiple JS files

## 📝 Maintenance Commands

```bash
# List all assets
find modules/alumni/assets -type f

# Count lines of code
wc -l modules/alumni/assets/css/*.css modules/alumni/assets/js/*.js

# Check file sizes
du -sh modules/alumni/assets/*

# Regenerate images
php modules/alumni/assets/images/generate_placeholders.php

# Clear cache
rm -rf xoops_data/caches/*/*
```

## 📚 Documentation

- **README.md** → Complete documentation
- **ASSETS_SUMMARY.md** → Overview and features
- **QUICK_REFERENCE.md** → This file
- **Inline Comments** → In all source files

---

**Version:** 1.0.0 | **Last Updated:** 2026-02-16 | **Status:** Production Ready
