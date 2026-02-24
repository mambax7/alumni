# Alumni Module - Frontend Pages Documentation

## Created Files

All frontend pages for the Alumni Management System have been successfully created following XOOPS 2.5.12 conventions and the Jobs module patterns.

### Main Pages (10 files)

1. **index.php** - Alumni Directory Listing
   - Displays paginated list of alumni profiles
   - Filter by graduation year, industry, location, keyword
   - Featured profiles section
   - Grid/list view toggle
   - Respects privacy settings (public/alumni-only)
   - View mode: `?view=grid` or `?view=list`

2. **profile.php** - Profile View/Edit
   - **Operations**: view, edit, save
   - View mode: Display profile details with skills
   - Shows connection status and actions
   - Edit mode: Full profile form
   - Save mode: Handles photo upload, updates profile
   - Permission checks (can view/edit profile)
   - Increment view counter

3. **events.php** - Events Listing
   - Filter by category, event type, time (upcoming/past/all)
   - Featured events section
   - Shows RSVP status for logged-in users
   - Checks if event is full
   - Pagination support

4. **event.php** - Single Event Detail
   - Full event information
   - Attendees list (first 20)
   - Similar events (same category)
   - RSVP status and actions
   - Registration checks (deadline, capacity)
   - View counter increment

5. **search.php** - Advanced Search
   - Multiple search filters:
     - Keyword (searches multiple fields)
     - First name, last name
     - Graduation year range (start/end)
     - Industry, location, city, country
     - Company, position
     - Skills (searches skill table)
     - Allow mentorship/networking flags
   - Results with pagination
   - Form persistence (current values retained)

6. **dashboard.php** - User Dashboard
   - Requires login
   - Profile summary with stats
   - My connections (10 most recent)
   - Pending connection requests (received)
   - Upcoming events (RSVPs)
   - Mentorship requests (if mentor)
   - Total counts for all sections

7. **connections.php** - Manage Connections
   - **Tabs**: connections, sent, received
   - **connections**: Accepted connections
   - **sent**: Pending requests sent by user
   - **received**: Pending requests to accept/decline
   - Pagination per tab

8. **mentorship.php** - Mentorship Management
   - **Tabs**: mentors, my_mentorships, requests
   - **mentors**: Available mentors to request
   - **my_mentorships**: Active mentorships (as mentor/mentee)
   - **requests**: Pending requests (as mentor)
   - Shows request status on mentor profiles

### Action Handlers (2 files)

9. **rsvp.php** - RSVP Handler (AJAX)
   - **POST only** with CSRF validation
   - **Operations**: create, update, delete
   - Returns JSON responses
   - Validates event availability (deadline, capacity)
   - Updates event RSVP counter
   - Prevents duplicate RSVPs

10. **connect.php** - Connection Request Handler
    - **POST only** with CSRF validation
    - **Operations**: send, accept, decline, remove
    - Redirects with messages
    - Validates:
      - Target user exists and allows networking
      - No duplicate connections
      - Proper permissions
    - Updates profile connection counters

## Architecture Patterns Used

### Security
- ✅ CSRF token validation on all POST operations
- ✅ Input sanitization (htmlspecialchars, ENT_QUOTES)
- ✅ Type casting ((int), trim())
- ✅ Permission checks (login required, can edit/view)
- ✅ SQL injection prevention (Criteria API, no raw SQL)
- ✅ XSS prevention (Utility::sanitizeHtml())

### XOOPS Conventions
- ✅ `mainfile.php` and `common.php` includes
- ✅ `header.php` / `footer.php` pattern
- ✅ `alumni_getHandler()` for handlers
- ✅ `redirect_header()` for redirects
- ✅ Template assignment via `$xoopsTpl`
- ✅ Database prefix via `$db->prefix()`
- ✅ Criteria API for queries
- ✅ XoopsPageNav for pagination
- ✅ Language constants (no hardcoded strings)

