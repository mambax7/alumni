<?php
/**
 * Alumni Management System - Category Management
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 */

use XoopsModules\Alumni\{Helper, Utility};

$GLOBALS['xoopsOption']['template_main'] = 'file:' . \dirname(__DIR__) . '/templates/admin/alumni_admin_categories.tpl';

require __DIR__ . '/admin_header.php';

xoops_cp_header();
Utility::addAdminAssets();

// Get handler
$helper          = Helper::getInstance();
$categoryHandler = $helper->getHandler('category');

// Get operation
$op         = isset($_REQUEST['op'])          ? $_REQUEST['op']          : 'list';
$categoryId = isset($_REQUEST['category_id']) ? (int)$_REQUEST['category_id'] : 0;

// Assign safe defaults so the template never sees undefined variables
$xoopsTpl->assign('categories', []);

switch ($op) {
    case 'list':
    default:
        // Display navigation
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_ALUMNI_CATEGORY_ADD, 'categories.php?op=edit', 'add');
        $xoopsTpl->assign('admin_buttons', $adminObject->renderButton());

        // Get all categories ordered by display_order
        $criteria = new \CriteriaCompo();
        $criteria->setSort('display_order');
        $criteria->setOrder('ASC');
        $categoryObjs = $categoryHandler->getObjects($criteria);

        // Build array for template
        $categoriesArr = [];
        foreach ($categoryObjs as $category) {
            $categoriesArr[] = [
                'id'            => $category->getVar('category_id'),
                'name'          => Utility::sanitizeHtml($category->getVar('name')),
                'description'   => Utility::sanitizeHtml($category->getVar('description')),
                'display_order' => (int)$category->getVar('display_order'),
                'event_count'   => (int)$category->getVar('event_count'),
            ];
        }

        $xoopsTpl->assign('categories', $categoriesArr);
        break;

    case 'edit':
        $adminObject->displayNavigation(basename(__FILE__));

        // Get category if editing
        if ($categoryId > 0) {
            $category = $categoryHandler->get($categoryId);
            if (!$category) {
                redirect_header('categories.php', 3, _AM_ALUMNI_ERROR_NOT_FOUND);
            }
        } else {
            $category = $categoryHandler->create();
        }

        // Create form
        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $form = new XoopsThemeForm(
            $categoryId > 0 ? _AM_ALUMNI_CATEGORY_EDIT : _AM_ALUMNI_CATEGORY_ADD,
            'category_form',
            'categories.php',
            'post',
            true
        );

        $form->addElement(new XoopsFormText(_AM_ALUMNI_CATEGORY_NAME,        'name',          50, 100, $category->getVar('name',          'e')), true);
        $form->addElement(new XoopsFormTextArea(_AM_ALUMNI_CATEGORY_DESCRIPTION, 'description', $category->getVar('description', 'e'), 3, 60));
        $form->addElement(new XoopsFormText(_AM_ALUMNI_CATEGORY_WEIGHT,      'display_order', 10, 10,  $category->getVar('display_order', 'e')));

        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormHidden('category_id', $categoryId));
        $form->addElement(new XoopsFormButton('', 'submit', _AM_ALUMNI_ACTION_SAVE, 'submit'));

        echo $form->render();
        break;

    case 'save':
        // CSRF check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('categories.php', 3, _AM_ALUMNI_ERROR_GENERAL);
        }

        // Get category
        if ($categoryId > 0) {
            $category = $categoryHandler->get($categoryId);
            if (!$category) {
                redirect_header('categories.php', 3, _AM_ALUMNI_ERROR_NOT_FOUND);
            }
        } else {
            $category = $categoryHandler->create();
        }

        // Set values
        $category->setVar('name',          $_POST['name']);
        $category->setVar('description',   $_POST['description']);
        $category->setVar('display_order', (int)$_POST['display_order']);

        // Save
        if ($categoryHandler->insert($category)) {
            redirect_header('categories.php', 3, $categoryId > 0 ? _AM_ALUMNI_SUCCESS_CATEGORY_UPDATED : _AM_ALUMNI_SUCCESS_CATEGORY_ADDED);
        } else {
            redirect_header('categories.php', 3, _AM_ALUMNI_ERROR_SAVE);
        }
        break;

    case 'delete':
        // CSRF check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('categories.php', 3, _AM_ALUMNI_ERROR_GENERAL);
        }

        if ($categoryId > 0) {
            $category = $categoryHandler->get($categoryId);
            if ($category && $category->getVar('event_count') == 0) {
                if ($categoryHandler->delete($category)) {
                    redirect_header('categories.php', 3, _AM_ALUMNI_SUCCESS_CATEGORY_DELETED);
                } else {
                    redirect_header('categories.php', 3, _AM_ALUMNI_ERROR_DELETE);
                }
            } else {
                redirect_header('categories.php', 3, _AM_ALUMNI_WARNING_CATEGORY_NOT_EMPTY);
            }
        }
        redirect_header('categories.php', 3, _AM_ALUMNI_ERROR_INVALID_ID);
        break;
}

require __DIR__ . '/admin_footer.php';
