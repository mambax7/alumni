<?php
/**
 * Alumni Module
 *
 * @package   Alumni
 * @copyright 2025 XOOPS Alumni Module
 * @license   GPL 2.0 or later
 * @author    Alumni Module Development Team
 */

// Module Info
define('_MI_ALUMNI_NAME', 'Alumni');
define('_MI_ALUMNI_DESC', 'Complete alumni directory and networking system with events, mentorship, and community features');

// Menu Items
define('_MI_ALUMNI_MENU_INDEX', 'Browse Alumni');
define('_MI_ALUMNI_MENU_PROFILE', 'My Profile');
define('_MI_ALUMNI_MENU_EVENTS', 'Events');
define('_MI_ALUMNI_MENU_DIRECTORY', 'Directory');
define('_MI_ALUMNI_MENU_DASHBOARD', 'Dashboard');
define('_MI_ALUMNI_MENU_HOME', 'Home');


// Sub-menu Items
define('_MI_ALUMNI_SMENU_GRADUATES', 'Recent Graduates');
define('_MI_ALUMNI_MENU_SEARCH', 'Search');
define('_MI_ALUMNI_SMENU_MENTORSHIP', 'Mentorship Program');
define('_MI_ALUMNI_SMENU_CONNECTIONS', 'My Connections');
define('_MI_ALUMNI_SUBMENU_VIEWPROFILE', 'View Profile');
define('_MI_ALUMNI_SUBMENU_EDITPROFILE', 'Edit Profile');
define('_MI_ALUMNI_SUBMENU_CONNECTIONS', 'Connections');
define('_MI_ALUMNI_SUBMENU_MENTORSHIP', 'Mentorship');

// Block Names
define('_MI_ALUMNI_BLOCK_RECENT', 'Recent Alumni');
define('_MI_ALUMNI_BLOCK_RECENT_DESC', 'Display recently added alumni profiles');
define('_MI_ALUMNI_BLOCK_FEATURED', 'Featured Alumni');
define('_MI_ALUMNI_BLOCK_FEATURED_DESC', 'Display featured alumni profiles');
define('_MI_ALUMNI_BLOCK_EVENTS', 'Upcoming Events');
define('_MI_ALUMNI_BLOCK_EVENTS_DESC', 'Display upcoming alumni events');
define('_MI_ALUMNI_BLOCK_SEARCH', 'Quick Search');
define('_MI_ALUMNI_BLOCK_SEARCH_DESC', 'Quick alumni search form');
define('_MI_ALUMNI_BLOCK_STATS', 'Alumni Statistics');
define('_MI_ALUMNI_BLOCK_STATS_DESC', 'Display alumni network statistics');

// Admin Menu
define('_MI_ALUMNI_ADMENU_INDEX', 'Dashboard');
define('_MI_ALUMNI_ADMENU_DASHBOARD', 'Dashboard');
define('_MI_ALUMNI_ADMENU_PROFILES', 'Manage Profiles');
define('_MI_ALUMNI_ADMENU_EVENTS', 'Manage Events');
define('_MI_ALUMNI_ADMENU_CATEGORIES', 'Manage Categories');
define('_MI_ALUMNI_ADMENU_RSVPS', 'Event RSVPs');
define('_MI_ALUMNI_ADMENU_MENTORSHIP', 'Mentorships');
define('_MI_ALUMNI_ADMENU_MENTORSHIPS', 'Mentorships');
define('_MI_ALUMNI_ADMENU_CONNECTIONS', 'Connections');
define('_MI_ALUMNI_ADMENU_SKILLS', 'Skills');
define('_MI_ALUMNI_ADMENU_ABOUT', 'About');

// Configuration Options
define('_MI_ALUMNI_CONF_PER_PAGE', 'Items per page');
define('_MI_ALUMNI_CONF_PER_PAGE_DESC', 'Number of items to display per page in listings');

define('_MI_ALUMNI_CONF_DATE_FORMAT', 'Date format');
define('_MI_ALUMNI_CONF_DATE_FORMAT_DESC', 'Date format for display (PHP date() format)');

define('_MI_ALUMNI_CONF_TIME_FORMAT', 'Time format');
define('_MI_ALUMNI_CONF_TIME_FORMAT_DESC', 'Time format for display (PHP date() format)');

