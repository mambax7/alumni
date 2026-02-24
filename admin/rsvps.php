<?php
/**
 * Alumni Management System - RSVP Management
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 */

use XoopsModules\Alumni\{Helper, Utility};

$GLOBALS['xoopsOption']['template_main'] = 'file:' . \dirname(__DIR__) . '/templates/admin/alumni_admin_rsvps.tpl';

require __DIR__ . '/admin_header.php';

xoops_cp_header();
Utility::addAdminAssets();

// Get handlers
$helper         = Helper::getInstance();
$rsvpHandler    = $helper->getHandler('rsvp');
$eventHandler   = $helper->getHandler('event');
$memberHandler  = xoops_getHandler('member');

// Get operation
$op     = isset($_REQUEST['op'])      ? $_REQUEST['op']          : 'list';
$rsvpId = isset($_REQUEST['rsvp_id']) ? (int)$_REQUEST['rsvp_id'] : 0;

// Assign safe defaults so the template never sees undefined variables
$xoopsTpl->assign('rsvps',              []);
$xoopsTpl->assign('events',             []);
$xoopsTpl->assign('event_summary',      null);
$xoopsTpl->assign('filter_event_id',    0);
$xoopsTpl->assign('filter_rsvp_status', '');
$xoopsTpl->assign('pagenav',            '');

