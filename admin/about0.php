<?php

declare(strict_types=1);

/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Alumni Module v2.0.0 - About Page
 *
 * Standard XOOPS module "About" page rendered via Xmf\Module\Admin.
 * Displays module metadata (version, author, credits, license, links)
 * as defined in xoops_version.php, plus an optional PayPal donate button
 * and changelog information.
 *
 * @package         XoopsModules\Alumni
 * @copyright       Copyright (c) 2004-2026 James Cotton
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2.0 or later}
 * @author          XOOPS Module Dev Team
 * @since           2.0.0
 */

use Xmf\Module\Admin;

/** @var Admin $adminObject */
require_once __DIR__ . '/admin_header.php';
xoops_cp_header();

$adminObject->displayNavigation(basename(__FILE__));
Admin::setPaypal('xoopsfoundation@gmail.com');
$adminObject->displayAbout(false);

require_once __DIR__ . '/admin_footer.php';
