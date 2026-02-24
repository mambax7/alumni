# Installation Guide

## Alumni Network Management System for XOOPS 2.5.12

### Requirements

**Minimum Requirements:**
- XOOPS 2.5.11 or higher
- PHP 7.4 or higher (compatible through PHP 8.4)
- MySQL 5.5+ or MariaDB 10.0+
- Apache 2.2+ or Nginx 1.10+
- Web server with mod_rewrite enabled (recommended)

**Recommended:**
- PHP 8.0 or higher
- MySQL 5.7+ or MariaDB 10.3+
- HTTPS enabled
- Adequate disk space for profile photos and event images (~100MB minimum)

### Installation Steps

#### Standard Installation

1. **Download the Module**
   - Extract the `alumni` folder from the archive

2. **Upload Files**
   - Upload the `alumni` folder to your XOOPS installation's `modules/` directory
   - Path should be: `your-xoops-root/modules/alumni/`

3. **Set Permissions**
   - Ensure the following directories are writable by the web server:
     ```
     uploads/alumni/ (will be created during installation)
     uploads/alumni/photos/
     uploads/alumni/events/
     ```

4. **Install via XOOPS Admin**
   - Log in to your XOOPS administration panel
   - Navigate to: **System → Modules → Install**
   - Locate "Alumni Network" in the uninstalled modules list
   - Click the **Install** icon
   - Follow the installation wizard
   - Installation will:
     - Create 9 database tables
     - Insert sample data (10 profiles, 5 events, 5 categories, 15 skills)
     - Create upload directories
     - Set default module preferences

5. **Configure Module**
   - After installation, navigate to: **System → Modules → Alumni**
   - Click **Preferences** to configure:
     - Items per page
     - Photo upload settings (max size, dimensions, allowed extensions)
     - Event image settings
     - Privacy defaults
     - Enable/disable features (events, connections, mentorship, comments)
     - Notification settings
     - RSVP and connection limits

6. **Set Permissions**
   - Navigate to: **System → Groups**
   - Set appropriate permissions for each user group:
     - View profiles
     - Create/edit profiles
     - Submit events
     - Manage connections
     - Participate in mentorship
     - Admin access

7. **Configure Blocks (Optional)**
   - Navigate to: **System → Blocks**
   - Available Alumni blocks:
     - Recent Alumni Profiles
     - Upcoming Events
     - Quick Search
     - Alumni Statistics
   - Assign blocks to desired page positions

### Post-Installation Steps

1. **Test the Installation**
   - Visit the module: `http://yoursite.com/modules/alumni/`
   - Verify sample data displays correctly
   - Test user registration and profile creation
   - Test event RSVP functionality

2. **Customize Settings**
   - Update module preferences to match your institution's needs
   - Configure privacy settings
   - Set up email notifications

3. **Remove Sample Data (Production Sites)**
   - Navigate to: **Admin → Alumni → Profiles**
   - Delete sample profiles if not needed
   - Navigate to: **Admin → Alumni → Events**
   - Delete sample events
   - Navigate to: **Admin → Alumni → Categories**
   - Adjust or remove sample categories

4. **Add Real Content**
   - Create real alumni profiles
   - Set up actual events
   - Configure meaningful categories
   - Add relevant skills

### Upgrade Instructions

**From Previous Version:**

If upgrading from an earlier version:
1. **Backup your data** - Always backup your database and files before upgrading
2. Upload new files to the `modules/alumni/` directory (overwrite existing)
3. Navigate to: **System → Modules**
4. Click the **Update** icon next to "Alumni Network"
5. Follow the update wizard

**Note:** This is the initial 1.0.0 release, so no upgrade paths exist yet.

### Uninstallation

To completely remove the module:

1. Navigate to: **System → Modules**
2. Click the **Uninstall** icon next to "Alumni Network"
3. Confirm uninstallation

**Warning:** Uninstallation will:
- Delete all database tables and data (profiles, events, connections, etc.)
- Remove uploaded files (photos, event images)
- This action is **irreversible** - make backups first!

### Troubleshooting

**Installation Fails:**
- Check PHP version meets requirements (7.4+)
- Verify database credentials are correct
- Ensure proper file permissions on `xoops_data/` and `uploads/` directories
- Check PHP error logs for specific issues

**Blank Page After Installation:**
- Check for PHP errors in your error log
- Verify all language constants are defined
- Clear XOOPS cache: **System → Maintenance → Cache**

**Upload Directory Issues:**
- Ensure `uploads/` directory is writable
- Check PHP upload settings: `upload_max_filesize` and `post_max_size`
- Verify safe mode is disabled or properly configured

**Sample Data Not Appearing:**
- Re-run installation
- Check database tables were created: `xoops_alumni_*`
- Verify module is activated: **System → Modules**

### Support Resources

- **Module Documentation:** See README.md in module root
- **Developer Guide:** See CLAUDE.md in module root
- **Changelog:** See CHANGELOG.md in module root or changelog.txt in docs/
- **XOOPS Forums:** https://xoops.org/modules/newbb/
- **XOOPS Operations Manual:** https://xoops.org/modules/smartsection/

### Additional Notes

- The module includes comprehensive sample data for testing and demonstration
- All features can be enabled/disabled via module preferences
- Privacy controls are granular - users control who sees their information
- File uploads are validated for security (type, size, mime-type)
- All forms include CSRF protection
- SQL injection prevention via parameterized queries throughout

---

**For detailed feature documentation, see README.md**

**For developer documentation, see CLAUDE.md**
