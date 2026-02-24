<?php

declare(strict_types=1);

/**
 * Alumni Management System - Profile View/Edit.
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
$profileHandler = $helper->getHandler('profile');
$skillHandler = $helper->getHandler('skill');
$connectionHandler = $helper->getHandler('connection');

$op = $_REQUEST['op'] ?? 'view';
$profileId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$currentUserId = Utility::getCurrentUserId();

switch ($op) {
    case 'view':
    default:
        if ($profileId === 0) {
            if (Utility::isUserLoggedIn()) {
                // Redirect logged-in user to their own profile, or prompt to create one
                $ownProfile = $profileHandler->getProfileByUserId($currentUserId);
                if ($ownProfile && ! $ownProfile->isNew()) {
                    redirect_header('profile.php?id=' . $ownProfile->getVar('profile_id'), 0, '');
                } else {
                    redirect_header('profile.php?op=edit', 3, _MD_ALUMNI_CREATE_PROFILE);
                }
            } else {
                redirect_header('index.php', 3, _MD_ALUMNI_ERROR_INVALID_PROFILE);
            }
            exit();
        }

        // Get profile object
        $profile = $profileHandler->get($profileId);

        if (! $profile || $profile->isNew()) {
            redirect_header('index.php', 3, _MD_ALUMNI_ERROR_PROFILE_NOT_FOUND);
            exit();
        }

        // Check viewing permission
        if (! Utility::canViewProfile($profile, $currentUserId)) {
            redirect_header('index.php', 3, _MD_ALUMNI_ERROR_NO_PERMISSION);
            exit();
        }

        // Increment views (if not viewing own profile)
        if ($profile->getVar('user_id') !== $currentUserId) {
            $profile->setVar('views', $profile->getVar('views') + 1);
            $profileHandler->insert($profile, true);
        }

        // Get profile skills via join table
        $skills = $skillHandler->getProfileSkills($profileId);

        $skillsArray = [];
        foreach ($skills as $skill) {
            $skillsArray[] = [
                'id'   => $skill->getVar('skill_id'),
                'name' => Utility::sanitizeHtml($skill->getVar('name')),
            ];
        }

        // Check connection status ('none' = no connection, matches template check)
        $connectionStatus = 'none';
        $canConnect = false;

        if ($currentUserId > 0 && $profile->getVar('user_id') !== $currentUserId) {
            // getConnection() checks both directions (requester/recipient)
            $connection = $connectionHandler->getConnection($currentUserId, $profile->getVar('user_id'));
            if ($connection !== null) {
                $connectionStatus = $connection->getVar('status');
            } else {
                $canConnect = true;
            }
        }

        // Set template
        $GLOBALS['xoopsOption']['template_main'] = 'db:alumni_profile.tpl';
        require_once XOOPS_ROOT_PATH . '/header.php';

        // Prepare profile data for template
        $profileData = Utility::formatProfileData($profile);

        // Merge in data that requires runtime context
        $isOwner = ($profile->getVar('user_id') === $currentUserId);
        $profileData['is_owner'] = $isOwner;
        $profileData['skills'] = array_column($skillsArray, 'name'); // flat list for badge display

        // Assign data to template
        $xoopsTpl->assign('profile', $profileData);
        $xoopsTpl->assign('connection_status', $connectionStatus);
        $xoopsTpl->assign('can_connect', $canConnect);
        $xoopsTpl->assign('is_owner', $isOwner);
        $xoopsTpl->assign('is_logged_in', $currentUserId > 0);
        $xoopsTpl->assign('can_edit', Utility::canEditProfile($profile, $currentUserId));

        // SEO Meta tags
        $xoopsTpl->assign('xoops_pagetitle', $profileData['full_name'] . ' - ' . $xoopsConfig['sitename']);

        require_once XOOPS_ROOT_PATH . '/footer.php';

        break;
    case 'edit':
        // Must be logged in
        if (! Utility::isUserLoggedIn()) {
            redirect_header('user.php', 3, _MD_ALUMNI_ERROR_LOGIN_REQUIRED);
            exit();
        }

        // Get profile by user ID or profile ID
        if ($profileId > 0) {
            $profile = $profileHandler->get($profileId);
        } else {
            // Get current user's profile
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('user_id', $currentUserId));
            $profiles = $profileHandler->getObjects($criteria);
            $profile = ! empty($profiles) ? $profiles[0] : $profileHandler->create();
        }

        // Check edit permission
        if (! Utility::canEditProfile($profile, $currentUserId)) {
            redirect_header('index.php', 3, _MD_ALUMNI_ERROR_NO_PERMISSION);
            exit();
        }

        // Set template
        $GLOBALS['xoopsOption']['template_main'] = 'db:alumni_profile_edit.tpl';
        require_once XOOPS_ROOT_PATH . '/header.php';

        // Get profile data
        $profileData = $profile->isNew() ? [] : Utility::formatProfileData($profile);

        // Get graduation years
        $graduationYears = Utility::getGraduationYears();

        // Get industries
        $industries = Utility::getIndustryOptions();

        // Get privacy options
        $privacyOptions = Utility::getPrivacyOptions();

        // Assign to template
        $xoopsTpl->assign('profile', $profileData);
        $xoopsTpl->assign('is_new', $profile->isNew());
        $xoopsTpl->assign('graduation_years', $graduationYears);
        $xoopsTpl->assign('industries', $industries);
        $xoopsTpl->assign('privacy_options', $privacyOptions);

        // SEO
        $xoopsTpl->assign('xoops_pagetitle', _MD_ALUMNI_EDIT_PROFILE . ' - ' . $xoopsConfig['sitename']);

        require_once XOOPS_ROOT_PATH . '/footer.php';

        break;
    case 'save':
        // Must be logged in
        if (! Utility::isUserLoggedIn()) {
            redirect_header('user.php', 3, _MD_ALUMNI_ERROR_LOGIN_REQUIRED);
            exit();
        }

        // CSRF check
        if (! $GLOBALS['xoopsSecurity']->check()) {
            redirect_header('profile.php?op=edit', 3, _MD_ALUMNI_ERROR_SECURITY);
            exit();
        }

        // Get profile
        $profileId = isset($_POST['profile_id']) ? (int) $_POST['profile_id'] : 0;

        if ($profileId > 0) {
            $profile = $profileHandler->get($profileId);
        } else {
            $profile = $profileHandler->create();
            $profile->setVar('user_id', $currentUserId);
            $profile->setVar('created', time());
        }

        // Check edit permission
        if (! Utility::canEditProfile($profile, $currentUserId)) {
            redirect_header('index.php', 3, _MD_ALUMNI_ERROR_NO_PERMISSION);
            exit();
        }

        // Get form data
        $profile->setVar('first_name', $_POST['first_name']);
        $profile->setVar('last_name', $_POST['last_name']);
        $profile->setVar('graduation_year', (int) $_POST['graduation_year']);
        $profile->setVar('degree', $_POST['degree']);
        $profile->setVar('major', $_POST['major']);
        $profile->setVar('department', $_POST['department']);
        $profile->setVar('current_company', $_POST['current_company']);
        $profile->setVar('current_position', $_POST['current_position']);
        $profile->setVar('industry', $_POST['industry']);
        $profile->setVar('location', $_POST['location']);
        $profile->setVar('city', $_POST['city']);
        $profile->setVar('country', $_POST['country']);
        $profile->setVar('bio', $_POST['bio']);
        $profile->setVar('linkedin_url', $_POST['linkedin_url']);
        $profile->setVar('twitter_url', $_POST['twitter_url']);
        $profile->setVar('facebook_url', $_POST['facebook_url']);
        $profile->setVar('website_url', $_POST['website_url']);
        $profile->setVar('email', $_POST['email']);
        $profile->setVar('phone', $_POST['phone']);
        $profile->setVar('privacy_profile', $_POST['privacy_profile']);
        $profile->setVar('privacy_email', $_POST['privacy_email']);
        $profile->setVar('privacy_phone', $_POST['privacy_phone']);
        $profile->setVar('allow_mentorship', isset($_POST['allow_mentorship']) ? 1 : 0);
        $profile->setVar('allow_networking', isset($_POST['allow_networking']) ? 1 : 0);
        $profile->setVar('updated', time());

        // Handle photo upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadDir = ALUMNI_UPLOAD_PATH . '/photos';

            // Create directory if it doesn't exist
            if (! is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $maxFileSize = 2097152; // 2MB

            $uploadResult = Utility::uploadFile(
                $_FILES['photo'],
                $uploadDir,
                $allowedExtensions,
                $maxFileSize
            );

            if ($uploadResult['success']) {
                // Delete old photo
                if (! $profile->isNew() && ! empty($profile->getVar('photo'))) {
                    $oldPhoto = $uploadDir . '/' . $profile->getVar('photo');
                    if (file_exists($oldPhoto)) {
                        unlink($oldPhoto);
                    }
                }
                $profile->setVar('photo', $uploadResult['filename']);
            }
        }

        // Save profile
        if ($profileHandler->insert($profile)) {
            redirect_header(
                'profile.php?id=' . $profile->getVar('profile_id'),
                2,
                _MD_ALUMNI_PROFILE_SAVED
            );
        } else {
            redirect_header('profile.php?op=edit', 3, _MD_ALUMNI_ERROR_SAVE_FAILED);
        }
        exit();

        break;
}
