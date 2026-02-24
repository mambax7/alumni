<?php

declare(strict_types=1);
/**
 * Alumni Management System - Event Management.
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 */

use XoopsModules\Alumni\Helper;
use XoopsModules\Alumni\Utility;

$GLOBALS['xoopsOption']['template_main'] = 'file:' . dirname(__DIR__) . '/templates/admin/alumni_admin_events.tpl';

require __DIR__ . '/admin_header.php';

xoops_cp_header();
Utility::addAdminAssets();

// Get handlers
$helper = Helper::getInstance();
$eventHandler = $helper->getHandler('event');
$categoryHandler = $helper->getHandler('category');

// Get operation
$op = $_REQUEST['op'] ?? 'list';
$eventId = isset($_REQUEST['event_id']) ? (int) $_REQUEST['event_id'] : 0;

switch ($op) {
    case 'list':
    default:
        // Display navigation
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_ALUMNI_EVENT_ADD, 'events.php?op=edit', 'add');
        $xoopsTpl->assign('admin_buttons', $adminObject->renderButton());

        // Get filters
        $filter_status = $_GET['status'] ?? '';
        $filter_category = isset($_GET['category_id']) ? (int) $_GET['category_id'] : 0;
        $filter_featured = isset($_GET['featured']) ? (int) $_GET['featured'] : -1;
        $start = isset($_GET['start']) ? (int) $_GET['start'] : 0;
        $limit = 20;

        // Build criteria
        $criteria = new CriteriaCompo();
        if (! empty($filter_status)) {
            $criteria->add(new Criteria('status', $filter_status));
        }
        if ($filter_category > 0) {
            $criteria->add(new Criteria('category_id', $filter_category));
        }
        if ($filter_featured >= 0) {
            $criteria->add(new Criteria('featured', $filter_featured));
        }
        $criteria->setSort('start_date');
        $criteria->setOrder('DESC');
        $criteria->setStart($start);
        $criteria->setLimit($limit);

        // Get events
        $eventsObjs = $eventHandler->getObjects($criteria);
        $eventCount = $eventHandler->getCount($criteria);

        // Get categories for filter dropdown
        $catsObjs = $categoryHandler->getObjects(null, true);
        $catsArr = [];
        foreach ($catsObjs as $cat) {
            $catsArr[] = [
                'id'   => $cat->getVar('category_id'),
                'name' => Utility::sanitizeHtml($cat->getVar('name')),
            ];
        }

        // Build events array for template
        $eventsArr = [];
        foreach ($eventsObjs as $event) {
            $category = $categoryHandler->get($event->getVar('category_id'));
            $status = $event->getVar('status');
            $statusLabels = [
                'published' => defined('_MD_ALUMNI_STATUS_PUBLISHED') ? _MD_ALUMNI_STATUS_PUBLISHED : 'Published',
                'draft'     => defined('_MD_ALUMNI_STATUS_DRAFT') ? _MD_ALUMNI_STATUS_DRAFT : 'Draft',
                'cancelled' => defined('_MD_ALUMNI_STATUS_CANCELLED') ? _MD_ALUMNI_STATUS_CANCELLED : 'Cancelled',
            ];
            $eventsArr[] = [
                'id'            => $event->getVar('event_id'),
                'title'         => Utility::sanitizeHtml($event->getVar('title')),
                'category_name' => $category ? Utility::sanitizeHtml($category->getVar('name')) : '-',
                'start_date'    => formatTimestamp($event->getVar('start_date'), 's'),
                'location'      => Utility::sanitizeHtml($event->getVar('location')),
                'rsvp_count'    => (int) $event->getVar('rsvp_count'),
                'status'        => $status,
                'status_label'  => $statusLabels[$status] ?? $status,
                'featured'      => (int) $event->getVar('featured'),
            ];
        }

        // Pagination
        $pagenavStr = '';
        if ($eventCount > $limit) {
            require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $extra = 'op=list'
                . (! empty($filter_status) ? '&status=' . urlencode($filter_status) : '')
                . ($filter_category > 0 ? '&category_id=' . $filter_category : '');
            $pagenav = new XoopsPageNav($eventCount, $limit, $start, 'start', $extra);
            $pagenavStr = $pagenav->renderNav();
        }

        $xoopsTpl->assign('events', $eventsArr);
        $xoopsTpl->assign('categories', $catsArr);
        $xoopsTpl->assign('filter_status', $filter_status);
        $xoopsTpl->assign('filter_category_id', $filter_category);
        $xoopsTpl->assign('pagenav', $pagenavStr);

        break;
    case 'edit':
        $adminObject->displayNavigation(basename(__FILE__));

        // Get event if editing
        if ($eventId > 0) {
            $event = $eventHandler->get($eventId);
            if (! $event) {
                redirect_header('events.php', 3, _AM_ALUMNI_ERROR_NOT_FOUND);
            }
        } else {
            $event = $eventHandler->create();
        }

        // Create form
        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $form = new XoopsThemeForm(
            $eventId > 0 ? _AM_ALUMNI_EVENT_EDIT : _AM_ALUMNI_EVENT_ADD,
            'event_form',
            'events.php',
            'post',
            true
        );

        $form->addElement(new XoopsFormText(_AM_ALUMNI_FORM_TITLE, 'title', 50, 200, $event->getVar('title', 'e')), true);
        $form->addElement(new XoopsFormTextArea(_AM_ALUMNI_FORM_DESCRIPTION, 'description', $event->getVar('description', 'e'), 5, 60));

        // Category
        $categorySelect = new XoopsFormSelect(_AM_ALUMNI_FORM_CATEGORY, 'category_id', $event->getVar('category_id'));
        $categories = $categoryHandler->getObjects(null, true);
        $categoryOptions = [0 => _NONE];
        foreach ($categories as $cat) {
            $categoryOptions[$cat->getVar('category_id')] = $cat->getVar('name');
        }
        $categorySelect->addOptionArray($categoryOptions);
        $form->addElement($categorySelect);

        $form->addElement(new XoopsFormText(_AM_ALUMNI_FORM_LOCATION, 'location', 50, 200, $event->getVar('location', 'e')));
        $form->addElement(new XoopsFormText(_MD_ALUMNI_VENUE, 'venue', 50, 200, $event->getVar('venue', 'e')));

        // Dates
        $form->addElement(new XoopsFormDateTime(_MD_ALUMNI_START_DATE, 'start_date', 15, $event->getVar('start_date', 'e')), true);
        $form->addElement(new XoopsFormDateTime(_MD_ALUMNI_END_DATE, 'end_date', 15, $event->getVar('end_date', 'e')));
        $form->addElement(new XoopsFormDateTime(_MD_ALUMNI_REGISTRATION_DEADLINE, 'registration_deadline', 15, $event->getVar('registration_deadline', 'e')));

        $form->addElement(new XoopsFormText(_MD_ALUMNI_MAX_ATTENDEES, 'max_attendees', 10, 10, $event->getVar('max_attendees', 'e')));

        // Event type
        $typeSelect = new XoopsFormSelect(_MD_ALUMNI_EVENT_TYPE, 'event_type', $event->getVar('event_type'));
        $typeSelect->addOptionArray(Utility::getEventTypeOptions());
        $form->addElement($typeSelect);

        $form->addElement(new XoopsFormText(_MD_ALUMNI_MEETING_URL, 'meeting_url', 50, 255, $event->getVar('meeting_url', 'e')));

        // Contact info
        $form->addElement(new XoopsFormText(_MD_ALUMNI_CONTACT_NAME, 'contact_name', 50, 100, $event->getVar('contact_name', 'e')));
        $form->addElement(new XoopsFormText(_MD_ALUMNI_CONTACT_EMAIL, 'contact_email', 50, 100, $event->getVar('contact_email', 'e')));
        $form->addElement(new XoopsFormText(_MD_ALUMNI_CONTACT_PHONE, 'contact_phone', 20, 20, $event->getVar('contact_phone', 'e')));

        // Status
        $statusSelect = new XoopsFormSelect(_AM_ALUMNI_FORM_STATUS, 'status', $event->getVar('status'));
        $statusSelect->addOptionArray([
            'published' => _MD_ALUMNI_STATUS_PUBLISHED,
            'draft'     => _MD_ALUMNI_STATUS_DRAFT,
            'cancelled' => _MD_ALUMNI_STATUS_CANCELLED,
        ]);
        $form->addElement($statusSelect);

        $form->addElement(new XoopsFormRadioYN(_AM_ALUMNI_FORM_FEATURED, 'featured', $event->getVar('featured')));

        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormHidden('event_id', $eventId));
        $form->addElement(new XoopsFormButton('', 'submit', _AM_ALUMNI_ACTION_SAVE, 'submit'));

        echo $form->render();

        break;
    case 'save':
        // CSRF check
        if (! $GLOBALS['xoopsSecurity']->check()) {
            redirect_header('events.php', 3, _AM_ALUMNI_ERROR_GENERAL);
        }

        // Get event
        if ($eventId > 0) {
            $event = $eventHandler->get($eventId);
            if (! $event) {
                redirect_header('events.php', 3, _AM_ALUMNI_ERROR_NOT_FOUND);
            }
        } else {
            $event = $eventHandler->create();
            $event->setVar('created', time());
            $event->setVar('created_by', $GLOBALS['xoopsUser']->uid());
        }

        // Set values
        $event->setVar('title', $_POST['title']);
        $event->setVar('description', $_POST['description']);
        $event->setVar('category_id', (int) $_POST['category_id']);
        $event->setVar('location', $_POST['location']);
        $event->setVar('venue', $_POST['venue']);
        $event->setVar('start_date', (int) $_POST['start_date']);
        $event->setVar('end_date', (int) $_POST['end_date']);
        $event->setVar('registration_deadline', (int) $_POST['registration_deadline']);
        $event->setVar('max_attendees', (int) $_POST['max_attendees']);
        $event->setVar('event_type', $_POST['event_type']);
        $event->setVar('meeting_url', $_POST['meeting_url']);
        $event->setVar('contact_name', $_POST['contact_name']);
        $event->setVar('contact_email', $_POST['contact_email']);
        $event->setVar('contact_phone', $_POST['contact_phone']);
        $event->setVar('status', $_POST['status']);
        $event->setVar('featured', (int) $_POST['featured']);
        $event->setVar('updated', time());

        // Save
        if ($eventHandler->insert($event)) {
            redirect_header('events.php', 3, $eventId > 0 ? _AM_ALUMNI_SUCCESS_EVENT_UPDATED : _AM_ALUMNI_SUCCESS_EVENT_ADDED);
        } else {
            redirect_header('events.php', 3, _AM_ALUMNI_ERROR_SAVE);
        }

        break;
    case 'delete':
        // CSRF check
        if (! $GLOBALS['xoopsSecurity']->check()) {
            redirect_header('events.php', 3, _AM_ALUMNI_ERROR_GENERAL);
        }

        if ($eventId > 0) {
            if ($eventHandler->delete($eventHandler->get($eventId))) {
                redirect_header('events.php', 3, _AM_ALUMNI_SUCCESS_EVENT_DELETED);
            } else {
                redirect_header('events.php', 3, _AM_ALUMNI_ERROR_DELETE);
            }
        }
        redirect_header('events.php', 3, _AM_ALUMNI_ERROR_INVALID_ID);

        break;
    case 'feature':
        // CSRF check
        if (! $GLOBALS['xoopsSecurity']->check()) {
            redirect_header('events.php', 3, _AM_ALUMNI_ERROR_GENERAL);
        }

        if ($eventId > 0) {
            $event = $eventHandler->get($eventId);
            if ($event) {
                $event->setVar('featured', $event->getVar('featured') ? 0 : 1);
                if ($eventHandler->insert($event)) {
                    redirect_header('events.php', 3, _AM_ALUMNI_SUCCESS_FEATURE);
                }
            }
        }
        redirect_header('events.php', 3, _AM_ALUMNI_ERROR_GENERAL);

        break;
    case 'duplicate':
        // CSRF check
        if (! $GLOBALS['xoopsSecurity']->check()) {
            redirect_header('events.php', 3, _AM_ALUMNI_ERROR_GENERAL);
        }

        if ($eventId > 0) {
            $originalEvent = $eventHandler->get($eventId);
            if ($originalEvent) {
                $newEvent = $eventHandler->create();
                $newEvent->setVar('title', $originalEvent->getVar('title') . ' (Copy)');
                $newEvent->setVar('description', $originalEvent->getVar('description'));
                $newEvent->setVar('category_id', $originalEvent->getVar('category_id'));
                $newEvent->setVar('location', $originalEvent->getVar('location'));
                $newEvent->setVar('venue', $originalEvent->getVar('venue'));
                $newEvent->setVar('event_type', $originalEvent->getVar('event_type'));
                $newEvent->setVar('max_attendees', $originalEvent->getVar('max_attendees'));
                $newEvent->setVar('contact_name', $originalEvent->getVar('contact_name'));
                $newEvent->setVar('contact_email', $originalEvent->getVar('contact_email'));
                $newEvent->setVar('contact_phone', $originalEvent->getVar('contact_phone'));
                $newEvent->setVar('status', 'draft');
                $newEvent->setVar('featured', 0);
                $newEvent->setVar('created', time());
                $newEvent->setVar('created_by', $GLOBALS['xoopsUser']->uid());

                if ($eventHandler->insert($newEvent)) {
                    redirect_header('events.php?op=edit&event_id=' . $newEvent->getVar('event_id'), 3, _SUCCESS);
                }
            }
        }
        redirect_header('events.php', 3, _AM_ALUMNI_ERROR_GENERAL);

        break;
}

require __DIR__ . '/admin_footer.php';
