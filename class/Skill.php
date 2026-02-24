<?php

declare(strict_types=1);
/**
 * Alumni Management System - Skill Handler.
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 *
 * @version     1.0.0
 */

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * Class AlumniSkill.
 */
class AlumniSkill extends XoopsObject
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->initVar('skill_id', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, '', true, 100);
        $this->initVar('profile_count', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('created', XOBJ_DTYPE_INT, 0, false);
    }
}

/**
 * Class AlumniSkillHandler.
 */
class AlumniSkillHandler extends XoopsPersistableObjectHandler
{
    /**
     * Constructor.
     *
     * @param XoopsDatabase|null $db Database connection
     */
    public function __construct(?XoopsDatabase $db = null)
    {
        parent::__construct($db, 'alumni_skills', 'AlumniSkill', 'skill_id', 'name');
    }

    /**
     * Get or create skill by name.
     *
     * @param string $name Skill name
     *
     * @return AlumniSkill|null Skill object
     */
    public function getByName($name)
    {
        $criteria = new Criteria('name', trim($name));
        $skills = $this->getObjects($criteria);

        return ! empty($skills) ? $skills[0] : null;
    }

    /**
     * Get profile skills.
     *
     * @param int $profileId Profile ID
     *
     * @return array Array of skill objects
     */
    public function getProfileSkills($profileId)
    {
        $sql = sprintf(
            'SELECT s.* FROM %s s
             INNER JOIN %s psl ON s.skill_id = psl.skill_id
             WHERE psl.profile_id = %u
             ORDER BY s.name ASC',
            $this->db->prefix('alumni_skills'),
            $this->db->prefix('alumni_profile_skill_link'),
            (int) $profileId
        );

        $result = $this->db->query($sql);
        if (! $result) {
            return [];
        }

        $skills = [];
        while ($row = $this->db->fetchArray($result)) {
            $skill = $this->create(false);
            $skill->assignVars($row);
            $skills[] = $skill;
        }

        return $skills;
    }

    /**
     * Get popular skills.
     *
     * @param int $limit Limit
     *
     * @return array Array of skill objects
     */
    public function getPopularSkills($limit = 20)
    {
        $criteria = new Criteria('profile_count', 0, '>');
        $criteria->setSort('profile_count');
        $criteria->setOrder('DESC');
        $criteria->setLimit($limit);

        return $this->getObjects($criteria);
    }

    /**
     * Add skill to profile.
     *
     * @param int $profileId Profile ID
     * @param string $skillName Skill name
     *
     * @return bool True on success
     */
    public function addSkillToProfile($profileId, $skillName)
    {
        $skillName = trim($skillName);
        if (empty($skillName)) {
            return false;
        }

        // Get or create skill
        $skill = $this->getByName($skillName);
        if (! $skill) {
            $skill = $this->create();
            $skill->setVar('name', $skillName);
            $skill->setVar('created', time());

            if (! $this->insert($skill)) {
                return false;
            }
        }

        // Check if link already exists
        $sql = sprintf(
            'SELECT COUNT(*) FROM %s WHERE profile_id = %u AND skill_id = %u',
            $this->db->prefix('alumni_profile_skill_link'),
            (int) $profileId,
            $skill->getVar('skill_id')
        );

        $result = $this->db->query($sql);
        if (! $result) {
            return false;
        }

        [$count] = $this->db->fetchRow($result);
        if ($count > 0) {
            return true; // Already exists
        }

        // Create link
        $sql = sprintf(
            'INSERT INTO %s (profile_id, skill_id) VALUES (%u, %u)',
            $this->db->prefix('alumni_profile_skill_link'),
            (int) $profileId,
            $skill->getVar('skill_id')
        );

        if (! $this->db->queryF($sql)) {
            return false;
        }

        // Update profile count
        $this->updateProfileCount($skill->getVar('skill_id'));

        return true;
    }

    /**
     * Remove skill from profile.
     *
     * @param int $profileId Profile ID
     * @param int $skillId Skill ID
     *
     * @return bool True on success
     */
    public function removeSkillFromProfile($profileId, $skillId)
    {
        $sql = sprintf(
            'DELETE FROM %s WHERE profile_id = %u AND skill_id = %u',
            $this->db->prefix('alumni_profile_skill_link'),
            (int) $profileId,
            (int) $skillId
        );

        if (! $this->db->queryF($sql)) {
            return false;
        }

        // Update profile count
        $this->updateProfileCount($skillId);

        return true;
    }

    /**
     * Update profile count for skill.
     *
     * @param int $skillId Skill ID
     *
     * @return bool True on success
     */
    public function updateProfileCount($skillId)
    {
        $skill = $this->get($skillId);
        if (! $skill) {
            return false;
        }

        $sql = sprintf(
            'SELECT COUNT(*) FROM %s WHERE skill_id = %u',
            $this->db->prefix('alumni_profile_skill_link'),
            (int) $skillId
        );

        $result = $this->db->query($sql);
        if (! $result) {
            return false;
        }

        [$count] = $this->db->fetchRow($result);
        $skill->setVar('profile_count', $count);

        return $this->insert($skill, true);
    }

    /**
     * Get profiles with skill.
     *
     * @param int $skillId Skill ID
     * @param int $limit Limit
     *
     * @return array Array of profile IDs
     */
    public function getProfilesWithSkill($skillId, $limit = 0)
    {
        $sql = sprintf(
            'SELECT profile_id FROM %s WHERE skill_id = %u',
            $this->db->prefix('alumni_profile_skill_link'),
            (int) $skillId
        );

        if ($limit > 0) {
            $sql .= ' LIMIT ' . (int) $limit;
        }

        $result = $this->db->query($sql);
        if (! $result) {
            return [];
        }

        $profileIds = [];
        while ($row = $this->db->fetchArray($result)) {
            $profileIds[] = $row['profile_id'];
        }

        return $profileIds;
    }

    /**
     * Clear all skills from profile.
     *
     * @param int $profileId Profile ID
     *
     * @return bool True on success
     */
    public function clearProfileSkills($profileId)
    {
        // Get skill IDs first for updating counts
        $sql = sprintf(
            'SELECT skill_id FROM %s WHERE profile_id = %u',
            $this->db->prefix('alumni_profile_skill_link'),
            (int) $profileId
        );

        $result = $this->db->query($sql);
        if (! $result) {
            return false;
        }

        $skillIds = [];
        while ($row = $this->db->fetchArray($result)) {
            $skillIds[] = $row['skill_id'];
        }

        // Delete links
        $sql = sprintf(
            'DELETE FROM %s WHERE profile_id = %u',
            $this->db->prefix('alumni_profile_skill_link'),
            (int) $profileId
        );

        if (! $this->db->queryF($sql)) {
            return false;
        }

        // Update profile counts
        foreach ($skillIds as $skillId) {
            $this->updateProfileCount($skillId);
        }

        return true;
    }
}
