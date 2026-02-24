<?php

declare(strict_types=1);

namespace XoopsModules\Alumni;

use XoopsModules\Alumni\Common;

/**
 * Utility — static helper methods for the Alumni module.
 *
 * Extends SysUtility for framework-level helpers.
 * Add module-specific helpers here as static methods.
 *
 * @copyright XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 */
class Utility extends Common\SysUtility
{
    // -------------------------------------------------------------------------
    // Admin theme assets
    // -------------------------------------------------------------------------

    /**
     * Register module CSS + TableSorter into <head> via xoTheme.
     *
     * Must be called immediately after xoops_cp_header() so that
     * $GLOBALS['xoTheme'] is the real admin theme instance.
     */
    public static function addAdminAssets(): void
    {
        $helper  = Helper::getInstance();
        $xoTheme = $GLOBALS['xoTheme'];
        $xoTheme->addStylesheet($helper->url('/assets/css/admin.css'));
        $xoTheme->addScript('browse.php?Frameworks/jquery/plugins/jquery.tablesorter.js');
        $xoTheme->addScript('', ['type' => 'text/javascript'],
            'window.addEventListener("load",function(){jQuery(".xm-sortable").tablesorter({theme:"default",widthFixed:true});});'
        );
    }

    // -------------------------------------------------------------------------
    // String helpers
    // -------------------------------------------------------------------------

