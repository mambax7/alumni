<?php

/**
 * Alumni Management System - User Dashboard
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 * @version     1.0.0
 */

use XoopsModules\Alumni\{Helper, Utility};

require_once __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/include/common.php';

// Must be logged in
if (!Utility::isUserLoggedIn()) {
    redirect_header('user.php', 3, _MD_ALUMNI_ERROR_LOGIN_REQUIRED);
    exit();
}

$currentUserId =Utility::getCurrentUserId();

$helper = Helper::getInstance();
$profileHandler = $helper->getHandler('profile');
$connectionHandler = $helper->getHandler('connection');
$rsvpHandler = $helper->getHandler('rsvp');
$eventHandler = $helper->getHandler('event');
$mentorshipHandler = $helper->getHandler('mentorship');

// Get user's profile
$profileCriteria = new CriteriaCompo();
$profileCriteria->add(new Criteria('user_id', $currentUserId));
$profiles = $profileHandler->getObjects($profileCriteria);
$profile = !empty($profiles) ? $profiles[0] : null;

// Get profile summary
$profileSummary = [];
if ($profile) {
    $profileSummary = [
        'id'                => $profile->getVar('profile_id'),
        'full_name'         => Utility::sanitizeHtml($profile->getVar('first_name') . ' ' . $profile->getVar('last_name')),
        'photo_url'         => !empty($profile->getVar('photo'))
                               ? ALUMNI_UPLOAD_URL . '/photos/' . $profile->getVar('photo')
                               : ALUMNI_URL . '/assets/images/default-avatar.png',
        'current_position'  => Utility::sanitizeHtml($profile->getVar('current_position')),
        'current_company'   => Utility::sanitizeHtml($profile->getVar('current_company')),
        'graduation_year'   => $profile->getVar('graduation_year'),
        'views'             => $profile->getVar('views'),
        'connections_count' => $profile->getVar('connections_count'),
        'url'               => ALUMNI_URL . '/profile.php?id=' . $profile->getVar('profile_id')
    ];
}

// Get connections (accepted only, both directions) - limited to 10
// getUserConnections() handles both requester_id and recipient_id correctly
$connections = $connectionHandler->getUserConnections($currentUserId, 'accepted', 10);

$connectionsArray = [];
foreach ($connections as $connection) {
    // Determine the other user in the connection
    $connectedUserId = ($connection->getVar('requester_id') == $currentUserId)
        ? $connection->getVar('recipient_id')
        : $connection->getVar('requester_id');

    $connProfile = $profileHandler->getProfileByUserId($connectedUserId);

    if ($connProfile) {
        $connectionsArray[] = [
            'connection_id'  => $connection->getVar('connection_id'),
            'user_id'        => $connectedUserId,
            'name'           => Utility::sanitizeHtml($connProfile->getVar('first_name') . ' ' . $connProfile->getVar('last_name')),
            'photo_url'      => !empty($connProfile->getVar('photo'))
                                ? ALUMNI_UPLOAD_URL . '/photos/' . $connProfile->getVar('photo')
                                : ALUMNI_URL . '/assets/images/default-avatar.png',
            'position'       => Utility::sanitizeHtml($connProfile->getVar('current_position')),
            'company'        => Utility::sanitizeHtml($connProfile->getVar('current_company')),
            'profile_url'    => ALUMNI_URL . '/profile.php?id=' . $connProfile->getVar('profile_id'),
            'connected_date' => Utility::formatDate($connection->getVar('updated'))
        ];
    }
}

// Get total connections count (both directions)
$totalConnections = count($connectionHandler->getUserConnections($currentUserId, 'accepted'));

// Get pending connection requests received by current user
// getPendingRequests() uses recipient_id correctly
$pendingRequests = $connectionHandler->getPendingRequests($currentUserId);
$pendingRequests  = array_slice($pendingRequests, 0, 5);

