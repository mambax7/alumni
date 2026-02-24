![XOOPS CMS](https://xoops.org/images/logoXoops4GithubRepository.png)
# Job Search Module - Developer Quick Reference

## Module Structure Overview

This module follows XOOPS 2.5.12 conventions and modern PHP best practices. The core architecture is complete with handlers, database schema, and basic frontend/admin structure.

## Quick Start Development

### 1. Essential Files Created ✅

#### Configuration
- `xoops_version.php` - Module manifest (complete)
- `include/common.php` - Common functions and autoloader

#### Database
- `sql/mysql.sql` - Complete schema (7 tables)
- `include/install.php` - Installation with sample data generator
- `include/uninstall.php` - Cleanup on uninstall
- `include/onupdate.php` - Update handler

#### Handlers (Complete MVC Models)
- `class/Job.php` - Job object + handler with search methods
- `class/Company.php` - Company object + handler
- `class/Category.php` - Category object + handler
- `class/Application.php` - Application object + handler
- `class/Favorite.php` - Favorite object + handler
- `class/Tag.php` - Tag object + handler with linking
- `class/Utility.php` - Helper utilities (file upload, sanitization)

#### Frontend
- `index.php` - Job listing page (with filters)
- `blocks.php` - Block functions (recent, featured, search)

#### Admin
- `admin/menu.php` - Admin navigation
- `admin/index.php` - Dashboard with statistics

#### Templates
- `templates/jobs_index.tpl` - Main listing template
- `templates/jobs_header.tpl` - Common header
- `templates/jobs_footer.tpl` - Common footer

#### Assets
- `assets/css/style.css` - Complete Bootstrap 5 styling
- `assets/js/jobs.js` - Frontend JavaScript
- `assets/images/logo.png` - Module logo (generated)
- `assets/images/default-company.png` - Placeholder (generated)

#### Language Files
- `language/english/modinfo.php` - Module info constants
- `language/english/main.php` - Frontend constants
- `language/english/admin.php` - Admin constants
- `language/english/blocks.php` - Block constants

## 📝 What's Complete

### ✅ Core Infrastructure (100%)
- [x] Module manifest with all configs
- [x] Database schema (7 tables, fully indexed)
- [x] All 7 handler classes with CRUD methods
- [x] Installation script with sample data
- [x] Language files (complete set)
- [x] Common functions and utilities

### ✅ Security (100%)
- [x] SQL injection prevention (parameterized queries)
- [x] XSS protection (sanitization)
- [x] File upload validation
- [x] Permission checks
- [x] Unique filename generation

### ✅ Admin Panel (30%)
- [x] Admin menu structure
- [x] Dashboard with statistics
- [x] Xmf\Module\Admin integration
- [ ] CRUD pages (jobs, companies, categories, applications, tags)

### ✅ Frontend (20%)
- [x] Job listing with pagination
- [x] Search/filter functionality
- [ ] Job detail page
- [ ] Company profile page
- [ ] Application form
- [ ] User dashboard
- [ ] Search page

### ✅ Blocks (80%)
- [x] Block functions (show/edit)
- [ ] Block templates

### ✅ Assets (90%)
- [x] CSS (complete, responsive)
- [x] JavaScript (basic interactions)
- [x] Placeholder images
- [ ] Admin icons (16x16, 32x32)

## 🚀 Next Development Steps

### Phase 1: Core Functionality (Priority)

#### 1. Create Frontend Pages
```php
job.php            // Job detail with apply button
apply.php          // Application form with resume upload
search.php         // Advanced search
company.php        // Company profile
dashboard.php      // User dashboard
submit.php         // Job submission form
companies.php      // Company directory
```

#### 2. Create Corresponding Templates
```smarty
jobs_detail.tpl        // Job detail layout
jobs_apply.tpl         // Application form
jobs_search.tpl        // Search interface
jobs_company.tpl       // Company profile
jobs_dashboard.tpl     // User dashboard
jobs_submit.tpl        // Job submission
```

#### 3. Complete Admin CRUD
```php
admin/jobs.php          // Job management (list, add, edit, delete)
admin/companies.php     // Company management
admin/categories.php    // Category management
admin/applications.php  // Application management
admin/tags.php          // Tag management
admin/about.php         // About page
```

### Phase 2: Enhanced Features

#### 1. Block Templates
```smarty
blocks/jobs_block_recent.tpl
blocks/jobs_block_featured.tpl
blocks/jobs_block_search.tpl
```

#### 2. AJAX Features
```php
ajax.php  // Handle AJAX requests (favorites, etc.)
```

#### 3. Email Notifications
- New job notification to admin
- Application received confirmation
- Application status change notification

## 🔧 Development Patterns

### Getting a Handler
```php
$jobHandler = jobs_getHandler('job');
$companyHandler = jobs_getHandler('company');
```

### Creating an Object
```php
$job = $jobHandler->create();
$job->setVar('title', 'Senior Developer');
$job->setVar('company_id', 1);
// ... set other fields
$jobHandler->insert($job);
```

### Searching with Criteria
```php
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('status', 'active'));
$criteria->add(new Criteria('category_id', 1));
$criteria->setLimit(20);
$criteria->setStart(0);
$jobs = $jobHandler->getObjects($criteria);
```

### File Upload
```php
$result = JobsUtility::uploadFile(
    $_FILES['resume'],
    XOOPS_UPLOAD_PATH . '/jobs/resumes',
    ['pdf', 'doc', 'docx'],
    5242880 // 5MB
);

if ($result['success']) {
    $filename = $result['filename'];
} else {
    $error = $result['error'];
}
```

### Sanitizing Output
```php
$clean = JobsUtility::sanitizeHtml($userInput);
```

## 📋 Code Examples

### Example: Job Detail Page (job.php)
```php
<?php
require_once __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/include/common.php';

$jobId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($jobId === 0) {
    redirect_header('index.php', 3, _MD_JOBS_ERROR_INVALID_JOB);
}

$jobHandler = jobs_getHandler('job');
$job = $jobHandler->get($jobId);

if (!$job || !$job->isActive()) {
    redirect_header('index.php', 3, _MD_JOBS_ERROR_INVALID_JOB);
}

// Increment views
$jobHandler->incrementViews($jobId);

// Get related data
$companyHandler = jobs_getHandler('company');
$company = $companyHandler->get($job->getVar('company_id'));

// Template assignment
$GLOBALS['xoopsOption']['template_main'] = 'jobs_detail.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

$xoopsTpl->assign('job', [
    'id' => $job->getVar('job_id'),
    'title' => $job->getVar('title'),
    'description' => $job->getVar('description'),
    // ... more fields
]);

require_once XOOPS_ROOT_PATH . '/footer.php';
```

### Example: Admin CRUD (admin/jobs.php)
```php
<?php
require_once __DIR__ . '/../../../include/cp_header.php';
require_once dirname(__DIR__) . '/include/common.php';

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';

switch ($op) {
    case 'list':
        // Show job listing table
        break;

    case 'edit':
        // Show edit form
        break;

    case 'save':
        // Handle form submission
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('jobs.php', 3, _AM_JOBS_ERROR_SAVE);
        }
        // Save logic here
        break;

    case 'delete':
        // Delete job
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('jobs.php', 3, _AM_JOBS_ERROR_DELETE);
        }
        // Delete logic here
        break;
}
```

## 🗄️ Database Table Reference

### jobs_jobs
Primary job listings table
- Key indexes: `status`, `featured`, `category_id`, `company_id`
- FULLTEXT: `title`, `description`

### jobs_companies
Company profiles
- Key fields: `name`, `logo`, `website`, `active_jobs`

### jobs_categories
Job categories
- Key fields: `name`, `display_order`, `job_count`

### jobs_applications
Application submissions
- Key indexes: `job_id`, `user_id`, `status`
- Files stored in: `/uploads/jobs/resumes/`

### jobs_favorites
User saved jobs
- Unique index on: `user_id`, `job_id`

### jobs_tags
Tag system
- Unique index on: `name`

### jobs_job_tag_link
Many-to-many job-tag relationship

## 🎨 Template Variables

### jobs_index.tpl
```smarty
{$jobs}           // Array of job objects
{$categories}     // Array of categories
{$total_jobs}     // Total count
{$pagenav}        // Pagination HTML
{$job_types}      // Job type options
```

### Smarty Delimiters
**IMPORTANT**: XOOPS uses custom delimiters!
```smarty
<{$variable}>           // Output variable
<{if $condition}>       // Conditional
<{foreach}>             // Loop
<{include file="db:template.tpl"}>  // Include
```

## ⚙️ Configuration Access

```php
// Get module config
$perPage = jobs_getConfig('per_page');
$enableApproval = jobs_getConfig('enable_approval');

// Get all config
$config = jobs_getConfig();
```

## 🔐 Permission Checks

```php
// Check if user is logged in
if (!jobs_isUserLoggedIn()) {
    redirect_header('login.php', 3, _MD_JOBS_ERROR_LOGIN_REQUIRED);
}

// Check if user is admin
if (!jobs_isUserAdmin()) {
    redirect_header('index.php', 3, _NOPERM);
}

// Get current user ID
$userId = jobs_getCurrentUserId();
```

## 📦 Helper Functions

### Common Functions (include/common.php)
```php
jobs_getHandler($name)              // Get handler instance
jobs_getConfig($key)                // Get configuration
jobs_isUserLoggedIn()               // Check login status
jobs_isUserAdmin()                  // Check admin status
jobs_getCurrentUserId()             // Get current user ID
jobs_getJobTypeOptions()            // Get job type array
jobs_getExperienceLevelOptions()    // Get experience levels
jobs_generatePagination()           // Generate pagination HTML
```

### Utility Functions (class/Utility.php)
```php
JobsUtility::uploadFile()           // Handle file upload
JobsUtility::deleteFile()           // Delete file
JobsUtility::sanitizeHtml()         // Sanitize HTML
JobsUtility::formatDate()           // Format timestamp
JobsUtility::getTimeAgo()           // Get relative time
JobsUtility::truncate()             // Truncate text
JobsUtility::redirect()             // Redirect with message
JobsUtility::sendEmail()            // Send email
```

## 🧪 Testing Checklist

### Installation Testing
- [ ] Module installs without errors
- [ ] All database tables created
- [ ] Sample data inserted correctly
- [ ] Upload directories created
- [ ] Permissions set correctly

### Frontend Testing
- [ ] Job listings display
- [ ] Filters work correctly
- [ ] Pagination functions
- [ ] Job detail page loads
- [ ] Application form works
- [ ] File upload succeeds
- [ ] Favorites save

### Admin Testing
- [ ] Dashboard shows statistics
- [ ] Job CRUD operations
- [ ] Company management
- [ ] Category management
- [ ] Application review
- [ ] Status changes work

### Security Testing
- [ ] SQL injection attempts fail
- [ ] XSS attempts are sanitized
- [ ] Invalid file uploads rejected
- [ ] Unauthenticated access blocked
- [ ] Permission checks work

## 📚 Additional Resources

### XOOPS Documentation
- API Reference: https://api.xoops.org/
- Module Development: https://xoops.org/modules/wfchannel/
- Forums: https://xoops.org/modules/newbb/

### Tools
- PHPStan: For static analysis
- XOOPS Debug: Enable XOOPS_DEBUG in mainfile.php
- MySQL Workbench: Database design tool

### Standards
- PSR-12: PHP coding standard
- XOOPS Conventions: Language constants, file structure
- Bootstrap 5: UI framework

## 🐛 Common Issues & Solutions

**Issue**: Handlers not found
**Solution**: Check class autoloading in include/common.php

**Issue**: Templates not updating
**Solution**: Clear templates_c cache directory

**Issue**: File uploads failing
**Solution**: Check directory permissions and PHP upload limits

**Issue**: Database errors
**Solution**: Verify table prefix matches XOOPS installation

**Issue**: Permission denied
**Solution**: Set group permissions in System Admin → Groups

## 📞 Support

For questions or issues:
1. Check README.md
2. Review PROJECT_SUMMARY.md
3. Consult XOOPS forums
4. Review code comments
5. Check XOOPS API documentation

---

**Last Updated**: 2026-02-15
**Module Version**: 1.0.0
**XOOPS Version**: 2.5.12
