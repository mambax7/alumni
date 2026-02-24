<?php

declare(strict_types=1);
/**
 * Alumni Module - Block Functions.
 *
 * @copyright 2025 XOOPS Alumni Module
 * @license   GPL 2.0 or later
 * @author    Alumni Module Development Team
 */

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

use XoopsModules\Alumni\Helper;
use XoopsModules\Alumni\Utility;

require_once __DIR__ . '/preloads/autoloader.php';

/**
 * Recent Alumni Block - Show Function.
 *
 * @param array $options Block options [limit, sort]
 *
 * @return array
 */
function alumni_block_recent_show($options)
{
    $helper = Helper::getInstance();
    $profileHandler = $helper->getHandler('profile');

    $limit = (int) ($options[0] ?? 10);
    $sortBy = $options[1] ?? 'newest';

    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('status', 'active'));
    $criteria->setLimit($limit);

    switch ($sortBy) {
        case 'newest':
            $criteria->setSort('created');
            $criteria->setOrder('DESC');

            break;
        case 'oldest':
            $criteria->setSort('created');
            $criteria->setOrder('ASC');

            break;
        case 'name':
            $criteria->setSort('first_name');
            $criteria->setOrder('ASC');

            break;
        case 'year':
            $criteria->setSort('graduation_year');
            $criteria->setOrder('DESC');

            break;
        case 'random':
            $criteria->setSort('RAND()');

            break;
    }

    $profiles = $profileHandler->getObjects($criteria);

    $block = [];
    $block['profiles'] = [];

    foreach ($profiles as $profile) {
        $photoUrl = XOOPS_URL . '/modules/alumni/assets/images/default-avatar.png';
        if ($profile->getVar('photo')) {
            $photoUrl = XOOPS_UPLOAD_URL . '/alumni/photos/' . $profile->getVar('photo');
        }

        $block['profiles'][] = [
            'id'              => $profile->getVar('profile_id'),
            'first_name'      => Utility::sanitizeHtml($profile->getVar('first_name')),
            'last_name'       => Utility::sanitizeHtml($profile->getVar('last_name')),
            'full_name'       => Utility::sanitizeHtml($profile->getVar('first_name') . ' ' . $profile->getVar('last_name')),
            'photo'           => $photoUrl,
            'graduation_year' => $profile->getVar('graduation_year'),
            'job_title'       => Utility::sanitizeHtml($profile->getVar('job_title')),
            'company'         => Utility::sanitizeHtml($profile->getVar('company')),
            'location'        => Utility::sanitizeHtml($profile->getVar('city')),
            'bio'             => Utility::truncate($profile->getVar('bio'), 100),
            'time_ago'        => Utility::getTimeAgo($profile->getVar('created')),
            'url'             => XOOPS_URL . '/modules/alumni/profile.php?id=' . $profile->getVar('profile_id'),
        ];
    }

    return $block;
}

/**
 * Recent Alumni Block - Edit Function.
 *
 * @param array $options Block options
 *
 * @return string
 */
function alumni_block_recent_edit($options)
{
    $form = '<label>' . _MB_ALUMNI_LIMIT . '</label><br>';
    $form .= '<input type="text" name="options[0]" value="' . ($options[0] ?? 10) . '" size="5"><br><br>';

    $form .= '<label>' . _MB_ALUMNI_SORT . '</label><br>';
    $form .= '<select name="options[1]">';
    $sortOptions = [
        'newest' => _MB_ALUMNI_SORT_NEWEST,
        'oldest' => _MB_ALUMNI_SORT_OLDEST,
        'name'   => _MB_ALUMNI_SORT_NAME_ASC,
        'year'   => _MB_ALUMNI_SORT_YEAR_DESC,
        'random' => _MB_ALUMNI_SORT_RANDOM,
    ];
    foreach ($sortOptions as $key => $label) {
        $selected = (($options[1] ?? 'newest') === $key) ? ' selected' : '';
        $form .= '<option value="' . $key . '"' . $selected . '>' . $label . '</option>';
    }
    $form .= '</select>';

    return $form;
}

