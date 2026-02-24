<?php

declare(strict_types=1);

/**
 * Alumni Admin Dashboard.
 *
 * @copyright XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 */

use XoopsModules\Alumni\Common\TestdataButtons;
use XoopsModules\Alumni\Helper;
use XoopsModules\Alumni\Utility;

$GLOBALS['xoopsOption']['template_main'] = 'file:' . dirname(__DIR__) . '/templates/admin/alumni_admin_dashboard.tpl';

require __DIR__ . '/admin_header.php';
defined('XOOPS_ROOT_PATH') || die('Restricted access');

$helper = Helper::getInstance();

xoops_cp_header();
Utility::addAdminAssets();

$adminObject->displayNavigation(basename(__FILE__));

// ------------- Test Data Buttons -------------------------------------------
$op = isset($_REQUEST['op']) ? htmlspecialchars(trim($_REQUEST['op']), ENT_QUOTES) : '';
switch ($op) {
    case 'hide_buttons':
        TestdataButtons::hideButtons();

        break;
    case 'show_buttons':
        TestdataButtons::showButtons();

        break;
}

if ((int) $helper->getConfig('displaySampleButton') === 1) {
    TestdataButtons::loadButtonConfig($adminObject);
    $xoopsTpl->assign('xm_testdata_buttons', $adminObject->renderButton('left', ''));
} else {
    $xoopsTpl->assign('xm_testdata_buttons', '');
}
// ------------- End Test Data Buttons ---------------------------------------

// Get handlers
$profileHandler = $helper->getHandler('profile');
$eventHandler = $helper->getHandler('event');
$categoryHandler = $helper->getHandler('category');
$rsvpHandler = $helper->getHandler('rsvp');
$connectionHandler = $helper->getHandler('connection');
$mentorshipHandler = $helper->getHandler('mentorship');

// Get statistics
$stats = [
    'total_profiles'     => $profileHandler->getCount(),
    'active_profiles'    => $profileHandler->getCount(new Criteria('status', 'active')),
    'pending_profiles'   => $profileHandler->getCount(new Criteria('status', 'pending')),
    'featured_profiles'  => $profileHandler->getCount(new Criteria('featured', 1)),
    'total_events'       => $eventHandler->getCount(),
    'upcoming_events'    => $eventHandler->getCount(new Criteria('start_date', time(), '>=')),
    'past_events'        => $eventHandler->getCount(new Criteria('start_date', time(), '<')),
    'total_categories'   => $categoryHandler->getCount(),
    'total_rsvps'        => $rsvpHandler->getCount(),
    'total_connections'  => $connectionHandler->getCount(),
    'active_connections' => $connectionHandler->getCount(new Criteria('status', 'accepted')),
    'total_mentorships'  => $mentorshipHandler->getCount(),
    'active_mentorships' => $mentorshipHandler->getCount(new Criteria('status', 'active')),
];

// Recent (last 30 days)
$recentCriteria = new CriteriaCompo();
$recentCriteria->add(new Criteria('created', time() - (30 * 86400), '>='));
$stats['recent_profiles'] = $profileHandler->getCount($recentCriteria);

$recentEventsCriteria = new CriteriaCompo();
$recentEventsCriteria->add(new Criteria('created', time() - (30 * 86400), '>='));
$stats['recent_events'] = $eventHandler->getCount($recentEventsCriteria);

// Recent profiles for display
$criteria = new CriteriaCompo();
$criteria->setSort('created');
$criteria->setOrder('DESC');
$criteria->setLimit(10);
$recentProfiles = $profileHandler->getObjects($criteria);

// Recent events for display
$eventCriteria = new CriteriaCompo();
$eventCriteria->setSort('created');
$eventCriteria->setOrder('DESC');
$eventCriteria->setLimit(10);
$recentEvents = $eventHandler->getObjects($eventCriteria);

