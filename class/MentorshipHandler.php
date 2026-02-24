<?php

declare(strict_types=1);

namespace XoopsModules\Alumni;

use Criteria;
use CriteriaCompo;
use XoopsDatabase;
use XoopsObject;
use XoopsPersistableObjectHandler;

use function defined;

/**
 * Alumni Management System - Mentorship Handler.
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 *
 * @version     1.0.0
 */

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * Class Mentorship.
 */
class Mentorship extends XoopsObject
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->initVar('mentorship_id', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('mentor_id', XOBJ_DTYPE_INT, 0, true);
        $this->initVar('mentee_id', XOBJ_DTYPE_INT, 0, true);
        $this->initVar('status', XOBJ_DTYPE_ENUM, 'pending', false, false, false, ['pending', 'active', 'completed', 'declined']);
        $this->initVar('subject', XOBJ_DTYPE_TXTBOX, '', true, 255);
        $this->initVar('message', XOBJ_DTYPE_TXTAREA, '', false);
        $this->initVar('start_date', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('end_date', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('created', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
    }
}

/**
 * Class MentorshipHandler.
 */
class MentorshipHandler extends XoopsPersistableObjectHandler
{
    /**
     * Constructor.
     *
     * @param XoopsDatabase|null $db Database connection
     * @param Helper|null $helper Helper instance
     */
    public function __construct(?XoopsDatabase $db = null, ?Helper $helper = null)
    {
        parent::__construct($db, 'alumni_mentorship', Mentorship::class, 'mentorship_id', 'mentorship_id');
    }

    /**
     * Get mentorship requests received by mentor.
     *
     * @param int $mentorId Mentor user ID
     * @param string $status Status filter
     *
     * @return array Array of mentorship objects
     */
    public function getMentorRequests($mentorId, $status = '')
    {
        $criteria = new Criteria('mentor_id', (int) $mentorId);

        if (! empty($status)) {
            $criteria->add(new Criteria('status', $status));
        }

        $criteria->setSort('created');
        $criteria->setOrder('DESC');

        return $this->getObjects($criteria);
    }

    /**
     * Get mentorship requests sent by mentee.
     *
     * @param int $menteeId Mentee user ID
     * @param string $status Status filter
     *
     * @return array Array of mentorship objects
     */
    public function getMenteeRequests($menteeId, $status = '')
    {
        $criteria = new Criteria('mentee_id', (int) $menteeId);

        if (! empty($status)) {
            $criteria->add(new Criteria('status', $status));
        }

        $criteria->setSort('created');
        $criteria->setOrder('DESC');

        return $this->getObjects($criteria);
    }

    /**
     * Get active mentorships for user (as mentor or mentee).
     *
     * @param int $userId User ID
     *
     * @return array Array of mentorship objects
     */
    public function getActiveMentorships($userId)
    {
        $criteria = new CriteriaCompo();

        $userCriteria = new CriteriaCompo();
        $userCriteria->add(new Criteria('mentor_id', (int) $userId), 'OR');
        $userCriteria->add(new Criteria('mentee_id', (int) $userId), 'OR');

        $criteria->add($userCriteria);
        $criteria->add(new Criteria('status', 'active'));
        $criteria->setSort('start_date');
        $criteria->setOrder('DESC');

        return $this->getObjects($criteria);
    }

    /**
     * Check if mentorship exists between two users.
     *
     * @param int $mentorId Mentor user ID
     * @param int $menteeId Mentee user ID
     *
     * @return Mentorship|null Mentorship object or null
     */
    public function getMentorship($mentorId, $menteeId)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('mentor_id', (int) $mentorId));
        $criteria->add(new Criteria('mentee_id', (int) $menteeId));

        $mentorships = $this->getObjects($criteria);

        return ! empty($mentorships) ? $mentorships[0] : null;
    }
}