define('_MI_ALUMNI_CONF_ALLOW_REGISTRATION', 'Allow user registration');
define('_MI_ALUMNI_CONF_ALLOW_REGISTRATION_DESC', 'Allow users to register themselves in the alumni directory');

define('_MI_ALUMNI_CONF_APPROVAL_REQUIRED', 'Admin approval required');
define('_MI_ALUMNI_CONF_APPROVAL_REQUIRED_DESC', 'Require admin approval for new alumni profiles');

define('_MI_ALUMNI_CONF_PHOTO_MAX_SIZE', 'Max photo size (bytes)');
define('_MI_ALUMNI_CONF_PHOTO_MAX_SIZE_DESC', 'Maximum allowed photo file size in bytes (default: 2097152 = 2MB)');

define('_MI_ALUMNI_CONF_PHOTO_WIDTH', 'Photo display width');
define('_MI_ALUMNI_CONF_PHOTO_WIDTH_DESC', 'Width in pixels for profile photo display');

define('_MI_ALUMNI_CONF_PHOTO_HEIGHT', 'Photo display height');
define('_MI_ALUMNI_CONF_PHOTO_HEIGHT_DESC', 'Height in pixels for profile photo display');

define('_MI_ALUMNI_CONF_ENABLE_EVENTS', 'Enable events');
define('_MI_ALUMNI_CONF_ENABLE_EVENTS_DESC', 'Enable alumni events functionality');

define('_MI_ALUMNI_CONF_ENABLE_MENTORSHIP', 'Enable mentorship');
define('_MI_ALUMNI_CONF_ENABLE_MENTORSHIP_DESC', 'Enable alumni mentorship program');

define('_MI_ALUMNI_CONF_ENABLE_CONNECTIONS', 'Enable connections');
define('_MI_ALUMNI_CONF_ENABLE_CONNECTIONS_DESC', 'Allow alumni to connect with each other');

define('_MI_ALUMNI_CONF_PRIVACY_DEFAULT', 'Default privacy level');
define('_MI_ALUMNI_CONF_PRIVACY_DEFAULT_DESC', 'Default privacy level for new profiles');

define('_MI_ALUMNI_CONF_PRIVACY_PUBLIC', 'Public');
define('_MI_ALUMNI_CONF_PRIVACY_MEMBERS', 'Members Only');
define('_MI_ALUMNI_CONF_PRIVACY_CONNECTIONS', 'Connections Only');
define('_MI_ALUMNI_CONF_PRIVACY_PRIVATE', 'Private');

define('_MI_ALUMNI_CONF_ENABLE_LINKEDIN', 'Enable LinkedIn integration');
define('_MI_ALUMNI_CONF_ENABLE_LINKEDIN_DESC', 'Allow users to link their LinkedIn profiles');

define('_MI_ALUMNI_CONF_ENABLE_TWITTER', 'Enable Twitter integration');
define('_MI_ALUMNI_CONF_ENABLE_TWITTER_DESC', 'Allow users to link their Twitter profiles');

define('_MI_ALUMNI_CONF_ENABLE_FACEBOOK', 'Enable Facebook integration');
define('_MI_ALUMNI_CONF_ENABLE_FACEBOOK_DESC', 'Allow users to link their Facebook profiles');

define('_MI_ALUMNI_CONF_EVENT_RSVP_DEADLINE', 'RSVP deadline (days)');
define('_MI_ALUMNI_CONF_EVENT_RSVP_DEADLINE_DESC', 'Number of days before event when RSVP closes');

define('_MI_ALUMNI_CONF_NOTIFY_NEW_PROFILE', 'Notify on new profile');
define('_MI_ALUMNI_CONF_NOTIFY_NEW_PROFILE_DESC', 'Send notification to admins when new profile is submitted');

define('_MI_ALUMNI_CONF_NOTIFY_NEW_EVENT', 'Notify on new event');
define('_MI_ALUMNI_CONF_NOTIFY_NEW_EVENT_DESC', 'Send notification to alumni when new event is created');

define('_MI_ALUMNI_CONF_NOTIFY_CONNECTION', 'Notify on connection request');
define('_MI_ALUMNI_CONF_NOTIFY_CONNECTION_DESC', 'Send notification when someone requests a connection');

define('_MI_ALUMNI_CONF_ENABLE_SEARCH', 'Enable advanced search');
define('_MI_ALUMNI_CONF_ENABLE_SEARCH_DESC', 'Enable advanced alumni search functionality');

