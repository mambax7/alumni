# Alumni Network Management System

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/XoopsModules25x/alumni/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/XoopsModules25x/alumni/?branch=main)
[![GitHub Actions](https://github.com/XoopsModules25x/alumni/workflows/CodeQL/badge.svg)](https://github.com/XoopsModules25x/alumni/actions)
![XOOPS Module](https://img.shields.io/badge/XOOPS-2.5.12-blue.svg)
![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-brightgreen.svg)
![License](https://img.shields.io/badge/License-GPL%202.0-red.svg)
![Version](https://img.shields.io/badge/Version-1.0.0-orange.svg)

A comprehensive alumni network management system for XOOPS 2.5.12 that enables educational institutions to maintain strong connections with their graduates through profiles, events, mentorship programs, and networking features.

---

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [User Guide](#user-guide)
- [Administrator Guide](#administrator-guide)
- [Troubleshooting](#troubleshooting)
- [FAQ](#faq)
- [Support](#support)
- [Credits](#credits)
- [License](#license)

---

## Features

### 🎓 Profile Management
- **Complete Alumni Profiles**: Personal, academic, and professional information
- **Photo Uploads**: Profile photos with automatic resizing
- **Privacy Controls**: Granular privacy settings (public, alumni-only, private)
- **Skills Tracking**: Tag and categorize professional skills
- **Social Integration**: LinkedIn, Twitter, Facebook, and website links
- **View Counters**: Track profile visibility and engagement

### 🤝 Networking & Connections
- **Connection System**: Send and accept connection requests
- **Connection Management**: View, accept, decline, and remove connections
- **Status Tracking**: Pending, accepted, declined, and blocked states
- **Connection Limits**: Configurable maximum connections per user
- **Privacy Aware**: Respects user networking preferences

### 📅 Event Management
- **Event Types**: Physical, online, and hybrid events
- **RSVP System**: Attend, maybe, or decline with guest count
- **Event Categories**: Organize by reunion, career fair, networking, etc.
- **Registration Deadlines**: Automatic deadline enforcement
- **Capacity Management**: Set maximum attendees
- **Featured Events**: Highlight important events
- **Event Images**: Visual event promotion
- **Attendee Lists**: See who's attending events

### 👨‍🏫 Mentorship Program
- **Mentor Availability**: Alumni can offer to mentor
- **Mentorship Requests**: Students/alumni can request mentors
- **Status Tracking**: Active, pending, completed, declined
- **Mentorship Area**: Specify expertise areas
- **Request Management**: Accept or decline mentorship requests

### 🔍 Advanced Search
- **Multiple Filters**: Name, year, industry, location, company, position
- **Skill Search**: Find alumni by specific skills
- **Year Range**: Search within graduation year ranges
- **Keyword Search**: Full-text search across profiles
- **Mentor Search**: Find available mentors
- **Location Search**: City, state, country filters

### 📊 Dashboard & Analytics
- **User Dashboard**: Personal stats, connections, upcoming events
- **Admin Dashboard**: Comprehensive statistics and insights
- **Profile Views**: Track engagement metrics
- **Connection Stats**: Monitor network growth
- **Event Analytics**: RSVP counts and attendance tracking

### 🧩 Blocks & Widgets
- **Recent Alumni Block**: Display newest profiles
- **Upcoming Events Block**: Show upcoming events
- **Quick Search Block**: Fast profile search
- **Statistics Block**: Alumni network stats

### 🔒 Security & Privacy
- **CSRF Protection**: All forms secured with tokens
- **SQL Injection Prevention**: Parameterized queries throughout
- **XSS Protection**: Output sanitization everywhere
- **File Upload Validation**: Secure image uploads
- **Permission Checks**: Role-based access control
- **Privacy Settings**: User-controlled visibility

### 📱 Modern Design
- **Bootstrap 5**: Responsive, mobile-first design
- **Font Awesome 6**: Professional iconography
- **Grid/List Views**: Flexible display options
- **Accessibility**: ARIA labels and semantic HTML
- **Mobile Responsive**: Works on all devices

---

## Requirements

### System Requirements

| Component | Minimum Version | Recommended |
|-----------|----------------|-------------|
| **XOOPS** | 2.5.11 | 2.5.12 |
| **PHP** | 7.4.0 | 8.0+ |
| **MySQL** | 5.5 | 5.7+ / 8.0+ |
| **Web Server** | Apache 2.2 / Nginx 1.10 | Apache 2.4 / Nginx 1.18+ |
| **PHP Memory** | 128M | 256M+ |
| **Upload Max** | 8M | 16M+ |

### PHP Extensions Required

- `mysqli` or `pdo_mysql`
- `gd` or `imagick` (for image processing)
- `mbstring` (for multibyte strings)
- `json` (for data handling)
- `fileinfo` (for file uploads)

### XOOPS Modules (Optional)

- **XMF Library**: Module framework (recommended)
- **Publisher**: Content publishing
- **System**: Core system module

---

## Installation

### Step 1: Download and Extract

1. Download the Alumni module package
2. Extract to `/modules/alumni/` in your XOOPS installation

```bash
cd /path/to/xoops/modules
unzip alumni-1.0.0.zip
# Should create: modules/alumni/
```

### Step 2: Set Permissions

```bash
# Linux/Unix
chmod 755 modules/alumni
chmod -R 755 modules/alumni/uploads
chmod -R 755 uploads/alumni

# Create upload directories if they don't exist
mkdir -p uploads/alumni/photos
mkdir -p uploads/alumni/events
chmod 755 uploads/alumni/photos
chmod 755 uploads/alumni/events
```

**Windows**: Right-click folders → Properties → Security → Ensure IIS/Apache user has write permissions.

### Step 3: Install via XOOPS Admin

1. Login to XOOPS Administration
2. Navigate to **System → Modules**
3. Find **Alumni Network** in the uninstalled modules list
4. Click **Install** icon
5. Follow the installation wizard
6. Installation will create:
   - 9 database tables
   - Sample data (10 profiles, 5 events, 5 categories)
   - Required directories

### Step 4: Configure Permissions

1. Go to **System → Groups**
2. Set permissions for:
   - **Anonymous Users**: View public profiles, events
   - **Registered Users**: Create profile, connect, RSVP
   - **Administrators**: Full access

### Step 5: Verify Installation

1. Navigate to `/modules/alumni/`
2. Check that sample alumni profiles display
3. Verify admin panel: `/modules/alumni/admin/`
4. Test block installation: **System → Blocks**

---

## Configuration

### Module Preferences

Access via: **System → Modules → Alumni → Preferences**

#### Display Settings

| Setting | Description | Default |
|---------|-------------|---------|
| **Profiles Per Page** | Number of profiles per page | 20 |
| **Events Per Page** | Number of events per page | 10 |

#### Approval Workflow

| Setting | Description | Default |
|---------|-------------|---------|
| **Enable Approval** | Require admin approval for new profiles | Yes |
| **Auto-Approve Members** | Automatically approve registered users | No |

#### Photo Settings

| Setting | Description | Default |
|---------|-------------|---------|
| **Max Photo Size** | Maximum file size in bytes | 2 MB |
| **Allowed Extensions** | Photo file types | jpg, jpeg, png, gif |
| **Max Width** | Maximum image width | 800px |
| **Max Height** | Maximum image height | 800px |

#### Event Image Settings

| Setting | Description | Default |
|---------|-------------|---------|
| **Max Image Size** | Maximum file size for events | 3 MB |
| **Allowed Extensions** | Event image types | jpg, jpeg, png, gif |

#### Feature Toggles

| Setting | Description | Default |
|---------|-------------|---------|
| **Enable Mentorship** | Mentorship program | On |
| **Enable Connections** | Connection system | On |
| **Enable Events** | Event management | On |
| **Enable Comments** | Profile comments | On |

#### Privacy Defaults

| Setting | Options | Default |
|---------|---------|---------|
| **Email Privacy** | Public / Alumni / Private | Alumni |
| **Phone Privacy** | Public / Alumni / Private | Private |
| **Profile Privacy** | Public / Alumni / Private | Alumni |

#### Limits

| Setting | Description | Default |
|---------|-------------|---------|
| **Max Connections** | Maximum connections per user | 500 |
| **Max Event Attendees** | Max attendees (0 = unlimited) | 0 |

#### Notification Settings

| Setting | Description | Default |
|---------|-------------|---------|
| **Notify Admin - New Profile** | Email admin on new profiles | Yes |
| **Notify Admin - New Event** | Email admin on new events | Yes |
| **Notify - Connection Request** | Email on connection requests | Yes |
| **Notify - Mentorship Request** | Email on mentorship requests | Yes |
| **Notify - Event RSVP** | Email event organizer on RSVPs | Yes |

---

## User Guide

### Creating Your Profile

1. **Login** to your XOOPS account
2. Navigate to **Alumni → My Profile**
3. Click **Edit Profile**
4. Fill in information:
   - **Personal**: Name, photo, bio
   - **Academic**: Graduation year, degree, major
   - **Professional**: Job title, company, industry, skills
   - **Contact**: Email, phone, website, social links
   - **Settings**: Privacy controls, mentorship availability
5. Click **Save Changes**
6. Profile will be pending approval (if enabled)

### Searching for Alumni

#### Quick Search
1. Use the search block or go to **Alumni → Search**
2. Enter keywords, graduation year, or location
3. Click **Search**

#### Advanced Search
1. Navigate to **Alumni → Advanced Search**
2. Use multiple filters:
   - Name, graduation year range
   - Industry, company, position
   - Location (city, state, country)
   - Skills
   - Mentorship availability
3. Click **Search** to see results

### Connecting with Alumni

1. Visit an alumni profile
2. Click **Connect** button
3. Wait for them to accept your request
4. Manage connections in **My Dashboard → Connections**

#### Connection Actions
- **Accept**: Approve a pending connection request
- **Decline**: Reject a connection request
- **Remove**: Disconnect from an existing connection
- **Cancel**: Withdraw a pending sent request

### Viewing Events

1. Navigate to **Alumni → Events**
2. Filter by:
   - **Upcoming**: Future events only
   - **Past**: Historical events
   - **My Events**: Events you've RSVP'd to
3. Click event for details

### RSVPing to Events

1. Open event detail page
2. Select RSVP status:
   - **Attending**
   - **Maybe**
   - **Not Attending**
3. Enter number of guests (if allowed)
4. Add optional notes
5. Click **Update RSVP**
6. Change or cancel anytime before event

### Using the Dashboard

Access: **Alumni → Dashboard**

**Dashboard Sections:**
- **Quick Stats**: Connections, events, views, mentorships
- **Connection Requests**: Accept/decline pending requests
- **Upcoming Events**: Your RSVP'd events
- **My Connections**: Grid of connected alumni
- **Profile Summary**: Your profile snapshot

### Mentorship Program

#### Becoming a Mentor
1. Edit your profile
2. Check **Available for Mentorship**
3. Save changes
4. You'll appear in mentor listings

#### Finding a Mentor
1. Navigate to **Alumni → Mentorship**
2. Click **Find Mentors** tab
3. Search by name, industry, or skills
4. Click **Request Mentor** on desired profile
5. Mentor will receive notification

#### Managing Mentorship
- **As Mentor**: Accept/decline requests in Dashboard
- **As Mentee**: Track your mentors in Mentorship page
- **Status**: View active, pending, or completed mentorships

---

## Administrator Guide

### Accessing Admin Panel

1. Login as administrator
2. Navigate to `/modules/alumni/admin/`
3. Or: **System → Modules → Alumni → Administration**

### Admin Dashboard

**Statistics Cards:**
- Total Alumni
- Active Profiles
- Upcoming Events
- Pending Approvals

**Recent Activity:**
- Latest 10 profiles
- Latest 10 events
- Connection statistics
- Mentorship statistics

### Managing Profiles

Access: **Admin → Profiles**

#### Operations

**List View:**
- Filter by status (active, pending, inactive)
- Filter by graduation year
- Filter by featured status
- Pagination support

**Add/Edit Profile:**
1. Click **Add Profile** or **Edit** on existing
2. Fill in all sections:
   - Personal information
   - Academic details
   - Professional information
   - Contact details
   - Privacy settings
3. Click **Save**

**Bulk Actions:**
- **Approve**: Activate pending profiles
- **Feature**: Mark as featured alumni
- **Delete**: Remove profiles (with confirmation)

### Managing Events

Access: **Admin → Events**

#### Operations

**List View:**
- Filter by status, category, featured
- View RSVP counts
- Quick status changes

**Add/Edit Event:**
1. Click **Add Event** or **Edit**
2. Complete form:
   - Title, description, image
   - Category, event type
   - Date/time, location/venue
   - Registration deadline
   - Max attendees
   - Contact information
   - Meeting URL (for online events)
3. Set status (draft, active, cancelled, completed)
4. Click **Save**

**Additional Actions:**
- **Duplicate**: Clone event for recurring events
- **View RSVPs**: See attendee list
- **Export**: Download attendee list as CSV

### Managing Categories

Access: **Admin → Categories**

#### Category Types
- **Event Categories**: Reunion, career fair, networking, etc.
- **General Categories**: For future content types

#### Operations
1. Click **Add Category**
2. Set name, description, type
3. Set display order (for sorting)
4. Save
5. **Note**: Cannot delete categories with events

### Managing RSVPs

Access: **Admin → RSVPs**

#### Features
- Filter by event
- Filter by RSVP status (attending, maybe, not attending)
- View guest counts
- Export attendee lists as CSV

#### CSV Export
1. Select event
2. Click **Export CSV**
3. Downloads with columns:
   - Name, Email, Phone
   - RSVP Status, Guests
   - Notes, RSVP Date

### Managing Connections

Access: **Admin → Connections**

#### View Options
- All connections
- Filter by status (accepted, pending, declined, blocked)
- View connection details
- Delete problematic connections

#### Statistics
- Total connections
- Active connections
- Pending requests
- Declined requests

### Managing Mentorships

Access: **Admin → Mentorship**

#### Operations
- View all mentorships
- Filter by status (active, pending, completed, declined)
- View mentorship details
- Activate pending mentorships
- Complete active mentorships
- Delete mentorships if needed

### Managing Skills

Access: **Admin → Skills**

#### Features
- View all skills with usage counts
- Add new skills
- Edit skill names
- Merge duplicate skills
- Delete unused skills

#### Merging Skills
1. Select source skill (to be removed)
2. Select target skill (to keep)
3. Click **Merge**
4. All profile associations transfer to target skill

### Module Statistics

Access: **Admin → About**

**Information Displayed:**
- Module version and details
- Total counts (profiles, events, connections, etc.)
- System information (XOOPS, PHP, MySQL versions)
- Feature list
- Support links

---

## Troubleshooting

### Common Issues

#### Profiles Not Displaying

**Problem**: Alumni directory shows empty or errors

**Solutions:**
1. Check module is installed correctly
2. Verify database tables exist (9 tables)
3. Check profile approval settings
4. Clear Smarty cache:
   ```bash
   rm -rf xoops_data/caches/smarty_cache/*
   rm -rf xoops_data/caches/smarty_compile/*
   ```

#### Photo Upload Fails

**Problem**: Cannot upload profile or event photos

**Solutions:**
1. Check upload directory permissions:
   ```bash
   chmod 755 uploads/alumni
   chmod 755 uploads/alumni/photos
   chmod 755 uploads/alumni/events
   ```
2. Verify PHP upload limits in `php.ini`:
   ```ini
   upload_max_filesize = 8M
   post_max_size = 8M
   ```
3. Check allowed extensions in module preferences
4. Verify image dimensions don't exceed limits

#### Events Not Showing

**Problem**: Events page empty or events missing

**Solutions:**
1. Check event status (must be "active")
2. Verify event date is in future for "Upcoming" filter
3. Check category assignment
4. Clear module cache
5. Verify event created_by user exists

#### Connection Requests Not Working

**Problem**: Cannot send or receive connection requests

**Solutions:**
1. Verify both users have "allow networking" enabled
2. Check for existing connection (no duplicates)
3. Verify CSRF token in forms
4. Check browser console for JavaScript errors
5. Test with different browsers

#### RSVP Errors

**Problem**: RSVP submission fails

**Solutions:**
1. Check event is "active" status
2. Verify registration deadline hasn't passed
3. Check event capacity (if set)
4. Ensure user is logged in
5. Check CSRF token validation

#### Search Returns No Results

**Problem**: Search always shows empty results

**Solutions:**
1. Check profile privacy settings
2. Verify profiles are "active" status
3. Test with broader search terms
4. Check FULLTEXT index on database:
   ```sql
   SHOW INDEX FROM alumni_profiles WHERE Key_name = 'name_bio';
   ```
5. Rebuild search index if needed

#### Admin Pages Access Denied

**Problem**: Cannot access admin panel

**Solutions:**
1. Verify you're logged in as administrator
2. Check XOOPS group permissions
3. Check module admin permissions
4. Clear browser cookies/cache
5. Verify `alumni_isUserAdmin()` function works

#### Email Notifications Not Sending

**Problem**: No notification emails received

**Solutions:**
1. Check notification settings in module preferences
2. Verify XOOPS mail configuration
3. Test with different email addresses
4. Check server mail logs
5. Verify PHP `mail()` function works

---

## FAQ

### General Questions

**Q: Can anonymous visitors view alumni profiles?**
A: Yes, if profile privacy is set to "public". Alumni-only and private profiles are hidden.

**Q: Can I import existing alumni data?**
A: Yes, you can use SQL INSERT statements or create a custom import script. Contact support for assistance.

**Q: Is there a mobile app?**
A: No mobile app yet, but the website is fully responsive and mobile-friendly.

**Q: Can alumni delete their own profiles?**
A: No, only administrators can delete profiles. Alumni can set their profile to inactive.

### Profile Questions

**Q: How do I change my privacy settings?**
A: Edit your profile → Privacy Settings section → Select public/alumni/private for each field.

**Q: Can I have multiple graduation years?**
A: No, currently only one graduation year per profile. Future versions may support multiple degrees.

**Q: What photo formats are supported?**
A: JPG, JPEG, PNG, and GIF up to 2MB by default (configurable by admin).

**Q: How do I add skills to my profile?**
A: Edit profile → Skills section → Type skill names separated by commas.

### Connection Questions

**Q: How many connections can I have?**
A: Default limit is 500 connections (configurable by admin).

**Q: Can I block someone?**
A: Currently no, but you can decline their connection request or contact admin.

**Q: Will they know if I decline their connection?**
A: No notification is sent, their request just shows as declined on their end.

### Event Questions

**Q: Can I create my own events?**
A: Only administrators can create events. Submit requests through the contact form.

**Q: Can I bring guests to events?**
A: If allowed by event organizer, you can specify guest count when RSVPing.

**Q: What if an event is full?**
A: You cannot RSVP once capacity is reached. Contact event organizer for waitlist.

**Q: Can I change my RSVP?**
A: Yes, you can update or cancel your RSVP anytime before the event.

### Mentorship Questions

**Q: How do I become a mentor?**
A: Edit profile → Check "Available for Mentorship" → Save.

**Q: Can I have multiple mentors?**
A: Yes, you can request mentorship from multiple alumni.

**Q: What happens after mentorship request is accepted?**
A: Contact information is shared, and you manage the relationship directly.

**Q: Can I stop being a mentor?**
A: Yes, edit profile and uncheck "Available for Mentorship".

---

## Support

### Getting Help

#### Documentation
- **Developer Guide**: See `DEVELOPER_GUIDE.md` for code patterns
- **Installation Guide**: See `INSTALL.txt` for detailed steps
- **CLAUDE.md**: For developers using Claude Code

#### Community Support
- **XOOPS Forums**: https://xoops.org/modules/newbb/
- **Module Forum**: (To be created)
- **GitHub Issues**: (If available)

#### Professional Support
- **Email**: support@yourdomain.com
- **Website**: https://yourdomain.com/support
- **Commercial Support**: Available for customization and hosting

### Reporting Bugs

When reporting bugs, please include:
1. XOOPS version
2. PHP version
3. MySQL version
4. Module version
5. Steps to reproduce
6. Expected behavior
7. Actual behavior
8. Screenshots if applicable
9. Error messages from logs

### Feature Requests

Submit feature requests with:
1. Clear description of feature
2. Use case / benefit
3. How it should work
4. Priority level (nice-to-have vs. essential)

---

## Credits

### Development Team

**XOOPS Development Team**
- Module architecture and design
- Security implementation
- XOOPS integration

### Special Thanks

- **XOOPS Community**: For framework and support
- **Bootstrap Team**: For responsive framework
- **Font Awesome**: For iconography
- **Contributors**: All testers and contributors

### Built With

- **XOOPS 2.5.12**: Content management system
- **PHP 7.4+**: Server-side language
- **MySQL 5.5+**: Database system
- **Bootstrap 5**: Frontend framework
- **Font Awesome 6**: Icon library
- **jQuery**: JavaScript library (via XOOPS)
- **Smarty 3**: Template engine

---

## License

This module is released under the **GNU General Public License v2.0 or later**.

```
Alumni Network Management System for XOOPS
Copyright (C) 2026 XOOPS Development Team

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
```

**Full License**: https://www.gnu.org/licenses/gpl-2.0.html

---

## Changelog

See `CHANGELOG.md` for version history.

---

## Screenshots

> **Note**: Add screenshots after deployment

1. **Alumni Directory** - Grid view of alumni profiles
2. **Profile View** - Detailed alumni profile page
3. **Event Listing** - Upcoming events with RSVP
4. **Dashboard** - User dashboard with stats
5. **Admin Panel** - Administrative interface
6. **Search Results** - Advanced search interface
7. **Mentorship** - Mentor directory
8. **Mobile View** - Responsive design

---

**Module Version**: 1.0.0
**Last Updated**: 2026-02-16
**Compatibility**: XOOPS 2.5.11+ | PHP 7.4+ | MySQL 5.5+

---

**For developers**: See [CLAUDE.md](CLAUDE.md) for development patterns and guidelines.
