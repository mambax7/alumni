<?php
/**
 * Alumni Network Management System
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package     alumni
 * @author      XOOPS Development Team
 * @version     1.0.0
 */

$moduleDirName      = basename(__DIR__);
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

$modversion = [
    'name'                => _MI_ALUMNI_NAME,
    'version'             => '1.0.0',
    'description'         => _MI_ALUMNI_DESC,
    'author'              => 'XOOPS Development Team',
    'author_mail'         => 'author@email.com',
    'author_website_url'  => 'https://xoops.org',
    'author_website_name' => 'XOOPS',
    'credits'             => 'The XOOPS Project',
    'license'             => 'GNU GPL 2.0 or later',
    'license_url'         => 'https://www.gnu.org/licenses/gpl-2.0.html',
    'help'                => 'page=help',
    'release_info'        => 'release_info',
    'release_file'        => XOOPS_URL . "/modules/{$moduleDirName}/docs/release_info.txt",
    'release_date'        => '2026/02/16',
    'manual'              => 'link to manual',
    'manual_file'         => XOOPS_URL . "/modules/{$moduleDirName}/docs/manual.txt",
    'min_php'             => '7.4.0',
    'min_xoops'           => '2.5.11',
    'min_admin'           => '1.2',
    'min_db'              => ['mysql' => '5.5'],
    'image'               => 'assets/images/logoModule.png',
    'iconsmall'           => 'assets/images/iconsmall.png',
    'iconbig'             => 'assets/images/iconbig.png',
    'dirname'             => $moduleDirName,
    'modicons16'          => 'assets/images/icons/16',
    'modicons32'          => 'assets/images/icons/32',
    'module_status'       => 'Beta 1',
    'system_menu'         => 1,
    'hasAdmin'            => 1,
    'hasMain'             => 1,
    'adminindex'          => 'admin/index.php',
    'adminmenu'           => 'admin/menu.php',
    'onInstall'           => 'include/install.php',
    'onUpdate'            => 'include/onupdate.php',
    'onUninstall'         => 'include/uninstall.php',
];

// Templates
$modversion['templates'] = [
    // Frontend templates
    ['file' => 'alumni_header.tpl', 'description' => 'Common header'],
    ['file' => 'alumni_footer.tpl', 'description' => 'Common footer'],
    ['file' => 'alumni_index.tpl', 'description' => 'Alumni directory index page'],
    ['file' => 'alumni_profile.tpl', 'description' => 'Alumni profile view page'],
    ['file' => 'alumni_profile_edit.tpl', 'description' => 'Alumni profile edit page'],
    ['file' => 'alumni_events.tpl', 'description' => 'Events listing page'],
    ['file' => 'alumni_event_detail.tpl', 'description' => 'Event detail page'],
    ['file' => 'alumni_search.tpl', 'description' => 'Alumni search page'],
    ['file' => 'alumni_dashboard.tpl', 'description' => 'User dashboard'],
    ['file' => 'alumni_connections.tpl', 'description' => 'Connections page'],
    ['file' => 'alumni_mentorship.tpl', 'description' => 'Mentorship page'],
    // Admin templates
    ['file' => 'admin/alumni_admin_dashboard.tpl', 'description' => 'Admin dashboard', 'type' => 'admin'],
    // Blocks
    ['file' => 'alumni_block_recent.tpl', 'description' => 'Recent alumni profiles block'],
    ['file' => 'alumni_block_events.tpl', 'description' => 'Upcoming events block'],
    ['file' => 'alumni_block_search.tpl', 'description' => 'Quick search block'],
    ['file' => 'alumni_block_stats.tpl', 'description' => 'Alumni statistics block'],
];

// Database tables
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'] = [
    'alumni_profiles',
    'alumni_categories',
    'alumni_events',
    'alumni_rsvps',
    'alumni_connections',
    'alumni_mentorship',
    'alumni_skills',
    'alumni_profile_skill_link',
    'alumni_comments',
];



// Main menu
//$modversion['hasMain'] = 1;

