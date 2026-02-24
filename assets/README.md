# Alumni Module - Assets Documentation

This directory contains all frontend assets for the Alumni module.

## Directory Structure

```
assets/
├── css/
│   ├── style.css         # Main frontend stylesheet (Bootstrap 5 compatible)
│   └── admin.css         # Admin panel stylesheet
├── js/
│   └── alumni.js         # Frontend JavaScript (vanilla JS, no jQuery)
├── images/
│   ├── default-avatar.png   # Default profile photo (400x400)
│   ├── default-event.png    # Default event banner (1200x400)
│   ├── logo.png             # Module logo (128x128)
│   ├── iconsmall.png        # Small icon (16x16)
│   ├── iconbig.png          # Large icon (32x32)
│   ├── generate_placeholders.php  # Script to regenerate images
│   └── icons/
│       ├── index16.png      # Dashboard icon (16x16)
│       ├── index32.png      # Dashboard icon (32x32)
│       ├── profiles16.png   # Profiles icon (16x16)
│       ├── profiles32.png   # Profiles icon (32x32)
│       ├── events16.png     # Events icon (16x16)
│       ├── events32.png     # Events icon (32x32)
│       ├── connections16.png # Connections icon (16x16)
│       ├── connections32.png # Connections icon (32x32)
│       ├── about16.png      # About icon (16x16)
│       └── about32.png      # About icon (32x32)
└── README.md             # This file
```

## CSS Files

### style.css (Main Frontend Stylesheet)

**Features:**
- Mobile-first responsive design
- Bootstrap 5 compatible
- CSS custom properties for theming
- Print styles included

**Components:**
- Profile cards (grid and list views)
- Event cards with date badges
- Directory views (grid/list toggle)
- Filter panels
- Dashboard widgets
- Mentorship cards
- Connection status badges
- RSVP status indicators
- Search forms
- Skill tags

**CSS Custom Properties:**
```css
--alumni-primary: #0d6efd
--alumni-secondary: #6c757d
--alumni-success: #28a745
--alumni-danger: #dc3545
--alumni-warning: #ffc107
--alumni-info: #17a2b8
--alumni-light: #f8f9fa
--alumni-dark: #333
```

### admin.css (Admin Panel Stylesheet)

**Features:**
- Protects XOOPS admin chrome from Bootstrap interference
- Dashboard statistics cards
- Admin tables and filters
- Form enhancements
- Activity timeline
- Chart containers

**Key Classes:**
- `.dashboard-stat-card` - Statistics card styling
- `.admin-filter-panel` - Filter form container
- `.table-actions` - Action buttons in tables
- `.status-badge` - Status indicator badges
- `.activity-timeline` - Activity log timeline

## JavaScript File

### alumni.js (Frontend JavaScript)

**Features:**
- Vanilla JavaScript (ES6+)
- No jQuery dependency
- Progressive enhancement
- Modern browser support (PHP 7.4+ target browsers)

**Functionality:**
1. **View Toggle** - Switch between grid/list views
2. **AJAX RSVP** - Event RSVP handling without page reload
3. **Connection Requests** - Send/accept/decline connections
4. **Filter Handlers** - Auto-submit filter forms
5. **Image Upload Preview** - Preview images before upload
6. **Skill Tag Management** - Add/remove skill tags dynamically
7. **Form Validation** - Client-side form validation
8. **Search Autocomplete** - Real-time search suggestions
9. **Smooth Scrolling** - Smooth scroll to anchor links
10. **Mobile Menu** - Mobile navigation toggle
11. **Auto-hide Alerts** - Automatically dismiss alerts after 5 seconds

**Browser Compatibility:**
- Chrome 60+
- Firefox 60+
- Safari 12+
- Edge 79+

## Images

### Main Images

**default-avatar.png (400x400)**
- Default profile photo placeholder
- Used when alumni hasn't uploaded a photo
- Gray background with "No Photo" text

**default-event.png (1200x400)**
- Default event banner placeholder
- Used when event doesn't have a custom banner
- Gray background with "Event Banner" text

**logo.png (128x128)**
- Module logo
- Blue background with "Alumni" text
- Used in module info and branding

**iconsmall.png (16x16)**
- Small module icon
- Used in XOOPS module list
- Solid blue square

**iconbig.png (32x32)**
- Large module icon
- Used in admin panel
- Solid blue square

### Admin Icons (icons/ directory)

All admin icons are available in two sizes (16x16 and 32x32):

- **index** - Dashboard icon (grid pattern)
- **profiles** - Profiles management icon (person silhouette)
- **events** - Events management icon (calendar)
- **connections** - Connections management icon (network nodes)
- **about** - About/info icon (info circle)

