# Language Constants Fixed - Alumni Module

## Summary of Missing Constants Added

This document lists all the language constants that were missing and have now been added to prevent "Undefined constant" errors that cause blank screens.

---

## 1. Admin Constants (admin.php)

**Added 2 constants:**

```php
define('_AM_ALUMNI_STAT_ACTIVE_CONNECTIONS', 'Active Connections');
define('_AM_ALUMNI_UNKNOWN', 'Unknown');
```

**Used in:**
- `admin/connections.php` (lines 66, 115-116)
- `admin/about.php` (line 74)
- `admin/index.php` (line 112)

---

## 2. Main/Frontend Constants (main.php)

**Added 53 constants in the following categories:**

### Error Messages (20 constants)
```php
define('_MD_ALUMNI_ERROR_INVALID_PROFILE', 'Invalid profile ID');
define('_MD_ALUMNI_ERROR_NO_PERMISSION', 'You do not have permission to perform this action');
define('_MD_ALUMNI_ERROR_SECURITY', 'Security token validation failed. Please try again.');
define('_MD_ALUMNI_ERROR_SAVE_FAILED', 'Failed to save changes. Please try again.');
define('_MD_ALUMNI_ERROR_DELETE_FAILED', 'Failed to delete item. Please try again.');
define('_MD_ALUMNI_ERROR_UPLOAD_FAILED', 'File upload failed. Please try again.');
define('_MD_ALUMNI_ERROR_INVALID_FILE_TYPE', 'Invalid file type. Please upload a valid file.');
define('_MD_ALUMNI_ERROR_INVALID_EVENT', 'Invalid event ID');
define('_MD_ALUMNI_ERROR_EVENT_NOT_AVAILABLE', 'This event is not available');
define('_MD_ALUMNI_ERROR_EVENT_FULL', 'This event is full. No more registrations accepted.');
define('_MD_ALUMNI_ERROR_REGISTRATION_CLOSED', 'Registration for this event has closed');
define('_MD_ALUMNI_ERROR_ALREADY_RSVP', 'You have already RSVP\'d to this event');
define('_MD_ALUMNI_ERROR_RSVP_NOT_FOUND', 'RSVP not found');
define('_MD_ALUMNI_ERROR_INVALID_USER', 'Invalid user ID');
define('_MD_ALUMNI_ERROR_INVALID_CONNECTION', 'Invalid connection ID');
define('_MD_ALUMNI_ERROR_CONNECTION_EXISTS', 'Connection request already exists');
define('_MD_ALUMNI_ERROR_CONNECTION_NOT_FOUND', 'Connection not found');
define('_MD_ALUMNI_ERROR_NETWORKING_DISABLED', 'This user has disabled networking');
define('_MD_ALUMNI_ERROR_INVALID_REQUEST', 'Invalid request');
define('_MD_ALUMNI_ERROR_INVALID_OPERATION', 'Invalid operation');
```

### Success Messages (4 constants)
```php
define('_MD_ALUMNI_PROFILE_SAVED', 'Profile saved successfully');
define('_MD_ALUMNI_RSVP_DELETED', 'RSVP cancelled successfully');
define('_MD_ALUMNI_CONNECTION_REQUEST_SENT', 'Connection request sent successfully');
define('_MD_ALUMNI_CONNECTION_REMOVED', 'Connection removed successfully');
```

### Event Types (9 constants)
```php
define('_MD_ALUMNI_EVENT_TYPE_REUNION', 'Reunion');
define('_MD_ALUMNI_EVENT_TYPE_NETWORKING', 'Networking');
define('_MD_ALUMNI_EVENT_TYPE_SEMINAR', 'Seminar');
define('_MD_ALUMNI_EVENT_TYPE_WORKSHOP', 'Workshop');
define('_MD_ALUMNI_EVENT_TYPE_CONFERENCE', 'Conference');
define('_MD_ALUMNI_EVENT_TYPE_SOCIAL', 'Social');
define('_MD_ALUMNI_EVENT_TYPE_WEBINAR', 'Webinar');
define('_MD_ALUMNI_EVENT_TYPE_FUNDRAISER', 'Fundraiser');
define('_MD_ALUMNI_EVENT_TYPE_OTHER', 'Other');
```

