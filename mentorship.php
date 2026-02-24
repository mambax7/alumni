<?php

/**
 * Alumni Management System - Mentorship Management
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
$mentorshipHandler = $helper->getHandler('mentorship');
$profileHandler = $helper->getHandler('profile');

// Get tab filter
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'mentors'; // mentors, my_mentorships, requests

// Pagination
$limit = Utility::config('per_page') ?: 20;
$start = isset($_GET['start']) ? (int)$_GET['start'] : 0;

$itemsArray = [];
$totalCount = 0;

switch ($tab) {
    case 'mentors':
        // Show available mentors (profiles that allow mentorship)
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('status', 'active'));
        $criteria->add(new Criteria('allow_mentorship', 1));
        $criteria->add(new Criteria('user_id', $currentUserId, '!='));

        // Check privacy
        if (!Utility::isUserLoggedIn()) {
            $criteria->add(new Criteria('privacy_profile', 'public'));
        }

        $totalCount = $profileHandler->getCount($criteria);

        $criteria->setStart($start);
        $criteria->setLimit($limit);
        $criteria->setSort('updated');
        $criteria->setOrder('DESC');

        $profiles = $profileHandler->getObjects($criteria);

        foreach ($profiles as $profile) {
            // Check if user has already requested mentorship
            $requestCriteria = new CriteriaCompo();
            $requestCriteria->add(new Criteria('mentor_id', $profile->getVar('user_id')));
            $requestCriteria->add(new Criteria('mentee_id', $currentUserId));
            $existingRequest = $mentorshipHandler->getObjects($requestCriteria);

            $requestStatus = null;
            if (!empty($existingRequest)) {
                $requestStatus = $existingRequest[0]->getVar('status');
            }

            $itemsArray[] = [
                'profile_id'      => $profile->getVar('profile_id'),
                'user_id'         => $profile->getVar('user_id'),
                'name'            => Utility::sanitizeHtml($profile->getVar('first_name') . ' ' . $profile->getVar('last_name')),
                'photo_url'       => !empty($profile->getVar('photo'))
                                     ? ALUMNI_UPLOAD_URL . '/photos/' . $profile->getVar('photo')
                                     : ALUMNI_URL . '/assets/images/default-avatar.png',
                'graduation_year' => $profile->getVar('graduation_year'),
                'position'        => Utility::sanitizeHtml($profile->getVar('current_position')),
                'company'         => Utility::sanitizeHtml($profile->getVar('current_company')),
                'industry'        => Utility::sanitizeHtml($profile->getVar('industry')),
                'location'        => Utility::sanitizeHtml($profile->getVar('location')),
                'bio'             => Utility::truncate(strip_tags($profile->getVar('bio')), 150),
                'profile_url'     => ALUMNI_URL . '/profile.php?id=' . $profile->getVar('profile_id'),
                'request_status'  => $requestStatus,
                'can_request'     => $requestStatus === null
            ];
        }
        break;

    case 'my_mentorships':
        // Get mentorships where current user is mentee or mentor
        $criteria = new CriteriaCompo();
        $mentorCriteria = new CriteriaCompo();
        $mentorCriteria->add(new Criteria('mentor_id', $currentUserId), 'OR');
        $mentorCriteria->add(new Criteria('mentee_id', $currentUserId), 'OR');
        $criteria->add($mentorCriteria);

        $totalCount = $mentorshipHandler->getCount($criteria);

        $criteria->setStart($start);
        $criteria->setLimit($limit);
        $criteria->setSort('start_date');
        $criteria->setOrder('DESC');

        $mentorships = $mentorshipHandler->getObjects($criteria);

        foreach ($mentorships as $mentorship) {
            $isMentor = $mentorship->getVar('mentor_id') == $currentUserId;
            $otherUserId = $isMentor ? $mentorship->getVar('mentee_id') : $mentorship->getVar('mentor_id');

            $profileCriteria = new CriteriaCompo();
            $profileCriteria->add(new Criteria('user_id', $otherUserId));
            $profiles = $profileHandler->getObjects($profileCriteria);

            if (!empty($profiles)) {
                $profile = $profiles[0];
                $itemsArray[] = [
                    'mentorship_id'   => $mentorship->getVar('mentorship_id'),
                    'is_mentor'       => $isMentor,
                    'role'            => $isMentor ? _MD_ALUMNI_MENTOR : _MD_ALUMNI_MENTEE,
                    'user_id'         => $otherUserId,
                    'name'            => Utility::sanitizeHtml($profile->getVar('first_name') . ' ' . $profile->getVar('last_name')),
                    'photo_url'       => !empty($profile->getVar('photo'))
                                         ? ALUMNI_UPLOAD_URL . '/photos/' . $profile->getVar('photo')
                                         : ALUMNI_URL . '/assets/images/default-avatar.png',
                    'position'        => Utility::sanitizeHtml($profile->getVar('current_position')),
                    'company'         => Utility::sanitizeHtml($profile->getVar('current_company')),
                    'profile_url'     => ALUMNI_URL . '/profile.php?id=' . $profile->getVar('profile_id'),
                    'status'          => $mentorship->getVar('status'),
                    'start_date'      => Utility::formatDate($mentorship->getVar('start_date')),
                    'end_date'        => $mentorship->getVar('end_date') ? Utility::formatDate($mentorship->getVar('end_date')) : null,
                ];
            }
        }
        break;

    case 'requests':
        // Get mentorship requests where current user is mentor (pending only)
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('mentor_id', $currentUserId));
        $criteria->add(new Criteria('status', 'pending'));

        $totalCount = $mentorshipHandler->getCount($criteria);

        $criteria->setStart($start);
        $criteria->setLimit($limit);
        $criteria->setSort('request_date');
        $criteria->setOrder('DESC');

        $mentorships = $mentorshipHandler->getObjects($criteria);

        foreach ($mentorships as $mentorship) {
            $menteeId = $mentorship->getVar('mentee_id');

            $profileCriteria = new CriteriaCompo();
            $profileCriteria->add(new Criteria('user_id', $menteeId));
            $profiles = $profileHandler->getObjects($profileCriteria);

            if (!empty($profiles)) {
                $profile = $profiles[0];
                $itemsArray[] = [
                    'mentorship_id'   => $mentorship->getVar('mentorship_id'),
                    'user_id'         => $menteeId,
                    'name'            => Utility::sanitizeHtml($profile->getVar('first_name') . ' ' . $profile->getVar('last_name')),
                    'photo_url'       => !empty($profile->getVar('photo'))
                                         ? ALUMNI_UPLOAD_URL . '/photos/' . $profile->getVar('photo')
                                         : ALUMNI_URL . '/assets/images/default-avatar.png',
                    'graduation_year' => $profile->getVar('graduation_year'),
                    'position'        => Utility::sanitizeHtml($profile->getVar('current_position')),
                    'company'         => Utility::sanitizeHtml($profile->getVar('current_company')),
                    'profile_url'     => ALUMNI_URL . '/profile.php?id=' . $profile->getVar('profile_id'),
                    'request_message' => Utility::sanitizeHtml($mentorship->getVar('request_message')),
                    'request_date'    => Utility::formatDate($mentorship->getVar('request_date')),
                    'status'          => $mentorship->getVar('status')
                ];
            }
        }
        break;
}

// Pagination
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
$pagenav = new XoopsPageNav($totalCount, $limit, $start, 'start', 'tab=' . $tab);

// Set template
$GLOBALS['xoopsOption']['template_main'] = 'db:alumni_mentorship.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

// Assign to template
$xoopsTpl->assign('items', $itemsArray);
$xoopsTpl->assign('total_count', $totalCount);
$xoopsTpl->assign('current_tab', $tab);
$xoopsTpl->assign('pagenav', $pagenav->renderNav());

// SEO
$xoopsTpl->assign('xoops_pagetitle', _MD_ALUMNI_MENTORSHIP . ' - ' . $xoopsConfig['sitename']);

require_once XOOPS_ROOT_PATH . '/footer.php';