$pendingRequestsArray = [];
foreach ($pendingRequests as $request) {
    $requesterId = $request->getVar('requester_id');  // correct column

    $requesterProfile = $profileHandler->getProfileByUserId($requesterId);

    if ($requesterProfile) {
        $pendingRequestsArray[] = [
            'connection_id'  => $request->getVar('connection_id'),
            'user_id'        => $requesterId,
            'name'           => Utility::sanitizeHtml($requesterProfile->getVar('first_name') . ' ' . $requesterProfile->getVar('last_name')),
            'photo_url'      => !empty($requesterProfile->getVar('photo'))
                                ? ALUMNI_UPLOAD_URL . '/photos/' . $requesterProfile->getVar('photo')
                                : ALUMNI_URL . '/assets/images/default-avatar.png',
            'position'       => Utility::sanitizeHtml($requesterProfile->getVar('current_position')),
            'company'        => Utility::sanitizeHtml($requesterProfile->getVar('current_company')),
            'profile_url'    => ALUMNI_URL . '/profile.php?id=' . $requesterProfile->getVar('profile_id'),
            'request_date'   => Utility::formatDate($request->getVar('created'))  // correct column
        ];
    }
}

// Get upcoming events (RSVPs)
$rsvpCriteria = new CriteriaCompo();
$rsvpCriteria->add(new Criteria('user_id', $currentUserId));
$rsvpCriteria->add(new Criteria('status', 'attending'));
$rsvpCriteria->setSort('created');
$rsvpCriteria->setOrder('DESC');
$rsvps = $rsvpHandler->getObjects($rsvpCriteria);

$upcomingEventsArray = [];
$currentTime = time();

foreach ($rsvps as $rsvp) {
    $event = $eventHandler->get($rsvp->getVar('event_id'));

    if ($event && !$event->isNew() && $event->getVar('start_date') >= $currentTime) {
        $upcomingEventsArray[] = [
            'rsvp_id'              => $rsvp->getVar('rsvp_id'),
            'event_id'             => $event->getVar('event_id'),
            'title'                => Utility::sanitizeHtml($event->getVar('title')),
            'location'             => Utility::sanitizeHtml($event->getVar('location')),
            'start_date'           => $event->getVar('start_date'),
            'start_date_formatted' => Utility::formatDate($event->getVar('start_date'), 'M j, Y g:i A'),
            'event_type'           => $event->getVar('event_type'),
            'image_url'            => !empty($event->getVar('image'))
                                      ? ALUMNI_UPLOAD_URL . '/events/' . $event->getVar('image')
                                      : ALUMNI_URL . '/assets/images/default-event.png',
            'url'                  => ALUMNI_URL . '/event.php?id=' . $event->getVar('event_id')
        ];
    }
}

// Sort upcoming events by start date
usort($upcomingEventsArray, function($a, $b) {
    return $a['start_date'] - $b['start_date'];
});

// Limit to 5
$upcomingEventsArray = array_slice($upcomingEventsArray, 0, 5);

// Get mentorship requests (if user allows mentorship)
$mentorshipRequestsArray = [];
if ($profile && $profile->getVar('allow_mentorship')) {
    $mentorshipCriteria = new CriteriaCompo();
    $mentorshipCriteria->add(new Criteria('mentor_id', $currentUserId));
    $mentorshipCriteria->add(new Criteria('status', 'pending'));
    $mentorshipCriteria->setLimit(5);
    $mentorshipRequests = $mentorshipHandler->getObjects($mentorshipCriteria);

    foreach ($mentorshipRequests as $mentorship) {
        $menteeId = $mentorship->getVar('mentee_id');

        $menteeProfileCriteria = new CriteriaCompo();
        $menteeProfileCriteria->add(new Criteria('user_id', $menteeId));
        $menteeProfiles = $profileHandler->getObjects($menteeProfileCriteria);

        if (!empty($menteeProfiles)) {
            $menteeProfile = $menteeProfiles[0];
            $mentorshipRequestsArray[] = [
                'mentorship_id' => $mentorship->getVar('mentorship_id'),
                'mentee_id'     => $menteeId,
                'name'          => Utility::sanitizeHtml($menteeProfile->getVar('first_name') . ' ' . $menteeProfile->getVar('last_name')),
                'photo_url'     => !empty($menteeProfile->getVar('photo'))
                                   ? ALUMNI_UPLOAD_URL . '/photos/' . $menteeProfile->getVar('photo')
                                   : ALUMNI_URL . '/assets/images/default-avatar.png',
                'message'       => Utility::sanitizeHtml($mentorship->getVar('message')),
                'profile_url'   => ALUMNI_URL . '/profile.php?id=' . $menteeProfile->getVar('profile_id'),
                'request_date'  => Utility::formatDate($mentorship->getVar('created'))
            ];
        }
    }
}

