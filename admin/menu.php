<?php

declare(strict_types=1);

/**
 * Alumni Module — Admin Menu.
 *
 * @copyright XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 */

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

use Xmf\Module\Admin;
use XoopsModules\Alumni\Helper;

require dirname(__DIR__) . '/preloads/autoloader.php';

$helper = Helper::getInstance();
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');

$pathIcon32 = Admin::menuIconPath('');
$pathModIcon32 = $helper->url('assets/images/icons/32/');

$adminmenu = [];

$adminmenu[] = [
    'title' => _AM_ALUMNI_DASHBOARD,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png',
];

$adminmenu[] = [
    'title' => _AM_ALUMNI_PROFILES,
    'link'  => 'admin/profiles.php',
    'icon'  => $pathIcon32 . '/user-icon.png',
];

$adminmenu[] = [
    'title' => _AM_ALUMNI_EVENTS,
    'link'  => 'admin/events.php',
    'icon'  => $pathIcon32 . '/calendar.png',
];

$adminmenu[] = [
    'title' => _AM_ALUMNI_CATEGORIES,
    'link'  => 'admin/categories.php',
    'icon'  => $pathIcon32 . '/category.png',
];

$adminmenu[] = [
    'title' => _AM_ALUMNI_RSVPS,
    'link'  => 'admin/rsvps.php',
    'icon'  => $pathIcon32 . '/slideshow.png',
];

$adminmenu[] = [
    'title' => _AM_ALUMNI_CONNECTIONS,
    'link'  => 'admin/connections.php',
    'icon'  => $pathIcon32 . '/manage.png',
];

$adminmenu[] = [
    'title' => _AM_ALUMNI_MENTORSHIPS,
    'link'  => 'admin/mentorship.php',
    'icon'  => $pathIcon32 . '/emoticon.png',
];

$adminmenu[] = [
    'title' => _AM_ALUMNI_SKILLS,
    'link'  => 'admin/skills.php',
    'icon'  => $pathIcon32 . '/button_ok.png',
];

$adminmenu[] = [
    'title' => _AM_ALUMNI_BLOCKSADMIN,
    'link'  => 'admin/blocksadmin.php',
    'icon'  => $pathIcon32 . '/block.png',
];

$adminmenu[] = [
    'title' => _MI_ALUMNI_MENU_CLONE,
    'link'  => 'admin/clone.php',
    'icon'  => $pathModIcon32 . 'editcopy.png',
];

$adminmenu[] = [
    'title' => _AM_ALUMNI_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png',
];

return $adminmenu;