switch ($op) {
    case 'list':
    default:
        // Display navigation
        $adminObject->displayNavigation(basename(__FILE__));
        $xoopsTpl->assign('admin_buttons', $adminObject->renderButton());

        // Get filters
        $filter_event_id    = isset($_GET['event_id'])    ? (int)$_GET['event_id']    : 0;
        $filter_rsvp_status = isset($_GET['rsvp_status']) ? $_GET['rsvp_status']      : '';
        $start              = isset($_GET['start'])       ? (int)$_GET['start']       : 0;
        $limit              = 20;

        // Build criteria
        $criteria = new CriteriaCompo();
        if ($filter_event_id > 0) {
            $criteria->add(new Criteria('event_id', $filter_event_id));
        }
        if (!empty($filter_rsvp_status)) {
            $criteria->add(new Criteria('status', $filter_rsvp_status));
        }
        $criteria->setSort('created');
        $criteria->setOrder('DESC');
        $criteria->setStart($start);
        $criteria->setLimit($limit);

        // Get RSVPs
        $rsvpObjs  = $rsvpHandler->getObjects($criteria);
        $rsvpCount = $rsvpHandler->getCount($criteria);

        // Get events for filter dropdown
        $eventsObjs = $eventHandler->getObjects(null, true);
        $eventsArr  = [];
        foreach ($eventsObjs as $evt) {
            $eventsArr[] = [
                'id'    => $evt->getVar('event_id'),
                'title' => Utility::sanitizeHtml($evt->getVar('title')),
            ];
        }

        // Event summary block
        $eventSummary = null;
        if ($filter_event_id > 0) {
            $summaryEvent = $eventHandler->get($filter_event_id);
            if ($summaryEvent) {
                $attCriteria = new CriteriaCompo();
                $attCriteria->add(new Criteria('event_id',    $filter_event_id));
                $attCriteria->add(new Criteria('status', 'attending'));
                $attendingCount = $rsvpHandler->getCount($attCriteria);
                $eventSummary = [
                    'title'          => Utility::sanitizeHtml($summaryEvent->getVar('title')),
                    'attending_count' => $attendingCount,
                ];
            }
        }

        // Build RSVP rows for template
        $statusLabels = [
            'attending'     => defined('_MD_ALUMNI_RSVP_ATTENDING')     ? _MD_ALUMNI_RSVP_ATTENDING     : 'Attending',
            'not_attending' => defined('_MD_ALUMNI_RSVP_NOT_ATTENDING') ? _MD_ALUMNI_RSVP_NOT_ATTENDING : 'Not Attending',
            'maybe'         => defined('_MD_ALUMNI_RSVP_MAYBE')         ? _MD_ALUMNI_RSVP_MAYBE         : 'Maybe',
        ];
        $rsvpsArr = [];
        foreach ($rsvpObjs as $rsvp) {
            $evObj  = $eventHandler->get($rsvp->getVar('event_id'));
            $user   = $memberHandler->getUser($rsvp->getVar('user_id'));
            $status = (string)$rsvp->getVar('status');
            $rsvpsArr[] = [
                'id'          => $rsvp->getVar('rsvp_id'),
                'event_title' => $evObj ? Utility::sanitizeHtml($evObj->getVar('title')) : '-',
                'username'    => $user  ? $user->getVar('uname') : _AM_ALUMNI_UNKNOWN,
                'status'      => $status,
                'status_label'=> $statusLabels[$status] ?? $status,
                'guests'      => (int)$rsvp->getVar('guests'),
                'notes_short' => Utility::sanitizeHtml(Utility::truncate((string)$rsvp->getVar('comment'), 50)),
                'created'     => formatTimestamp($rsvp->getVar('created'), 's'),
            ];
        }

        // Pagination
        $pagenavStr = '';
        if ($rsvpCount > $limit) {
            require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $extra    = 'op=list'
                . ($filter_event_id > 0         ? '&event_id='    . $filter_event_id                : '')
                . (!empty($filter_rsvp_status)  ? '&rsvp_status=' . urlencode($filter_rsvp_status)  : '');
            $pagenav  = new XoopsPageNav($rsvpCount, $limit, $start, 'start', $extra);
            $pagenavStr = $pagenav->renderNav();
        }

        $xoopsTpl->assign('rsvps',              $rsvpsArr);
        $xoopsTpl->assign('events',             $eventsArr);
        $xoopsTpl->assign('event_summary',      $eventSummary);
        $xoopsTpl->assign('filter_event_id',    $filter_event_id);
        $xoopsTpl->assign('filter_rsvp_status', $filter_rsvp_status);
        $xoopsTpl->assign('pagenav',            $pagenavStr);
        break;

    case 'view':
        $adminObject->displayNavigation(basename(__FILE__));

        if ($rsvpId > 0) {
            $rsvp = $rsvpHandler->get($rsvpId);
            if (!$rsvp) {
                redirect_header('rsvps.php', 3, _AM_ALUMNI_ERROR_NOT_FOUND);
            }

            $evObj = $eventHandler->get($rsvp->getVar('event_id'));
            $user  = $memberHandler->getUser($rsvp->getVar('user_id'));

            echo '<div class="xm-view-card">';
            echo '<h3>' . _AM_ALUMNI_RSVP_VIEW . '</h3>';
            echo '<dl class="xm-dl">';
            echo '<dt>' . _AM_ALUMNI_TH_EVENT_NAME . '</dt><dd>' . ($evObj ? Utility::sanitizeHtml($evObj->getVar('title')) : '-') . '</dd>';
            echo '<dt>' . _AM_ALUMNI_TH_NAME . '</dt><dd>' . ($user ? $user->getVar('uname') : _AM_ALUMNI_UNKNOWN) . '</dd>';
            echo '<dt>' . _AM_ALUMNI_FORM_STATUS . '</dt><dd>' . $rsvp->getVar('status') . '</dd>';
            echo '<dt>' . _MD_ALUMNI_GUESTS . '</dt><dd>' . $rsvp->getVar('guests') . '</dd>';
            echo '<dt>' . _MD_ALUMNI_NOTES . '</dt><dd>' . Utility::sanitizeHtml((string)$rsvp->getVar('comment')) . '</dd>';
            echo '<dt>' . _AM_ALUMNI_TH_DATE_ADDED . '</dt><dd>' . formatTimestamp($rsvp->getVar('created'), 's') . '</dd>';
            echo '</dl>';
            echo '<a href="rsvps.php" class="xm-btn xm-btn--secondary">' . _AM_ALUMNI_ACTION_BACK . '</a>';
            echo '</div>';
        }
        break;

    case 'delete':
        // CSRF check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('rsvps.php', 3, _AM_ALUMNI_ERROR_GENERAL);
        }

        if ($rsvpId > 0) {
            $rsvp = $rsvpHandler->get($rsvpId);
            if ($rsvp && $rsvpHandler->delete($rsvp)) {
                // Update event RSVP count
                $evObj = $eventHandler->get($rsvp->getVar('event_id'));
                if ($evObj) {
                    $evObj->setVar('rsvp_count', max(0, $evObj->getVar('rsvp_count') - 1));
                    $eventHandler->insert($evObj);
                }
                redirect_header('rsvps.php', 3, _AM_ALUMNI_SUCCESS_RSVP_DELETED);
            } else {
                redirect_header('rsvps.php', 3, _AM_ALUMNI_ERROR_DELETE);
            }
        }
        redirect_header('rsvps.php', 3, _AM_ALUMNI_ERROR_INVALID_ID);
        break;

    case 'export':
        $exportEventId = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;
        if ($exportEventId > 0) {
            $evObj = $eventHandler->get($exportEventId);
            if (!$evObj) {
                redirect_header('rsvps.php', 3, _AM_ALUMNI_ERROR_NOT_FOUND);
            }

            // Get all attending RSVPs for this event
            $expCriteria = new CriteriaCompo();
            $expCriteria->add(new Criteria('event_id',    $exportEventId));
            $expCriteria->add(new Criteria('status', 'attending'));
            $exportRsvps = $rsvpHandler->getObjects($expCriteria);

            // Generate CSV
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="event_' . $exportEventId . '_attendees.csv"');

            $output = fopen('php://output', 'w');
            fputcsv($output, ['Name', 'Email', 'Guests', 'Notes', 'RSVP Date']);

            foreach ($exportRsvps as $rsvp) {
                $user = $memberHandler->getUser($rsvp->getVar('user_id'));
                fputcsv($output, [
                    $user ? $user->getVar('uname') : '',
                    $user ? $user->getVar('email') : '',
                    $rsvp->getVar('guests'),
                    (string)$rsvp->getVar('comment'),
                    formatTimestamp($rsvp->getVar('created'), 's'),
                ]);
            }

            fclose($output);
            exit;
        }
        redirect_header('rsvps.php', 3, _AM_ALUMNI_ERROR_INVALID_ID);
        break;
}

require __DIR__ . '/admin_footer.php';
