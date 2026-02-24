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
 * Alumni Management System - Category Handler.
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 *
 * @version     1.0.0
 */

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * Class Category.
 */
class Category extends XoopsObject
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->initVar('category_id', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, '', true, 100);
        $this->initVar('description', XOBJ_DTYPE_TXTAREA, '', false);
        $this->initVar('image', XOBJ_DTYPE_TXTBOX, '', false, 255);
        $this->initVar('type', XOBJ_DTYPE_ENUM, 'general', false, false, false, ['event', 'general']);
        $this->initVar('display_order', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('event_count', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('created', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
    }
}

/**
 * Class CategoryHandler.
 */
class CategoryHandler extends XoopsPersistableObjectHandler
{
    /**
     * Constructor.
     *
     * @param XoopsDatabase|null $db Database connection
     * @param Helper|null $helper Helper instance
     */
    public function __construct(?XoopsDatabase $db = null, ?Helper $helper = null)
    {
        parent::__construct($db, 'alumni_categories', Category::class, 'category_id', 'name');
    }

    /**
     * Get categories by type.
     *
     * @param string $type Category type (event|general)
     *
     * @return array Array of category objects
     */
    public function getByType($type = 'event')
    {
        $criteria = new Criteria('type', $type);
        $criteria->setSort('display_order');
        $criteria->setOrder('ASC');

        return $this->getObjects($criteria);
    }

    /**
     * Update event count for category.
     *
     * @param int $categoryId Category ID
     *
     * @return bool True on success
     */
    public function updateEventCount($categoryId)
    {
        $eventHandler = xoops_getModuleHandler('event', 'alumni');
        if (! $eventHandler) {
            return false;
        }

        $category = $this->get($categoryId);
        if (! $category) {
            return false;
        }

        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('category_id', $categoryId));
        $criteria->add(new Criteria('status', 'active'));

        $count = $eventHandler->getCount($criteria);
        $category->setVar('event_count', $count);

        return $this->insert($category, true);
    }

    /**
     * Get all categories ordered.
     *
     * @return array Array of category objects
     */
    public function getAllOrdered()
    {
        $criteria = new Criteria('category_id', 0, '>');
        $criteria->setSort('display_order');
        $criteria->setOrder('ASC');

        return $this->getObjects($criteria);
    }
}