### User Experience
- ✅ Pagination on all listings
- ✅ Search/filter persistence (form values retained)
- ✅ View counters (profiles, events)
- ✅ Privacy controls (public/alumni/private)
- ✅ Status indicators (pending, accepted, attending)
- ✅ Friendly messages (success/error)
- ✅ Responsive design ready

### Database Operations
- ✅ CriteriaCompo for complex queries
- ✅ Proper sorting and ordering
- ✅ Count queries before pagination
- ✅ Foreign key lookups (profiles, events, categories)
- ✅ Atomic updates (RSVP counts, connection counts)
- ✅ Soft deletes where appropriate

## URL Patterns

```
# Listing pages
/modules/alumni/index.php?year=2020&industry=Technology&start=20
/modules/alumni/events.php?cat=1&type=reunion&time=upcoming
/modules/alumni/search.php?keyword=engineer&year_start=2015&year_end=2020

# Detail pages
/modules/alumni/profile.php?id=123
/modules/alumni/event.php?id=45

# Edit pages
/modules/alumni/profile.php?op=edit&id=123

# User pages (login required)
/modules/alumni/dashboard.php
/modules/alumni/connections.php?tab=received
/modules/alumni/mentorship.php?tab=mentors

# Actions (POST)
/modules/alumni/profile.php (op=save)
/modules/alumni/rsvp.php (op=create|update|delete)
/modules/alumni/connect.php (op=send|accept|decline|remove)
```

## Filter Examples

### Index Page
```
?year=2020              - Graduation year
?industry=Technology    - Industry filter
?location=New York      - Location search
?keyword=engineer       - Keyword search
?featured=1             - Featured only
?view=grid              - View mode
```

### Events Page
```
?cat=1                  - Category ID
?type=reunion           - Event type
?time=upcoming          - Time filter (upcoming/past/all)
?featured=1             - Featured only
?keyword=networking     - Keyword search
```

### Search Page
```
?keyword=engineer           - General keyword
?first_name=John           - First name
?last_name=Smith           - Last name
?year_start=2015           - Year range start
?year_end=2020             - Year range end
?industry=Technology       - Industry
?location=California       - Location
?city=San Francisco        - City
?country=USA               - Country
?company=Google            - Company
?position=Engineer         - Position
?skill=Python              - Skill name
?allow_mentorship=1        - Mentors only
?allow_networking=1        - Open to networking
```

## Required Templates

The following templates need to be created to complete the frontend:

1. `alumni_index.tpl` - Directory listing
2. `alumni_profile.tpl` - Profile view
3. `alumni_profile_edit.tpl` - Profile edit form
4. `alumni_events.tpl` - Events listing
5. `alumni_event_detail.tpl` - Event detail
6. `alumni_search.tpl` - Advanced search
7. `alumni_dashboard.tpl` - User dashboard
8. `alumni_connections.tpl` - Connections management
9. `alumni_mentorship.tpl` - Mentorship management

## Language Constants Required

All language constants are already defined in `language/english/main.php`:

### Error Messages
- `_MD_ALUMNI_ERROR_LOGIN_REQUIRED`
- `_MD_ALUMNI_ERROR_INVALID_PROFILE`
- `_MD_ALUMNI_ERROR_PROFILE_NOT_FOUND`
- `_MD_ALUMNI_ERROR_NO_PERMISSION`
- `_MD_ALUMNI_ERROR_SECURITY`
- `_MD_ALUMNI_ERROR_SAVE_FAILED`
- `_MD_ALUMNI_ERROR_DELETE_FAILED`
- `_MD_ALUMNI_ERROR_INVALID_EVENT`
- `_MD_ALUMNI_ERROR_EVENT_NOT_FOUND`
- `_MD_ALUMNI_ERROR_INVALID_REQUEST`
- `_MD_ALUMNI_ERROR_INVALID_OPERATION`