define('_MI_ALUMNI_CONF_SEARCH_MIN_CHARS', 'Search minimum characters');
define('_MI_ALUMNI_CONF_SEARCH_MIN_CHARS_DESC', 'Minimum number of characters required for search');

// Templates
define('_MI_ALUMNI_TEMPLATE_INDEX', 'Alumni Index');
define('_MI_ALUMNI_TEMPLATE_PROFILE', 'Alumni Profile');
define('_MI_ALUMNI_TEMPLATE_DIRECTORY', 'Alumni Directory');
define('_MI_ALUMNI_TEMPLATE_EVENTS', 'Events Listing');
define('_MI_ALUMNI_TEMPLATE_EVENT_DETAIL', 'Event Detail');
define('_MI_ALUMNI_TEMPLATE_DASHBOARD', 'User Dashboard');
define('_MI_ALUMNI_TEMPLATE_SEARCH', 'Search Results');
define('_MI_ALUMNI_TEMPLATE_CONNECTIONS', 'Connections');
define('_MI_ALUMNI_TEMPLATE_MENTORSHIP', 'Mentorship');

// ---- Config pref titles & descriptions (used by xoops_version.php $modversion['config']) ----

// Pagination
define('_MI_ALUMNI_CONFIG_PERPAGE',               'Profiles per page');
define('_MI_ALUMNI_CONFIG_PERPAGE_DESC',          'Number of alumni profiles to display per page in directory listings.');
define('_MI_ALUMNI_CONFIG_EVENTS_PERPAGE',        'Events per page');
define('_MI_ALUMNI_CONFIG_EVENTS_PERPAGE_DESC',   'Number of events to display per page in the events listing.');

// Approval workflow
define('_MI_ALUMNI_CONFIG_APPROVAL',              'Enable approval workflow');
define('_MI_ALUMNI_CONFIG_APPROVAL_DESC',         'When enabled, new alumni profiles must be approved by an administrator before appearing publicly.');
define('_MI_ALUMNI_CONFIG_AUTO_APPROVE',          'Auto-approve registered members');
define('_MI_ALUMNI_CONFIG_AUTO_APPROVE_DESC',     'Automatically approve alumni profiles submitted by registered XOOPS members.');

// Photo settings
define('_MI_ALUMNI_CONFIG_PHOTO_MAXSIZE',         'Max profile photo size (bytes)');
define('_MI_ALUMNI_CONFIG_PHOTO_MAXSIZE_DESC',    'Maximum file size for profile photos in bytes. Default: 2097152 (2 MB).');
define('_MI_ALUMNI_CONFIG_PHOTO_EXT',             'Allowed photo file types');
define('_MI_ALUMNI_CONFIG_PHOTO_EXT_DESC',        'Comma-separated list of allowed photo extensions (e.g. jpg,jpeg,png,gif).');
define('_MI_ALUMNI_CONFIG_PHOTO_WIDTH',           'Max profile photo width (px)');
define('_MI_ALUMNI_CONFIG_PHOTO_WIDTH_DESC',      'Maximum width in pixels for uploaded profile photos.');
define('_MI_ALUMNI_CONFIG_PHOTO_HEIGHT',          'Max profile photo height (px)');
define('_MI_ALUMNI_CONFIG_PHOTO_HEIGHT_DESC',     'Maximum height in pixels for uploaded profile photos.');

// Event image settings
define('_MI_ALUMNI_CONFIG_EVENT_IMAGE_MAXSIZE',     'Max event image size (bytes)');
define('_MI_ALUMNI_CONFIG_EVENT_IMAGE_MAXSIZE_DESC','Maximum file size for event images in bytes. Default: 3145728 (3 MB).');
define('_MI_ALUMNI_CONFIG_EVENT_IMAGE_EXT',         'Allowed event image file types');
define('_MI_ALUMNI_CONFIG_EVENT_IMAGE_EXT_DESC',    'Comma-separated list of allowed event image extensions (e.g. jpg,jpeg,png,gif).');