// Prepare profiles array
$profilesArray = [];
$statusClasses = [
    'active'   => 'success',
    'pending'  => 'warning',
    'inactive' => 'danger',
    'rejected' => 'secondary',
];
$statusLabels = [
    'active'   => _MD_ALUMNI_STATUS_ACTIVE,
    'pending'  => _MD_ALUMNI_STATUS_PENDING,
    'inactive' => _MD_ALUMNI_STATUS_INACTIVE,
    'rejected' => _MD_ALUMNI_STATUS_REJECTED,
];

foreach ($recentProfiles as $profile) {
    $status = $profile->getVar('status');
    $profilesArray[] = [
        'id'                => $profile->getVar('profile_id'),
        'name'              => Utility::sanitizeHtml($profile->getVar('first_name') . ' ' . $profile->getVar('last_name')),
        'graduation_year'   => $profile->getVar('graduation_year'),
        'company'           => Utility::sanitizeHtml($profile->getVar('current_company')),
        'status'            => $status,
        'status_class'      => $statusClasses[$status] ?? 'secondary',
        'status_label'      => $statusLabels[$status] ?? $status,
        'featured'          => $profile->getVar('featured'),
        'created_formatted' => formatTimestamp($profile->getVar('created'), 's'),
    ];
}

// Prepare events array
$eventsArray = [];
$eventStatusClasses = [
    'published' => 'success',
    'draft'     => 'warning',
    'cancelled' => 'danger',
];
$eventStatusLabels = [
    'published' => _MD_ALUMNI_STATUS_PUBLISHED,
    'draft'     => _MD_ALUMNI_STATUS_DRAFT,
    'cancelled' => _MD_ALUMNI_STATUS_CANCELLED,
];

$memberHandler = xoops_getHandler('member');
foreach ($recentEvents as $event) {
    $category = $categoryHandler->get($event->getVar('category_id'));
    $creator = $memberHandler->getUser($event->getVar('created_by'));
    $status = $event->getVar('status');

    $eventsArray[] = [
        'id'                   => $event->getVar('event_id'),
        'title'                => Utility::sanitizeHtml($event->getVar('title')),
        'category_name'        => $category ? Utility::sanitizeHtml($category->getVar('name')) : _AM_ALUMNI_UNKNOWN,
        'start_date_formatted' => formatTimestamp($event->getVar('start_date'), 's'),
        'rsvp_count'           => $event->getVar('rsvp_count'),
        'status'               => $status,
        'status_class'         => $eventStatusClasses[$status] ?? 'secondary',
        'status_label'         => $eventStatusLabels[$status] ?? $status,
        'featured'             => $event->getVar('featured'),
        'created_by_name'      => $creator ? $creator->getVar('uname') : _AM_ALUMNI_UNKNOWN,
    ];
}

// Info box
$adminObject->addInfoBox(_AM_ALUMNI_STATISTICS);
$adminObject->addInfoBoxLine(
    sprintf(
        _AM_ALUMNI_STAT_TOTAL_PROFILES . ': %d (' . _AM_ALUMNI_STAT_ACTIVE_PROFILES . ': %d)',
        $stats['total_profiles'],
        $stats['active_profiles']
    ),
    '',
    'Green'
);
$adminObject->addInfoBoxLine(
    sprintf(_AM_ALUMNI_STAT_PENDING_PROFILES . ': %d', $stats['pending_profiles']),
    '',
    'Orange'
);
$adminObject->addInfoBoxLine(
    sprintf(
        _AM_ALUMNI_STAT_TOTAL_EVENTS . ': %d (' . _AM_ALUMNI_STAT_UPCOMING_EVENTS . ': %d)',
        $stats['total_events'],
        $stats['upcoming_events']
    ),
    '',
    'Blue'
);
$adminObject->addInfoBoxLine(
    sprintf(_AM_ALUMNI_STAT_TOTAL_CONNECTIONS . ': %d', $stats['total_connections']),
    '',
    'Red'
);
echo $adminObject->renderInfoBox();

// Assign to template — rendered by xoops_cp_footer() via db:alumni_admin_dashboard.tpl
$xoopsTpl->assign('stats', $stats);
$xoopsTpl->assign('recent_profiles', $profilesArray);
$xoopsTpl->assign('recent_events', $eventsArray);

require __DIR__ . '/admin_footer.php';