    /**
     * Truncate a UTF-8 string to $length characters, appending '...' if cut.
     */
    public static function truncate(string $text, int $length = 100, string $suffix = '...'): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        return mb_substr($text, 0, $length) . $suffix;
    }

    /**
     * Format a Unix timestamp for display; returns '' for zero/negative values.
     */
    public static function formatDate(int $timestamp, string $format = 'M j, Y'): string
    {
        if ($timestamp <= 0) {
            return '';
        }
        return date($format, $timestamp);
    }

    /**
     * Get relative time string (e.g. "2 hours ago").
     */
    public static function getTimeAgo(int $timestamp): string
    {
        $diff = time() - $timestamp;

        if ($diff < 60) {
            return _MD_ALUMNI_TIME_JUSTNOW;
        }
        if ($diff < 3600) {
            return sprintf(_MD_ALUMNI_TIME_MINUTES_AGO, (int)floor($diff / 60));
        }
        if ($diff < 86400) {
            return sprintf(_MD_ALUMNI_TIME_HOURS_AGO, (int)floor($diff / 3600));
        }
        if ($diff < 2592000) {
            return sprintf(_MD_ALUMNI_TIME_DAYS_AGO, (int)floor($diff / 86400));
        }
        if ($diff < 31536000) {
            return sprintf(_MD_ALUMNI_TIME_MONTHS_AGO, (int)floor($diff / 2592000));
        }
        return sprintf(_MD_ALUMNI_TIME_YEARS_AGO, (int)floor($diff / 31536000));
    }

    // -------------------------------------------------------------------------
    // Alumni-specific helpers
    // -------------------------------------------------------------------------

    /**
     * Return years elapsed since a graduation year.
     */
    public static function getYearsSinceGraduation(int $graduationYear): int
    {
        return (int)date('Y') - $graduationYear;
    }

    /**
     * Build a profile page URL (uses Helper to avoid hardcoding the module name).
     */
    public static function getProfileUrl(int $profileId): string
    {
        return Helper::getInstance()->url('/profile.php?id=' . $profileId);
    }

    /**
     * Build an event page URL.
     */
    public static function getEventUrl(int $eventId): string
    {
        return Helper::getInstance()->url('/event.php?id=' . $eventId);
    }

    // -------------------------------------------------------------------------
    // Access / auth
    // -------------------------------------------------------------------------

    /**
     * Return true if a user is currently logged in.
     */
    public static function isUserLoggedIn(): bool
    {
        return is_object($GLOBALS['xoopsUser'] ?? null);
    }

    /**
     * Return true if the current user is a module admin.
     */
    public static function isModuleAdmin(): bool
    {
        global $xoopsUser, $xoopsModule;
        return is_object($xoopsUser) && is_object($xoopsModule)
            && $xoopsUser->isAdmin($xoopsModule->getVar('mid'));
    }

    /**
     * Return the current user ID, or 0 for guests.
     */
    public static function getCurrentUserId(): int
    {
        global $xoopsUser;
        return is_object($xoopsUser) ? (int)$xoopsUser->getVar('uid') : 0;
    }

    // -------------------------------------------------------------------------
    // Config
    // -------------------------------------------------------------------------

    /**
     * Get a module config value, returning $default when absent or empty.
     *
     * @param mixed $default
     * @return mixed
     */
    public static function config(string $key, $default = null)
    {
        $helper = Helper::getInstance();
        $val    = $helper->getConfig($key);
        return ($val !== null && $val !== '') ? $val : $default;
    }

    // -------------------------------------------------------------------------
    // Select-option arrays
    // -------------------------------------------------------------------------

    /**
     * Return an ordered [ year => year ] array for graduation-year dropdowns.
     * Range: current year down to (current year − 80).
     *
     * @return array<int|string, int|string>
     */
    public static function getGraduationYears(): array
    {
        $currentYear = (int)date('Y');
        $years       = ['' => '---'];
        for ($y = $currentYear; $y >= $currentYear - 80; $y--) {
            $years[$y] = $y;
        }
        return $years;
    }

    /**
     * Return an ordered [ value => label ] array of industry options.
     * Labels fall back to plain-English strings when language constants are
     * not yet loaded (e.g. during install or CLI runs).
     *
     * @return array<string, string>
     */
    public static function getIndustryOptions(): array
    {
        return [
            ''                                       => defined('_MD_ALUMNI_INDUSTRY_SELECT')          ? _MD_ALUMNI_INDUSTRY_SELECT          : '--- Select Industry ---',
            'Technology'                             => defined('_MD_ALUMNI_INDUSTRY_TECH')             ? _MD_ALUMNI_INDUSTRY_TECH             : 'Technology',
            'Finance'                                => defined('_MD_ALUMNI_INDUSTRY_FINANCE')          ? _MD_ALUMNI_INDUSTRY_FINANCE          : 'Finance',
            'Healthcare'                             => defined('_MD_ALUMNI_INDUSTRY_HEALTHCARE')       ? _MD_ALUMNI_INDUSTRY_HEALTHCARE       : 'Healthcare',
            'Education'                              => defined('_MD_ALUMNI_INDUSTRY_EDUCATION')        ? _MD_ALUMNI_INDUSTRY_EDUCATION        : 'Education',
            'Law'                                    => defined('_MD_ALUMNI_INDUSTRY_LAW')              ? _MD_ALUMNI_INDUSTRY_LAW              : 'Law',
            'Design'                                 => defined('_MD_ALUMNI_INDUSTRY_DESIGN')           ? _MD_ALUMNI_INDUSTRY_DESIGN           : 'Design',
            'Aerospace'                              => defined('_MD_ALUMNI_INDUSTRY_AEROSPACE')        ? _MD_ALUMNI_INDUSTRY_AEROSPACE        : 'Aerospace',
            'Architecture & Real Estate'             => defined('_MD_ALUMNI_INDUSTRY_ARCHITECTURE')     ? _MD_ALUMNI_INDUSTRY_ARCHITECTURE     : 'Architecture & Real Estate',
            'Construction & Infrastructure'          => defined('_MD_ALUMNI_INDUSTRY_CONSTRUCTION')     ? _MD_ALUMNI_INDUSTRY_CONSTRUCTION     : 'Construction & Infrastructure',
            'Consumer Goods'                         => defined('_MD_ALUMNI_INDUSTRY_CONSUMER_GOODS')   ? _MD_ALUMNI_INDUSTRY_CONSUMER_GOODS   : 'Consumer Goods',
            'Cybersecurity'                          => defined('_MD_ALUMNI_INDUSTRY_CYBERSECURITY')    ? _MD_ALUMNI_INDUSTRY_CYBERSECURITY    : 'Cybersecurity',
            'Environmental Services'                 => defined('_MD_ALUMNI_INDUSTRY_ENVIRONMENT')      ? _MD_ALUMNI_INDUSTRY_ENVIRONMENT      : 'Environmental Services',
            'Media & Journalism'                     => defined('_MD_ALUMNI_INDUSTRY_MEDIA')            ? _MD_ALUMNI_INDUSTRY_MEDIA            : 'Media & Journalism',
            'Medical Devices'                        => defined('_MD_ALUMNI_INDUSTRY_MEDICAL_DEV')      ? _MD_ALUMNI_INDUSTRY_MEDICAL_DEV      : 'Medical Devices',
            'Non-Profit & International Development' => defined('_MD_ALUMNI_INDUSTRY_NGO')              ? _MD_ALUMNI_INDUSTRY_NGO              : 'Non-Profit & International Development',
            'Accounting & Auditing'                  => defined('_MD_ALUMNI_INDUSTRY_ACCOUNTING')       ? _MD_ALUMNI_INDUSTRY_ACCOUNTING       : 'Accounting & Auditing',
            'Other'                                  => defined('_MD_ALUMNI_INDUSTRY_OTHER')            ? _MD_ALUMNI_INDUSTRY_OTHER            : 'Other',
        ];
    }

    /**
     * Check whether the current user can view the given profile.
     *
     * - public  → always visible
     * - alumni  → logged-in users only
     * - private → profile owner or module admin only
     */
    public static function canViewProfile(\XoopsObject $profile): bool
    {
        $privacy = $profile->getVar('privacy_profile');
        if ($privacy === 'public') {
            return true;
        }
        if (!static::isUserLoggedIn()) {
            return false;
        }
        if ($privacy === 'alumni') {
            return true;
        }
        return static::getCurrentUserId() === (int)$profile->getVar('user_id')
            || static::isModuleAdmin();
    }

    /**
     * Check whether the current user can edit the given profile.
     * Only the owner or a module admin may edit.
     */
    public static function canEditProfile(\XoopsObject $profile): bool
    {
        if (!static::isUserLoggedIn()) {
            return false;
        }
        return static::getCurrentUserId() === (int)$profile->getVar('user_id')
            || static::isModuleAdmin();
    }

    /**
     * Flatten a Profile object into a template-ready associative array.
     *
     * @param  \XoopsObject $profile
     * @return array<string, mixed>
     */
    public static function formatProfileData(\XoopsObject $profile): array
    {
        $photoFile = $profile->getVar('photo', 'e');
        $photoUrl  = !empty($photoFile)
            ? ALUMNI_UPLOAD_URL . '/photos/' . $photoFile
            : ALUMNI_URL . '/assets/images/default-avatar.png';

        return [
            // Primary keys
            'id'                  => (int)$profile->getVar('profile_id'),
            'user_id'             => (int)$profile->getVar('user_id'),
            'first_name'          => $profile->getVar('first_name',       'e'),
            'last_name'           => $profile->getVar('last_name',        'e'),
            'full_name'           => trim($profile->getVar('first_name', 'e') . ' ' . $profile->getVar('last_name', 'e')),
            'photo'               => $photoUrl,
            'photo_raw'           => $photoFile,
            'graduation_year'     => (int)$profile->getVar('graduation_year'),
            'degree'              => $profile->getVar('degree',           'e'),
            'major'               => $profile->getVar('major',            'e'),
            'department'          => $profile->getVar('department',       'e'),
            'current_company'     => $profile->getVar('current_company',  'e'),
            'current_position'    => $profile->getVar('current_position', 'e'),
            'industry'            => $profile->getVar('industry',         'e'),
            'location'            => $profile->getVar('location',         'e'),
            'city'                => $profile->getVar('city',             'e'),
            'country'             => $profile->getVar('country',          'e'),
            'bio'                 => $profile->getVar('bio',              'e'),
            'email'               => $profile->getVar('email',            'e'),
            'phone'               => $profile->getVar('phone',            'e'),
            'linkedin_url'        => $profile->getVar('linkedin_url',     'e'),
            'twitter_url'         => $profile->getVar('twitter_url',      'e'),
            'facebook_url'        => $profile->getVar('facebook_url',     'e'),
            'website_url'         => $profile->getVar('website_url',      'e'),
            'privacy_profile'     => $profile->getVar('privacy_profile'),
            'allow_mentorship'    => (int)$profile->getVar('allow_mentorship'),
            'allow_networking'    => (int)$profile->getVar('allow_networking'),
            'status'              => $profile->getVar('status'),
            'featured'            => (int)$profile->getVar('featured'),
            'views'               => (int)$profile->getVar('views'),
            'connections_count'   => (int)$profile->getVar('connections_count'),
            'created'             => (int)$profile->getVar('created'),
            'url'                 => static::getProfileUrl((int)$profile->getVar('profile_id')),
            // Template-friendly aliases expected by alumni_profile.tpl / alumni_index.tpl
            'job_title'           => $profile->getVar('current_position', 'e'),
            'company'             => $profile->getVar('current_company',  'e'),
            'website'             => $profile->getVar('website_url',      'e'),
            'linkedin'            => $profile->getVar('linkedin_url',     'e'),
            'twitter'             => $profile->getVar('twitter_url',      'e'),
            'facebook'            => $profile->getVar('facebook_url',     'e'),
            'profile_views'       => (int)$profile->getVar('views'),
            'events_count'        => 0,    // populated separately where needed
            'available_as_mentor' => (bool)$profile->getVar('allow_mentorship'),
            'skills'              => [],   // populated in profile.php after skill query
            'is_owner'            => false, // overridden in profile.php
        ];
    }

    /**
     * Flatten an Event object into a template-ready associative array.
     *
     * @param  \XoopsObject $event
     * @return array<string, mixed>
     */
    public static function formatEventData(\XoopsObject $event): array
    {
        $startTs   = (int)$event->getVar('start_date');
        $now       = time();
        $daysUntil = ($startTs > $now) ? (int)ceil(($startTs - $now) / 86400) : 0;

        $imageFile = $event->getVar('image', 'e');
        $imageUrl  = !empty($imageFile)
            ? ALUMNI_UPLOAD_URL . '/events/' . $imageFile
            : ALUMNI_URL . '/assets/images/default-event.png';

        return [
            // Primary keys
            'id'                    => (int)$event->getVar('event_id'),
            'category_id'           => (int)$event->getVar('category_id'),
            'title'                 => $event->getVar('title',          'e'),
            'description'           => static::truncate(strip_tags($event->getVar('description', 'e')), 200),
            'description_full'      => $event->getVar('description',    'e'),
            'image'                 => $imageFile,
            'image_url'             => $imageUrl,
            'location'              => $event->getVar('location',       'e'),
            'venue'                 => $event->getVar('venue',          'e'),
            'start_date'            => $startTs,
            'end_date'              => (int)$event->getVar('end_date'),
            'registration_deadline' => (int)$event->getVar('registration_deadline'),
            'max_attendees'         => (int)$event->getVar('max_attendees'),
            'rsvp_count'            => (int)$event->getVar('rsvp_count'),
            'event_type'            => $event->getVar('event_type'),
            'meeting_url'           => $event->getVar('meeting_url',    'e'),
            'contact_name'          => $event->getVar('contact_name',   'e'),
            'contact_email'         => $event->getVar('contact_email',  'e'),
            'contact_phone'         => $event->getVar('contact_phone',  'e'),
            'status'                => $event->getVar('status'),
            'featured'              => (int)$event->getVar('featured'),
            'views'                 => (int)$event->getVar('views'),
            'created_by'            => (int)$event->getVar('created_by'),
            'created'               => (int)$event->getVar('created'),
            'url'                   => static::getEventUrl((int)$event->getVar('event_id')),
            // Template-friendly aliases expected by alumni_events.tpl
            'month'                 => $startTs ? date('M', $startTs) : '',
            'day'                   => $startTs ? date('j', $startTs) : '',
            'year'                  => $startTs ? date('Y', $startTs) : '',
            'date_formatted'        => $startTs ? date('D, M j, Y', $startTs) : '',
            'time'                  => $startTs ? date('g:i A', $startTs) : '',
            'capacity'              => (int)$event->getVar('max_attendees'),
            'attendees'             => (int)$event->getVar('rsvp_count'),
            'type'                  => $event->getVar('event_type'),
            'is_upcoming'           => $startTs > $now,
            'days_until'            => $daysUntil,
        ];
    }

    public static function getPrivacyOptions(): array
    {
        return [
            'public'  => _MD_ALUMNI_PRIVACY_PUBLIC,
            'alumni'  => _MD_ALUMNI_PRIVACY_ALUMNI,
            'private' => _MD_ALUMNI_PRIVACY_PRIVATE,
        ];
    }

    public static function getEventTypeOptions(): array
    {
        return [
            'reunion'    => _MD_ALUMNI_EVENT_TYPE_REUNION,
            'networking' => _MD_ALUMNI_EVENT_TYPE_NETWORKING,
            'seminar'    => _MD_ALUMNI_EVENT_TYPE_SEMINAR,
            'workshop'   => _MD_ALUMNI_EVENT_TYPE_WORKSHOP,
            'conference' => _MD_ALUMNI_EVENT_TYPE_CONFERENCE,
            'social'     => _MD_ALUMNI_EVENT_TYPE_SOCIAL,
            'fundraiser' => _MD_ALUMNI_EVENT_TYPE_FUNDRAISER,
            'webinar'    => _MD_ALUMNI_EVENT_TYPE_WEBINAR,
            'other'      => _MD_ALUMNI_EVENT_TYPE_OTHER,
        ];
    }

    public static function getRsvpStatusOptions(): array
    {
        return [
            'attending'     => _MD_ALUMNI_RSVP_ATTENDING,
            'maybe'         => _MD_ALUMNI_RSVP_MAYBE,
            'not_attending' => _MD_ALUMNI_RSVP_NOT_ATTENDING,
        ];
    }

    // -------------------------------------------------------------------------
    // File helpers
    // -------------------------------------------------------------------------

    /**
     * Validate and move an uploaded file.
     *
     * @param array  $file         Entry from $_FILES
     * @param string $uploadDir    Destination directory (absolute path)
     * @param array  $allowedTypes Allowed extensions (lowercase)
     * @param int    $maxSize      Max bytes
     * @return array ['success' => bool, 'filename' => string] | ['success' => false, 'error' => string]
     */
    public static function uploadFile(array $file, string $uploadDir, array $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'], int $maxSize = 2097152): array
    {
        if (!isset($file['error']) || is_array($file['error'])) {
            return ['success' => false, 'error' => _MD_ALUMNI_ERROR_INVALID_FILE];
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => _MD_ALUMNI_ERROR_UPLOAD_FAILED];
        }
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'error' => _MD_ALUMNI_ERROR_FILE_TOO_LARGE];
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedTypes, true)) {
            return ['success' => false, 'error' => _MD_ALUMNI_ERROR_INVALID_FILE_TYPE];
        }

        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
            return ['success' => false, 'error' => _MD_ALUMNI_ERROR_UPLOAD_FAILED];
        }

        $filename    = uniqid('', true) . '_' . time() . '.' . $extension;
        $destination = $uploadDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return ['success' => false, 'error' => _MD_ALUMNI_ERROR_UPLOAD_FAILED];
        }

        return ['success' => true, 'filename' => $filename];
    }

    /**
     * Delete a file if it exists.
     */
    public static function deleteFile(string $filepath): bool
    {
        return file_exists($filepath) && is_file($filepath) && unlink($filepath);
    }

    /**
     * Sanitize HTML content
     *
     * @param string $text Text to sanitize
     * @return string
     */
    public static function sanitizeHtml($text)
    {
        $myts = \MyTextSanitizer::getInstance();
        return $myts->htmlSpecialChars($text??'');
    }
}