### Time Display (6 constants)
```php
define('_MD_ALUMNI_TIME_JUSTNOW', 'Just now');
define('_MD_ALUMNI_TIME_MINUTES_AGO', '%d minutes ago');
define('_MD_ALUMNI_TIME_HOURS_AGO', '%d hours ago');
define('_MD_ALUMNI_TIME_DAYS_AGO', '%d days ago');
define('_MD_ALUMNI_TIME_MONTHS_AGO', '%d months ago');
define('_MD_ALUMNI_TIME_YEARS_AGO', '%d years ago');
```

### Navigation & UI (4 constants)
```php
define('_MD_ALUMNI_BROWSE_DIRECTORY', 'Browse Directory');
define('_MD_ALUMNI_BROWSE_EVENTS', 'Browse Events');
define('_MD_ALUMNI_RESULTS_FOUND', '%d results found');
define('_MD_ALUMNI_PRIVACY_ALUMNI', 'Alumni Only');
```

### Mentorship (7 constants)
```php
define('_MD_ALUMNI_NO_PERMISSION_VIEW', 'You do not have permission to view this profile');
define('_MD_ALUMNI_MENTORSHIP_REQUEST_SENT', 'Mentorship request sent successfully');
define('_MD_ALUMNI_ERROR_MENTORSHIP_EXISTS', 'Mentorship request already exists');
define('_MD_ALUMNI_ERROR_MENTORSHIP_NOT_FOUND', 'Mentorship not found');
define('_MD_ALUMNI_MENTORSHIP_ACCEPTED', 'Mentorship request accepted');
define('_MD_ALUMNI_MENTORSHIP_DECLINED', 'Mentorship request declined');
define('_MD_ALUMNI_MENTORSHIP_COMPLETED_MSG', 'Mentorship marked as completed');
```

---

## 3. Module Info Constants (modinfo.php)

**Added 75 constants in the following categories:**

### Block Names and Descriptions (8 constants)
```php
define('_MI_ALUMNI_BLOCK_RECENT', 'Recent Alumni');
define('_MI_ALUMNI_BLOCK_RECENT_DESC', 'Display recently added alumni profiles');
define('_MI_ALUMNI_BLOCK_EVENTS', 'Upcoming Events');
define('_MI_ALUMNI_BLOCK_EVENTS_DESC', 'Display upcoming alumni events');
define('_MI_ALUMNI_BLOCK_SEARCH', 'Alumni Search');
define('_MI_ALUMNI_BLOCK_SEARCH_DESC', 'Quick search form for alumni directory');
define('_MI_ALUMNI_BLOCK_STATS', 'Alumni Statistics');
define('_MI_ALUMNI_BLOCK_STATS_DESC', 'Display alumni network statistics');
```

### Basic Config (4 constants)
```php
define('_MI_ALUMNI_NAME', 'Alumni');
define('_MI_ALUMNI_DESC', 'Alumni directory and networking system');
define('_MI_ALUMNI_CONFIG_PERPAGE', 'Items per page');
define('_MI_ALUMNI_CONFIG_PERPAGE_DESC', 'Number of items to display per page');
```

### Approval Settings (4 constants)
```php
define('_MI_ALUMNI_CONFIG_APPROVAL', 'Require admin approval');
define('_MI_ALUMNI_CONFIG_APPROVAL_DESC', 'Require admin approval for new profiles');
define('_MI_ALUMNI_CONFIG_AUTO_APPROVE', 'Auto-approve profiles');
define('_MI_ALUMNI_CONFIG_AUTO_APPROVE_DESC', 'Automatically approve new profile submissions');
```

