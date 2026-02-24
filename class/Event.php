<?php
/**
 * Alumni Management System - Event Handler
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 * @version     1.0.0
 */

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * Class AlumniEvent
 */
class AlumniEvent extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->initVar('event_id', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('category_id', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, '', true, 255);
        $this->initVar('description', XOBJ_DTYPE_TXTAREA, '', true);
        $this->initVar('image', XOBJ_DTYPE_TXTBOX, '', false, 255);
        $this->initVar('location', XOBJ_DTYPE_TXTBOX, '', false, 200);
        $this->initVar('venue', XOBJ_DTYPE_TXTBOX, '', false, 200);
        $this->initVar('start_date', XOBJ_DTYPE_INT, 0, true);
        $this->initVar('end_date', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('registration_deadline', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('max_attendees', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('rsvp_count', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('event_type', XOBJ_DTYPE_ENUM, 'physical', false, false, false, ['online', 'physical', 'hybrid']);
        $this->initVar('meeting_url', XOBJ_DTYPE_TXTBOX, '', false, 255);
        $this->initVar('contact_name', XOBJ_DTYPE_TXTBOX, '', false, 100);
        $this->initVar('contact_email', XOBJ_DTYPE_TXTBOX, '', false, 100);
        $this->initVar('contact_phone', XOBJ_DTYPE_TXTBOX, '', false, 50);
        $this->initVar('status', XOBJ_DTYPE_ENUM, 'draft', false, false, false, ['active', 'cancelled', 'completed', 'draft']);
        $this->initVar('featured', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('views', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('created_by', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('created', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
    }
}

/**
 * Class AlumniEventHandler
 */
class AlumniEventHandler extends XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param XoopsDatabase|null $db Database connection
     */
    public function __construct(?XoopsDatabase $db = null)
    {
        parent::__construct($db, 'alumni_events', 'AlumniEvent', 'event_id', 'title');
    }

    /**
     * Get upcoming events
     *
     * @param int $limit Limit
     * @param int $start Start offset
     * @return array Array of event objects
     */
    public function getUpcomingEvents($limit = 0, $start = 0)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('status', 'active'));
        $criteria->add(new Criteria('start_date', time(), '>='));
        $criteria->setSort('start_date');
        $criteria->setOrder('ASC');

        if ($limit > 0) {
            $criteria->setLimit($limit);
            $criteria->setStart($start);
        }

        return $this->getObjects($criteria);
    }

    /**
     * Get past events
     *
     * @param int $limit Limit
     * @param int $start Start offset
     * @return array Array of event objects
     */
    public function getPastEvents($limit = 0, $start = 0)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('status', 'completed'));
        $criteria->setSort('start_date');
        $criteria->setOrder('DESC');

        if ($limit > 0) {
            $criteria->setLimit($limit);
            $criteria->setStart($start);
        }

        return $this->getObjects($criteria);
    }

    /**
     * Get featured events
     *
     * @param int $limit Limit
     * @return array Array of event objects
     */
    public function getFeaturedEvents($limit = 5)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('status', 'active'));
        $criteria->add(new Criteria('featured', 1));
        $criteria->add(new Criteria('start_date', time(), '>='));
        $criteria->setSort('start_date');
        $criteria->setOrder('ASC');
        $criteria->setLimit($limit);

        return $this->getObjects($criteria);
    }

    /**
     * Get events by category
     *
     * @param int $categoryId Category ID
     * @param int $limit      Limit
     * @param int $start      Start offset
     * @return array Array of event objects
     */
    public function getEventsByCategory($categoryId, $limit = 0, $start = 0)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('category_id', (int)$categoryId));
        $criteria->add(new Criteria('status', 'active'));
        $criteria->setSort('start_date');
        $criteria->setOrder('ASC');

        if ($limit > 0) {
            $criteria->setLimit($limit);
            $criteria->setStart($start);
        }

        return $this->getObjects($criteria);
    }

    /**
     * Increment event views
     *
     * @param int $eventId Event ID
     * @return bool True on success
     */
    public function incrementViews($eventId)
    {
        $event = $this->get($eventId);
        if (!$event) {
            return false;
        }

        $views = $event->getVar('views');
        $event->setVar('views', $views + 1);

        return $this->insert($event, true);
    }

    /**
     * Update RSVP count
     *
     * @param int $eventId Event ID
     * @return bool True on success
     */
    public function updateRsvpCount($eventId)
    {
        $rsvpHandler = xoops_getModuleHandler('rsvp', 'alumni');
        if (!$rsvpHandler) {
            return false;
        }

        $event = $this->get($eventId);
        if (!$event) {
            return false;
        }

        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('event_id', $eventId));
        $criteria->add(new Criteria('status', 'attending'));

        $count = $rsvpHandler->getCount($criteria);
        $event->setVar('rsvp_count', $count);

        return $this->insert($event, true);
    }

    /**
     * Search events
     *
     * @param string $keyword Search keyword
     * @param array  $filters Additional filters
     * @param int    $limit   Limit
     * @param int    $start   Start offset
     * @return array Array of event objects
     */
    public function searchEvents($keyword = '', $filters = [], $limit = 0, $start = 0)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('status', 'active'));

        // Keyword search
        if (!empty($keyword)) {
            $keywordCriteria = new CriteriaCompo();
            $keywordCriteria->add(new Criteria('title', '%' . $keyword . '%', 'LIKE'), 'OR');
            $keywordCriteria->add(new Criteria('description', '%' . $keyword . '%', 'LIKE'), 'OR');
            $criteria->add($keywordCriteria);
        }

        // Category filter
        if (!empty($filters['category_id'])) {
            $criteria->add(new Criteria('category_id', (int)$filters['category_id']));
        }

        // Event type filter
        if (!empty($filters['event_type'])) {
            $criteria->add(new Criteria('event_type', $filters['event_type']));
        }

        // Date range filter
        if (!empty($filters['date_from'])) {
            $criteria->add(new Criteria('start_date', (int)$filters['date_from'], '>='));
        }
        if (!empty($filters['date_to'])) {
            $criteria->add(new Criteria('start_date', (int)$filters['date_to'], '<='));
        }

        $criteria->setSort('start_date');
        $criteria->setOrder('ASC');

        if ($limit > 0) {
            $criteria->setLimit($limit);
            $criteria->setStart($start);
        }

        return $this->getObjects($criteria);
    }

    /**
     * Check if event has available slots
     *
     * @param int $eventId Event ID
     * @return bool True if slots available
     */
    public function hasAvailableSlots($eventId)
    {
        $event = $this->get($eventId);
        if (!$event) {
            return false;
        }

        $maxAttendees = $event->getVar('max_attendees');
        if ($maxAttendees == 0) {
            return true; // Unlimited
        }

        $rsvpCount = $event->getVar('rsvp_count');
        return $rsvpCount < $maxAttendees;
    }

    /**
     * Get events by date range
     *
     * @param int $startDate Start timestamp
     * @param int $endDate   End timestamp
     * @param int $limit     Limit
     * @return array Array of event objects
     */
    public function getEventsByDateRange($startDate, $endDate, $limit = 0)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('status', 'active'));
        $criteria->add(new Criteria('start_date', $startDate, '>='));
        $criteria->add(new Criteria('start_date', $endDate, '<='));
        $criteria->setSort('start_date');
        $criteria->setOrder('ASC');

        if ($limit > 0) {
            $criteria->setLimit($limit);
        }

        return $this->getObjects($criteria);
    }
}
