<?php

/**
 * Alumni Management System - Manage Connections
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
$connectionHandler = $helper->getHandler('connection');
$profileHandler = $helper->getHandler('profile');

// Get tab filter
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'connections'; // connections, sent, received

// Pagination
$limit = Utility::config('per_page') ?: 20;
$start = isset($_GET['start']) ? (int)$_GET['start'] : 0;

$connectionsArray = [];
$totalCount = 0;

switch ($tab) {
    case 'connections':
        // Get accepted connections
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('user_id', $currentUserId));
        $criteria->add(new Criteria('status', 'accepted'));

        $totalCount = $connectionHandler->getCount($criteria);

        $criteria->setStart($start);
        $criteria->setLimit($limit);
        $criteria->setSort('connected_date');
        $criteria->setOrder('DESC');

        $connections = $connectionHandler->getObjects($criteria);

        foreach ($connections as $connection) {
            $connectedUserId = $connection->getVar('connected_user_id');

            $profileCriteria = new CriteriaCompo();
            $profileCriteria->add(new Criteria('user_id', $connectedUserId));
            $profiles = $profileHandler->getObjects($profileCriteria);

            if (!empty($profiles)) {
                $profile = $profiles[0];
                $connectionsArray[] = [
                    'connection_id'  => $connection->getVar('connection_id'),
                    'user_id'        => $connectedUserId,
                    'name'           => Utility::sanitizeHtml($profile->getVar('first_name') . ' ' . $profile->getVar('last_name')),
                    'photo_url'      => !empty($profile->getVar('photo'))
                                        ? ALUMNI_UPLOAD_URL . '/photos/' . $profile->getVar('photo')
                                        : ALUMNI_URL . '/assets/images/default-avatar.png',
                    'graduation_year' => $profile->getVar('graduation_year'),
                    'position'       => Utility::sanitizeHtml($profile->getVar('current_position')),
                    'company'        => Utility::sanitizeHtml($profile->getVar('current_company')),
                    'location'       => Utility::sanitizeHtml($profile->getVar('location')),
                    'profile_url'    => ALUMNI_URL . '/profile.php?id=' . $profile->getVar('profile_id'),
                    'connected_date' => Utility::formatDate($connection->getVar('connected_date')),
                    'status'         => $connection->getVar('status')
                ];
            }
        }
        break;

    case 'sent':
        // Get sent pending requests
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('user_id', $currentUserId));
        $criteria->add(new Criteria('status', 'pending'));

        $totalCount = $connectionHandler->getCount($criteria);

        $criteria->setStart($start);
        $criteria->setLimit($limit);
        $criteria->setSort('request_date');
        $criteria->setOrder('DESC');

        $connections = $connectionHandler->getObjects($criteria);

        foreach ($connections as $connection) {
            $connectedUserId = $connection->getVar('connected_user_id');

            $profileCriteria = new CriteriaCompo();
            $profileCriteria->add(new Criteria('user_id', $connectedUserId));
            $profiles = $profileHandler->getObjects($profileCriteria);

            if (!empty($profiles)) {
                $profile = $profiles[0];
                $connectionsArray[] = [
                    'connection_id'  => $connection->getVar('connection_id'),
                    'user_id'        => $connectedUserId,
                    'name'           => Utility::sanitizeHtml($profile->getVar('first_name') . ' ' . $profile->getVar('last_name')),
                    'photo_url'      => !empty($profile->getVar('photo'))
                                        ? ALUMNI_UPLOAD_URL . '/photos/' . $profile->getVar('photo')
                                        : ALUMNI_URL . '/assets/images/default-avatar.png',
                    'graduation_year' => $profile->getVar('graduation_year'),
                    'position'       => Utility::sanitizeHtml($profile->getVar('current_position')),
                    'company'        => Utility::sanitizeHtml($profile->getVar('current_company')),
                    'location'       => Utility::sanitizeHtml($profile->getVar('location')),
                    'profile_url'    => ALUMNI_URL . '/profile.php?id=' . $profile->getVar('profile_id'),
                    'request_date'   => Utility::formatDate($connection->getVar('request_date')),
                    'status'         => $connection->getVar('status')
                ];
            }
        }
        break;

    case 'received':
        // Get received pending requests
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('connected_user_id', $currentUserId));
        $criteria->add(new Criteria('status', 'pending'));

        $totalCount = $connectionHandler->getCount($criteria);

        $criteria->setStart($start);
        $criteria->setLimit($limit);
        $criteria->setSort('request_date');
        $criteria->setOrder('DESC');

        $connections = $connectionHandler->getObjects($criteria);

        foreach ($connections as $connection) {
            $requesterId = $connection->getVar('user_id');

            $profileCriteria = new CriteriaCompo();
            $profileCriteria->add(new Criteria('user_id', $requesterId));
            $profiles = $profileHandler->getObjects($profileCriteria);

            if (!empty($profiles)) {
                $profile = $profiles[0];
                $connectionsArray[] = [
                    'connection_id'  => $connection->getVar('connection_id'),
                    'user_id'        => $requesterId,
                    'name'           => Utility::sanitizeHtml($profile->getVar('first_name') . ' ' . $profile->getVar('last_name')),
                    'photo_url'      => !empty($profile->getVar('photo'))
                                        ? ALUMNI_UPLOAD_URL . '/photos/' . $profile->getVar('photo')
                                        : ALUMNI_URL . '/assets/images/default-avatar.png',
                    'graduation_year' => $profile->getVar('graduation_year'),
                    'position'       => Utility::sanitizeHtml($profile->getVar('current_position')),
                    'company'        => Utility::sanitizeHtml($profile->getVar('current_company')),
                    'location'       => Utility::sanitizeHtml($profile->getVar('location')),
                    'profile_url'    => ALUMNI_URL . '/profile.php?id=' . $profile->getVar('profile_id'),
                    'request_date'   => Utility::formatDate($connection->getVar('request_date')),
                    'message'        => Utility::sanitizeHtml($connection->getVar('message')),
                    'status'         => $connection->getVar('status')
                ];
            }
        }
        break;
}

// Pagination
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
$pagenav = new XoopsPageNav($totalCount, $limit, $start, 'start', 'tab=' . $tab);

// Set template
$GLOBALS['xoopsOption']['template_main'] = 'db:alumni_connections.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

// Assign to template
$xoopsTpl->assign('connections', $connectionsArray);
$xoopsTpl->assign('total_count', $totalCount);
$xoopsTpl->assign('current_tab', $tab);
$xoopsTpl->assign('pagenav', $pagenav->renderNav());

// SEO
$xoopsTpl->assign('xoops_pagetitle', _MD_ALUMNI_MY_CONNECTIONS . ' - ' . $xoopsConfig['sitename']);

require_once XOOPS_ROOT_PATH . '/footer.php';