## Usage Examples

### Including CSS in Templates

```smarty
<{* Include main stylesheet *}>
<link rel="stylesheet" href="<{$xoops_url}>/modules/alumni/assets/css/style.css">

<{* Or use preload to inject into head *}>
<{* $GLOBALS['xoTheme']->addStylesheet() *}>
```

### Including JavaScript in Templates

```smarty
<{* Include at end of body *}>
<script src="<{$xoops_url}>/modules/alumni/assets/js/alumni.js"></script>

<{* Or use preload to inject *}>
<{* $GLOBALS['xoTheme']->addScript() *}>
```

### Using Default Images

```php
// In PHP
define('ALUMNI_DEFAULT_AVATAR', XOOPS_URL . '/modules/alumni/assets/images/default-avatar.png');
define('ALUMNI_DEFAULT_EVENT', XOOPS_URL . '/modules/alumni/assets/images/default-event.png');

// Usage
$profilePhoto = $alumni->getVar('photo') ?: ALUMNI_DEFAULT_AVATAR;
```

```smarty
<{* In Smarty template *}>
<img src="<{$profile.photo|default:$xoops_url}/modules/alumni/assets/images/default-avatar.png">
```

### JavaScript AJAX Example

```javascript
// RSVP to event
fetch(XOOPS_URL + '/modules/alumni/ajax.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'action=rsvp&event_id=123&status=going'
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        console.log('RSVP successful');
    }
});
```

## Regenerating Placeholder Images

If you need to regenerate the placeholder images (e.g., after modifying the script):

```bash
cd modules/alumni/assets/images
php generate_placeholders.php
```

The script will:
1. Create all main placeholder images
2. Create all admin icon sizes
3. Overwrite existing files

## Customization

### Changing Colors

Edit the CSS custom properties in `style.css`:

```css
:root {
    --alumni-primary: #your-color;
    --alumni-secondary: #your-color;
    /* ... etc */
}
```

### Adding New Icons

1. Edit `generate_placeholders.php`
2. Add new icon configuration to `$adminIcons` array
3. Add drawing logic in `createIcon()` function
4. Run the script to generate

### Modifying JavaScript Behavior

The JavaScript is modular - each feature is in its own function:
- Find the relevant `init*()` function
- Modify the behavior as needed
- All functions are documented with comments

## Performance Considerations

### CSS
- Uses modern CSS features (custom properties, flexbox, grid)
- Minimal specificity for easy overrides
- Mobile-first approach reduces redundant styles
- Print styles minimize printed content

### JavaScript
- Event delegation where appropriate
- Debounced search autocomplete (300ms)
- Efficient DOM queries (querySelector)
- No memory leaks (proper event cleanup)

### Images
- All images are optimized PNGs
- Minimal file sizes
- Appropriate dimensions for use case

## Browser Support

### CSS
- All modern browsers
- IE11 not supported (uses CSS custom properties)

### JavaScript
- ES6+ features used
- Polyfills NOT included (modern browsers only)
- Features used: fetch API, arrow functions, const/let, template literals

## Accessibility

### CSS
- Proper color contrast ratios
- Focus states for interactive elements
- Print styles for document printing

### JavaScript
- Progressive enhancement (works without JS)
- Keyboard navigation support
- ARIA labels where appropriate
- Focus management in modals/dropdowns

## File Sizes

| File | Size | Notes |
|------|------|-------|
| style.css | ~25KB | Unminified, includes all components |
| admin.css | ~10KB | Admin-specific styles |
| alumni.js | ~18KB | Unminified, vanilla JavaScript |
| default-avatar.png | ~1.6KB | 400x400, optimized PNG |
| default-event.png | ~2.6KB | 1200x400, optimized PNG |
| logo.png | ~516B | 128x128, small logo |
| icons/*.png | ~125-250B each | Small icons, highly optimized |

**Total assets size: ~58KB** (unminified)

## Future Enhancements

Potential improvements:
- [ ] Minified CSS/JS versions for production
- [ ] SVG versions of icons for scalability
- [ ] Dark mode theme support
- [ ] Additional color schemes
- [ ] Lazy loading for images
- [ ] Service worker for offline support
- [ ] WebP image format support

## License

These assets are part of the Alumni module for XOOPS 2.5.12 and follow the same license as the module.

## Credits

- CSS Framework: Bootstrap 5 compatible
- Icons: Font Awesome 5 (referenced, not included)
- JavaScript: Vanilla ES6+
- Images: Generated with PHP GD library

---

**Last Updated:** 2026-02-16
**Version:** 1.0.0