### Feature Toggles (12 constants)
```php
define('_MI_ALUMNI_CONFIG_EVENTS', 'Enable events');
define('_MI_ALUMNI_CONFIG_EVENTS_DESC', 'Enable alumni events functionality');
define('_MI_ALUMNI_CONFIG_EVENTS_PERPAGE', 'Events per page');
define('_MI_ALUMNI_CONFIG_EVENTS_PERPAGE_DESC', 'Number of events to display per page');
define('_MI_ALUMNI_CONFIG_CONNECTIONS', 'Enable connections');
define('_MI_ALUMNI_CONFIG_CONNECTIONS_DESC', 'Allow alumni to connect with each other');
define('_MI_ALUMNI_CONFIG_MAX_CONNECTIONS', 'Maximum connections');
define('_MI_ALUMNI_CONFIG_MAX_CONNECTIONS_DESC', 'Maximum number of connections per user (0 = unlimited)');
define('_MI_ALUMNI_CONFIG_MENTORSHIP', 'Enable mentorship');
define('_MI_ALUMNI_CONFIG_MENTORSHIP_DESC', 'Enable alumni mentorship program');
define('_MI_ALUMNI_CONFIG_COMMENTS', 'Enable comments');
define('_MI_ALUMNI_CONFIG_COMMENTS_DESC', 'Allow comments on profiles and events');
```

### Photo Settings (8 constants)
```php
define('_MI_ALUMNI_CONFIG_PHOTO_MAXSIZE', 'Maximum photo size');
define('_MI_ALUMNI_CONFIG_PHOTO_MAXSIZE_DESC', 'Maximum file size for profile photos (bytes)');
define('_MI_ALUMNI_CONFIG_PHOTO_WIDTH', 'Photo display width');
define('_MI_ALUMNI_CONFIG_PHOTO_WIDTH_DESC', 'Width for profile photo display (pixels)');
define('_MI_ALUMNI_CONFIG_PHOTO_HEIGHT', 'Photo display height');
define('_MI_ALUMNI_CONFIG_PHOTO_HEIGHT_DESC', 'Height for profile photo display (pixels)');
define('_MI_ALUMNI_CONFIG_PHOTO_EXT', 'Allowed photo extensions');
define('_MI_ALUMNI_CONFIG_PHOTO_EXT_DESC', 'Comma-separated list of allowed file extensions');
```

### Event Image Settings (6 constants)
```php
define('_MI_ALUMNI_CONFIG_EVENT_IMAGE_MAXSIZE', 'Maximum event image size');
define('_MI_ALUMNI_CONFIG_EVENT_IMAGE_MAXSIZE_DESC', 'Maximum file size for event images (bytes)');
define('_MI_ALUMNI_CONFIG_EVENT_IMAGE_EXT', 'Allowed event image extensions');
define('_MI_ALUMNI_CONFIG_EVENT_IMAGE_EXT_DESC', 'Comma-separated list of allowed file extensions');
define('_MI_ALUMNI_CONFIG_MAX_ATTENDEES', 'Maximum event attendees');
define('_MI_ALUMNI_CONFIG_MAX_ATTENDEES_DESC', 'Default maximum attendees per event (0 = unlimited)');
```

### Privacy Settings (9 constants)
```php
define('_MI_ALUMNI_CONFIG_PRIVACY_PROFILE', 'Default profile privacy');
define('_MI_ALUMNI_CONFIG_PRIVACY_PROFILE_DESC', 'Default privacy level for new profiles');
define('_MI_ALUMNI_CONFIG_PRIVACY_EMAIL', 'Default email privacy');
define('_MI_ALUMNI_CONFIG_PRIVACY_EMAIL_DESC', 'Default privacy level for email addresses');
define('_MI_ALUMNI_CONFIG_PRIVACY_PHONE', 'Default phone privacy');
define('_MI_ALUMNI_CONFIG_PRIVACY_PHONE_DESC', 'Default privacy level for phone numbers');
define('_MI_ALUMNI_PRIVACY_PUBLIC', 'Public');
define('_MI_ALUMNI_PRIVACY_MEMBERS', 'Members Only');
define('_MI_ALUMNI_PRIVACY_PRIVATE', 'Private');
```

