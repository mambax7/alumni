<?php

/**
 * Alumni Management System - Single Event Detail
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 * @version     1.0.0
 */

use XoopsModules\Alumni\{Helper, Utility};

require_once __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/include/common.php';

$helper = Helper::getInstance();
$eventHandler = $helper->getHandler('event');
$rsvpHandler = $helper->getHandler('rsvp');
$categoryHandler = $helper->getHandler('category');

// Get event ID
$eventId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($eventId === 0) {
    redirect_header('events.php', 3, _MD_ALUMNI_ERROR_INVALID_EVENT);
    exit();
}

// Get event object
$event = $eventHandler->get($eventId);

if (!$event || $event->isNew()) {
    redirect_header('events.php', 3, _MD_ALUMNI_ERROR_EVENT_NOT_FOUND);
    exit();
}

// Check if event is active
if ($event->getVar('status') !== 'active') {
    $isAdmin = Utility::isModuleAdmin();
    if (!$isAdmin) {
        redirect_header('events.php', 3, _MD_ALUMNI_ERROR_EVENT_NOT_AVAILABLE);
        exit();
    }
}

// Get related data
$category = $categoryHandler->get($event->getVar('category_id'));

// Check user's RSVP status
$userRsvpStatus = null;
$userRsvp = null;
$canRsvp = false;

$currentUserId =Utility::getCurrentUserId();

if ($currentUserId > 0) {
    $rsvpCriteria = new CriteriaCompo();
    $rsvpCriteria->add(new Criteria('event_id', $eventId));
    $rsvpCriteria->add(new Criteria('user_id', $currentUserId));
    $rsvps = $rsvpHandler->getObjects($rsvpCriteria);

    if (!empty($rsvps)) {
        $userRsvp = $rsvps[0];
        $userRsvpStatus = $userRsvp->getVar('status');
    } else {
        $canRsvp = true;
    }
}

// Check if event is full
$isFull = false;
if ($event->getVar('max_attendees') > 0) {
    $isFull = $event->getVar('rsvp_count') >= $event->getVar('max_attendees');
}

// Check if registration is closed
$registrationClosed = false;
if ($event->getVar('registration_deadline') > 0) {
    $registrationClosed = $event->getVar('registration_deadline') < time();
}

// Get attendees (confirmed RSVPs)
$attendeesArray = [];
$attendeeCriteria = new CriteriaCompo();
$attendeeCriteria->add(new Criteria('event_id', $eventId));
$attendeeCriteria->add(new Criteria('status', 'attending'));
$attendeeCriteria->setLimit(20); // Show first 20
$attendeeCriteria->setSort('created');
$attendeeCriteria->setOrder('ASC');
$attendees = $rsvpHandler->getObjects($attendeeCriteria);

$profileHandler = $helper->getHandler('profile');
foreach ($attendees as $rsvp) {
    $attendeeProfileCriteria = new CriteriaCompo();
    $attendeeProfileCriteria->add(new Criteria('user_id', $rsvp->getVar('user_id')));
    $profiles = $profileHandler->getObjects($attendeeProfileCriteria);

    if (!empty($profiles)) {
        $profile = $profiles[0];
        $attendeesArray[] = [
            'user_id'    => $rsvp->getVar('user_id'),
            'name'       => Utility::sanitizeHtml($profile->getVar('first_name') . ' ' . $profile->getVar('last_name')),
            'photo_url'  => !empty($profile->getVar('photo'))
                            ? ALUMNI_UPLOAD_URL . '/photos/' . $profile->getVar('photo')
                            : ALUMNI_URL . '/assets/images/default-avatar.png',
            'profile_url' => ALUMNI_URL . '/profile.php?id=' . $profile->getVar('profile_id'),
            'rsvp_date'  => Utility::formatDate($rsvp->getVar('created'))
        ];
    }
}

// Get similar events (same category, upcoming)
$similarEventsArray = [];
$similarCriteria = new CriteriaCompo();
$similarCriteria->add(new Criteria('category_id', $event->getVar('category_id')));
$similarCriteria->add(new Criteria('event_id', $eventId, '!='));
$similarCriteria->add(new Criteria('status', 'active'));
$similarCriteria->add(new Criteria('start_date', time(), '>='));
$similarCriteria->setLimit(5);
$similarCriteria->setSort('start_date');
$similarCriteria->setOrder('ASC');
$similarEvents = $eventHandler->getObjects($similarCriteria);

foreach ($similarEvents as $similarEvent) {
    $similarEventsArray[] = [
        'id'                => $similarEvent->getVar('event_id'),
        'title'             => Utility::sanitizeHtml($similarEvent->getVar('title')),
        'location'          => Utility::sanitizeHtml($similarEvent->getVar('location')),
        'start_date'        => $similarEvent->getVar('start_date'),
        'start_date_formatted' => Utility::formatDate($similarEvent->getVar('start_date'), 'M j, Y'),
        'event_type'        => $similarEvent->getVar('event_type'),
        'url'               => ALUMNI_URL . '/event.php?id=' . $similarEvent->getVar('event_id')
    ];
}

// Increment views
$event->setVar('views', $event->getVar('views') + 1);
$eventHandler->insert($event, true);

// Set template
$GLOBALS['xoopsOption']['template_main'] = 'db:alumni_event_detail.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

// Prepare event data for template
$eventData = Utility::formatEventData($event);
$eventData['category_name'] = $category ? Utility::sanitizeHtml($category->getVar('name')) : _MD_ALUMNI_UNKNOWN;

// Assign data to template
$xoopsTpl->assign('event', $eventData);
$xoopsTpl->assign('attendees', $attendeesArray);
$xoopsTpl->assign('similar_events', $similarEventsArray);
$xoopsTpl->assign('user_rsvp_status', $userRsvpStatus);
$xoopsTpl->assign('can_rsvp', $canRsvp && !$isFull && !$registrationClosed);
$xoopsTpl->assign('is_full', $isFull);
$xoopsTpl->assign('registration_closed', $registrationClosed);
$xoopsTpl->assign('is_logged_in', $currentUserId > 0);
$xoopsTpl->assign('attendee_count', count($attendeesArray));
$xoopsTpl->assign('total_attendees', $event->getVar('rsvp_count'));

// SEO Meta tags
$xoopsTpl->assign('xoops_pagetitle', $event->getVar('title') . ' - ' . $xoopsConfig['sitename']);

require_once XOOPS_ROOT_PATH . '/footer.php';
