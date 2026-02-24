<?php

/**
 * Alumni Management System - Connection Request Handler
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

// POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_header('index.php', 3, _MD_ALUMNI_ERROR_INVALID_REQUEST);
    exit();
}

// CSRF check
if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header($_SERVER['HTTP_REFERER'] ?? 'index.php', 3, _MD_ALUMNI_ERROR_SECURITY);
    exit();
}

$helper = Helper::getInstance();
$connectionHandler = $helper->getHandler('connection');
$profileHandler = $helper->getHandler('profile');

$op = isset($_POST['op']) ? $_POST['op'] : '';
$connectedUserId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
$connectionId = isset($_POST['connection_id']) ? (int)$_POST['connection_id'] : 0;
$currentUserId =Utility::getCurrentUserId();

// Get redirect URL
$redirectUrl = isset($_POST['redirect']) ? $_POST['redirect'] : 'connections.php';

switch ($op) {
    case 'send':
        // Validate target user
        if ($connectedUserId === 0 || $connectedUserId === $currentUserId) {
            redirect_header($redirectUrl, 3, _MD_ALUMNI_ERROR_INVALID_USER);
            exit();
        }

        // Check if target profile exists and allows networking
        $profileCriteria = new CriteriaCompo();
        $profileCriteria->add(new Criteria('user_id', $connectedUserId));
        $profiles = $profileHandler->getObjects($profileCriteria);

        if (empty($profiles)) {
            redirect_header($redirectUrl, 3, _MD_ALUMNI_ERROR_PROFILE_NOT_FOUND);
            exit();
        }

        $targetProfile = $profiles[0];
        if (!$targetProfile->getVar('allow_networking')) {
            redirect_header($redirectUrl, 3, _MD_ALUMNI_ERROR_NETWORKING_DISABLED);
            exit();
        }

        // Check if connection already exists
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('user_id', $currentUserId));
        $criteria->add(new Criteria('connected_user_id', $connectedUserId));
        $existingConnections = $connectionHandler->getObjects($criteria);

        if (!empty($existingConnections)) {
            redirect_header($redirectUrl, 3, _MD_ALUMNI_ERROR_CONNECTION_EXISTS);
            exit();
        }

        // Check reverse connection
        $reverseCriteria = new CriteriaCompo();
        $reverseCriteria->add(new Criteria('user_id', $connectedUserId));
        $reverseCriteria->add(new Criteria('connected_user_id', $currentUserId));
        $reverseConnections = $connectionHandler->getObjects($reverseCriteria);

        if (!empty($reverseConnections)) {
            redirect_header($redirectUrl, 3, _MD_ALUMNI_ERROR_CONNECTION_EXISTS);
            exit();
        }

        // Create connection request
        $connection = $connectionHandler->create();
        $connection->setVar('user_id', $currentUserId);
        $connection->setVar('connected_user_id', $connectedUserId);
        $connection->setVar('status', 'pending');
        $connection->setVar('message', isset($_POST['message']) ? $_POST['message'] : '');
        $connection->setVar('request_date', time());

        if ($connectionHandler->insert($connection)) {
            redirect_header($redirectUrl, 2, _MD_ALUMNI_CONNECTION_REQUEST_SENT);
        } else {
            redirect_header($redirectUrl, 3, _MD_ALUMNI_ERROR_SAVE_FAILED);
        }
        break;

    case 'accept':
        // Get connection request
        if ($connectionId === 0) {
            redirect_header($redirectUrl, 3, _MD_ALUMNI_ERROR_INVALID_CONNECTION);
            exit();
        }

        $connection = $connectionHandler->get($connectionId);

        if (!$connection || $connection->isNew()) {
            redirect_header($redirectUrl, 3, _MD_ALUMNI_ERROR_CONNECTION_NOT_FOUND);
            exit();
        }

        // Verify user is the recipient
        if ($connection->getVar('connected_user_id') != $currentUserId) {
            redirect_header($redirectUrl, 3, _MD_ALUMNI_ERROR_NO_PERMISSION);
            exit();
        }

        // Update status
        $connection->setVar('status', 'accepted');
        $connection->setVar('connected_date', time());

        if ($connectionHandler->insert($connection)) {
            // Update connection counts for both users
            $requesterCriteria = new CriteriaCompo();
            $requesterCriteria->add(new Criteria('user_id', $connection->getVar('user_id')));
            $requesterProfiles = $profileHandler->getObjects($requesterCriteria);

            if (!empty($requesterProfiles)) {
                $requesterProfile = $requesterProfiles[0];
                $requesterProfile->setVar('connections_count', $requesterProfile->getVar('connections_count') + 1);
                $profileHandler->insert($requesterProfile, true);
            }

            $recipientCriteria = new CriteriaCompo();
            $recipientCriteria->add(new Criteria('user_id', $currentUserId));
            $recipientProfiles = $profileHandler->getObjects($recipientCriteria);

            if (!empty($recipientProfiles)) {
                $recipientProfile = $recipientProfiles[0];
                $recipientProfile->setVar('connections_count', $recipientProfile->getVar('connections_count') + 1);
                $profileHandler->insert($recipientProfile, true);
            }

            redirect_header($redirectUrl, 2, _MD_ALUMNI_CONNECTION_ACCEPTED);
        } else {
            redirect_header($redirectUrl, 3, _MD_ALUMNI_ERROR_SAVE_FAILED);
        }
        break;

    case 'decline':
        // Get connection request
        if ($connectionId === 0) {
            redirect_header($redirectUrl, 3, _MD_ALUMNI_ERROR_INVALID_CONNECTION);
            exit();
        }

        $connection = $connectionHandler->get($connectionId);

        if (!$connection || $connection->isNew()) {
            redirect_header($redirectUrl, 3, _MD_ALUMNI_ERROR_CONNECTION_NOT_FOUND);
            exit();
        }

        // Verify user is the recipient
        if ($connection->getVar('connected_user_id') != $currentUserId) {
            redirect_header($redirectUrl, 3, _MD_ALUMNI_ERROR_NO_PERMISSION);
            exit();
        }

        // Update status
        $connection->setVar('status', 'declined');

        if ($connectionHandler->insert($connection)) {
            redirect_header($redirectUrl, 2, _MD_ALUMNI_CONNECTION_DECLINED);
        } else {
            redirect_header($redirectUrl, 3, _MD_ALUMNI_ERROR_SAVE_FAILED);
        }
        break;

    case 'remove':
        // Get connection
        if ($connectionId === 0) {
            redirect_header($redirectUrl, 3, _MD_ALUMNI_ERROR_INVALID_CONNECTION);
            exit();
        }

        $connection = $connectionHandler->get($connectionId);

        if (!$connection || $connection->isNew()) {
            redirect_header($redirectUrl, 3, _MD_ALUMNI_ERROR_CONNECTION_NOT_FOUND);
            exit();
        }

        // Verify user is part of the connection
        if ($connection->getVar('user_id') != $currentUserId && $connection->getVar('connected_user_id') != $currentUserId) {
            redirect_header($redirectUrl, 3, _MD_ALUMNI_ERROR_NO_PERMISSION);
            exit();
        }

        // Delete connection
        if ($connectionHandler->delete($connection)) {
            // Update connection counts if it was accepted
            if ($connection->getVar('status') === 'accepted') {
                $user1Criteria = new CriteriaCompo();
                $user1Criteria->add(new Criteria('user_id', $connection->getVar('user_id')));
                $user1Profiles = $profileHandler->getObjects($user1Criteria);

                if (!empty($user1Profiles)) {
                    $user1Profile = $user1Profiles[0];
                    $user1Profile->setVar('connections_count', max(0, $user1Profile->getVar('connections_count') - 1));
                    $profileHandler->insert($user1Profile, true);
                }

                $user2Criteria = new CriteriaCompo();
                $user2Criteria->add(new Criteria('user_id', $connection->getVar('connected_user_id')));
                $user2Profiles = $profileHandler->getObjects($user2Criteria);

                if (!empty($user2Profiles)) {
                    $user2Profile = $user2Profiles[0];
                    $user2Profile->setVar('connections_count', max(0, $user2Profile->getVar('connections_count') - 1));
                    $profileHandler->insert($user2Profile, true);
                }
            }

            redirect_header($redirectUrl, 2, _MD_ALUMNI_CONNECTION_REMOVED);
        } else {
            redirect_header($redirectUrl, 3, _MD_ALUMNI_ERROR_DELETE_FAILED);
        }
        break;

    default:
        redirect_header($redirectUrl, 3, _MD_ALUMNI_ERROR_INVALID_OPERATION);
        break;
}

exit();