### Notification Settings (10 constants)
```php
define('_MI_ALUMNI_CONFIG_NOTIFY_PROFILE', 'Notify on new profile');
define('_MI_ALUMNI_CONFIG_NOTIFY_PROFILE_DESC', 'Send notification when new profile is submitted');
define('_MI_ALUMNI_CONFIG_NOTIFY_EVENT', 'Notify on new event');
define('_MI_ALUMNI_CONFIG_NOTIFY_EVENT_DESC', 'Notify users when new event is created');
define('_MI_ALUMNI_CONFIG_NOTIFY_CONNECTION', 'Notify on connection request');
define('_MI_ALUMNI_CONFIG_NOTIFY_CONNECTION_DESC', 'Notify user when connection request is received');
define('_MI_ALUMNI_CONFIG_NOTIFY_MENTORSHIP', 'Notify on mentorship request');
define('_MI_ALUMNI_CONFIG_NOTIFY_MENTORSHIP_DESC', 'Notify user when mentorship request is received');
define('_MI_ALUMNI_CONFIG_NOTIFY_RSVP', 'Notify on RSVP');
define('_MI_ALUMNI_CONFIG_NOTIFY_RSVP_DESC', 'Notify event organizer when someone RSVPs');
```

### Menu Items (9 constants)
```php
define('_MI_ALUMNI_MENU_HOME', 'Home');
define('_MI_ALUMNI_MENU_PROFILE', 'My Profile');
define('_MI_ALUMNI_MENU_EVENTS', 'Events');
define('_MI_ALUMNI_MENU_DASHBOARD', 'Dashboard');
define('_MI_ALUMNI_MENU_SEARCH', 'Search');
define('_MI_ALUMNI_SUBMENU_VIEWPROFILE', 'View Profile');
define('_MI_ALUMNI_SUBMENU_EDITPROFILE', 'Edit Profile');
define('_MI_ALUMNI_SUBMENU_CONNECTIONS', 'My Connections');
define('_MI_ALUMNI_SUBMENU_MENTORSHIP', 'Mentorship');
```

---

## 4. Block Constants (blocks.php)

**No additions needed** - The blocks.php file already had all required constants defined.

---

## Total Constants Added

- **admin.php**: 2 constants
- **main.php**: 53 constants
- **modinfo.php**: 75 constants
- **blocks.php**: 0 constants (already complete)

**TOTAL: 130 constants added**

---

## Files Affected

### Frontend Files Using These Constants:
- `profile.php`
- `event.php`
- `rsvp.php`
- `connect.php`
- `mentorship.php`
- `events.php`
- `search.php`
- `dashboard.php`
- `connections.php`

### Admin Files Using These Constants:
- `admin/connections.php`
- `admin/about.php`
- `admin/index.php`
- `admin/events.php`

### Core Files Using These Constants:
- `blocks.php`
- `xoops_version.php`
- `include/common.php`
- `class/Utility.php`

---

## Prevention Measures Implemented

1. **Updated MODULE_DEVELOPMENT_CHECKLIST.md** - Added critical section on language constants at the top of "Common Mistakes"
2. **Updated root CLAUDE.md** - Added language constant rules as the #1 and #2 items in "Common Pitfalls"
3. **Created MODULE_CLAUDE_TEMPLATE.md** - Template file with all critical rules for copying to new modules

## Rule to Follow

**When you write ANY language constant in code:**
1. STOP immediately
2. Open the appropriate language file
3. Add the `define()` statement RIGHT NOW
4. Then continue with your work

**Language constant prefixes:**
- Admin files → `_AM_MODULE_` → `language/english/admin.php`
- Frontend files → `_MD_MODULE_` → `language/english/main.php`
- Module info → `_MI_MODULE_` → `language/english/modinfo.php`
- Blocks → `_MB_MODULE_` → `language/english/blocks.php`

**One undefined constant = entire page goes blank with an error!**

---

Date: 2026-02-16
