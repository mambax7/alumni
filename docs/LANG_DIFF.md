![XOOPS CMS](https://xoops.org/images/logoXoops4GithubRepository.png)
# Language Differences

## Alumni Network Management System - Language Constant Changes Between Versions

This document tracks language constant additions, modifications, and deprecations across module versions.

---

## Version 1.0.0 (2026-02-16) - Initial Release

### Complete Language File Structure

This is the initial release, establishing the complete language constant structure:

#### modinfo.php (Module Info)
- **Prefix:** `_MI_ALUMNI_`
- **Total Constants:** 75+
- **Categories:**
  - Basic module info (name, description)
  - Block names and descriptions (4 blocks)
  - Configuration options (30+ settings)
  - Menu items and submenus (9 items)
  - Privacy levels (3 constants)

#### main.php (Frontend)
- **Prefix:** `_MD_ALUMNI_`
- **Total Constants:** 200+
- **Categories:**
  - Navigation and UI labels
  - Form fields and labels
  - Error messages (20+ constants)
  - Success messages (4+ constants)
  - Profile fields and sections
  - Event types (9 constants)
  - Time display (6 constants)
  - Privacy labels
  - Mentorship constants (7+ constants)
  - Search and filter labels
  - Dashboard elements
  - Connection status labels
  - RSVP status labels

#### admin.php (Admin Panel)
- **Prefix:** `_AM_ALUMNI_`
- **Total Constants:** 150+
- **Categories:**
  - Admin menu items
  - Dashboard statistics
  - CRUD operation labels
  - Column headers (tables)
  - Button labels
  - Filter and search labels
  - Status messages
  - Confirmation prompts
  - Error messages
  - Export labels
  - Management sections

#### blocks.php (Blocks)
- **Prefix:** `_MB_ALUMNI_`
- **Total Constants:** 20+
- **Categories:**
  - Block configuration options
  - Display labels
  - Filter options
  - View more links

---

## Future Versions

### Version 1.1.0 (Planned)

**Expected Additions:**

#### Email Notifications
```php
// main.php
define('_MD_ALUMNI_EMAIL_NEW_CONNECTION', 'New Connection Request');
define('_MD_ALUMNI_EMAIL_CONNECTION_ACCEPTED', 'Connection Request Accepted');
define('_MD_ALUMNI_EMAIL_EVENT_REMINDER', 'Event Reminder');
define('_MD_ALUMNI_EMAIL_MENTORSHIP_REQUEST', 'New Mentorship Request');
```

#### PDF Export
```php
// admin.php
define('_AM_ALUMNI_EXPORT_PDF', 'Export to PDF');
define('_AM_ALUMNI_PDF_GENERATED', 'PDF generated successfully');
```

#### CSV Import
```php
// admin.php
define('_AM_ALUMNI_IMPORT_CSV', 'Import from CSV');
define('_AM_ALUMNI_IMPORT_SUCCESS', '%d profiles imported successfully');
define('_AM_ALUMNI_IMPORT_ERROR', 'Import error on line %d');
```

### Version 1.2.0 (Planned)

**Expected Additions:**

#### Multi-Degree Support
```php
// main.php
define('_MD_ALUMNI_DEGREES', 'Degrees');
define('_MD_ALUMNI_ADD_DEGREE', 'Add Another Degree');
define('_MD_ALUMNI_PRIMARY_DEGREE', 'Primary Degree');
```

#### Job Board Integration
```php
// main.php
define('_MD_ALUMNI_JOB_BOARD', 'Job Board');
define('_MD_ALUMNI_POST_JOB', 'Post a Job');
define('_MD_ALUMNI_BROWSE_JOBS', 'Browse Jobs');
```

---

## Naming Conventions

### Prefix Rules (CRITICAL)

**Always use the correct prefix for each file:**

- **modinfo.php** → `_MI_ALUMNI_` (Module Info)
- **main.php** → `_MD_ALUMNI_` (Main/Frontend)
- **admin.php** → `_AM_ALUMNI_` (Admin)
- **blocks.php** → `_MB_ALUMNI_` (Blocks)

### Pattern Examples

```php
// CORRECT
define('_AM_ALUMNI_DASHBOARD', 'Dashboard');        // admin.php
define('_MD_ALUMNI_BROWSE_ALUMNI', 'Browse Alumni'); // main.php
define('_MI_ALUMNI_NAME', 'Alumni Network');        // modinfo.php
define('_MB_ALUMNI_LIMIT', 'Number of items');      // blocks.php

// WRONG - Incorrect prefix
define('_ALUMNI_AM_DASHBOARD', 'Dashboard');        // Wrong order!
define('_MD_ADMIN_SOMETHING', 'Something');         // Mixed prefixes!
```

### Constant Naming Guidelines

1. **Use descriptive names:**
   - `_MD_ALUMNI_ERROR_INVALID_PROFILE` ✅
   - `_MD_ALUMNI_ERR1` ❌

2. **Group related constants:**
   - `_MD_ALUMNI_ERROR_*` for errors
   - `_MD_ALUMNI_SUCCESS_*` for success messages
   - `_MD_ALUMNI_EVENT_TYPE_*` for event types

3. **Use consistent verb forms:**
   - `_AM_ALUMNI_PROFILE_SAVED` (past tense for messages)
   - `_MD_ALUMNI_SAVE_PROFILE` (imperative for buttons)

---

## Translation Notes

### For Translators

When creating a new language translation:

1. **Copy the English files** from `language/english/` to `language/your_language/`
2. **Keep all constant names identical** - only translate the VALUES
3. **Maintain placeholder syntax** - e.g., `%s`, `%d`, `%u` in strings
4. **Test thoroughly** - one missing constant can break the entire module

### Example Translation

```php
// English (language/english/main.php)
define('_MD_ALUMNI_RESULTS_FOUND', '%d results found');

// French (language/french/main.php)
define('_MD_ALUMNI_RESULTS_FOUND', '%d résultats trouvés');

// Spanish (language/spanish/main.php)
define('_MD_ALUMNI_RESULTS_FOUND', '%d resultados encontrados');
```

---

## Critical Rules

### ⚠️ NEVER DO THIS:

1. **Don't define constants in code without adding to language file:**
   ```php
   // ❌ WRONG - causes blank screen
   echo _MD_ALUMNI_NEW_CONSTANT;  // Not defined in main.php
   ```

2. **Don't use wrong prefix:**
   ```php
   // ❌ WRONG - in admin/profiles.php
   echo _MD_ALUMNI_SOMETHING;  // Should be _AM_ALUMNI_
   ```

3. **Don't duplicate constants:**
   ```php
   // ❌ WRONG - same constant twice in file
   define('_MD_ALUMNI_SAVE', 'Save');
   // ... 100 lines later ...
   define('_MD_ALUMNI_SAVE', 'Save');  // PHP 9 will error!
   ```

### ✅ ALWAYS DO THIS:

1. **Define constant immediately when used:**
   ```php
   // Step 1: Write code
   echo _MD_ALUMNI_NEW_LABEL;

   // Step 2: IMMEDIATELY add to language/english/main.php:
   define('_MD_ALUMNI_NEW_LABEL', 'New Label');
   ```

2. **Check for duplicates before adding:**
   ```bash
   grep "_MD_ALUMNI_SOMETHING" language/english/main.php
   ```

3. **Use search to verify constant exists:**
   ```bash
   grep -r "_MD_ALUMNI_CONSTANT_NAME" modules/alumni/
   ```

---

## Debugging Language Issues

### Common Errors

**"Undefined constant" error:**
- Add missing constant to appropriate language file
- Check prefix matches file context

**"Constant already defined" warning:**
- Find duplicate with: `grep "CONSTANT_NAME" file.php`
- Remove duplicate definition

**Blank page after language file edit:**
- Check for syntax errors (missing quotes, semicolons)
- Verify matching quotes: `'text'` or `"text"`
- Check for unescaped quotes inside strings: `"don\'t"` or `'don\'t'`

### Verification Commands

```bash
# Find all constants in a file
grep -oP "define\('\K[^']+(?=')" language/english/main.php | sort

# Find duplicates
grep -oP "define\('\K[^']+(?=')" language/english/main.php | sort | uniq -d

# Count constants
grep -c "^define" language/english/main.php

# Search for constant usage
grep -r "_MD_ALUMNI_SPECIFIC_CONSTANT" modules/alumni/
```

---

## Reference

For complete constant lists and usage, see:
- `language/english/modinfo.php` - Module info constants
- `language/english/main.php` - Frontend constants
- `language/english/admin.php` - Admin constants
- `language/english/blocks.php` - Block constants
- `LANGUAGE_CONSTANTS_FIXED.md` - Initial constant additions documentation

---

**Last Updated:** 2026-02-16
**Module Version:** 1.0.0
