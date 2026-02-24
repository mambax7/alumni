<?php
/**
 * Alumni Management System - Connection Management
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 */

use XoopsModules\Alumni\{Helper, Utility};

$GLOBALS['xoopsOption']['template_main'] = 'file:' . \dirname(__DIR__) . '/templates/admin/alumni_admin_connections.tpl';

require __DIR__ . '/admin_header.php';

xoops_cp_header();
Utility::addAdminAssets();

// Get handlers
$helper            = Helper::getInstance();
$connectionHandler = $helper->getHandler('connection');
$memberHandler     = xoops_getHandler('member');

// Get operation
$op           = isset($_REQUEST['op'])            ? $_REQUEST['op']              : 'list';
$connectionId = isset($_REQUEST['connection_id']) ? (int)$_REQUEST['connection_id'] : 0;

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

        // Get connections + counts
        $connectionObjs   = $connectionHandler->getObjects($criteria);
        $connectionCount  = $connectionHandler->getCount($criteria);

        // Statistics
        $stats = [
            'total'    => $connectionHandler->getCount(),
            'accepted' => $connectionHandler->getCount(new Criteria('status', 'accepted')),
            'pending'  => $connectionHandler->getCount(new Criteria('status', 'pending')),
            'declined' => $connectionHandler->getCount(new Criteria('status', 'declined')),
        ];

        // Build connections array for template
        $statusLabels = [
            'accepted' => defined('_MD_ALUMNI_CONNECTION_ACCEPTED') ? _MD_ALUMNI_CONNECTION_ACCEPTED : 'Accepted',
            'pending'  => defined('_MD_ALUMNI_CONNECTION_PENDING')  ? _MD_ALUMNI_CONNECTION_PENDING  : 'Pending',
            'declined' => defined('_MD_ALUMNI_CONNECTION_DECLINED') ? _MD_ALUMNI_CONNECTION_DECLINED : 'Declined',
            'blocked'  => defined('_MD_ALUMNI_CONNECTION_BLOCKED')  ? _MD_ALUMNI_CONNECTION_BLOCKED  : 'Blocked',
        ];
        $connectionsArr = [];
        foreach ($connectionObjs as $connection) {
            $requester = $memberHandler->getUser($connection->getVar('requester_id'));
            $recipient = $memberHandler->getUser($connection->getVar('recipient_id'));
            $status    = $connection->getVar('status');
            $connectionsArr[] = [
                'id'           => $connection->getVar('connection_id'),
                'requester'    => $requester ? $requester->getVar('uname') : _AM_ALUMNI_UNKNOWN,
                'recipient'    => $recipient ? $recipient->getVar('uname') : _AM_ALUMNI_UNKNOWN,
                'status'       => $status,
                'status_label' => $statusLabels[$status] ?? $status,
                'created'      => formatTimestamp($connection->getVar('created'), 's'),
            ];
        }

        // Pagination
        $pagenavStr = '';
        if ($connectionCount > $limit) {
            require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $extra    = 'op=list' . (!empty($filter_status) ? '&status=' . urlencode($filter_status) : '');
            $pagenav  = new XoopsPageNav($connectionCount, $limit, $start, 'start', $extra);
            $pagenavStr = $pagenav->renderNav();
        }

        $xoopsTpl->assign('connections',   $connectionsArr);
        $xoopsTpl->assign('stats',         $stats);
        $xoopsTpl->assign('filter_status', $filter_status);
        $xoopsTpl->assign('pagenav',       $pagenavStr);
        break;

    case 'view':
        $adminObject->displayNavigation(basename(__FILE__));

        if ($connectionId > 0) {
            $connection = $connectionHandler->get($connectionId);
            if (!$connection) {
                redirect_header('connections.php', 3, _AM_ALUMNI_ERROR_NOT_FOUND);
            }

            $requester = $memberHandler->getUser($connection->getVar('requester_id'));
            $recipient = $memberHandler->getUser($connection->getVar('recipient_id'));

            echo '<div class="xm-view-card">';
            echo '<h3>' . _AM_ALUMNI_CONNECTION_VIEW . '</h3>';
            echo '<dl class="xm-dl">';
            echo '<dt>' . _MD_ALUMNI_REQUESTER . '</dt><dd>' . ($requester ? $requester->getVar('uname') : _AM_ALUMNI_UNKNOWN) . '</dd>';
            echo '<dt>' . _MD_ALUMNI_RECIPIENT . '</dt><dd>' . ($recipient ? $recipient->getVar('uname') : _AM_ALUMNI_UNKNOWN) . '</dd>';
            echo '<dt>' . _AM_ALUMNI_FORM_STATUS . '</dt><dd>' . $connection->getVar('status') . '</dd>';
            echo '<dt>' . _MD_ALUMNI_MESSAGE . '</dt><dd>' . Utility::sanitizeHtml($connection->getVar('message')) . '</dd>';
            echo '<dt>' . _AM_ALUMNI_TH_DATE_ADDED . '</dt><dd>' . formatTimestamp($connection->getVar('created'), 's') . '</dd>';
            echo '<dt>' . _AM_ALUMNI_TH_DATE_UPDATED . '</dt><dd>' . formatTimestamp($connection->getVar('updated'), 's') . '</dd>';
            echo '</dl>';
            echo '<a href="connections.php" class="xm-btn xm-btn--secondary">' . _AM_ALUMNI_ACTION_BACK . '</a>';
            echo '</div>';
        }
        break;

    case 'delete':
        // CSRF check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('connections.php', 3, _AM_ALUMNI_ERROR_GENERAL);
        }

        if ($connectionId > 0) {
            if ($connectionHandler->delete($connectionHandler->get($connectionId))) {
                redirect_header('connections.php', 3, _AM_ALUMNI_SUCCESS_CONNECTION_DELETED);
            } else {
                redirect_header('connections.php', 3, _AM_ALUMNI_ERROR_DELETE);
            }
        }
        redirect_header('connections.php', 3, _AM_ALUMNI_ERROR_INVALID_ID);
        break;
}

require __DIR__ . '/admin_footer.php';
