<?php

declare(strict_types=1);
/**
 * Alumni Management System - Skill Management.
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 */

use XoopsModules\Alumni\Helper;
use XoopsModules\Alumni\Utility;

$GLOBALS['xoopsOption']['template_main'] = 'file:' . dirname(__DIR__) . '/templates/admin/alumni_admin_skills.tpl';

require __DIR__ . '/admin_header.php';

xoops_cp_header();
Utility::addAdminAssets();

// Get handler
$helper = Helper::getInstance();
$skillHandler = $helper->getHandler('skill');

// Get operation
$op = $_REQUEST['op'] ?? 'list';
$skillId = isset($_REQUEST['skill_id']) ? (int) $_REQUEST['skill_id'] : 0;

switch ($op) {
    case 'list':
    default:
        // Display navigation
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_ALUMNI_SKILL_ADD, 'skills.php?op=edit', 'add');
        $xoopsTpl->assign('admin_buttons', $adminObject->renderButton());

        // Get filters
        $filter_category = $_GET['category'] ?? '';
        $start = isset($_GET['start']) ? (int) $_GET['start'] : 0;
        $limit = 20;

        // Build criteria
        $criteria = new CriteriaCompo();
        $criteria->setSort('profile_count');
        $criteria->setOrder('DESC');
        $criteria->setStart($start);
        $criteria->setLimit($limit);

        // Get skills
        $skillObjs = $skillHandler->getObjects($criteria);
        $skillCount = $skillHandler->getCount($criteria);

        // Build array for template
        $skillsArr = [];
        foreach ($skillObjs as $skill) {
            $skillsArr[] = [
                'id'            => $skill->getVar('skill_id'),
                'name'          => Utility::sanitizeHtml($skill->getVar('name')),
                'profile_count' => (int) $skill->getVar('profile_count'),
            ];
        }

        // Pagination
        $pagenavStr = '';
        if ($skillCount > $limit) {
            require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $extra = 'op=list' . (! empty($filter_category) ? '&category=' . urlencode($filter_category) : '');
            $pagenav = new XoopsPageNav($skillCount, $limit, $start, 'start', $extra);
            $pagenavStr = $pagenav->renderNav();
        }

        $xoopsTpl->assign('skills', $skillsArr);
        $xoopsTpl->assign('pagenav', $pagenavStr);

        break;
    case 'edit':
        $adminObject->displayNavigation(basename(__FILE__));

        // Get skill if editing
        if ($skillId > 0) {
            $skill = $skillHandler->get($skillId);
            if (! $skill) {
                redirect_header('skills.php', 3, _AM_ALUMNI_ERROR_NOT_FOUND);
            }
        } else {
            $skill = $skillHandler->create();
        }

        // Create form
        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $form = new XoopsThemeForm(
            $skillId > 0 ? _AM_ALUMNI_SKILL_EDIT : _AM_ALUMNI_SKILL_ADD,
            'skill_form',
            'skills.php',
            'post',
            true
        );

        $form->addElement(new XoopsFormText(_AM_ALUMNI_SKILL_NAME, 'name', 50, 100, $skill->getVar('name', 'e')), true);
        $form->addElement(new XoopsFormTextArea(_AM_ALUMNI_SKILL_DESCRIPTION, 'description', $skill->getVar('description', 'e'), 3, 60));
        $form->addElement(new XoopsFormText(_AM_ALUMNI_SKILL_CATEGORY, 'category', 50, 50, $skill->getVar('category', 'e')));
        $form->addElement(new XoopsFormText(_AM_ALUMNI_SKILL_WEIGHT, 'weight', 10, 10, $skill->getVar('weight', 'e')));

        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormHidden('skill_id', $skillId));
        $form->addElement(new XoopsFormButton('', 'submit', _AM_ALUMNI_ACTION_SAVE, 'submit'));

        echo $form->render();

        break;
    case 'save':
        // CSRF check
        if (! $GLOBALS['xoopsSecurity']->check()) {
            redirect_header('skills.php', 3, _AM_ALUMNI_ERROR_GENERAL);
        }

        // Get skill
        if ($skillId > 0) {
            $skill = $skillHandler->get($skillId);
            if (! $skill) {
                redirect_header('skills.php', 3, _AM_ALUMNI_ERROR_NOT_FOUND);
            }
        } else {
            $skill = $skillHandler->create();
        }

        // Set values
        $skill->setVar('name', $_POST['name']);

        // Save
        if ($skillHandler->insert($skill)) {
            redirect_header('skills.php', 3, $skillId > 0 ? _AM_ALUMNI_SUCCESS_SKILL_UPDATED : _AM_ALUMNI_SUCCESS_SKILL_ADDED);
        } else {
            redirect_header('skills.php', 3, _AM_ALUMNI_ERROR_SAVE);
        }

        break;
    case 'delete':
        // CSRF check
        if (! $GLOBALS['xoopsSecurity']->check()) {
            redirect_header('skills.php', 3, _AM_ALUMNI_ERROR_GENERAL);
        }

        if ($skillId > 0) {
            if ($skillHandler->delete($skillHandler->get($skillId))) {
                redirect_header('skills.php', 3, _AM_ALUMNI_SUCCESS_SKILL_DELETED);
            } else {
                redirect_header('skills.php', 3, _AM_ALUMNI_ERROR_DELETE);
            }
        }
        redirect_header('skills.php', 3, _AM_ALUMNI_ERROR_INVALID_ID);

        break;
    case 'merge':
        $adminObject->displayNavigation(basename(__FILE__));

        // Merge skills functionality
        if (isset($_POST['source_id']) && isset($_POST['target_id'])) {
            // CSRF check
            if (! $GLOBALS['xoopsSecurity']->check()) {
                redirect_header('skills.php', 3, _AM_ALUMNI_ERROR_GENERAL);
            }

            $sourceId = (int) $_POST['source_id'];
            $targetId = (int) $_POST['target_id'];

            $sourceSkill = $skillHandler->get($sourceId);
            $targetSkill = $skillHandler->get($targetId);

            if ($sourceSkill && $targetSkill && $sourceId !== $targetId) {
                // Update target skill usage count
                $targetSkill->setVar('usage_count', $targetSkill->getVar('usage_count') + $sourceSkill->getVar('usage_count'));

                // Delete source skill after merging
                if ($skillHandler->insert($targetSkill) && $skillHandler->delete($sourceSkill)) {
                    redirect_header('skills.php', 3, _SUCCESS);
                }
            }
            redirect_header('skills.php', 3, _AM_ALUMNI_ERROR_GENERAL);
        }

        // Display merge form
        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $form = new XoopsThemeForm(_MD_ALUMNI_MERGE_SKILLS, 'merge_form', 'skills.php', 'post', true);

        $allSkills = $skillHandler->getObjects(null, true);
        $skillOptions = [];
        foreach ($allSkills as $skill) {
            $skillOptions[$skill->getVar('skill_id')] = $skill->getVar('name') . ' (' . $skill->getVar('usage_count') . ')';
        }

        $sourceSelect = new XoopsFormSelect(_MD_ALUMNI_SOURCE_SKILL, 'source_id', 0);
        $sourceSelect->addOptionArray($skillOptions);
        $form->addElement($sourceSelect, true);

        $targetSelect = new XoopsFormSelect(_MD_ALUMNI_TARGET_SKILL, 'target_id', 0);
        $targetSelect->addOptionArray($skillOptions);
        $form->addElement($targetSelect, true);

        $form->addElement(new XoopsFormHidden('op', 'merge'));
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

        echo $form->render();

        break;
}

require __DIR__ . '/admin_footer.php';
