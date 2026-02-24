<?php

declare(strict_types=1);

/**
 * Alumni Management System - RSVP Handler (AJAX).
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

header('Content-Type: application/json');

// Must be logged in
if (! Utility::isUserLoggedIn()) {
    echo json_encode([
        'success' => false,
        'error'   => _MD_ALUMNI_ERROR_LOGIN_REQUIRED,
    ]);
    exit();
}

// POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'error'   => _MD_ALUMNI_ERROR_INVALID_REQUEST,
    ]);
    exit();
}

// CSRF check
if (! $GLOBALS['xoopsSecurity']->check()) {
    echo json_encode([
        'success' => false,
        'error'   => _MD_ALUMNI_ERROR_SECURITY,
    ]);
    exit();
}

$helper = Helper::getInstance();
$rsvpHandler = $helper->getHandler('rsvp');
$eventHandler = $helper->getHandler('event');

$op = $_POST['op'] ?? '';
$eventId = isset($_POST['event_id']) ? (int) $_POST['event_id'] : 0;
$currentUserId = Utility::getCurrentUserId();

// Validate event
if ($eventId === 0) {
    echo json_encode([
        'success' => false,
        'error'   => _MD_ALUMNI_ERROR_INVALID_EVENT,
    ]);
    exit();
}

$event = $eventHandler->get($eventId);

if (! $event || $event->isNew() || $event->getVar('status') !== 'active') {
    echo json_encode([
        'success' => false,
        'error'   => _MD_ALUMNI_ERROR_EVENT_NOT_FOUND,
    ]);
    exit();
}

switch ($op) {
    case 'create':
        // Check if registration deadline has passed
        if ($event->getVar('registration_deadline') > 0 && $event->getVar('registration_deadline') < time()) {
            echo json_encode([
                'success' => false,
                'error'   => _MD_ALUMNI_ERROR_REGISTRATION_CLOSED,
            ]);
            exit();
        }

        // Check if event is full
        if ($event->getVar('max_attendees') > 0 && $event->getVar('rsvp_count') >= $event->getVar('max_attendees')) {
            echo json_encode([
                'success' => false,
                'error'   => _MD_ALUMNI_ERROR_EVENT_FULL,
            ]);
            exit();
        }

        // Check if user already has RSVP
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('event_id', $eventId));
        $criteria->add(new Criteria('user_id', $currentUserId));
        $existingRsvps = $rsvpHandler->getObjects($criteria);

        if (! empty($existingRsvps)) {
            echo json_encode([
                'success' => false,
                'error'   => _MD_ALUMNI_ERROR_ALREADY_RSVP,
            ]);
            exit();
        }

        $status = $_POST['status'] ?? 'attending';
        $guests = isset($_POST['guests']) ? (int) $_POST['guests'] : 0;

        // Validate status
        if (! in_array($status, ['attending', 'maybe', 'not_attending'], true)) {
            $status = 'attending';
        }

        // Create RSVP
        $rsvp = $rsvpHandler->create();
        $rsvp->setVar('event_id', $eventId);
        $rsvp->setVar('user_id', $currentUserId);
        $rsvp->setVar('status', $status);
        $rsvp->setVar('guests', $guests);
        $rsvp->setVar('rsvp_date', time());

        if ($rsvpHandler->insert($rsvp)) {
            // Update event RSVP count if attending
            if ($status === 'attending') {
                $event->setVar('rsvp_count', $event->getVar('rsvp_count') + 1);
                $eventHandler->insert($event, true);
            }

            echo json_encode([
                'success' => true,
                'message' => _MD_ALUMNI_RSVP_SUCCESS,
                'rsvp_id' => $rsvp->getVar('rsvp_id'),
                'status'  => $status,
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error'   => _MD_ALUMNI_ERROR_SAVE_FAILED,
            ]);
        }

        break;
    case 'update':
        // Get existing RSVP
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('event_id', $eventId));
        $criteria->add(new Criteria('user_id', $currentUserId));
        $rsvps = $rsvpHandler->getObjects($criteria);

        if (empty($rsvps)) {
            echo json_encode([
                'success' => false,
                'error'   => _MD_ALUMNI_ERROR_RSVP_NOT_FOUND,
            ]);
            exit();
        }

        $rsvp = $rsvps[0];
        $oldStatus = $rsvp->getVar('status');
        $newStatus = $_POST['status'] ?? 'attending';

        // Validate status
        if (! in_array($newStatus, ['attending', 'maybe', 'not_attending'], true)) {
            $newStatus = 'attending';
        }

        $rsvp->setVar('status', $newStatus);
        $rsvp->setVar('guests', isset($_POST['guests']) ? (int) $_POST['guests'] : $rsvp->getVar('guests'));

        if ($rsvpHandler->insert($rsvp)) {
            // Update event RSVP count
            if ($oldStatus === 'attending' && $newStatus !== 'attending') {
                $event->setVar('rsvp_count', max(0, $event->getVar('rsvp_count') - 1));
                $eventHandler->insert($event, true);
            } elseif ($oldStatus !== 'attending' && $newStatus === 'attending') {
                $event->setVar('rsvp_count', $event->getVar('rsvp_count') + 1);
                $eventHandler->insert($event, true);
            }

            echo json_encode([
                'success' => true,
                'message' => _MD_ALUMNI_RSVP_UPDATED,
                'status'  => $newStatus,
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error'   => _MD_ALUMNI_ERROR_SAVE_FAILED,
            ]);
        }

        break;
    case 'delete':
        // Get existing RSVP
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('event_id', $eventId));
        $criteria->add(new Criteria('user_id', $currentUserId));
        $rsvps = $rsvpHandler->getObjects($criteria);

        if (empty($rsvps)) {
            echo json_encode([
                'success' => false,
                'error'   => _MD_ALUMNI_ERROR_RSVP_NOT_FOUND,
            ]);
            exit();
        }

        $rsvp = $rsvps[0];
        $status = $rsvp->getVar('status');

        if ($rsvpHandler->delete($rsvp)) {
            // Update event RSVP count if was attending
            if ($status === 'attending') {
                $event->setVar('rsvp_count', max(0, $event->getVar('rsvp_count') - 1));
                $eventHandler->insert($event, true);
            }

            echo json_encode([
                'success' => true,
                'message' => _MD_ALUMNI_RSVP_DELETED,
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error'   => _MD_ALUMNI_ERROR_DELETE_FAILED,
            ]);
        }

        break;
    default:
        echo json_encode([
            'success' => false,
            'error'   => _MD_ALUMNI_ERROR_INVALID_OPERATION,
        ]);

        break;
}

exit();
