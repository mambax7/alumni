<?php
/**
 * Alumni Module - Update Handler
 *
 * @package   Alumni
 * @author    XOOPS Development Team
 * @copyright (c) 2025 XOOPS Project
 * @license   GPL 2.0 or later
 */

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * Update handler for Alumni module
 *
 * @param XoopsModule $module
 * @param mixed       $previousVersion
 * @return bool
 */
function xoops_module_update_alumni(XoopsModule $module, $previousVersion = null)
{
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $success = true;
    $errors = [];

    // Get current version
    $currentVersion = $module->getVar('version');

    // Version-specific updates
    if (version_compare($previousVersion, '1.0.0', '<')) {
        $success = updateTo100($db, $errors);
    }

    // Always perform these maintenance tasks on update

    // 1. Ensure upload directories exist
    $uploadPath = XOOPS_UPLOAD_PATH . '/alumni';
    $photosPath = $uploadPath . '/photos';
    $eventsPath = $uploadPath . '/events';

    $directories = [$uploadPath, $photosPath, $eventsPath];

    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0755, true)) {
                $errors[] = sprintf('Failed to create directory: %s', $dir);
                $success = false;
            } else {
                // Create .htaccess
                $htaccess = $dir . '/.htaccess';
                file_put_contents($htaccess, "Options -Indexes\n# Deny everything by default, then allow only image files (Apache 2.4)\nRequire all denied\n<FilesMatch \"\.(jpg|jpeg|png|gif|webp|svg)$\">\n    Require all granted\n</FilesMatch>\n");

                // Create index.html
                $indexHtml = $dir . '/index.html';
                file_put_contents($indexHtml, '<!DOCTYPE html><html><head><title></title></head><body></body></html>');
            }
        }
    }

    // 2. Clear module cache
    if (class_exists('XoopsCache')) {
        XoopsCache::delete('alumni_*');
    }

    // 3. Update module template compilation
    if (isset($GLOBALS['xoopsTpl']) && method_exists($GLOBALS['xoopsTpl'], 'clearCompiledTemplate')) {
        $GLOBALS['xoopsTpl']->clearCompiledTemplate();
    }

    // 4. Verify table indexes
    verifyIndexes($db);

    // Report results
    if (!empty($errors)) {
        error_log('Alumni module update errors: ' . implode('; ', $errors));
    }

    return $success;
}

/**
 * Update to version 1.0.0
 *
 * @param XoopsDatabase $db
 * @param array         $errors
 * @return bool
 */
function updateTo100($db, &$errors)
{
    $success = true;

    // Check if new columns need to be added to existing tables
    // This is a placeholder for future updates

    // Example: Add a new column if it doesn't exist
    /*
    $sql = "SHOW COLUMNS FROM " . $db->prefix('alumni_profiles') . " LIKE 'new_column'";
    $result = $db->query($sql);

    if ($db->getRowsNum($result) == 0) {
        $sql = "ALTER TABLE " . $db->prefix('alumni_profiles') . " ADD COLUMN new_column VARCHAR(255) DEFAULT NULL AFTER existing_column";
        if (!$db->queryF($sql)) {
            $errors[] = 'Failed to add new_column to alumni_profiles';
            $success = false;
        }
    }
    */

    return $success;
}

/**
 * Verify and create missing indexes
 *
 * @param XoopsDatabase $db
 * @return void
 */
function verifyIndexes($db)
{
    $tables = [
        'alumni_profiles' => [
            'idx_graduation_year' => 'graduation_year',
            'idx_department' => 'department',
            'idx_visibility' => 'visibility',
            'idx_created_at' => 'created_at'
        ],
        'alumni_events' => [
            'idx_category_id' => 'category_id',
            'idx_start_date' => 'start_date',
            'idx_status' => 'status',
            'idx_is_online' => 'is_online'
        ],
        'alumni_event_rsvp' => [
            'idx_event_id' => 'event_id',
            'idx_user_id' => 'user_id',
            'idx_status' => 'status'
        ],
        'alumni_connections' => [
            'idx_from_alumni' => 'from_alumni_id',
            'idx_to_alumni' => 'to_alumni_id',
            'idx_status' => 'status'
        ],
        'alumni_mentorship' => [
            'idx_mentee' => 'mentee_id',
            'idx_mentor' => 'mentor_id',
            'idx_status' => 'status'
        ],
        'alumni_skills' => [
            'idx_name' => 'name'
        ]
    ];

    foreach ($tables as $tableName => $indexes) {
        $fullTableName = $db->prefix($tableName);

        // Get existing indexes
        $sql    = "SHOW INDEX FROM " . $fullTableName;
        $result = $db->query($sql);

        $existingIndexes = [];
        if ($db->isResultSet($result)) {
            while ($row = $db->fetchArray($result)) {
                $existingIndexes[] = $row['Key_name'];
            }
        }
        // Create missing indexes
        foreach ($indexes as $indexName => $column) {
            if (!in_array($indexName, $existingIndexes)) {
                $sql = sprintf(
                    "ALTER TABLE %s ADD INDEX %s (%s)",
                    $fullTableName,
                    $indexName,
                    $column
                );
                $db->queryF($sql);
            }
        }
    }
}

/**
 * Migrate data from old structure to new structure
 * (Placeholder for future version updates)
 *
 * @param XoopsDatabase $db
 * @param array         $errors
 * @return bool
 */
function migrateData($db, &$errors)
{
    $success = true;

    // Example data migration logic
    /*
    $sql = "SELECT * FROM " . $db->prefix('old_table');
    $result = $db->query($sql);

    while ($row = $db->fetchArray($result)) {
        $insertSql = sprintf(
            "INSERT INTO %s (field1, field2) VALUES ('%s', '%s')",
            $db->prefix('new_table'),
            $db->escape($row['old_field1']),
            $db->escape($row['old_field2'])
        );

        if (!$db->queryF($insertSql)) {
            $errors[] = 'Failed to migrate row ID: ' . $row['id'];
            $success = false;
        }
    }
    */

    return $success;
}
