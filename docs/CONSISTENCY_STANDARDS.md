![XOOPS CMS](https://xoops.org/images/logoXoops4GithubRepository.png)
# Jobs Module - Consistency Standards

**CRITICAL**: All files in this module MUST follow these patterns consistently.

## File Structure Pattern

### ✅ CORRECT Pattern (Use This!)

```php
<?php

/**
 * Description
 *
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author       XOOPS Development Team
 */

use XoopsModules\Jobs\{Helper, Utility};
use Xmf\Request;

require_once __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/preloads/autoloader.php';

$helper = Helper::getInstance();
$jobHandler = $helper->getHandler('job');
```

### ❌ WRONG Pattern (Never Use!)

```php
<?php
require_once __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/include/common.php';  // ← OLD, don't use

// OLD patterns - NEVER use these:
$jobHandler = jobs_getHandler('job');          // ← WRONG
$jobHandler = xoops_getModuleHandler('job', 'jobs'); // ← WRONG
$config = jobs_getConfig('per_page');         // ← WRONG
JobsUtility::sanitizeHtml($text);             // ← WRONG
```

## Consistency Rules

### 1. Helper Access

✅ **ALWAYS**:
```php
use XoopsModules\Jobs\Helper;

$helper = Helper::getInstance();
$jobHandler = $helper->getHandler('job');
$config = $helper->getConfig('per_page');
```

❌ **NEVER**:
```php
$jobHandler = jobs_getHandler('job');
$config = jobs_getConfig('per_page');
```

### 2. Utility Class

✅ **ALWAYS**:
```php
use XoopsModules\Jobs\Utility;

$clean = Utility::sanitizeHtml($text);
$ago = Utility::getTimeAgo($timestamp);
$short = Utility::truncate($text, 200);
```

❌ **NEVER**:
```php
$clean = JobsUtility::sanitizeHtml($text);
```

### 3. Request Handling

✅ **ALWAYS**:
```php
use Xmf\Request;

$id = Request::getInt('id', 0);
$keyword = Request::getString('keyword', '');
$op = Request::getString('op', 'list');
```

❌ **NEVER**:
```php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$keyword = $_POST['keyword'] ?? '';
```

### 4. Autoloader

✅ **ALWAYS**:
```php
require_once __DIR__ . '/preloads/autoloader.php';
```

❌ **NEVER**:
```php
require_once __DIR__ . '/include/common.php';
```

### 5. Namespace Imports

✅ **ALWAYS** group imports:
```php
use XoopsModules\Jobs\{Helper, Utility};
use Xmf\Request;
use Xmf\Module\Admin;
```

❌ **NEVER** skip imports:
```php
// No use statements - then using full class names everywhere
$helper = \XoopsModules\Jobs\Helper::getInstance();
```

## File-by-File Checklist

### Frontend Pages
- ✅ index.php - UPDATED
- ✅ job.php - CORRECT
- ✅ apply.php - CORRECT
- ✅ search.php - CORRECT
- ✅ dashboard.php - CORRECT

### Admin Pages
- ✅ admin/admin_header.php - CORRECT
- ✅ admin/admin_footer.php - CORRECT
- ✅ admin/index.php - CORRECT
- ✅ admin/companies.php - CORRECT
- ⚠️ admin/jobs.php - NOT CREATED YET
- ✅ admin/applications.php - CORRECT
- ⚠️ admin/categories.php - NOT CREATED YET
- ⚠️ admin/tags.php - NOT CREATED YET

### Other Files
- ✅ blocks.php - UPDATED

### 6. XoopsPageNav (Pagination)

✅ **ALWAYS** load the class first:
```php
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
$pagenav = new XoopsPageNav($total, $limit, $start, 'start', $extraParams);
```

❌ **NEVER** forget to require:
```php
$pagenav = new XoopsPageNav($total, $limit, $start, 'start');  // ← Will fail!
```

### 7. Smarty Template Variable Checks

✅ **ALWAYS** check if array/object variables exist before using:
```smarty
<{if isset($breadcrumbs) && $breadcrumbs|@count > 0}>
    <{foreach item=crumb from=$breadcrumbs}>
        <{$crumb.title}>
    <{/foreach}>
<{/if}>
```

❌ **NEVER** assume variables exist:
```smarty
<{if $breadcrumbs}>  <!-- ← Will cause "Undefined array key" error -->
    <{foreach item=crumb from=$breadcrumbs}>
        <{$crumb.title}>
    <{/foreach}>
<{/if}>
```

## Code Review Checklist

Before committing any file, verify:

- [ ] Uses `use XoopsModules\Jobs\{Helper, Utility};`
- [ ] Uses `require_once __DIR__ . '/preloads/autoloader.php';`
- [ ] Gets handlers via `$helper->getHandler('name')`
- [ ] Gets config via `$helper->getConfig('key')`
- [ ] Uses `Utility::` not `JobsUtility::`
- [ ] Uses `Request::` for input handling
- [ ] Uses namespaced class names
- [ ] No `jobs_getHandler()` or `jobs_getConfig()` calls
- [ ] No `include/common.php` includes
- [ ] Loads `XoopsPageNav` class before using it
- [ ] Follows PSR-12 formatting

## Migration Notes

If you see old patterns in any file:

1. **Add namespace imports** at the top
2. **Load autoloader** instead of common.php
3. **Get Helper instance** once
4. **Replace all** `jobs_getHandler()` → `$helper->getHandler()`
5. **Replace all** `jobs_getConfig()` → `$helper->getConfig()`
6. **Replace all** `JobsUtility::` → `Utility::`
7. **Replace all** `$_GET/$_POST` → `Request::getType()`

## Example Migration

### Before (OLD):
```php
<?php
require_once __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/include/common.php';

$jobHandler = jobs_getHandler('job');
$perPage = jobs_getConfig('per_page');
$title = JobsUtility::sanitizeHtml($_GET['title']);
```

### After (NEW):
```php
<?php

use XoopsModules\Jobs\{Helper, Utility};
use Xmf\Request;

require_once __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/preloads/autoloader.php';

$helper = Helper::getInstance();
$jobHandler = $helper->getHandler('job');
$perPage = $helper->getConfig('per_page');
$title = Utility::sanitizeHtml(Request::getString('title', ''));
```

---

**REMEMBER**: Consistency across all files is CRITICAL for maintainability!
