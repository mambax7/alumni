<?php

/**
 * Alumni Language file - Common (English)
 *
 * Constants prefixed with _CO_ALUMNI_ are used by shared components
 * (TestdataButtons, Blocksadmin, etc.) and by the testdata/index.php script.
 *
 * Naming convention: _CO_<MODULENAME>_<KEY>
 *   - Leading underscore (_CO_) matches the XOOPS standard for all language constants.
 *   - Module name is hardcoded (not built dynamically).
 *
 * @copyright XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 */

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

// ---- Sample / test data buttons -----------------------------------------
define('_CO_ALUMNI_LOAD_SAMPLEDATA',          'Import Sample Data (will delete ALL current data)');
define('_CO_ALUMNI_LOAD_SAMPLEDATA_CONFIRM',  'Are you sure you want to Import Sample Data? This will DELETE ALL current module data and replace it with sample records.');
define('_CO_ALUMNI_LOAD_SAMPLEDATA_SUCCESS',  'Sample data imported successfully.');
define('_CO_ALUMNI_SAVE_SAMPLEDATA',          'Export Tables to YAML');
define('_CO_ALUMNI_SAVE_SAMPLEDATA_SUCCESS',  'Tables exported to YAML successfully.');
define('_CO_ALUMNI_CLEAR_SAMPLEDATA',         'Clear Sample Data');
define('_CO_ALUMNI_CLEAR_SAMPLEDATA_CONFIRM', 'Are you sure you want to Clear all Sample Data? This will TRUNCATE all module tables.');
define('_CO_ALUMNI_CLEAR_SAMPLEDATA_OK',      'All sample data has been cleared.');
define('_CO_ALUMNI_HIDE_SAMPLEDATA_BUTTONS',  'Hide the Import Buttons');
define('_CO_ALUMNI_SHOW_SAMPLEDATA_BUTTONS',  'Show the Import Buttons');
define('_CO_ALUMNI_CONFIRM',                  'Confirm');

// ---- Developer tools -----------------------------------------------------
define('_CO_ALUMNI_SHOW_DEV_TOOLS',      'Show Developer Tools?');
define('_CO_ALUMNI_SHOW_DEV_TOOLS_DESC', 'If Yes, additional developer/diagnostic tools are shown in the admin panel.');

// ---- Blocks admin --------------------------------------------------------
define('_CO_ALUMNI_BADMIN',               'Blocks Administration');
define('_CO_ALUMNI_SIDE',                 'Side');
define('_CO_ALUMNI_WEIGHT',               'Weight');
define('_CO_ALUMNI_VISIBLE',              'Visible');
define('_CO_ALUMNI_VISIBLEIN',            'Visible In');
define('_CO_ALUMNI_ACTION',               'Action');
define('_CO_ALUMNI_UPDATE_SUCCESS',       'Block updated successfully.');
define('_CO_ALUMNI_BLOCKS_CLONEBLOCK',    'Clone Block');
define('_CO_ALUMNI_DELETE_BLOCK_CONFIRM', 'Are you sure you want to delete this block?');
