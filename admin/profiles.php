<?php

declare(strict_types=1);
/**
 * Alumni Management System - Profile Management.
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 */

use XoopsModules\Alumni\Helper;
use XoopsModules\Alumni\Utility;

$GLOBALS['xoopsOption']['template_main'] = 'file:' . dirname(__DIR__) . '/templates/admin/alumni_admin_profiles.tpl';

require __DIR__ . '/admin_header.php';

xoops_cp_header();
Utility::addAdminAssets();

// Get handler
$helper = Helper::getInstance();
$profileHandler = $helper->getHandler('profile');
$memberHandler = xoops_getHandler('member');

// Get operation
$op = $_REQUEST['op'] ?? 'list';
$profileId = isset($_REQUEST['profile_id']) ? (int) $_REQUEST['profile_id'] : 0;

switch ($op) {
    case 'list':
    default:
        // Display navigation
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_ALUMNI_PROFILE_ADD, 'profiles.php?op=edit', 'add');
        $xoopsTpl->assign('admin_buttons', $adminObject->renderButton());

        // Get filters
        $filter_status = $_GET['status'] ?? '';
        $filter_year = isset($_GET['year']) ? (int) $_GET['year'] : 0;
        $filter_featured = isset($_GET['featured']) ? (int) $_GET['featured'] : -1;
        $start = isset($_GET['start']) ? (int) $_GET['start'] : 0;
        $limit = 20;

        // Build criteria
        $criteria = new CriteriaCompo();
        if (! empty($filter_status)) {
            $criteria->add(new Criteria('status', $filter_status));
        }
        if ($filter_year > 0) {
            $criteria->add(new Criteria('graduation_year', $filter_year));
        }
        if ($filter_featured >= 0) {
            $criteria->add(new Criteria('featured', $filter_featured));
        }
        $criteria->setSort('created');
        $criteria->setOrder('DESC');
        $criteria->setStart($start);
        $criteria->setLimit($limit);

        // Get profiles
        $profileObjs = $profileHandler->getObjects($criteria);
        $profileCount = $profileHandler->getCount($criteria);

        // Build profiles array for template
        $profilesArr = [];
        $statusLabels = [
            'active'   => defined('_MD_ALUMNI_STATUS_ACTIVE') ? _MD_ALUMNI_STATUS_ACTIVE : 'Active',
            'pending'  => defined('_MD_ALUMNI_STATUS_PENDING') ? _MD_ALUMNI_STATUS_PENDING : 'Pending',
            'inactive' => defined('_MD_ALUMNI_STATUS_INACTIVE') ? _MD_ALUMNI_STATUS_INACTIVE : 'Inactive',
        ];
        foreach ($profileObjs as $profile) {
            $status = $profile->getVar('status');
            $profilesArr[] = [
                'id'              => $profile->getVar('profile_id'),
                'name'            => Utility::sanitizeHtml($profile->getVar('first_name') . ' ' . $profile->getVar('last_name')),
                'graduation_year' => $profile->getVar('graduation_year'),
                'company'         => Utility::sanitizeHtml($profile->getVar('current_company')),
                'location'        => Utility::sanitizeHtml($profile->getVar('location')),
                'status'          => $status,
                'status_label'    => $statusLabels[$status] ?? $status,
                'featured'        => (int) $profile->getVar('featured'),
                'created'         => formatTimestamp($profile->getVar('created'), 's'),
            ];
        }

        // Pagination
        $pagenavStr = '';
        if ($profileCount > $limit) {
            require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $extra = 'op=list'
                . (! empty($filter_status) ? '&status=' . urlencode($filter_status) : '')
                . ($filter_year > 0 ? '&year=' . $filter_year : '')
                . ($filter_featured >= 0 ? '&featured=' . $filter_featured : '');
            $pagenav = new XoopsPageNav($profileCount, $limit, $start, 'start', $extra);
            $pagenavStr = $pagenav->renderNav();
        }

        $xoopsTpl->assign('profiles', $profilesArr);
        $xoopsTpl->assign('filter_status', $filter_status);
        $xoopsTpl->assign('filter_featured', $filter_featured);
        $xoopsTpl->assign('pagenav', $pagenavStr);

        break;
    case 'edit':
        $adminObject->displayNavigation(basename(__FILE__));

        // Get profile if editing
        if ($profileId > 0) {
            $profile = $profileHandler->get($profileId);
            if (! $profile) {
                redirect_header('profiles.php', 3, _AM_ALUMNI_ERROR_NOT_FOUND);
            }
        } else {
            $profile = $profileHandler->create();
        }

        // Create form
        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $form = new XoopsThemeForm(
            $profileId > 0 ? _AM_ALUMNI_PROFILE_EDIT : _AM_ALUMNI_PROFILE_ADD,
            'profile_form',
            'profiles.php',
            'post',
            true
        );

        $form->addElement(new XoopsFormText(_AM_ALUMNI_FORM_NAME . ' (First)', 'first_name', 50, 100, $profile->getVar('first_name', 'e')), true);
        $form->addElement(new XoopsFormText(_AM_ALUMNI_FORM_NAME . ' (Last)', 'last_name', 50, 100, $profile->getVar('last_name', 'e')), true);

        // Graduation year
        $yearSelect = new XoopsFormSelect(_AM_ALUMNI_TH_GRADUATION_YEAR, 'graduation_year', $profile->getVar('graduation_year'));
        $yearSelect->addOptionArray(Utility::getGraduationYears());
        $form->addElement($yearSelect, true);

        $form->addElement(new XoopsFormText(_MD_ALUMNI_DEGREE, 'degree', 50, 100, $profile->getVar('degree', 'e')));
        $form->addElement(new XoopsFormText(_MD_ALUMNI_MAJOR, 'major', 50, 100, $profile->getVar('major', 'e')));
        $form->addElement(new XoopsFormText(_MD_ALUMNI_CURRENT_COMPANY, 'current_company', 50, 100, $profile->getVar('current_company', 'e')));
        $form->addElement(new XoopsFormText(_MD_ALUMNI_CURRENT_POSITION, 'current_position', 50, 100, $profile->getVar('current_position', 'e')));
        $form->addElement(new XoopsFormText(_AM_ALUMNI_FORM_LOCATION, 'location', 50, 100, $profile->getVar('location', 'e')));

        // Status
        $statusSelect = new XoopsFormSelect(_AM_ALUMNI_FORM_STATUS, 'status', $profile->getVar('status'));
        $statusSelect->addOptionArray([
            'active'   => _MD_ALUMNI_STATUS_ACTIVE,
            'pending'  => _MD_ALUMNI_STATUS_PENDING,
            'inactive' => _MD_ALUMNI_STATUS_INACTIVE,
        ]);
        $form->addElement($statusSelect);

        $form->addElement(new XoopsFormRadioYN(_AM_ALUMNI_FORM_FEATURED, 'featured', $profile->getVar('featured')));

        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormHidden('profile_id', $profileId));
        $form->addElement(new XoopsFormButton('', 'submit', _AM_ALUMNI_ACTION_SAVE, 'submit'));

        echo $form->render();

        break;
    case 'save':
        // CSRF check
        if (! $GLOBALS['xoopsSecurity']->check()) {
            redirect_header('profiles.php', 3, _AM_ALUMNI_ERROR_GENERAL);
        }

        // Get profile
        if ($profileId > 0) {
            $profile = $profileHandler->get($profileId);
            if (! $profile) {
                redirect_header('profiles.php', 3, _AM_ALUMNI_ERROR_NOT_FOUND);
            }
        } else {
            $profile = $profileHandler->create();
            $profile->setVar('created', time());
            $profile->setVar('user_id', $GLOBALS['xoopsUser']->uid());
        }

        // Set values
        $profile->setVar('first_name', $_POST['first_name']);
        $profile->setVar('last_name', $_POST['last_name']);
        $profile->setVar('graduation_year', (int) $_POST['graduation_year']);
        $profile->setVar('degree', $_POST['degree']);
        $profile->setVar('major', $_POST['major']);
        $profile->setVar('current_company', $_POST['current_company']);
        $profile->setVar('current_position', $_POST['current_position']);
        $profile->setVar('location', $_POST['location']);
        $profile->setVar('status', $_POST['status']);
        $profile->setVar('featured', (int) $_POST['featured']);
        $profile->setVar('updated', time());

        // Save
        if ($profileHandler->insert($profile)) {
            redirect_header('profiles.php', 3, $profileId > 0 ? _AM_ALUMNI_SUCCESS_PROFILE_UPDATED : _AM_ALUMNI_SUCCESS_PROFILE_ADDED);
        } else {
            redirect_header('profiles.php', 3, _AM_ALUMNI_ERROR_SAVE);
        }

        break;
    case 'delete':
        // CSRF check
        if (! $GLOBALS['xoopsSecurity']->check()) {
            redirect_header('profiles.php', 3, _AM_ALUMNI_ERROR_GENERAL);
        }

        if ($profileId > 0) {
            if ($profileHandler->delete($profileHandler->get($profileId))) {
                redirect_header('profiles.php', 3, _AM_ALUMNI_SUCCESS_PROFILE_DELETED);
            } else {
                redirect_header('profiles.php', 3, _AM_ALUMNI_ERROR_DELETE);
            }
        }
        redirect_header('profiles.php', 3, _AM_ALUMNI_ERROR_INVALID_ID);

        break;
    case 'approve':
        // CSRF check
        if (! $GLOBALS['xoopsSecurity']->check()) {
            redirect_header('profiles.php', 3, _AM_ALUMNI_ERROR_GENERAL);
        }

        if ($profileId > 0) {
            $profile = $profileHandler->get($profileId);
            if ($profile) {
                $profile->setVar('status', 'active');
                if ($profileHandler->insert($profile)) {
                    redirect_header('profiles.php', 3, _AM_ALUMNI_SUCCESS_PROFILE_APPROVED);
                }
            }
        }
        redirect_header('profiles.php', 3, _AM_ALUMNI_ERROR_GENERAL);

        break;
    case 'feature':
        // CSRF check
        if (! $GLOBALS['xoopsSecurity']->check()) {
            redirect_header('profiles.php', 3, _AM_ALUMNI_ERROR_GENERAL);
        }

        if ($profileId > 0) {
            $profile = $profileHandler->get($profileId);
            if ($profile) {
                $profile->setVar('featured', $profile->getVar('featured') ? 0 : 1);
                if ($profileHandler->insert($profile)) {
                    redirect_header('profiles.php', 3, _AM_ALUMNI_SUCCESS_FEATURE);
                }
            }
        }
        redirect_header('profiles.php', 3, _AM_ALUMNI_ERROR_GENERAL);

        break;
}

require __DIR__ . '/admin_footer.php';
