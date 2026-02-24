<?php

declare(strict_types=1);
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
 * Class AlumniProfile.
 */
class AlumniProfile extends XoopsObject
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
        $this->initVar('email', XOBJ_DTYPE_TXTBOX, '', false, 100);
        $this->initVar('phone', XOBJ_DTYPE_TXTBOX, '', false, 50);
        $this->initVar('privacy_profile', XOBJ_DTYPE_ENUM, 'public', false, false, false, ['public', 'alumni', 'private']);
        $this->initVar('privacy_email', XOBJ_DTYPE_ENUM, 'alumni', false, false, false, ['public', 'alumni', 'private']);
        $this->initVar('privacy_phone', XOBJ_DTYPE_ENUM, 'private', false, false, false, ['public', 'alumni', 'private']);
        $this->initVar('allow_mentorship', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('allow_networking', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('status', XOBJ_DTYPE_ENUM, 'pending', false, false, false, ['active', 'inactive', 'pending']);
        $this->initVar('featured', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('views', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('connections_count', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('created', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
    }
}

/**
 * Class AlumniProfileHandler.
 */
class AlumniProfileHandler extends XoopsPersistableObjectHandler
{
    /**
     * Constructor.
     *
     * @param XoopsDatabase|null $db Database connection
     */
    public function __construct(?XoopsDatabase $db = null)
    {
        parent::__construct($db, 'alumni_profiles', 'AlumniProfile', 'profile_id', 'first_name');
    }

    /**
     * Get profile by user ID.
     *
     * @param int $userId User ID
     *
     * @return AlumniProfile|null Profile object or null
     */
    public function getProfileByUserId($userId)
    {
        $criteria = new Criteria('user_id', (int) $userId);
        $profiles = $this->getObjects($criteria);

        return ! empty($profiles) ? $profiles[0] : null;
    }

    /**
     * Get public profiles.
     *
     * @param Criteria|null $criteria Additional criteria
     * @param int $limit Limit
     * @param int $start Start offset
     *
     * @return array Array of profile objects
     */
    public function getPublicProfiles($criteria = null, $limit = 0, $start = 0)
    {
        if (null === $criteria) {
            $criteria = new CriteriaCompo();
        }

        $criteria->add(new Criteria('status', 'active'));
        $criteria->add(new Criteria('privacy_profile', 'public'));
        $criteria->setSort('created');
        $criteria->setOrder('DESC');

        if ($limit > 0) {
            $criteria->setLimit($limit);
            $criteria->setStart($start);
        }

        return $this->getObjects($criteria);
    }

    /**
     * Get featured profiles.
     *
     * @param int $limit Limit
     *
     * @return array Array of profile objects
     */
    public function getFeaturedProfiles($limit = 5)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('status', 'active'));
        $criteria->add(new Criteria('featured', 1));
        $criteria->setSort('views');
        $criteria->setOrder('DESC');
        $criteria->setLimit($limit);

        return $this->getObjects($criteria);
    }

    /**
     * Search profiles.
     *
     * @param string $keyword Search keyword
     * @param array $filters Additional filters
     * @param int $limit Limit
     * @param int $start Start offset
     *
     * @return array Array of profile objects
     */
    public function searchProfiles($keyword = '', $filters = [], $limit = 0, $start = 0)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('status', 'active'));

        // Keyword search
        if (! empty($keyword)) {
            $keywordCriteria = new CriteriaCompo();
            $keywordCriteria->add(new Criteria('first_name', '%' . $keyword . '%', 'LIKE'), 'OR');
            $keywordCriteria->add(new Criteria('last_name', '%' . $keyword . '%', 'LIKE'), 'OR');
            $keywordCriteria->add(new Criteria('bio', '%' . $keyword . '%', 'LIKE'), 'OR');
            $keywordCriteria->add(new Criteria('current_company', '%' . $keyword . '%', 'LIKE'), 'OR');
            $keywordCriteria->add(new Criteria('current_position', '%' . $keyword . '%', 'LIKE'), 'OR');
            $criteria->add($keywordCriteria);
        }

        // Graduation year filter
        if (! empty($filters['graduation_year'])) {
            $criteria->add(new Criteria('graduation_year', (int) $filters['graduation_year']));
        }

        // Year range filter
        if (! empty($filters['year_from'])) {
            $criteria->add(new Criteria('graduation_year', (int) $filters['year_from'], '>='));
        }
        if (! empty($filters['year_to'])) {
            $criteria->add(new Criteria('graduation_year', (int) $filters['year_to'], '<='));
        }

        // Industry filter
        if (! empty($filters['industry'])) {
            $criteria->add(new Criteria('industry', $filters['industry']));
        }

        // Location filter
        if (! empty($filters['location'])) {
            $criteria->add(new Criteria('location', '%' . $filters['location'] . '%', 'LIKE'));
        }

        // Department filter
        if (! empty($filters['department'])) {
            $criteria->add(new Criteria('department', $filters['department']));
        }

        $criteria->setSort('created');
        $criteria->setOrder('DESC');

        if ($limit > 0) {
            $criteria->setLimit($limit);
            $criteria->setStart($start);
        }

        return $this->getObjects($criteria);
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
        $profile = $this->get($profileId);
        if (! $profile) {
            return false;
        }

        $views = $profile->getVar('views');
        $profile->setVar('views', $views + 1);

        return $this->insert($profile, true);
    }

    /**
     * Update connections count.
     *
     * @param int $profileId Profile ID
     *
     * @return bool True on success
     */
    public function updateConnectionsCount($profileId)
    {
        $connectionHandler = xoops_getModuleHandler('connection', 'alumni');
        if (! $connectionHandler) {
            return false;
        }

        $profile = $this->get($profileId);
        if (! $profile) {
            return false;
        }

        // Count accepted connections
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('status', 'accepted'));

        $criteriaUser = new CriteriaCompo();
        $criteriaUser->add(new Criteria('requester_id', $profile->getVar('user_id')), 'OR');
        $criteriaUser->add(new Criteria('recipient_id', $profile->getVar('user_id')), 'OR');

        $criteria->add($criteriaUser);

        $count = $connectionHandler->getCount($criteria);
        $profile->setVar('connections_count', $count);

        return $this->insert($profile, true);
    }

    /**
     * Get profiles by graduation year.
     *
     * @param int $year Graduation year
     * @param int $limit Limit
     *
     * @return array Array of profile objects
     */
    public function getProfilesByYear($year, $limit = 0)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('status', 'active'));
        $criteria->add(new Criteria('graduation_year', (int) $year));
        $criteria->setSort('created');
        $criteria->setOrder('DESC');

        if ($limit > 0) {
            $criteria->setLimit($limit);
        }

        return $this->getObjects($criteria);
    }

    /**
     * Get available mentors.
     *
     * @param int $limit Limit
     * @param int $start Start offset
     *
     * @return array Array of profile objects
     */
    public function getAvailableMentors($limit = 0, $start = 0)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('status', 'active'));
        $criteria->add(new Criteria('allow_mentorship', 1));
        $criteria->setSort('connections_count');
        $criteria->setOrder('DESC');

        if ($limit > 0) {
            $criteria->setLimit($limit);
            $criteria->setStart($start);
        }

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
        $criteria->setSort('created');
        $criteria->setOrder('DESC');
        $criteria->setLimit($limit);

        return $this->getObjects($criteria);
    }

    /**
     * Get profiles awaiting approval.
     *
     * @return array Array of profile objects
     */
    public function getPendingProfiles()
    {
        $criteria = new Criteria('status', 'pending');
        $criteria->setSort('created');
        $criteria->setOrder('DESC');

        return $this->getObjects($criteria);
    }
}
