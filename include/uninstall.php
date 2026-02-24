<?php
/**
 * Alumni Module - Uninstallation Handler
 *
 * @package   Alumni
 * @author    XOOPS Development Team
 * @copyright (c) 2025 XOOPS Project
 * @license   GPL 2.0 or later
 */

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * Uninstallation handler for Alumni module
 *
 * @param XoopsModule $module
 * @return bool
 */
function xoops_module_uninstall_alumni(XoopsModule $module)
{
    $success = true;
    $errors = [];

    // Delete uploaded files
    $uploadPath = XOOPS_UPLOAD_PATH . '/alumni';

    if (is_dir($uploadPath)) {
        try {
            deleteDirectory($uploadPath);
        } catch (Exception $e) {
            $errors[] = sprintf('Failed to delete upload directory: %s', $e->getMessage());
            $success = false;
        }
    }

    // Clear module cache
    if (class_exists('XoopsCache')) {
        XoopsCache::delete('alumni_*');
    }

    // Clear Smarty cache for module templates
    $xoops = Xoops::getInstance();
    if (isset($xoops->tpl) && method_exists($xoops->tpl, 'clearCache')) {
        $xoops->tpl->clearCache('db:alumni_*');
    }

    // Report results
    if (!empty($errors)) {
        error_log('Alumni module uninstallation warnings: ' . implode('; ', $errors));
    }

    return $success;
}

/**
 * Recursively delete a directory and its contents
 *
 * @param string $dir Directory path
 * @return bool
 */
function deleteDirectory($dir)
{
    if (!is_dir($dir)) {
        return true;
    }

    $files = array_diff(scandir($dir), ['.', '..']);

    foreach ($files as $file) {
        $path = $dir . DIRECTORY_SEPARATOR . $file;

        if (is_dir($path)) {
            deleteDirectory($path);
        } else {
            if (!unlink($path)) {
                throw new Exception('Cannot delete file: ' . $path);
            }
        }
    }

    if (!rmdir($dir)) {
        throw new Exception('Cannot delete directory: ' . $dir);
    }

    return true;
}
