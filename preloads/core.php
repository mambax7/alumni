<?php
/**
 * Alumni Management System - Core Preload
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 * @version     1.0.0
 */

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * Class AlumniCorePreload
 */
class AlumniCorePreload extends XoopsPreloadItem
{
    /**
     * Event: core.include.common.end
     * Register PSR-4 autoloader
     *
     * @param array $args Event arguments
     */
    public static function eventCoreIncludeCommonEnd($args)
    {
        require __DIR__ . '/autoloader.php';
    }

    /**
     * Event: core.header.addmeta
     * NOTE: CSS is loaded in admin_footer.php AFTER Bootstrap
     * This ensures proper CSS cascade order
     *
     * @param array $args Event arguments
     */
    public static function eventCoreHeaderAddmeta($args)
    {
        // CSS loading handled in admin_footer.php
        // This ensures admin.css loads AFTER Bootstrap
    }

    /**
     * Event: core.header.end
     * Force-load critical CSS for admin pages to override Bootstrap
     * This fires at the very end of header rendering
     *
     * @param array $args Event arguments
     */
    public static function eventCoreHeaderEnd($args)
    {
        global $xoopsModule;

        // Only on admin pages
        if (defined('XOOPS_CP') && XOOPS_CP && $xoopsModule && $xoopsModule->getVar('dirname') === 'alumni') {
            // Add inline critical CSS to force icon visibility
            echo '<style type="text/css">
/* CRITICAL: NUCLEAR FORCE ALL ADMIN CHROME IMAGES TO DISPLAY */
/* Maximum specificity with every possible anti-hiding property */

/* Force containers visible */
body#alumni #xo-toolbar,
body#alumni div#xo-toolbar,
#xo-toolbar,
body#alumni #xo-headnav,
body#alumni div#xo-headnav,
#xo-headnav,
body#alumni #xo-footer-rss,
body#alumni div#xo-footer-rss,
#xo-footer-rss {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    overflow: visible !important;
}

/* Force ALL images in these containers visible with maximum specificity */
body#alumni #xo-toolbar img,
body#alumni div#xo-toolbar img,
#xo-toolbar img,
body#alumni #xo-headnav img,
body#alumni div#xo-headnav img,
#xo-headnav img,
body#alumni #xo-footer-rss img,
body#alumni div#xo-footer-rss img,
#xo-footer-rss img {
    display: inline-block !important;
    visibility: visible !important;
    opacity: 1 !important;
    position: static !important;
    clip: auto !important;
    clip-path: none !important;
    max-width: none !important;
    max-height: none !important;
    overflow: visible !important;
}

/* Specific sizing for toolbar icons */
body#alumni #xo-toolbar img,
body#alumni div#xo-toolbar img,
#xo-toolbar img {
    width: 32px !important;
    height: 32px !important;
}

/* Override Bootstrap visually-hidden class if applied */
body#alumni .visually-hidden img,
body#alumni .d-none img {
    display: inline-block !important;
    visibility: visible !important;
    opacity: 1 !important;
    position: static !important;
    clip: auto !important;
    width: auto !important;
    height: auto !important;
}
</style>';
        }
    }
}