### Success Messages
- `_MD_ALUMNI_PROFILE_SAVED`
- `_MD_ALUMNI_RSVP_SUCCESS`
- `_MD_ALUMNI_RSVP_UPDATED`
- `_MD_ALUMNI_RSVP_DELETED`
- `_MD_ALUMNI_CONNECTION_REQUEST_SENT`
- `_MD_ALUMNI_CONNECTION_ACCEPTED`
- `_MD_ALUMNI_CONNECTION_DECLINED`
- `_MD_ALUMNI_CONNECTION_REMOVED`

### Labels
- `_MD_ALUMNI_SHOWING_RESULTS`
- `_MD_ALUMNI_RESULTS_FOUND`
- `_MD_ALUMNI_BROWSE_ALUMNI`
- `_MD_ALUMNI_BROWSE_EVENTS`
- `_MD_ALUMNI_ADVANCED_SEARCH`
- `_MD_ALUMNI_MY_DASHBOARD`
- `_MD_ALUMNI_MY_CONNECTIONS`
- `_MD_ALUMNI_MENTORSHIP`
- `_MD_ALUMNI_EDIT_PROFILE`
- `_MD_ALUMNI_MENTOR`
- `_MD_ALUMNI_MENTEE`
- `_MD_ALUMNI_UNKNOWN`

## Testing Checklist

### Basic Functionality
- [ ] Index page loads with alumni list
- [ ] Filters work (year, industry, location, keyword)
- [ ] Pagination works
- [ ] Featured section appears
- [ ] View toggle (grid/list) works

### Profile Pages
- [ ] Profile view displays correctly
- [ ] Skills show up
- [ ] Connection status displays
- [ ] Edit link shows for own profile/admin
- [ ] Edit form loads with current data
- [ ] Save updates profile
- [ ] Photo upload works

### Events
- [ ] Events listing loads
- [ ] Time filters work (upcoming/past/all)
- [ ] Event detail shows correctly
- [ ] RSVP button appears for logged-in users
- [ ] Attendees list displays
- [ ] Similar events show

### Search
- [ ] All search filters work
- [ ] Skill search queries skill table
- [ ] Year range filter works
- [ ] Results display correctly
- [ ] Form retains search values

### Dashboard (Login Required)
- [ ] Profile summary shows
- [ ] Connections list appears
- [ ] Pending requests display
- [ ] Upcoming events show
- [ ] Mentorship requests appear (if mentor)

### Actions
- [ ] Connection request sends
- [ ] Accept/decline connection works
- [ ] RSVP creates/updates/deletes
- [ ] AJAX responses are JSON
- [ ] CSRF validation works
- [ ] Error messages display

### Security
- [ ] Guest users see only public profiles
- [ ] Login required pages redirect
- [ ] CSRF tokens validated
- [ ] Permissions checked
- [ ] XSS prevented (output sanitized)
- [ ] SQL injection prevented

## Next Steps

1. **Create Templates** - All 9 templates listed above
2. **Test Each Page** - Use testing checklist
3. **Add JavaScript** - AJAX interactions for RSVP, connections
4. **Style Enhancement** - CSS for responsive design
5. **Admin Pages** - Create admin CRUD interfaces
6. **Email Notifications** - Connection requests, RSVP confirmations
7. **Block Templates** - Display blocks for featured alumni, upcoming events

## File Summary

```
Created 10 frontend pages:
✅ index.php (269 lines) - Alumni directory
✅ profile.php (293 lines) - Profile view/edit
✅ events.php (183 lines) - Events listing
✅ event.php (157 lines) - Event detail
✅ search.php (209 lines) - Advanced search
✅ dashboard.php (248 lines) - User dashboard
✅ connections.php (166 lines) - Manage connections
✅ mentorship.php (188 lines) - Mentorship management
✅ rsvp.php (213 lines) - RSVP AJAX handler
✅ connect.php (250 lines) - Connection handler

Total: ~2,176 lines of PHP code
```

All pages follow XOOPS 2.5.12 conventions, implement proper security, and are ready for template integration.
