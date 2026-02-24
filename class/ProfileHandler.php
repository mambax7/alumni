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
 * Alumni Management System - Profile Handler.
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 *
 * @version     1.0.0
 */

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * Class Profile.
 */
class Profile extends XoopsObject
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->initVar('profile_id', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('user_id', XOBJ_DTYPE_INT, 0, true);
        $this->initVar('first_name', XOBJ_DTYPE_TXTBOX, '', true, 100);
        $this->initVar('last_name', XOBJ_DTYPE_TXTBOX, '', true, 100);
        $this->initVar('photo', XOBJ_DTYPE_TXTBOX, '', false, 255);
        $this->initVar('graduation_year', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('degree', XOBJ_DTYPE_TXTBOX, '', false, 200);
        $this->initVar('major', XOBJ_DTYPE_TXTBOX, '', false, 200);
        $this->initVar('department', XOBJ_DTYPE_TXTBOX, '', false, 200);
        $this->initVar('current_company', XOBJ_DTYPE_TXTBOX, '', false, 200);
        $this->initVar('current_position', XOBJ_DTYPE_TXTBOX, '', false, 200);
        $this->initVar('industry', XOBJ_DTYPE_TXTBOX, '', false, 100);
        $this->initVar('location', XOBJ_DTYPE_TXTBOX, '', false, 200);
        $this->initVar('city', XOBJ_DTYPE_TXTBOX, '', false, 100);
        $this->initVar('country', XOBJ_DTYPE_TXTBOX, '', false, 100);
        $this->initVar('bio', XOBJ_DTYPE_TXTAREA, '', false);
        $this->initVar('linkedin_url', XOBJ_DTYPE_TXTBOX, '', false, 255);
        $this->initVar('twitter_url', XOBJ_DTYPE_TXTBOX, '', false, 255);
        $this->initVar('facebook_url', XOBJ_DTYPE_TXTBOX, '', false, 255);
        $this->initVar('website_url', XOBJ_DTYPE_TXTBOX, '', false, 255);
        $this->initVar('email', XOBJ_DTYPE_TXTBOX, '', false, 255);
        $this->initVar('phone', XOBJ_DTYPE_TXTBOX, '', false, 50);
        $this->initVar('privacy_profile', XOBJ_DTYPE_ENUM, 'public', false, false, false, ['public', 'alumni', 'private']);
        $this->initVar('privacy_email', XOBJ_DTYPE_ENUM, 'alumni', false, false, false, ['public', 'alumni', 'private']);
        $this->initVar('privacy_phone', XOBJ_DTYPE_ENUM, 'private', false, false, false, ['public', 'alumni', 'private']);
        $this->initVar('allow_mentorship', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('allow_networking', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('status', XOBJ_DTYPE_ENUM, 'active', false, false, false, ['active', 'inactive', 'suspended']);
        $this->initVar('featured', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('views', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('connections_count', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('created', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
    }
}

/**
 * Class ProfileHandler.
 */
class ProfileHandler extends XoopsPersistableObjectHandler
{
    /**
     * Constructor.
     *
     * @param XoopsDatabase|null $db Database connection
     * @param Helper|null $helper Helper instance
     */
    public function __construct(?XoopsDatabase $db = null, ?Helper $helper = null)
    {
        parent::__construct($db, 'alumni_profiles', Profile::class, 'profile_id', 'first_name');
    }

    /**
     * Get profile by user ID.
     *
     * @param int $userId User ID
     *
     * @return Profile|null Profile object or null
     */
    public function getByUserId($userId)
    {
        $criteria = new Criteria('user_id', (int) $userId);
        $profiles = $this->getObjects($criteria);

        return ! empty($profiles) ? $profiles[0] : null;
    }

    /**
     * Search profiles.
     *
     * @param array $filters Search filters
     * @param int $limit Limit
     * @param int $start Start position
     *
     * @return array Array of profile objects
     */
    public function searchProfiles($filters = [], $limit = 20, $start = 0)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('status', 'active'));

        if (! empty($filters['graduation_year'])) {
            $criteria->add(new Criteria('graduation_year', (int) $filters['graduation_year']));
        }

        if (! empty($filters['degree'])) {
            $criteria->add(new Criteria('degree', '%' . $filters['degree'] . '%', 'LIKE'));
        }

        if (! empty($filters['major'])) {
            $criteria->add(new Criteria('major', '%' . $filters['major'] . '%', 'LIKE'));
        }

        if (! empty($filters['industry'])) {
            $criteria->add(new Criteria('industry', $filters['industry']));
        }

        if (! empty($filters['location'])) {
            $criteria->add(new Criteria('location', '%' . $filters['location'] . '%', 'LIKE'));
        }

        if (! empty($filters['keyword'])) {
            $keywordCriteria = new CriteriaCompo();
            $keywordCriteria->add(new Criteria('first_name', '%' . $filters['keyword'] . '%', 'LIKE'), 'OR');
            $keywordCriteria->add(new Criteria('last_name', '%' . $filters['keyword'] . '%', 'LIKE'), 'OR');
            $keywordCriteria->add(new Criteria('current_company', '%' . $filters['keyword'] . '%', 'LIKE'), 'OR');
            $keywordCriteria->add(new Criteria('current_position', '%' . $filters['keyword'] . '%', 'LIKE'), 'OR');
            $criteria->add($keywordCriteria);
        }

        $criteria->setLimit($limit);
        $criteria->setStart($start);
        $criteria->setSort('updated');
        $criteria->setOrder('DESC');

        return $this->getObjects($criteria);
    }

    /**
     * Get featured profiles.
     *
     * @param int $limit Limit
     *
     * @return array Array of profile objects
     */
    public function getFeaturedProfiles($limit = 10)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('status', 'active'));
        $criteria->add(new Criteria('featured', 1));
        $criteria->setLimit($limit);
        $criteria->setSort('views');
        $criteria->setOrder('DESC');

        return $this->getObjects($criteria);
    }

    /**
     * Get recent profiles.
     *
     * @param int $limit Limit
     *
     * @return array Array of profile objects
     */
    public function getRecentProfiles($limit = 10)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('status', 'active'));
        $criteria->setLimit($limit);
        $criteria->setSort('created');
        $criteria->setOrder('DESC');

        return $this->getObjects($criteria);
    }

    /**
     * Update profile statistics.
     *
     * @param int $profileId Profile ID
     * @param string $field Field to increment
     * @param int $amount Amount to increment
     *
     * @return bool True on success
     */
    public function updateStats($profileId, $field, $amount = 1)
    {
        $profile = $this->get($profileId);
        if (! $profile) {
            return false;
        }

        $profile->setVar($field, $profile->getVar($field) + $amount);

        return $this->insert($profile, true);
    }

    /**
     * Increment profile views.
     *
     * @param int $profileId Profile ID
     *
     * @return bool True on success
     */
    public function incrementViews($profileId)
    {
        return $this->updateStats($profileId, 'views', 1);
    }
}
