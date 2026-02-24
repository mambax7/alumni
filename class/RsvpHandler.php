<?php

declare(strict_types=1);

namespace XoopsModules\Alumni;

use Criteria;
use CriteriaCompo;
use XoopsDatabase;
use XoopsObject;
use XoopsPersistableObjectHandler;

use function defined;
use function sprintf;

/**
 * Alumni Management System - RSVP Handler.
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 *
 * @version     1.0.0
 */

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * Class Rsvp.
 */
class Rsvp extends XoopsObject
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->initVar('rsvp_id', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('event_id', XOBJ_DTYPE_INT, 0, true);
        $this->initVar('user_id', XOBJ_DTYPE_INT, 0, true);
        $this->initVar('status', XOBJ_DTYPE_ENUM, 'attending', false, false, false, ['attending', 'maybe', 'declined']);
        $this->initVar('guests', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('comment', XOBJ_DTYPE_TXTAREA, '', false);
        $this->initVar('created', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
    }
}

/**
 * Class RsvpHandler.
 */
class RsvpHandler extends XoopsPersistableObjectHandler
{
    /**
     * Constructor.
     *
     * @param XoopsDatabase|null $db Database connection
     * @param Helper|null $helper Helper instance
     */
    public function __construct(?XoopsDatabase $db = null, ?Helper $helper = null)
    {
        parent::__construct($db, 'alumni_rsvps', Rsvp::class, 'rsvp_id', 'rsvp_id');
    }

    /**
     * Get user RSVP for event.
     *
     * @param int $eventId Event ID
     * @param int $userId User ID
     *
     * @return Rsvp|null RSVP object or null
     */
    public function getUserRsvp($eventId, $userId)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('event_id', (int) $eventId));
        $criteria->add(new Criteria('user_id', (int) $userId));

        $rsvps = $this->getObjects($criteria);

        return ! empty($rsvps) ? $rsvps[0] : null;
    }

    /**
     * Get event RSVPs by status.
     *
     * @param int $eventId Event ID
     * @param string $status RSVP status (attending|maybe|declined)
     * @param int $limit Limit
     *
     * @return array Array of RSVP objects
     */
    public function getEventRsvps($eventId, $status = '', $limit = 0)
    {
        $criteria = new Criteria('event_id', (int) $eventId);

        if (! empty($status)) {
            $criteria->add(new Criteria('status', $status));
        }

        $criteria->setSort('created');
        $criteria->setOrder('ASC');

        if ($limit > 0) {
            $criteria->setLimit($limit);
        }

        return $this->getObjects($criteria);
    }

    /**
     * Get user's upcoming RSVPs.
     *
     * @param int $userId User ID
     * @param int $limit Limit
     *
     * @return array Array of RSVP objects
     */
    public function getUserUpcomingRsvps($userId, $limit = 0)
    {
        $sql = sprintf(
            'SELECT r.* FROM %s r
             INNER JOIN %s e ON r.event_id = e.event_id
             WHERE r.user_id = %u
             AND r.status = "attending"
             AND e.start_date >= %u
             AND e.status = "active"
             ORDER BY e.start_date ASC',
            $this->db->prefix('alumni_rsvps'),
            $this->db->prefix('alumni_events'),
            (int) $userId,
            time()
        );

        if ($limit > 0) {
            $sql .= ' LIMIT ' . (int) $limit;
        }

        $result = $this->db->query($sql);
        if (! $result) {
            return [];
        }

        $rsvps = [];
        while ($row = $this->db->fetchArray($result)) {
            $rsvp = $this->create(false);
            $rsvp->assignVars($row);
            $rsvps[] = $rsvp;
        }

        return $rsvps;
    }

    /**
     * Delete RSVPs for event.
     *
     * @param int $eventId Event ID
     *
     * @return bool True on success
     */
    public function deleteByEvent($eventId)
    {
        $criteria = new Criteria('event_id', (int) $eventId);

        return $this->deleteAll($criteria);
    }
}
