<?php
/**
 * Alumni Management System - Common bootstrap
 *
 * Loads the autoloader and defines module-level constants via Constants::define().
 * All helper logic lives in XoopsModules\Alumni\Utility — call it directly.
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 * @version     1.0.0
 */

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

// Load autoloader (also makes Constants class available)
require_once dirname(__DIR__) . '/preloads/autoloader.php';

// Define all ALUMNI_* constants via the Constants class
\XoopsModules\Alumni\Constants::define();