// Sub-menu
$modversion['sub'] = [
//    [
//        'name' => _MI_ALUMNI_MENU_HOME,
//        'url'  => 'index.php',
//    ],
    [
        'name' => _MI_ALUMNI_MENU_EVENTS,
        'url'  => 'events.php',
    ],
    [
        'name' => _MI_ALUMNI_MENU_SEARCH,
        'url'  => 'search.php',
    ],
    [
        'name' => _MI_ALUMNI_MENU_DASHBOARD,
        'url'  => 'dashboard.php',
    ],
    [
        'name' => _MI_ALUMNI_MENU_PROFILE,
        'url'  => 'profile.php',
        'sub'  => [
            [
                'name' => _MI_ALUMNI_SUBMENU_VIEWPROFILE,
                'url'  => 'profile.php?action=view',
            ],
            [
                'name' => _MI_ALUMNI_SUBMENU_EDITPROFILE,
                'url'  => 'profile.php?action=edit',
            ],
            [
                'name' => _MI_ALUMNI_SUBMENU_CONNECTIONS,
                'url'  => 'connections.php',
            ],
            [
                'name' => _MI_ALUMNI_SUBMENU_MENTORSHIP,
                'url'  => 'mentorship.php',
            ],
        ],
    ],
];

// Blocks
$modversion['blocks'] = [
    [
        'file'        => 'blocks.php',
        'name'        => _MI_ALUMNI_BLOCK_RECENT,
        'description' => _MI_ALUMNI_BLOCK_RECENT_DESC,
        'show_func'   => 'alumni_block_recent_show',
        'edit_func'   => 'alumni_block_recent_edit',
        'options'     => '10|created',
        'template'    => 'alumni_block_recent.tpl',
    ],
    [
        'file'        => 'blocks.php',
        'name'        => _MI_ALUMNI_BLOCK_EVENTS,
        'description' => _MI_ALUMNI_BLOCK_EVENTS_DESC,
        'show_func'   => 'alumni_block_events_show',
        'edit_func'   => 'alumni_block_events_edit',
        'options'     => '5',
        'template'    => 'alumni_block_events.tpl',
    ],
    [
        'file'        => 'blocks.php',
        'name'        => _MI_ALUMNI_BLOCK_SEARCH,
        'description' => _MI_ALUMNI_BLOCK_SEARCH_DESC,
        'show_func'   => 'alumni_block_search_show',
        'template'    => 'alumni_block_search.tpl',
    ],
    [
        'file'        => 'blocks.php',
        'name'        => _MI_ALUMNI_BLOCK_STATS,
        'description' => _MI_ALUMNI_BLOCK_STATS_DESC,
        'show_func'   => 'alumni_block_stats_show',
        'template'    => 'alumni_block_stats.tpl',
    ],
];