/**
 * Events Block - Show Function.
 *
 * @param array $options Block options [limit, days_ahead]
 *
 * @return array
 */
function alumni_block_events_show($options)
{
    $helper = Helper::getInstance();
    $eventHandler = $helper->getHandler('event');

    $limit = (int) ($options[0] ?? 5);
    $daysAhead = (int) ($options[1] ?? 30);

    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('status', 'published'));
    $criteria->add(new Criteria('event_date', time(), '>='));

    if ($daysAhead > 0) {
        $endDate = time() + ($daysAhead * 86400);
        $criteria->add(new Criteria('event_date', $endDate, '<='));
    }

    $criteria->setLimit($limit);
    $criteria->setSort('event_date');
    $criteria->setOrder('ASC');

    $events = $eventHandler->getObjects($criteria);

    $block = [];
    $block['events'] = [];

    foreach ($events as $event) {
        $block['events'][] = [
            'id'                   => $event->getVar('event_id'),
            'title'                => Utility::sanitizeHtml($event->getVar('title')),
            'description'          => Utility::truncate($event->getVar('description'), 100),
            'event_date'           => $event->getVar('event_date'),
            'event_date_formatted' => date('M j, Y', $event->getVar('event_date')),
            'event_time'           => date('g:i A', $event->getVar('event_date')),
            'location'             => Utility::sanitizeHtml($event->getVar('location')),
            'venue'                => Utility::sanitizeHtml($event->getVar('venue')),
            'capacity'             => $event->getVar('capacity'),
            'attendees'            => $event->getVar('attendees'),
            'days_until'           => ceil(($event->getVar('event_date') - time()) / 86400),
            'url'                  => XOOPS_URL . '/modules/alumni/event.php?id=' . $event->getVar('event_id'),
        ];
    }

    return $block;
}

/**
 * Events Block - Edit Function.
 *
 * @param array $options Block options
 *
 * @return string
 */
function alumni_block_events_edit($options)
{
    $form = '<label>' . _MB_ALUMNI_LIMIT . '</label><br>';
    $form .= '<input type="text" name="options[0]" value="' . ($options[0] ?? 5) . '" size="5"><br><br>';

    $form .= '<label>' . _MB_ALUMNI_EVENTS_DAYS_AHEAD . '</label><br>';
    $form .= '<input type="text" name="options[1]" value="' . ($options[1] ?? 30) . '" size="5"><br>';
    $form .= '<small>' . _MB_ALUMNI_EVENTS_DAYS_AHEAD_DESC . '</small>';

    return $form;
}

/**
 * Search Block - Show Function.
 *
 * @return array
 */
function alumni_block_search_show()
{
    $block = [];
    $block['search_url'] = XOOPS_URL . '/modules/alumni/search.php';
    $block['module_url'] = XOOPS_URL . '/modules/alumni';

    // Get graduation year range
    $helper = Helper::getInstance();
    $profileHandler = $helper->getHandler('profile');

    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $sql = sprintf(
        'SELECT MIN(graduation_year) as min_year, MAX(graduation_year) as max_year FROM %s WHERE status = "active"',
        $db->prefix('alumni_profiles')
    );
    $result = $db->query($sql);

    if ($result && $row = $db->fetchArray($result)) {
        $block['min_year'] = $row['min_year'] ?: (date('Y') - 50);
        $block['max_year'] = $row['max_year'] ?: date('Y');
    } else {
        $block['min_year'] = date('Y') - 50;
        $block['max_year'] = date('Y');
    }

    return $block;
}

/**
 * Search Block - Edit Function (no options).
 *
 * @return string
 */
function alumni_block_search_edit()
{
    return _MB_ALUMNI_EVENTS_DESC;
}

/**
 * Statistics Block - Show Function.
 *
 * @param array $options Block options [show various stats]
 *
 * @return array
 */
