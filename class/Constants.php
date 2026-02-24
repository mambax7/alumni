<?php

declare(strict_types=1);

namespace XoopsModules\Alumni;

use function define;
use function defined;

/**
 * Constants — defines all module-level PHP constants.
 *
 * Call Constants::define() once per request, right after the autoloader is
 * loaded and XOOPS_ROOT_PATH / XOOPS_URL are available.
 *
 * Using a class keeps the definition logic inside the autoloaded tree, so
 * any entry-point (index.php, admin/*.php, etc.) that loads the autoloader
 * can call this without pulling in a separate include file.
 *
 * @copyright XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 */
class Constants
{
    public const DIRNAME = 'alumni';

    /**
     * Define all ALUMNI_* PHP constants for this request.
     *
     * Safe to call multiple times — every constant is guarded with defined().
     */
    public static function define(): void
    {
        defined('ALUMNI_DIRNAME') || define('ALUMNI_DIRNAME', self::DIRNAME);
        defined('ALUMNI_URL') || define('ALUMNI_URL', XOOPS_URL . '/modules/' . self::DIRNAME);
        defined('ALUMNI_ROOT_PATH') || define('ALUMNI_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . self::DIRNAME);
        defined('ALUMNI_UPLOAD_PATH') || define('ALUMNI_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . self::DIRNAME);
        defined('ALUMNI_UPLOAD_URL') || define('ALUMNI_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . self::DIRNAME);
    }
}