// Config options
$modversion['config'] = [
    // Pagination settings
    [
        'name'        => 'per_page',
        'title'       => '_MI_ALUMNI_CONFIG_PERPAGE',
        'description' => '_MI_ALUMNI_CONFIG_PERPAGE_DESC',
        'formtype'    => 'textbox',
        'valuetype'   => 'int',
        'default'     => 20,
    ],
    [
        'name'        => 'events_per_page',
        'title'       => '_MI_ALUMNI_CONFIG_EVENTS_PERPAGE',
        'description' => '_MI_ALUMNI_CONFIG_EVENTS_PERPAGE_DESC',
        'formtype'    => 'textbox',
        'valuetype'   => 'int',
        'default'     => 10,
    ],

    // Approval workflow
    [
        'name'        => 'enable_approval',
        'title'       => '_MI_ALUMNI_CONFIG_APPROVAL',
        'description' => '_MI_ALUMNI_CONFIG_APPROVAL_DESC',
        'formtype'    => 'yesno',
        'valuetype'   => 'int',
        'default'     => 1,
    ],
    [
        'name'        => 'auto_approve_members',
        'title'       => '_MI_ALUMNI_CONFIG_AUTO_APPROVE',
        'description' => '_MI_ALUMNI_CONFIG_AUTO_APPROVE_DESC',
        'formtype'    => 'yesno',
        'valuetype'   => 'int',
        'default'     => 0,
    ],

    // Photo settings
    [
        'name'        => 'photo_max_size',
        'title'       => '_MI_ALUMNI_CONFIG_PHOTO_MAXSIZE',
        'description' => '_MI_ALUMNI_CONFIG_PHOTO_MAXSIZE_DESC',
        'formtype'    => 'textbox',
        'valuetype'   => 'int',
        'default'     => 2097152, // 2 MB
    ],
    [
        'name'        => 'photo_allowed_ext',
        'title'       => '_MI_ALUMNI_CONFIG_PHOTO_EXT',
        'description' => '_MI_ALUMNI_CONFIG_PHOTO_EXT_DESC',
        'formtype'    => 'textbox',
        'valuetype'   => 'text',
        'default'     => 'jpg,jpeg,png,gif',
    ],
    [
        'name'        => 'photo_max_width',
        'title'       => '_MI_ALUMNI_CONFIG_PHOTO_WIDTH',
        'description' => '_MI_ALUMNI_CONFIG_PHOTO_WIDTH_DESC',
        'formtype'    => 'textbox',
        'valuetype'   => 'int',
        'default'     => 800,
    ],
    [
        'name'        => 'photo_max_height',
        'title'       => '_MI_ALUMNI_CONFIG_PHOTO_HEIGHT',
        'description' => '_MI_ALUMNI_CONFIG_PHOTO_HEIGHT_DESC',
        'formtype'    => 'textbox',
        'valuetype'   => 'int',
        'default'     => 800,
    ],

    // Event image settings
    [
        'name'        => 'event_image_max_size',
        'title'       => '_MI_ALUMNI_CONFIG_EVENT_IMAGE_MAXSIZE',
        'description' => '_MI_ALUMNI_CONFIG_EVENT_IMAGE_MAXSIZE_DESC',
        'formtype'    => 'textbox',
        'valuetype'   => 'int',
        'default'     => 3145728, // 3 MB
    ],
    [
        'name'        => 'event_image_allowed_ext',
        'title'       => '_MI_ALUMNI_CONFIG_EVENT_IMAGE_EXT',
        'description' => '_MI_ALUMNI_CONFIG_EVENT_IMAGE_EXT_DESC',
        'formtype'    => 'textbox',
        'valuetype'   => 'text',
        'default'     => 'jpg,jpeg,png,gif',
    ],

    // Feature toggles
    [
        'name'        => 'enable_mentorship',
        'title'       => '_MI_ALUMNI_CONFIG_MENTORSHIP',
        'description' => '_MI_ALUMNI_CONFIG_MENTORSHIP_DESC',
        'formtype'    => 'yesno',
        'valuetype'   => 'int',
        'default'     => 1,
    ],
    [
        'name'        => 'enable_connections',
        'title'       => '_MI_ALUMNI_CONFIG_CONNECTIONS',
        'description' => '_MI_ALUMNI_CONFIG_CONNECTIONS_DESC',
        'formtype'    => 'yesno',
        'valuetype'   => 'int',
        'default'     => 1,
    ],
    [
        'name'        => 'enable_events',
        'title'       => '_MI_ALUMNI_CONFIG_EVENTS',
        'description' => '_MI_ALUMNI_CONFIG_EVENTS_DESC',
        'formtype'    => 'yesno',
        'valuetype'   => 'int',
        'default'     => 1,
    ],
    [
        'name'        => 'enable_comments',
        'title'       => '_MI_ALUMNI_CONFIG_COMMENTS',
        'description' => '_MI_ALUMNI_CONFIG_COMMENTS_DESC',
        'formtype'    => 'yesno',
        'valuetype'   => 'int',
        'default'     => 1,
    ],

    // Privacy defaults
    [
        'name'        => 'default_privacy_email',
        'title'       => '_MI_ALUMNI_CONFIG_PRIVACY_EMAIL',
        'description' => '_MI_ALUMNI_CONFIG_PRIVACY_EMAIL_DESC',
        'formtype'    => 'select',
        'valuetype'   => 'text',
        'default'     => 'members',
        'options'     => [
            '_MI_ALUMNI_PRIVACY_PUBLIC'  => 'public',
            '_MI_ALUMNI_PRIVACY_MEMBERS' => 'members',
            '_MI_ALUMNI_PRIVACY_PRIVATE' => 'private',
        ],
    ],
    [
        'name'        => 'default_privacy_phone',
        'title'       => '_MI_ALUMNI_CONFIG_PRIVACY_PHONE',
        'description' => '_MI_ALUMNI_CONFIG_PRIVACY_PHONE_DESC',
        'formtype'    => 'select',
        'valuetype'   => 'text',
        'default'     => 'private',
        'options'     => [
            '_MI_ALUMNI_PRIVACY_PUBLIC'  => 'public',
            '_MI_ALUMNI_PRIVACY_MEMBERS' => 'members',
            '_MI_ALUMNI_PRIVACY_PRIVATE' => 'private',
        ],
    ],
    [
        'name'        => 'default_privacy_profile',
        'title'       => '_MI_ALUMNI_CONFIG_PRIVACY_PROFILE',
        'description' => '_MI_ALUMNI_CONFIG_PRIVACY_PROFILE_DESC',
        'formtype'    => 'select',
        'valuetype'   => 'text',
        'default'     => 'members',
        'options'     => [
            '_MI_ALUMNI_PRIVACY_PUBLIC'  => 'public',
            '_MI_ALUMNI_PRIVACY_MEMBERS' => 'members',
            '_MI_ALUMNI_PRIVACY_PRIVATE' => 'private',
        ],
    ],

    // Limits
    [
        'name'        => 'max_connections',
        'title'       => '_MI_ALUMNI_CONFIG_MAX_CONNECTIONS',
        'description' => '_MI_ALUMNI_CONFIG_MAX_CONNECTIONS_DESC',
        'formtype'    => 'textbox',
        'valuetype'   => 'int',
        'default'     => 500,
    ],
    [
        'name'        => 'max_event_attendees',
        'title'       => '_MI_ALUMNI_CONFIG_MAX_ATTENDEES',
        'description' => '_MI_ALUMNI_CONFIG_MAX_ATTENDEES_DESC',
        'formtype'    => 'textbox',
        'valuetype'   => 'int',
        'default'     => 0, // 0 = unlimited
    ],

    // Notification settings
    [
        'name'        => 'notify_admin_new_profile',
        'title'       => '_MI_ALUMNI_CONFIG_NOTIFY_PROFILE',
        'description' => '_MI_ALUMNI_CONFIG_NOTIFY_PROFILE_DESC',
        'formtype'    => 'yesno',
        'valuetype'   => 'int',
        'default'     => 1,
    ],
    [
        'name'        => 'notify_admin_new_event',
        'title'       => '_MI_ALUMNI_CONFIG_NOTIFY_EVENT',
        'description' => '_MI_ALUMNI_CONFIG_NOTIFY_EVENT_DESC',
        'formtype'    => 'yesno',
        'valuetype'   => 'int',
        'default'     => 1,
    ],
    [
        'name'        => 'notify_connection_request',
        'title'       => '_MI_ALUMNI_CONFIG_NOTIFY_CONNECTION',
        'description' => '_MI_ALUMNI_CONFIG_NOTIFY_CONNECTION_DESC',
        'formtype'    => 'yesno',
        'valuetype'   => 'int',
        'default'     => 1,
    ],
    [
        'name'        => 'notify_mentorship_request',
        'title'       => '_MI_ALUMNI_CONFIG_NOTIFY_MENTORSHIP',
        'description' => '_MI_ALUMNI_CONFIG_NOTIFY_MENTORSHIP_DESC',
        'formtype'    => 'yesno',
        'valuetype'   => 'int',
        'default'     => 1,
    ],
    [
        'name'        => 'notify_event_rsvp',
        'title'       => '_MI_ALUMNI_CONFIG_NOTIFY_RSVP',
        'description' => '_MI_ALUMNI_CONFIG_NOTIFY_RSVP_DESC',
        'formtype'    => 'yesno',
        'valuetype'   => 'int',
        'default'     => 1,
    ],
    // ---- Test data / sample data buttons ------------------------------------
    [
        'name'        => 'displaySampleButton',
        'title'       => '_MI_ALUMNI_SHOW_SAMPLE_BUTTON',
        'description' => '_MI_ALUMNI_SHOW_SAMPLE_BUTTON_DESC',
        'formtype'    => 'yesno',
        'valuetype'   => 'int',
        'default'     => 1,
    ],
    [
        'name'        => 'displayDeveloperTools',
        'title'       => '_MI_ALUMNI_SHOW_DEV_TOOLS',
        'description' => '_MI_ALUMNI_SHOW_DEV_TOOLS_DESC',
        'formtype'    => 'yesno',
        'valuetype'   => 'int',
        'default'     => 0,
    ],
];

// Search
$modversion['hasSearch']      = 1;
$modversion['search']['file'] = 'include/search.inc.php';
$modversion['search']['func'] = 'alumni_search';

// Comments
$modversion['hasComments']          = 1;
$modversion['comments']['pageName'] = 'profile.php';
$modversion['comments']['itemName'] = 'profile_id';

return $modversion;
