<?php

declare(strict_types=1);

/**
 * Alumni Management System - Events Listing.
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 *
 * @version     1.0.0
 */

use XoopsModules\Alumni\Helper;
use XoopsModules\Alumni\Utility;

require_once __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/include/common.php';

$helper = Helper::getInstance();
$eventHandler = $helper->getHandler('event');
$categoryHandler = $helper->getHandler('category');

// Pagination
$limit = Utility::config('per_page') ?: 20;
$start = isset($_GET['start']) ? (int) $_GET['start'] : 0;

// Get filters from request
$categoryId = isset($_GET['cat']) ? (int) $_GET['cat'] : 0;
$eventType = isset($_GET['type']) ? trim($_GET['type']) : '';
$timeFilter = isset($_GET['time']) ? trim($_GET['time']) : 'upcoming'; // upcoming, past, all
$featured = isset($_GET['featured']) ? (int) $_GET['featured'] : 0;
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

// Build search criteria
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('status', 'active'));

if ($categoryId > 0) {
    $criteria->add(new Criteria('category_id', $categoryId));
}

if (! empty($eventType)) {
    $criteria->add(new Criteria('event_type', $eventType));
}

if (! empty($keyword)) {
    $keywordCriteria = new CriteriaCompo();
    $keywordCriteria->add(new Criteria('title', '%' . $keyword . '%', 'LIKE'), 'OR');
    $keywordCriteria->add(new Criteria('description', '%' . $keyword . '%', 'LIKE'), 'OR');
    $keywordCriteria->add(new Criteria('location', '%' . $keyword . '%', 'LIKE'), 'OR');
    $criteria->add($keywordCriteria);
}

if ($featured) {
    $criteria->add(new Criteria('featured', 1));
}

// Time filter
$currentTime = time();
switch ($timeFilter) {
    case 'upcoming':
        $criteria->add(new Criteria('start_date', $currentTime, '>='));

        break;
    case 'past':
        $criteria->add(new Criteria('start_date', $currentTime, '<'));

        break;
    case 'all':
    default:
        // No date filter
        break;
}

// Get total count
$totalEvents = $eventHandler->getCount($criteria);

// Set pagination
$criteria->setLimit($limit);
$criteria->setStart($start);

// Sort by date
if ($timeFilter === 'past') {
    $criteria->setSort('start_date');
    $criteria->setOrder('DESC');
} else {
    $criteria->setSort('start_date');
    $criteria->setOrder('ASC');
}

// Get events
$events = $eventHandler->getObjects($criteria);

// Format events for template
$eventsArray = [];
foreach ($events as $event) {
    $eventData = Utility::formatEventData($event);

    // Add RSVP status if user is logged in
    if (Utility::isUserLoggedIn()) {
        $rsvpHandler = $helper->getHandler('rsvp');
        $rsvpCriteria = new CriteriaCompo();
        $rsvpCriteria->add(new Criteria('event_id', $event->getVar('event_id')));
        $rsvpCriteria->add(new Criteria('user_id', Utility::getCurrentUserId()));
        $rsvps = $rsvpHandler->getObjects($rsvpCriteria);

        if (! empty($rsvps)) {
            $eventData['user_rsvp_status'] = $rsvps[0]->getVar('status');
            $eventData['has_rsvp'] = true;
        } else {
            $eventData['user_rsvp_status'] = null;
            $eventData['has_rsvp'] = false;
        }
    } else {
        $eventData['user_rsvp_status'] = null;
        $eventData['has_rsvp'] = false;
    }

    // Check if event is full
    $eventData['is_full'] = false;
    if ($event->getVar('max_attendees') > 0) {
        $eventData['is_full'] = $event->getVar('rsvp_count') >= $event->getVar('max_attendees');
    }

    $eventsArray[] = $eventData;
}

// Get featured events (if not already filtering)
$featuredEventsArray = [];
if (empty($keyword) && ! $featured) {
    $featuredCriteria = new CriteriaCompo();
    $featuredCriteria->add(new Criteria('status', 'active'));
    $featuredCriteria->add(new Criteria('featured', 1));
    $featuredCriteria->add(new Criteria('start_date', $currentTime, '>='));
    $featuredCriteria->setLimit(3);
    $featuredCriteria->setSort('start_date');
    $featuredCriteria->setOrder('ASC');
    $featuredEvents = $eventHandler->getObjects($featuredCriteria);

    foreach ($featuredEvents as $event) {
        $featuredEventsArray[] = Utility::formatEventData($event);
    }
}

// Get categories for filter
$categoriesArray = [];
$allCategories = $categoryHandler->getObjects(null, true);
foreach ($allCategories as $cat) {
    $categoriesArray[] = [
        'id'   => $cat->getVar('category_id'),
        'name' => Utility::sanitizeHtml($cat->getVar('name')),
    ];
}

// Get event types for filter
$eventTypes = Utility::getEventTypeOptions();

// Pagination
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
$pagenav = new XoopsPageNav(
    $totalEvents,
    $limit,
    $start,
    'start',
    'cat=' . $categoryId . '&type=' . urlencode($eventType) .
    '&time=' . urlencode($timeFilter) . '&featured=' . $featured .
    '&keyword=' . urlencode($keyword)
);

// Set template
$GLOBALS['xoopsOption']['template_main'] = 'db:alumni_events.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

// Pre-format the results text in PHP
$resultsText = sprintf(
    _MD_ALUMNI_SHOWING_RESULTS,
    $start + 1,
    min($start + $limit, $totalEvents),
    $totalEvents
);

// Assign to template
$xoopsTpl->assign('events', $eventsArray);
$xoopsTpl->assign('featured_events', $featuredEventsArray);
$xoopsTpl->assign('categories', $categoriesArray);
$xoopsTpl->assign('event_types', $eventTypes);
$xoopsTpl->assign('total_events', $totalEvents);
$xoopsTpl->assign('results_text', $resultsText);
$xoopsTpl->assign('pagenav', $pagenav->renderNav());
$xoopsTpl->assign('is_admin', $helper->isUserAdmin());
$xoopsTpl->assign('is_logged_in', Utility::isUserLoggedIn());
$xoopsTpl->assign('filter', $timeFilter);  // template uses $filter, not $current_time

// Current filter values (kept for back-compat)
$xoopsTpl->assign('current_category', $categoryId);
$xoopsTpl->assign('current_type', $eventType);
$xoopsTpl->assign('current_time', $timeFilter);
$xoopsTpl->assign('current_featured', $featured);
$xoopsTpl->assign('current_keyword', htmlspecialchars($keyword, ENT_QUOTES));

// SEO
$xoopsTpl->assign('xoops_pagetitle', _MD_ALUMNI_BROWSE_EVENTS . ' - ' . $xoopsConfig['sitename']);

require_once XOOPS_ROOT_PATH . '/footer.php';
