![XOOPS CMS](https://xoops.org/images/logoXoops4GithubRepository.png)
# Alumni Module — User Tutorial

## Overview

The Alumni module lets educational institutions maintain connections with graduates.
This tutorial walks through the most common tasks for both alumni and administrators.

---

## For Alumni: Getting Started

### 1. Create Your Profile

1. Log in to the site with your XOOPS account.
2. Navigate to **Alumni → My Profile**.
3. Click **Edit Profile** and fill in your details:
   - **Personal**: First name, last name, bio, profile photo
   - **Academic**: Graduation year, degree, major, field of study
   - **Professional**: Job title, company, industry, location
   - **Skills**: Add skills relevant to your career (comma-separated)
   - **Contact**: Email, phone, LinkedIn, Twitter, personal website
   - **Privacy**: Choose who can see each piece of information
4. Click **Save Changes**.

Your profile may require admin approval before it appears in the directory.

---

### 2. Browse the Alumni Directory

1. Go to **Alumni → Directory**.
2. Use the search filters at the top to narrow results:
   - **Name** — search by first or last name
   - **Graduation Year** — filter by year or year range
   - **Industry** — narrow by sector
   - **Location** — filter by city or country
3. Click any alumni card to view their full profile.
4. Switch between **Grid** and **List** view using the toggle.

---

### 3. Connect with Other Alumni

1. Open another alumni's profile.
2. Click **Connect**.
3. They will receive a notification and can accept or decline.
4. Manage all your connections from **My Dashboard → Connections**.

**Tips:**
- You can cancel a pending request from your dashboard.
- A connection gives both parties access to contact details (subject to privacy settings).

---

### 4. RSVP to Events

1. Go to **Alumni → Events** to see upcoming events.
2. Click an event for full details.
3. Click **RSVP** and choose:
   - **Attending** — you'll be there
   - **Maybe** — you're not sure yet
   - **Not Attending** — you can't make it
4. Optionally add the number of guests and a note.
5. Click **Submit RSVP**.

You can update or cancel your RSVP at any time before the event.

---

### 5. Find a Mentor

1. Navigate to **Alumni → Mentorship → Find a Mentor**.
2. Browse or search for mentors by industry, skills, or name.
3. Click **Request Mentor** on the profile of a mentor who's a good fit.
4. The mentor will be notified and can accept or decline.
5. Track your mentorship requests in **My Dashboard → Mentorships**.

---

### 6. Become a Mentor

1. Edit your profile (**My Profile → Edit Profile**).
2. In the **Professional** section, check **Available for Mentorship**.
3. Optionally describe your mentorship areas in the bio field.
4. Save — you will now appear in the mentor directory.

To stop mentoring, uncheck **Available for Mentorship** and save.

---

## For Administrators

### Managing the Alumni Directory

1. Log in and go to **Admin Panel → Alumni**.
2. Navigate to **Profiles** in the admin menu.
3. Use the status filter to see **Pending** profiles awaiting approval.
4. Click **Approve** (or the status badge) to activate a profile.
5. Use **Feature** to highlight notable alumni on the front page.

**Bulk operations** — use the checkboxes in the list to approve or delete multiple profiles at once.

---

### Creating Events

1. In the admin menu, click **Events → Add Event**.
2. Fill in the form:
   - **Title & Description** — what the event is about
   - **Category** — reunion, networking, seminar, etc.
   - **Type** — in-person, online, or hybrid
   - **Dates** — start date/time and optional end date/time
   - **Location / Venue** — address or online meeting link
   - **Registration Deadline** — last date to RSVP
   - **Max Attendees** — set 0 for unlimited
   - **Status** — set to **Published** when ready to go public
3. Click **Save**.

---

### Loading Sample Data (Development)

If the **Test Data** buttons are visible in the admin panel:

1. Click **Load Sample Data** to populate all tables with realistic fixture data.
2. This loads YAML fixtures from `testdata/english/` and copies sample images.
3. Use **Save Sample Data** to export your current database rows back to YAML.
4. Use **Clear Sample Data** to wipe all module rows.

> **Note:** The Test Data buttons are controlled by **Module Preferences → Display Sample Button**.
> Set to **No** on production sites.

---

### Configuring Blocks

1. Go to **Admin → Blocks Admin**.
2. Enable or disable the blocks provided by the module:
   - **Recent Alumni** — shows newest approved profiles
   - **Upcoming Events** — lists the next few events
   - **Quick Search** — search form block
   - **Network Stats** — total alumni, events, connections
3. Drag blocks to the desired side column and save order.

---

## Privacy Reference

| Setting | Public | Alumni Only | Private |
|---------|--------|-------------|---------|
| Visible to guests | Yes | No | No |
| Visible to logged-in members | Yes | Yes | No |
| Visible to own connections | Yes | Yes | Yes |
| Always visible to admins | Yes | Yes | Yes |

Privacy is set independently for:
- Profile visibility
- Email address
- Phone number
- Location

---

## Tips & Best Practices

- **Keep your profile current** — update your job title and company whenever they change.
- **Add a photo** — profiles with photos get significantly more views.
- **Use privacy controls** — set sensitive fields (email, phone) to **Alumni Only** or **Private**.
- **Enable mentorship** — even an hour a month can make a big difference to a recent graduate.
- **RSVP early** — events may have capacity limits.

---

*For technical details and developer documentation, see [CLAUDE.md](../CLAUDE.md) and the inline PHPDoc comments throughout the codebase.*
