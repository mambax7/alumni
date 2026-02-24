<?php
/**
 * Alumni Module - Installation Handler
 *
 * @package   Alumni
 * @author    XOOPS Development Team
 * @copyright (c) 2025 XOOPS Project
 * @license   GPL 2.0 or later
 */

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * Installation handler for Alumni module
 *
 * @param XoopsModule $module
 * @return bool
 */
function xoops_module_install_alumni(XoopsModule $module)
{
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $success = true;
    $errors = [];

    // Create upload directories
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
                // Create .htaccess to allow image access
                $htaccess = $dir . '/.htaccess';
                file_put_contents($htaccess, "Options -Indexes\n# Deny everything by default, then allow only image files (Apache 2.4)\nRequire all denied\n<FilesMatch \"\.(jpg|jpeg|png|gif|webp|svg)$\">\n    Require all granted\n</FilesMatch>\n");

                // Create index.html for security
                $indexHtml = $dir . '/index.html';
                file_put_contents($indexHtml, '<!DOCTYPE html><html><head><title></title></head><body></body></html>');
            }
        }
    }

    if (!$success) {
        return false;
    }

    // Insert sample event categories
    $categories = [
        ['name' => 'Networking Events', 'description' => 'Professional networking and social gatherings', 'icon' => 'fa-users'],
        ['name' => 'Career Development', 'description' => 'Workshops, seminars, and career advancement programs', 'icon' => 'fa-briefcase'],
        ['name' => 'Social Gatherings', 'description' => 'Casual meetups and social activities', 'icon' => 'fa-coffee'],
        ['name' => 'Fundraising', 'description' => 'Charity events and fundraising campaigns', 'icon' => 'fa-heart'],
        ['name' => 'Sports & Recreation', 'description' => 'Sports tournaments and recreational activities', 'icon' => 'fa-futbol']
    ];

    foreach ($categories as $cat) {
        $sql = sprintf(
            "INSERT INTO %s (name, description, icon, created_at) VALUES ('%s', '%s', '%s', %u)",
            $db->prefix('alumni_event_categories'),
            $db->escape($cat['name']),
            $db->escape($cat['description']),
            $db->escape($cat['icon']),
            time()
        );
        if (!$db->queryF($sql)) {
            $errors[] = sprintf('Failed to insert category: %s', $cat['name']);
            $success = false;
        }
    }

    // Insert sample alumni profiles
    $profiles = [
        [
            'uid' => 0, // Will be assigned to actual users if available
            'full_name' => 'Jennifer Martinez',
            'graduation_year' => 1995,
            'department' => 'Computer Science',
            'degree' => 'Bachelor of Science',
            'company' => 'Tech Innovations Inc.',
            'position' => 'Senior Software Architect',
            'industry' => 'Technology',
            'location' => 'San Francisco, CA',
            'bio' => 'Passionate about building scalable cloud solutions and mentoring young developers.',
            'linkedin' => 'https://linkedin.com/in/jennifermartinez',
            'email' => 'jennifer.martinez@example.com',
            'phone' => '+1-415-555-0101',
            'privacy_email' => 'public',
            'privacy_phone' => 'alumni',
            'visibility' => 'public'
        ],
        [
            'uid' => 0,
            'full_name' => 'Michael Chen',
            'graduation_year' => 2008,
            'department' => 'Business Administration',
            'degree' => 'Master of Business Administration',
            'company' => 'Global Finance Corp',
            'position' => 'Investment Director',
            'industry' => 'Finance',
            'location' => 'New York, NY',
            'bio' => 'Specializing in emerging markets and sustainable investment strategies.',
            'linkedin' => 'https://linkedin.com/in/michaelchen',
            'email' => 'mchen@example.com',
            'phone' => '+1-212-555-0202',
            'privacy_email' => 'alumni',
            'privacy_phone' => 'private',
            'visibility' => 'public'
        ],
        [
            'uid' => 0,
            'full_name' => 'Sarah Thompson',
            'graduation_year' => 2012,
            'department' => 'Mechanical Engineering',
            'degree' => 'Bachelor of Engineering',
            'company' => 'Aerospace Dynamics',
            'position' => 'Lead Engineer',
            'industry' => 'Aerospace',
            'location' => 'Seattle, WA',
            'bio' => 'Working on next-generation propulsion systems for commercial aircraft.',
            'linkedin' => 'https://linkedin.com/in/sarahthompson',
            'twitter' => '@sarahthompson_eng',
            'email' => 'sarah.t@example.com',
            'privacy_email' => 'public',
            'privacy_phone' => 'private',
            'visibility' => 'public'
        ],
        [
            'uid' => 0,
            'full_name' => 'David Kumar',
            'graduation_year' => 2005,
            'department' => 'Medicine',
            'degree' => 'Doctor of Medicine',
            'company' => 'City Medical Center',
            'position' => 'Chief of Cardiology',
            'industry' => 'Healthcare',
            'location' => 'Boston, MA',
            'bio' => 'Dedicated to advancing cardiac care through research and patient-centered treatment.',
            'linkedin' => 'https://linkedin.com/in/davidkumar',
            'email' => 'dr.kumar@example.com',
            'phone' => '+1-617-555-0303',
            'privacy_email' => 'alumni',
            'privacy_phone' => 'alumni',
            'visibility' => 'public'
        ],
        [
            'uid' => 0,
            'full_name' => 'Emily Rodriguez',
            'graduation_year' => 2015,
            'department' => 'Marketing',
            'degree' => 'Bachelor of Arts',
            'company' => 'Creative Agency Pro',
            'position' => 'Marketing Manager',
            'industry' => 'Marketing',
            'location' => 'Los Angeles, CA',
            'bio' => 'Crafting compelling brand stories for Fortune 500 companies.',
            'linkedin' => 'https://linkedin.com/in/emilyrodriguez',
            'twitter' => '@emily_markets',
            'website' => 'https://emilyrodriguez.com',
            'email' => 'emily.r@example.com',
            'privacy_email' => 'public',
            'privacy_phone' => 'private',
            'visibility' => 'public'
        ],
        [
            'uid' => 0,
            'full_name' => 'Robert Johnson',
            'graduation_year' => 1988,
            'department' => 'Civil Engineering',
            'degree' => 'Bachelor of Engineering',
            'company' => 'Johnson Construction LLC',
            'position' => 'Founder & CEO',
            'industry' => 'Construction',
            'location' => 'Chicago, IL',
            'bio' => 'Building sustainable infrastructure for 35+ years. Mentor to young entrepreneurs.',
            'linkedin' => 'https://linkedin.com/in/robertjohnson',
            'email' => 'rob.johnson@example.com',
            'phone' => '+1-312-555-0404',
            'privacy_email' => 'public',
            'privacy_phone' => 'public',
            'visibility' => 'public'
        ],
        [
            'uid' => 0,
            'full_name' => 'Priya Patel',
            'graduation_year' => 2018,
            'department' => 'Data Science',
            'degree' => 'Master of Science',
            'company' => 'AI Research Labs',
            'position' => 'Machine Learning Engineer',
            'industry' => 'Artificial Intelligence',
            'location' => 'Austin, TX',
            'bio' => 'Developing ethical AI solutions for healthcare and education sectors.',
            'linkedin' => 'https://linkedin.com/in/priyapatel',
            'github' => 'https://github.com/priyapatel',
            'email' => 'priya.patel@example.com',
            'privacy_email' => 'alumni',
            'privacy_phone' => 'private',
            'visibility' => 'public'
        ],
        [
            'uid' => 0,
            'full_name' => 'James Wilson',
            'graduation_year' => 2010,
            'department' => 'Law',
            'degree' => 'Juris Doctor',
            'company' => 'Wilson & Associates',
            'position' => 'Senior Partner',
            'industry' => 'Legal',
            'location' => 'Washington, DC',
            'bio' => 'Specializing in corporate law and intellectual property rights.',
            'linkedin' => 'https://linkedin.com/in/jameswilson',
            'email' => 'j.wilson@example.com',
            'phone' => '+1-202-555-0505',
            'privacy_email' => 'alumni',
            'privacy_phone' => 'alumni',
            'visibility' => 'public'
        ],
        [
            'uid' => 0,
            'full_name' => 'Lisa Anderson',
            'graduation_year' => 2020,
            'department' => 'Environmental Science',
            'degree' => 'Bachelor of Science',
            'company' => 'Green Future Initiative',
            'position' => 'Sustainability Consultant',
            'industry' => 'Environmental',
            'location' => 'Portland, OR',
            'bio' => 'Helping organizations achieve carbon neutrality and sustainable practices.',
            'linkedin' => 'https://linkedin.com/in/lisaanderson',
            'twitter' => '@lisa_green',
            'email' => 'lisa.anderson@example.com',
            'privacy_email' => 'public',
            'privacy_phone' => 'private',
            'visibility' => 'public'
        ],
        [
            'uid' => 0,
            'full_name' => 'Carlos Mendoza',
            'graduation_year' => 2003,
            'department' => 'Architecture',
            'degree' => 'Master of Architecture',
            'company' => 'Modern Design Studio',
            'position' => 'Principal Architect',
            'industry' => 'Architecture',
            'location' => 'Miami, FL',
            'bio' => 'Award-winning architect specializing in sustainable urban development.',
            'linkedin' => 'https://linkedin.com/in/carlosmendoza',
            'website' => 'https://moderndesignstudio.com',
            'email' => 'carlos.m@example.com',
            'phone' => '+1-305-555-0606',
            'privacy_email' => 'public',
            'privacy_phone' => 'alumni',
            'visibility' => 'public'
        ]
    ];

    $alumniIds = [];
    foreach ($profiles as $profile) {
        $sql = sprintf(
            "INSERT INTO %s (uid, full_name, graduation_year, department, degree, company, position, industry, location, bio, linkedin, twitter, github, website, email, phone, privacy_email, privacy_phone, visibility, created_at, updated_at, last_login) VALUES (%u, '%s', %u, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %u, %u, %u)",
            $db->prefix('alumni_profiles'),
            $profile['uid'],
            $db->escape($profile['full_name']),
            $profile['graduation_year'],
            $db->escape($profile['department']),
            $db->escape($profile['degree']),
            $db->escape($profile['company']),
            $db->escape($profile['position']),
            $db->escape($profile['industry']),
            $db->escape($profile['location']),
            $db->escape($profile['bio']),
            $db->escape($profile['linkedin'] ?? ''),
            $db->escape($profile['twitter'] ?? ''),
            $db->escape($profile['github'] ?? ''),
            $db->escape($profile['website'] ?? ''),
            $db->escape($profile['email']),
            $db->escape($profile['phone'] ?? ''),
            $profile['privacy_email'],
            $profile['privacy_phone'],
            $profile['visibility'],
            time(),
            time(),
            time()
        );

        if ($db->queryF($sql)) {
            $alumniIds[] = $db->getInsertId();
        } else {
            $errors[] = sprintf('Failed to insert profile: %s', $profile['full_name']);
            $success = false;
        }
    }

    // Insert skills
    $skills = [
        'Project Management', 'Leadership', 'Data Analysis', 'Cloud Computing',
        'Machine Learning', 'Financial Planning', 'Marketing Strategy', 'Public Speaking',
        'Software Development', 'Business Development', 'Research', 'Design Thinking',
        'Negotiation', 'Strategic Planning', 'Team Building'
    ];

    $skillIds = [];
    foreach ($skills as $skill) {
        $sql = sprintf(
            "INSERT INTO %s (name, created_at) VALUES ('%s', %u)",
            $db->prefix('alumni_skills'),
            $db->escape($skill),
            time()
        );

        if ($db->queryF($sql)) {
            $skillIds[] = $db->getInsertId();
        }
    }

    // Link skills to alumni (random distribution)
    if (!empty($alumniIds) && !empty($skillIds)) {
        foreach ($alumniIds as $alumniId) {
            // Assign 3-5 random skills to each alumni
            $numSkills = rand(3, 5);
            $selectedSkills = array_rand(array_flip($skillIds), $numSkills);
            $selectedSkills = is_array($selectedSkills) ? $selectedSkills : [$selectedSkills];

            foreach ($selectedSkills as $skillId) {
                $sql = sprintf(
                    "INSERT INTO %s (alumni_id, skill_id, created_at) VALUES (%u, %u, %u)",
                    $db->prefix('alumni_profile_skills'),
                    $alumniId,
                    $skillId,
                    time()
                );
                $db->queryF($sql);
            }
        }
    }

    // Insert sample events
    $now = time();
    $events = [
        [
            'category_id' => 1,
            'title' => 'Annual Alumni Networking Gala 2025',
            'description' => 'Join us for our premier networking event of the year! Connect with fellow alumni, enjoy fine dining, and celebrate our community achievements.',
            'start_date' => $now + (30 * 86400), // 30 days from now
            'end_date' => $now + (30 * 86400) + (4 * 3600),
            'location' => 'Grand Ballroom, Hilton Downtown',
            'address' => '123 Main Street, City Center',
            'city' => 'New York',
            'state' => 'NY',
            'country' => 'USA',
            'is_online' => 0,
            'max_attendees' => 200,
            'registration_required' => 1,
            'registration_deadline' => $now + (20 * 86400),
            'status' => 'published'
        ],
        [
            'category_id' => 2,
            'title' => 'Career Development Workshop: Leadership in Tech',
            'description' => 'Learn essential leadership skills for the modern tech industry. Interactive workshop led by industry experts.',
            'start_date' => $now + (45 * 86400),
            'end_date' => $now + (45 * 86400) + (6 * 3600),
            'location' => 'Virtual Event',
            'is_online' => 1,
            'meeting_url' => 'https://zoom.us/j/example123',
            'max_attendees' => 500,
            'registration_required' => 1,
            'registration_deadline' => $now + (40 * 86400),
            'status' => 'published'
        ],
        [
            'category_id' => 3,
            'title' => 'Summer BBQ & Family Day',
            'description' => 'Bring your family for a fun-filled day of food, games, and reconnecting with old friends!',
            'start_date' => $now + (90 * 86400),
            'end_date' => $now + (90 * 86400) + (8 * 3600),
            'location' => 'University Campus Grounds',
            'address' => 'University Avenue',
            'city' => 'College Town',
            'state' => 'CA',
            'country' => 'USA',
            'is_online' => 0,
            'max_attendees' => 0, // No limit
            'registration_required' => 1,
            'registration_deadline' => $now + (80 * 86400),
            'status' => 'published'
        ],
        [
            'category_id' => 4,
            'title' => 'Alumni Scholarship Fundraiser',
            'description' => 'Help us raise funds for deserving students. Silent auction, dinner, and inspiring speakers.',
            'start_date' => $now + (60 * 86400),
            'end_date' => $now + (60 * 86400) + (5 * 3600),
            'location' => 'University Conference Center',
            'address' => '456 Campus Drive',
            'city' => 'College Town',
            'state' => 'CA',
            'country' => 'USA',
            'is_online' => 0,
            'max_attendees' => 150,
            'registration_required' => 1,
            'registration_deadline' => $now + (50 * 86400),
            'status' => 'published'
        ],
        [
            'category_id' => 5,
            'title' => 'Alumni Golf Tournament',
            'description' => 'Annual golf tournament and networking opportunity. All skill levels welcome!',
            'start_date' => $now + (75 * 86400),
            'end_date' => $now + (75 * 86400) + (8 * 3600),
            'location' => 'Riverside Golf Club',
            'address' => '789 Golf Course Road',
            'city' => 'Greenfield',
            'state' => 'FL',
            'country' => 'USA',
            'is_online' => 0,
            'max_attendees' => 100,
            'registration_required' => 1,
            'registration_deadline' => $now + (65 * 86400),
            'status' => 'published'
        ],
        [
            'category_id' => 2,
            'title' => 'Entrepreneurship Bootcamp',
            'description' => 'Three-day intensive program for alumni looking to start or grow their business.',
            'start_date' => $now - (15 * 86400), // Past event
            'end_date' => $now - (13 * 86400),
            'location' => 'Innovation Hub',
            'address' => '321 Startup Lane',
            'city' => 'San Francisco',
            'state' => 'CA',
            'country' => 'USA',
            'is_online' => 0,
            'max_attendees' => 50,
            'registration_required' => 1,
            'registration_deadline' => $now - (20 * 86400),
            'status' => 'published'
        ],
        [
            'category_id' => 1,
            'title' => 'Quarterly Virtual Coffee Chat',
            'description' => 'Casual online meetup to stay connected. Drop in anytime during the hour!',
            'start_date' => $now + (15 * 86400),
            'end_date' => $now + (15 * 86400) + 3600,
            'location' => 'Virtual Event',
            'is_online' => 1,
            'meeting_url' => 'https://meet.google.com/example',
            'max_attendees' => 0,
            'registration_required' => 0,
            'status' => 'published'
        ],
        [
            'category_id' => 3,
            'title' => 'Holiday Celebration 2024',
            'description' => 'Celebrate the season with fellow alumni. Dinner, entertainment, and holiday cheer!',
            'start_date' => $now - (60 * 86400), // Past event
            'end_date' => $now - (60 * 86400) + (4 * 3600),
            'location' => 'Grand Hotel Ballroom',
            'address' => '555 Celebration Avenue',
            'city' => 'Chicago',
            'state' => 'IL',
            'country' => 'USA',
            'is_online' => 0,
            'max_attendees' => 180,
            'registration_required' => 1,
            'registration_deadline' => $now - (70 * 86400),
            'status' => 'published'
        ]
    ];

    $eventIds = [];
    foreach ($events as $event) {
        $sql = sprintf(
            "INSERT INTO %s (category_id, title, description, start_date, end_date, location, address, city, state, country, is_online, meeting_url, max_attendees, registration_required, registration_deadline, status, created_by, created_at, updated_at) VALUES (%u, '%s', '%s', %u, %u, '%s', '%s', '%s', '%s', '%s', %u, '%s', %u, %u, %u, '%s', %u, %u, %u)",
            $db->prefix('alumni_events'),
            $event['category_id'],
            $db->escape($event['title']),
            $db->escape($event['description']),
            $event['start_date'],
            $event['end_date'],
            $db->escape($event['location']),
            $db->escape($event['address'] ?? ''),
            $db->escape($event['city'] ?? ''),
            $db->escape($event['state'] ?? ''),
            $db->escape($event['country'] ?? ''),
            $event['is_online'],
            $db->escape($event['meeting_url'] ?? ''),
            $event['max_attendees'],
            $event['registration_required'],
            $event['registration_deadline'] ?? 0,
            $event['status'],
            1, // Admin user
            time(),
            time()
        );

        if ($db->queryF($sql)) {
            $eventIds[] = $db->getInsertId();
        } else {
            $errors[] = sprintf('Failed to insert event: %s', $event['title']);
            $success = false;
        }
    }

    // Insert sample RSVPs
    if (!empty($alumniIds) && !empty($eventIds)) {
        $rsvpStatuses = ['confirmed', 'tentative', 'declined'];
        $rsvpCount = 0;

        foreach ($eventIds as $eventId) {
            // 3-5 RSVPs per event
            $numRsvps = rand(3, 5);
            $selectedAlumni = array_rand(array_flip($alumniIds), min($numRsvps, count($alumniIds)));
            $selectedAlumni = is_array($selectedAlumni) ? $selectedAlumni : [$selectedAlumni];

            foreach ($selectedAlumni as $alumniId) {
                $status = $rsvpStatuses[array_rand($rsvpStatuses)];
                $notes = ($status === 'tentative') ? 'Will try to make it if schedule permits' : '';

                $sql = sprintf(
                    "INSERT INTO %s (event_id, user_id, status, notes, created_at) VALUES (%u, %u, '%s', '%s', %u)",
                    $db->prefix('alumni_event_rsvp'),
                    $eventId,
                    $alumniId,
                    $status,
                    $db->escape($notes),
                    time() - rand(0, 86400 * 10)
                );

                if ($db->queryF($sql)) {
                    $rsvpCount++;
                }
            }
        }
    }

    // Insert sample connections
    if (count($alumniIds) >= 2) {
        $connectionStatuses = ['pending', 'accepted'];
        $connectionCount = 0;

        // Create 8 sample connections
        for ($i = 0; $i < 8; $i++) {
            $fromId = $alumniIds[array_rand($alumniIds)];
            $toId = $alumniIds[array_rand($alumniIds)];

            // Ensure different alumni
            while ($fromId === $toId) {
                $toId = $alumniIds[array_rand($alumniIds)];
            }

            $status = $connectionStatuses[array_rand($connectionStatuses)];

            $sql = sprintf(
                "INSERT INTO %s (from_alumni_id, to_alumni_id, status, created_at, updated_at) VALUES (%u, %u, '%s', %u, %u)",
                $db->prefix('alumni_connections'),
                $fromId,
                $toId,
                $status,
                time() - rand(0, 86400 * 30),
                time()
            );

            if ($db->queryF($sql)) {
                $connectionCount++;
            }
        }
    }

    // Insert sample mentorship requests
    if (count($alumniIds) >= 2) {
        $mentorshipStatuses = ['pending', 'accepted', 'declined'];
        $topics = [
            'Career transition from academia to industry',
            'Starting a tech company',
            'Leadership development',
            'Breaking into finance sector',
            'Work-life balance strategies'
        ];

        for ($i = 0; $i < 5; $i++) {
            $menteeId = $alumniIds[array_rand($alumniIds)];
            $mentorId = $alumniIds[array_rand($alumniIds)];

            while ($menteeId === $mentorId) {
                $mentorId = $alumniIds[array_rand($alumniIds)];
            }

            $status = $mentorshipStatuses[array_rand($mentorshipStatuses)];
            $topic = $topics[$i % count($topics)];

            $sql = sprintf(
                "INSERT INTO %s (mentee_id, mentor_id, topic, message, status, created_at, updated_at) VALUES (%u, %u, '%s', '%s', '%s', %u, %u)",
                $db->prefix('alumni_mentorship'),
                $menteeId,
                $mentorId,
                $db->escape($topic),
                $db->escape('I would greatly appreciate your guidance and insights on this topic.'),
                $status,
                time() - rand(0, 86400 * 20),
                time()
            );

            $db->queryF($sql);
        }
    }

    // Report results
    if (!empty($errors)) {
        error_log('Alumni module installation errors: ' . implode('; ', $errors));
    }

    return $success;
}
