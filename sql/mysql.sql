# Alumni Management System - MySQL Schema
# Version: 1.0.0
# XOOPS 2.5.12 Compatible

# ===========================================================================
# TABLE: alumni_profiles - Alumni member profiles
# ===========================================================================
CREATE TABLE `alumni_profiles` (
  `profile_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `first_name` varchar(100) NOT NULL DEFAULT '',
  `last_name` varchar(100) NOT NULL DEFAULT '',
  `photo` varchar(255) DEFAULT NULL,
  `graduation_year` int(4) unsigned DEFAULT NULL,
  `degree` varchar(200) DEFAULT NULL,
  `major` varchar(200) DEFAULT NULL,
  `department` varchar(200) DEFAULT NULL,
  `current_company` varchar(200) DEFAULT NULL,
  `current_position` varchar(200) DEFAULT NULL,
  `industry` varchar(100) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `bio` text,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `twitter_url` varchar(255) DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `privacy_profile` enum('public','alumni','private') DEFAULT 'public',
  `privacy_email` enum('public','alumni','private') DEFAULT 'alumni',
  `privacy_phone` enum('public','alumni','private') DEFAULT 'private',
  `allow_mentorship` tinyint(1) unsigned DEFAULT '0',
  `allow_networking` tinyint(1) unsigned DEFAULT '1',
  `status` enum('active','inactive','pending') DEFAULT 'pending',
  `featured` tinyint(1) unsigned DEFAULT '0',
  `views` int(11) unsigned DEFAULT '0',
  `connections_count` int(11) unsigned DEFAULT '0',
  `created` int(10) unsigned DEFAULT '0',
  `updated` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`profile_id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `graduation_year` (`graduation_year`),
  KEY `status` (`status`),
  KEY `featured` (`featured`),
  KEY `location` (`location`),
  KEY `industry` (`industry`),
  KEY `last_name` (`last_name`),
  KEY `department` (`department`),
  KEY `allow_mentorship` (`allow_mentorship`),
  FULLTEXT KEY `name_bio` (`first_name`,`last_name`,`bio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

# ===========================================================================
# TABLE: alumni_categories - Event and content categories
# ===========================================================================
CREATE TABLE `alumni_categories` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `type` enum('event','general') DEFAULT 'general',
  `display_order` int(5) unsigned DEFAULT '0',
  `event_count` int(11) unsigned DEFAULT '0',
  `created` int(10) unsigned DEFAULT '0',
  `updated` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`category_id`),
  KEY `type` (`type`),
  KEY `display_order` (`display_order`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

# ===========================================================================
# TABLE: alumni_events - Alumni events and gatherings
# ===========================================================================
CREATE TABLE `alumni_events` (
  `event_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `venue` varchar(200) DEFAULT NULL,
  `start_date` int(10) unsigned NOT NULL,
  `end_date` int(10) unsigned DEFAULT NULL,
  `registration_deadline` int(10) unsigned DEFAULT NULL,
  `max_attendees` int(11) unsigned DEFAULT '0',
  `rsvp_count` int(11) unsigned DEFAULT '0',
  `event_type` enum('online','physical','hybrid') DEFAULT 'physical',
  `meeting_url` varchar(255) DEFAULT NULL,
  `contact_name` varchar(100) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `contact_phone` varchar(50) DEFAULT NULL,
  `status` enum('active','cancelled','completed','draft') DEFAULT 'draft',
  `featured` tinyint(1) unsigned DEFAULT '0',
  `views` int(11) unsigned DEFAULT '0',
  `created_by` int(11) unsigned DEFAULT '0',
  `created` int(10) unsigned DEFAULT '0',
  `updated` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`event_id`),
  KEY `category_id` (`category_id`),
  KEY `status` (`status`),
  KEY `featured` (`featured`),
  KEY `start_date` (`start_date`),
  KEY `end_date` (`end_date`),
  KEY `event_type` (`event_type`),
  KEY `created_by` (`created_by`),
  FULLTEXT KEY `title_description` (`title`,`description`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

# ===========================================================================
# TABLE: alumni_rsvps - Event RSVP responses
# ===========================================================================
CREATE TABLE `alumni_rsvps` (
  `rsvp_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `status` enum('attending','maybe','declined') DEFAULT 'attending',
  `guests` int(2) unsigned DEFAULT '0',
  `comment` text,
  `created` int(10) unsigned DEFAULT '0',
  `updated` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`rsvp_id`),
  UNIQUE KEY `event_user` (`event_id`,`user_id`),
  KEY `event_id` (`event_id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

# ===========================================================================
# TABLE: alumni_connections - Alumni networking connections
# ===========================================================================
CREATE TABLE `alumni_connections` (
  `connection_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `requester_id` int(11) unsigned NOT NULL,
  `recipient_id` int(11) unsigned NOT NULL,
  `status` enum('pending','accepted','declined','blocked') DEFAULT 'pending',
  `message` text,
  `created` int(10) unsigned DEFAULT '0',
  `updated` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`connection_id`),
  UNIQUE KEY `requester_recipient` (`requester_id`,`recipient_id`),
  KEY `requester_id` (`requester_id`),
  KEY `recipient_id` (`recipient_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

# ===========================================================================
# TABLE: alumni_mentorship - Mentorship requests and relationships
# ===========================================================================
CREATE TABLE `alumni_mentorship` (
  `mentorship_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mentor_id` int(11) unsigned NOT NULL,
  `mentee_id` int(11) unsigned NOT NULL,
  `status` enum('pending','active','completed','declined') DEFAULT 'pending',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `message` text,
  `start_date` int(10) unsigned DEFAULT '0',
  `end_date` int(10) unsigned DEFAULT '0',
  `created` int(10) unsigned DEFAULT '0',
  `updated` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`mentorship_id`),
  KEY `mentor_id` (`mentor_id`),
  KEY `mentee_id` (`mentee_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

# ===========================================================================
# TABLE: alumni_skills - Skills and interests tags
# ===========================================================================
CREATE TABLE `alumni_skills` (
  `skill_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `profile_count` int(11) unsigned DEFAULT '0',
  `created` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`skill_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

# ===========================================================================
# TABLE: alumni_profile_skill_link - Profile-to-skill relationships
# ===========================================================================
CREATE TABLE `alumni_profile_skill_link` (
  `link_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) unsigned NOT NULL,
  `skill_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`link_id`),
  UNIQUE KEY `profile_skill` (`profile_id`,`skill_id`),
  KEY `profile_id` (`profile_id`),
  KEY `skill_id` (`skill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

# ===========================================================================
# TABLE: alumni_comments - Comments on profiles and events (optional)
# ===========================================================================
CREATE TABLE `alumni_comments` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(11) unsigned NOT NULL,
  `item_type` enum('profile','event') DEFAULT 'profile',
  `user_id` int(11) unsigned NOT NULL,
  `comment` text NOT NULL,
  `status` enum('active','pending','spam') DEFAULT 'active',
  `created` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `item_id` (`item_id`),
  KEY `item_type` (`item_type`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
