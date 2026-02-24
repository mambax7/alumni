<?php
/**
 * Alumni Management System - Mentorship Management
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 */

use XoopsModules\Alumni\{Helper, Utility};

$GLOBALS['xoopsOption']['template_main'] = 'file:' . \dirname(__DIR__) . '/templates/admin/alumni_admin_mentorship.tpl';

require __DIR__ . '/admin_header.php';

xoops_cp_header();
Utility::addAdminAssets();

// Get handlers
$helper            = Helper::getInstance();
$mentorshipHandler = $helper->getHandler('mentorship');
$memberHandler     = xoops_getHandler('member');

// Get operation
$op           = isset($_REQUEST['op'])             ? $_REQUEST['op']               : 'list';
$mentorshipId = isset($_REQUEST['mentorship_id'])  ? (int)$_REQUEST['mentorship_id'] : 0;

switch ($op) {
    case 'list':
    default:
        // Display navigation
        $adminObject->displayNavigation(basename(__FILE__));
        $xoopsTpl->assign('admin_buttons', $adminObject->renderButton());

        // Get filters
        $filter_status = isset($_GET['status']) ? $_GET['status'] : '';
        $start         = isset($_GET['start'])  ? (int)$_GET['start'] : 0;
        $limit         = 20;

        // Build criteria
        $criteria = new CriteriaCompo();
        if (!empty($filter_status)) {
            $criteria->add(new Criteria('status', $filter_status));
        }
        $criteria->setSort('created');
        $criteria->setOrder('DESC');
        $criteria->setStart($start);
        $criteria->setLimit($limit);

        // Get mentorships + counts
        $mentorshipObjs  = $mentorshipHandler->getObjects($criteria);
        $mentorshipCount = $mentorshipHandler->getCount($criteria);

        // Statistics
        $stats = [
            'total'     => $mentorshipHandler->getCount(),
            'active'    => $mentorshipHandler->getCount(new Criteria('status', 'active')),
            'pending'   => $mentorshipHandler->getCount(new Criteria('status', 'pending')),
            'completed' => $mentorshipHandler->getCount(new Criteria('status', 'completed')),
        ];

        // Build mentorships array for template
        $statusLabels = [
            'active'    => defined('_MD_ALUMNI_MENTORSHIP_ACTIVE')    ? _MD_ALUMNI_MENTORSHIP_ACTIVE    : 'Active',
            'pending'   => defined('_MD_ALUMNI_MENTORSHIP_PENDING')   ? _MD_ALUMNI_MENTORSHIP_PENDING   : 'Pending',
            'completed' => defined('_MD_ALUMNI_MENTORSHIP_COMPLETED') ? _MD_ALUMNI_MENTORSHIP_COMPLETED : 'Completed',
            'declined'  => defined('_MD_ALUMNI_MENTORSHIP_DECLINED')  ? _MD_ALUMNI_MENTORSHIP_DECLINED  : 'Declined',
        ];
        $mentorshipsArr = [];
        foreach ($mentorshipObjs as $mentorship) {
            $mentor = $memberHandler->getUser($mentorship->getVar('mentor_id'));
            $mentee = $memberHandler->getUser($mentorship->getVar('mentee_id'));
            $status = $mentorship->getVar('status');
            $mentorshipsArr[] = [
                'id'           => $mentorship->getVar('mentorship_id'),
                'mentor'       => $mentor ? $mentor->getVar('uname') : _AM_ALUMNI_UNKNOWN,
                'mentee'       => $mentee ? $mentee->getVar('uname') : _AM_ALUMNI_UNKNOWN,
                'area'         => Utility::sanitizeHtml($mentorship->getVar('mentorship_area')),
                'status'       => $status,
                'status_label' => $statusLabels[$status] ?? $status,
                'created'      => formatTimestamp($mentorship->getVar('created'), 's'),
            ];
        }

        // Pagination
        $pagenavStr = '';
        if ($mentorshipCount > $limit) {
            require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $extra    = 'op=list' . (!empty($filter_status) ? '&status=' . urlencode($filter_status) : '');
            $pagenav  = new XoopsPageNav($mentorshipCount, $limit, $start, 'start', $extra);
            $pagenavStr = $pagenav->renderNav();
        }

        $xoopsTpl->assign('mentorships',   $mentorshipsArr);
        $xoopsTpl->assign('stats',         $stats);
        $xoopsTpl->assign('filter_status', $filter_status);
        $xoopsTpl->assign('pagenav',       $pagenavStr);
        break;

    case 'view':
        $adminObject->displayNavigation(basename(__FILE__));

        if ($mentorshipId > 0) {
            $mentorship = $mentorshipHandler->get($mentorshipId);
            if (!$mentorship) {
                redirect_header('mentorship.php', 3, _AM_ALUMNI_ERROR_NOT_FOUND);
            }

            $mentor = $memberHandler->getUser($mentorship->getVar('mentor_id'));
            $mentee = $memberHandler->getUser($mentorship->getVar('mentee_id'));

            echo '<div class="xm-view-card">';
            echo '<h3>' . _AM_ALUMNI_MENTORSHIP_VIEW . '</h3>';
            echo '<dl class="xm-dl">';
            echo '<dt>' . _MD_ALUMNI_MENTOR . '</dt><dd>' . ($mentor ? $mentor->getVar('uname') : _AM_ALUMNI_UNKNOWN) . '</dd>';
            echo '<dt>' . _MD_ALUMNI_MENTEE . '</dt><dd>' . ($mentee ? $mentee->getVar('uname') : _AM_ALUMNI_UNKNOWN) . '</dd>';
            echo '<dt>' . _MD_ALUMNI_MENTORSHIP_AREA . '</dt><dd>' . Utility::sanitizeHtml($mentorship->getVar('mentorship_area')) . '</dd>';
            echo '<dt>' . _AM_ALUMNI_FORM_STATUS . '</dt><dd>' . $mentorship->getVar('status') . '</dd>';
            echo '<dt>' . _MD_ALUMNI_MESSAGE . '</dt><dd>' . Utility::sanitizeHtml($mentorship->getVar('message')) . '</dd>';
            echo '<dt>' . _MD_ALUMNI_START_DATE . '</dt><dd>' . ($mentorship->getVar('start_date') ? formatTimestamp($mentorship->getVar('start_date'), 's') : '-') . '</dd>';
            echo '<dt>' . _MD_ALUMNI_END_DATE . '</dt><dd>' . ($mentorship->getVar('end_date')   ? formatTimestamp($mentorship->getVar('end_date'),   's') : '-') . '</dd>';
            echo '<dt>' . _AM_ALUMNI_TH_DATE_ADDED . '</dt><dd>' . formatTimestamp($mentorship->getVar('created'), 's') . '</dd>';
            echo '</dl>';
            echo '<a href="mentorship.php" class="xm-btn xm-btn--secondary">' . _AM_ALUMNI_ACTION_BACK . '</a>';
            echo '</div>';
        }
        break;

    case 'activate':
        // CSRF check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('mentorship.php', 3, _AM_ALUMNI_ERROR_GENERAL);
        }

        if ($mentorshipId > 0) {
            $mentorship = $mentorshipHandler->get($mentorshipId);
            if ($mentorship) {
                $mentorship->setVar('status',     'active');
                $mentorship->setVar('start_date', time());
                if ($mentorshipHandler->insert($mentorship)) {
                    redirect_header('mentorship.php', 3, _AM_ALUMNI_SUCCESS_ACTIVATE);
                }
            }
        }
        redirect_header('mentorship.php', 3, _AM_ALUMNI_ERROR_GENERAL);
        break;

    case 'complete':
        // CSRF check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('mentorship.php', 3, _AM_ALUMNI_ERROR_GENERAL);
        }

        if ($mentorshipId > 0) {
            $mentorship = $mentorshipHandler->get($mentorshipId);
            if ($mentorship) {
                $mentorship->setVar('status',   'completed');
                $mentorship->setVar('end_date', time());
                if ($mentorshipHandler->insert($mentorship)) {
                    redirect_header('mentorship.php', 3, _AM_ALUMNI_SUCCESS_MENTORSHIP_UPDATED);
                }
            }
        }
        redirect_header('mentorship.php', 3, _AM_ALUMNI_ERROR_GENERAL);
        break;

    case 'delete':
        // CSRF check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('mentorship.php', 3, _AM_ALUMNI_ERROR_GENERAL);
        }

        if ($mentorshipId > 0) {
            if ($mentorshipHandler->delete($mentorshipHandler->get($mentorshipId))) {
                redirect_header('mentorship.php', 3, _AM_ALUMNI_SUCCESS_DELETE);
            } else {
                redirect_header('mentorship.php', 3, _AM_ALUMNI_ERROR_DELETE);
            }
        }
        redirect_header('mentorship.php', 3, _AM_ALUMNI_ERROR_INVALID_ID);
        break;
}

require __DIR__ . '/admin_footer.php';