function alumni_block_stats_show($options)
{
    $helper = Helper::getInstance();
    $db = XoopsDatabaseFactory::getDatabaseConnection();

    $block = [];
    $block['show_profiles'] = (int) ($options[0] ?? 1);
    $block['show_events'] = (int) ($options[1] ?? 1);
    $block['show_connections'] = (int) ($options[2] ?? 1);
    $block['show_mentorships'] = (int) ($options[3] ?? 1);

    // Total profiles
    if ($block['show_profiles']) {
        $sql = sprintf('SELECT COUNT(*) FROM %s WHERE status = "active"', $db->prefix('alumni_profiles'));
        $result = $db->query($sql);
        if ($result && $row = $db->fetchRow($result)) {
            $block['total_profiles'] = (int) $row[0];
        }
    }

    // Total events
    if ($block['show_events']) {
        $sql = sprintf('SELECT COUNT(*) FROM %s WHERE status = "published"', $db->prefix('alumni_events'));
        $result = $db->query($sql);
        if ($result && $row = $db->fetchRow($result)) {
            $block['total_events'] = (int) $row[0];
        }

        // Upcoming events
        $sql = sprintf(
            'SELECT COUNT(*) FROM %s WHERE status = "published" AND event_date >= %u',
            $db->prefix('alumni_events'),
            time()
        );
        $result = $db->query($sql);
        if ($result && $row = $db->fetchRow($result)) {
            $block['upcoming_events'] = (int) $row[0];
        }
    }

    // Total connections
    if ($block['show_connections']) {
        $sql = sprintf('SELECT COUNT(*) FROM %s WHERE status = "accepted"', $db->prefix('alumni_connections'));
        $result = $db->query($sql);
        if ($result && $row = $db->fetchRow($result)) {
            $block['total_connections'] = (int) $row[0];
        }
    }

    // Total mentorships
    if ($block['show_mentorships']) {
        $sql = sprintf('SELECT COUNT(*) FROM %s WHERE status = "active"', $db->prefix('alumni_mentorship'));
        $result = $db->query($sql);
        if ($result && $row = $db->fetchRow($result)) {
            $block['total_mentorships'] = (int) $row[0];
        }
    }

    // Countries represented
    $sql = sprintf(
        'SELECT COUNT(DISTINCT country) FROM %s WHERE status = "active" AND country != ""',
        $db->prefix('alumni_profiles')
    );
    $result = $db->query($sql);
    if ($result && $row = $db->fetchRow($result)) {
        $block['countries'] = (int) $row[0];
    }

    // Industries represented
    $sql = sprintf(
        'SELECT COUNT(DISTINCT industry) FROM %s WHERE status = "active" AND industry != ""',
        $db->prefix('alumni_profiles')
    );
    $result = $db->query($sql);
    if ($result && $row = $db->fetchRow($result)) {
        $block['industries'] = (int) $row[0];
    }

    // Graduation year range
    $sql = sprintf(
        'SELECT MIN(graduation_year) as min_year, MAX(graduation_year) as max_year FROM %s WHERE status = "active"',
        $db->prefix('alumni_profiles')
    );
    $result = $db->query($sql);
    if ($result && $row = $db->fetchArray($result)) {
        $block['year_range'] = $row['min_year'] . ' - ' . $row['max_year'];
    }

    return $block;
}

/**
 * Statistics Block - Edit Function.
 *
 * @param array $options Block options
 *
 * @return string
 */
function alumni_block_stats_edit($options)
{
    $form = '<label><input type="checkbox" name="options[0]" value="1"' . (($options[0] ?? 1) ? ' checked' : '') . '> ' . _MB_ALUMNI_STATS_SHOW_PROFILES . '</label><br>';
    $form .= '<label><input type="checkbox" name="options[1]" value="1"' . (($options[1] ?? 1) ? ' checked' : '') . '> ' . _MB_ALUMNI_STATS_SHOW_EVENTS . '</label><br>';
    $form .= '<label><input type="checkbox" name="options[2]" value="1"' . (($options[2] ?? 1) ? ' checked' : '') . '> ' . _MB_ALUMNI_STATS_SHOW_CONNECTIONS . '</label><br>';
    $form .= '<label><input type="checkbox" name="options[3]" value="1"' . (($options[3] ?? 1) ? ' checked' : '') . '> ' . _MB_ALUMNI_STATS_SHOW_MENTORSHIPS . '</label>';

    return $form;
}