// Set template
$GLOBALS['xoopsOption']['template_main'] = 'db:alumni_dashboard.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

// ── Build template-compatible derived data ─────────────────────────────────

// user_profile — profile quick-view widget uses .name, .photo, .available_as_mentor
$userProfile = $profileSummary ? [
    'id'                  => $profileSummary['id'],
    'name'                => $profileSummary['full_name'],
    'photo'               => $profileSummary['photo_url'],
    'graduation_year'     => $profileSummary['graduation_year'],
    'available_as_mentor' => $profile ? (bool)$profile->getVar('allow_mentorship') : false,
] : [];

// Stats widget
$stats = [
    'connections'   => $totalConnections,
    'events'        => count($upcomingEventsArray),
    'profile_views' => $profileSummary['views'] ?? 0,
    'mentorships'   => count($mentorshipRequestsArray),
];

// my_connections — template needs 'photo' not 'photo_url'
$myConnections = array_map(static function ($c) {
    return $c + ['photo' => $c['photo_url']];
}, $connectionsArray);

// connection_requests — template needs 'photo', 'job_title', 'time_ago'
$connectionRequests = array_map(static function ($r) {
    return $r + [
        'photo'     => $r['photo_url'],
        'job_title' => $r['position'],
        'time_ago'  => $r['request_date'],
    ];
}, $pendingRequestsArray);

// my_events — template needs 'date_formatted', 'days_until', 'rsvp_status'
$nowTs   = time();
$myEvents = array_map(static function ($e) use ($nowTs) {
    $daysUntil = max(0, (int)(($e['start_date'] - $nowTs) / 86400));
    return $e + [
        'date_formatted' => $e['start_date_formatted'],
        'days_until'     => $daysUntil,
        'rsvp_status'    => 'Attending',
    ];
}, $upcomingEventsArray);

// Assign data to template
$xoopsTpl->assign('stats',               $stats);
$xoopsTpl->assign('user_profile',        $userProfile);
$xoopsTpl->assign('my_connections',      $myConnections);
$xoopsTpl->assign('connection_requests', $connectionRequests);
$xoopsTpl->assign('my_events',           $myEvents);
$xoopsTpl->assign('mentorship_requests', $mentorshipRequestsArray);
$xoopsTpl->assign('recent_activity',     []);
// Back-compat aliases (kept in case any block template references them)
$xoopsTpl->assign('profile',             $profileSummary);
$xoopsTpl->assign('has_profile',         $profile !== null);
$xoopsTpl->assign('connections',         $connectionsArray);
$xoopsTpl->assign('total_connections',   $totalConnections);
$xoopsTpl->assign('pending_requests',    $pendingRequestsArray);
$xoopsTpl->assign('upcoming_events',     $upcomingEventsArray);
$xoopsTpl->assign('user_name',           $GLOBALS['xoopsUser']->getVar('name'));

// SEO
$xoopsTpl->assign('xoops_pagetitle', _MD_ALUMNI_MY_DASHBOARD . ' - ' . $xoopsConfig['sitename']);

require_once XOOPS_ROOT_PATH . '/footer.php';
