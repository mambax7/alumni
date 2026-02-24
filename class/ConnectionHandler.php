<?php namespace XoopsModules\Alumni;

/**
 * Alumni Management System - Connection Handler
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 * @version     1.0.0
 */

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * Class Connection
 */
class Connection extends \XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->initVar('connection_id', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('requester_id', XOBJ_DTYPE_INT, 0, true);
        $this->initVar('recipient_id', XOBJ_DTYPE_INT, 0, true);
        $this->initVar('status', XOBJ_DTYPE_ENUM, 'pending', false, false, false, ['pending', 'accepted', 'declined', 'blocked']);
        $this->initVar('message', XOBJ_DTYPE_TXTAREA, '', false);
        $this->initVar('created', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
    }
}

/**
 * Class ConnectionHandler
 */
class ConnectionHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param \XoopsDatabase|null $db Database connection
     * @param Helper|null $helper Helper instance
     */
    public function __construct(?\XoopsDatabase $db = null, ?Helper $helper = null)
    {
        parent::__construct($db, 'alumni_connections', Connection::class, 'connection_id', 'connection_id');
    }

    /**
     * Get user connections by status
     *
     * @param int    $userId User ID
     * @param string $status Connection status
     * @param int    $limit  Limit
     * @return array Array of connection objects
     */
    public function getUserConnections($userId, $status = 'accepted', $limit = 0)
    {
        $criteria = new \CriteriaCompo();

        $userCriteria = new \CriteriaCompo();
        $userCriteria->add(new \Criteria('requester_id', (int)$userId), 'OR');
        $userCriteria->add(new \Criteria('recipient_id', (int)$userId), 'OR');

        $criteria->add($userCriteria);

        if (!empty($status)) {
            $criteria->add(new \Criteria('status', $status));
        }

        $criteria->setSort('updated');
        $criteria->setOrder('DESC');

        if ($limit > 0) {
            $criteria->setLimit($limit);
        }

        return $this->getObjects($criteria);
    }

    /**
     * Get pending connection requests received by user
     *
     * @param int $userId User ID
     * @return array Array of connection objects
     */
    public function getPendingRequests($userId)
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('recipient_id', (int)$userId));
        $criteria->add(new \Criteria('status', 'pending'));
        $criteria->setSort('created');
        $criteria->setOrder('DESC');

        return $this->getObjects($criteria);
    }

    /**
     * Get pending connection requests sent by user
     *
     * @param int $userId User ID
     * @return array Array of connection objects
     */
    public function getSentRequests($userId)
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('requester_id', (int)$userId));
        $criteria->add(new \Criteria('status', 'pending'));
        $criteria->setSort('created');
        $criteria->setOrder('DESC');

        return $this->getObjects($criteria);
    }

    /**
     * Check if two users are connected
     *
     * @param int $userId1 First user ID
     * @param int $userId2 Second user ID
     * @return bool True if connected
     */
    public function areConnected($userId1, $userId2)
    {
        $criteria = new \CriteriaCompo();

        $pairCriteria = new \CriteriaCompo();

        $pair1 = new \CriteriaCompo();
        $pair1->add(new \Criteria('requester_id', (int)$userId1));
        $pair1->add(new \Criteria('recipient_id', (int)$userId2));

        $pair2 = new \CriteriaCompo();
        $pair2->add(new \Criteria('requester_id', (int)$userId2));
        $pair2->add(new \Criteria('recipient_id', (int)$userId1));

        $pairCriteria->add($pair1, 'OR');
        $pairCriteria->add($pair2, 'OR');

        $criteria->add($pairCriteria);
        $criteria->add(new \Criteria('status', 'accepted'));

        $count = $this->getCount($criteria);
        return $count > 0;
    }

    /**
     * Get connection between two users
     *
     * @param int $userId1 First user ID
     * @param int $userId2 Second user ID
     * @return Connection|null Connection object or null
     */
    public function getConnection($userId1, $userId2)
    {
        $criteria = new \CriteriaCompo();

        $pairCriteria = new \CriteriaCompo();

        $pair1 = new \CriteriaCompo();
        $pair1->add(new \Criteria('requester_id', (int)$userId1));
        $pair1->add(new \Criteria('recipient_id', (int)$userId2));

        $pair2 = new \CriteriaCompo();
        $pair2->add(new \Criteria('requester_id', (int)$userId2));
        $pair2->add(new \Criteria('recipient_id', (int)$userId1));

        $pairCriteria->add($pair1, 'OR');
        $pairCriteria->add($pair2, 'OR');

        $criteria->add($pairCriteria);

        $connections = $this->getObjects($criteria);
        return !empty($connections) ? $connections[0] : null;
    }
}
