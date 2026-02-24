<?php

declare(strict_types=1);
/**
 * Alumni Management System - About.
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 */

use Xmf\Module\Admin;
use XoopsModules\Alumni\Utility;

/** @var Admin $adminObject */
require_once __DIR__ . '/admin_header.php';

xoops_cp_header();
Utility::addAdminAssets();

$adminObject->displayNavigation(basename(__FILE__));
Admin::setPaypal('xoopsfoundation@gmail.com');
$adminObject->displayAbout(false);

require_once __DIR__ . '/admin_footer.php';