// Feature toggles
define('_MI_ALUMNI_CONFIG_MENTORSHIP',            'Enable mentorship programme');
define('_MI_ALUMNI_CONFIG_MENTORSHIP_DESC',       'Allow alumni to sign up as mentors and accept mentorship requests.');
define('_MI_ALUMNI_CONFIG_CONNECTIONS',           'Enable connections');
define('_MI_ALUMNI_CONFIG_CONNECTIONS_DESC',      'Allow alumni to send and accept connection requests to build a network.');
define('_MI_ALUMNI_CONFIG_EVENTS',                'Enable events');
define('_MI_ALUMNI_CONFIG_EVENTS_DESC',           'Enable the alumni events system including RSVP functionality.');
define('_MI_ALUMNI_CONFIG_COMMENTS',              'Enable profile comments');
define('_MI_ALUMNI_CONFIG_COMMENTS_DESC',         'Allow logged-in alumni to post comments on each other\'s profiles.');

// Privacy defaults — select option labels used in xoops_version.php 'options' arrays
define('_MI_ALUMNI_PRIVACY_PUBLIC',               'Public');
define('_MI_ALUMNI_PRIVACY_MEMBERS',              'Alumni Only');
define('_MI_ALUMNI_PRIVACY_PRIVATE',              'Private');

// Privacy pref titles & descriptions
define('_MI_ALUMNI_CONFIG_PRIVACY_EMAIL',         'Default email privacy');
define('_MI_ALUMNI_CONFIG_PRIVACY_EMAIL_DESC',    'Default visibility for the email address field on new profiles.');
define('_MI_ALUMNI_CONFIG_PRIVACY_PHONE',         'Default phone privacy');
define('_MI_ALUMNI_CONFIG_PRIVACY_PHONE_DESC',    'Default visibility for the phone number field on new profiles.');
define('_MI_ALUMNI_CONFIG_PRIVACY_PROFILE',       'Default profile privacy');
define('_MI_ALUMNI_CONFIG_PRIVACY_PROFILE_DESC',  'Default overall profile visibility for new profiles.');

// Limits
define('_MI_ALUMNI_CONFIG_MAX_CONNECTIONS',       'Maximum connections per user');
define('_MI_ALUMNI_CONFIG_MAX_CONNECTIONS_DESC',  'Maximum number of peer connections a single alumni account may have. Set 0 for unlimited.');
define('_MI_ALUMNI_CONFIG_MAX_ATTENDEES',         'Maximum event attendees');
define('_MI_ALUMNI_CONFIG_MAX_ATTENDEES_DESC',    'Default maximum number of RSVP attendees per event. Set 0 for unlimited.');

// Notification settings
define('_MI_ALUMNI_CONFIG_NOTIFY_PROFILE',          'Notify admin: new profile');
define('_MI_ALUMNI_CONFIG_NOTIFY_PROFILE_DESC',     'Send an email to administrators whenever a new alumni profile is submitted.');
define('_MI_ALUMNI_CONFIG_NOTIFY_EVENT',            'Notify admin: new event');
define('_MI_ALUMNI_CONFIG_NOTIFY_EVENT_DESC',       'Send an email to administrators whenever a new event is created.');
define('_MI_ALUMNI_CONFIG_NOTIFY_CONNECTION',       'Notify user: connection request');
define('_MI_ALUMNI_CONFIG_NOTIFY_CONNECTION_DESC',  'Send an email notification when a user receives a new connection request.');
define('_MI_ALUMNI_CONFIG_NOTIFY_MENTORSHIP',       'Notify user: mentorship request');
define('_MI_ALUMNI_CONFIG_NOTIFY_MENTORSHIP_DESC',  'Send an email notification when a mentor receives a new mentorship request.');
define('_MI_ALUMNI_CONFIG_NOTIFY_RSVP',             'Notify organiser: event RSVP');
define('_MI_ALUMNI_CONFIG_NOTIFY_RSVP_DESC',        'Send an email to the event organiser whenever an attendee submits or changes their RSVP.');

// ---- Clone module --------------------------------------------------------
define('_MI_ALUMNI_MENU_CLONE', 'Clone Module');

// ---- Test data / sample data buttons ------------------------------------
define('_MI_ALUMNI_SHOW_SAMPLE_BUTTON',      'Show sample data buttons?');
define('_MI_ALUMNI_SHOW_SAMPLE_BUTTON_DESC', 'If Yes (1), Import / Export / Clear / Hide buttons appear on admin pages. Set to No (0) to hide all buttons permanently.');
define('_MI_ALUMNI_SHOW_DEV_TOOLS',          'Show developer tools?');
define('_MI_ALUMNI_SHOW_DEV_TOOLS_DESC',     'If Yes, additional developer/diagnostic tools are shown in the admin panel.');
