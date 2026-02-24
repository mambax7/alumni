<?php

declare(strict_types=1);

/**
 * Alumni Management System - Advanced Search.
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 *
 * @version     1.0.0
 */

use XoopsModules\Alumni\Helper;
use XoopsModules\Alumni\Utility;

require_once __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/include/common.php';

$helper = Helper::getInstance();
$profileHandler = $helper->getHandler('profile');
$skillHandler = $helper->getHandler('skill');

// Get search parameters — names match the template form fields
$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
$name = isset($_GET['name']) ? trim($_GET['name']) : '';  // combined name search
$yearFrom = isset($_GET['year_from']) ? (int) $_GET['year_from'] : 0;
$yearTo = isset($_GET['year_to']) ? (int) $_GET['year_to'] : 0;
$major = isset($_GET['major']) ? trim($_GET['major']) : '';
$degree = isset($_GET['degree']) ? trim($_GET['degree']) : '';
$industry = isset($_GET['industry']) ? trim($_GET['industry']) : '';
$company = isset($_GET['company']) ? trim($_GET['company']) : '';
$city = isset($_GET['city']) ? trim($_GET['city']) : '';
$state = isset($_GET['state']) ? trim($_GET['state']) : '';
$country = isset($_GET['country']) ? trim($_GET['country']) : '';
$skills = isset($_GET['skills']) ? trim($_GET['skills']) : '';  // skills field
$mentor = isset($_GET['mentor']) ? (int) $_GET['mentor'] : 0;   // mentor checkbox

// Determine if the form has been submitted
$hasSearched = ! empty(array_filter($_GET, function ($v) {
    return $v !== '' && $v !== '0' && $v !== 0;
}));

// Pagination
$start = isset($_GET['start']) ? (int) $_GET['start'] : 0;
$limit = Utility::config('per_page') ?: 20;

// Build search criteria
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('status', 'active'));

// Check privacy - only show public profiles to guests
if (! Utility::isUserLoggedIn()) {
    $criteria->add(new Criteria('privacy_profile', 'public'));
}

// General keyword search
if (! empty($keyword)) {
    $keywordCriteria = new CriteriaCompo();
    $keywordCriteria->add(new Criteria('first_name', '%' . $keyword . '%', 'LIKE'), 'OR');
    $keywordCriteria->add(new Criteria('last_name', '%' . $keyword . '%', 'LIKE'), 'OR');
    $keywordCriteria->add(new Criteria('current_company', '%' . $keyword . '%', 'LIKE'), 'OR');
    $keywordCriteria->add(new Criteria('current_position', '%' . $keyword . '%', 'LIKE'), 'OR');
    $keywordCriteria->add(new Criteria('bio', '%' . $keyword . '%', 'LIKE'), 'OR');
    $criteria->add($keywordCriteria);
}

// Combined name search (first or last)
if (! empty($name)) {
    $nameCriteria = new CriteriaCompo();
    $nameCriteria->add(new Criteria('first_name', '%' . $name . '%', 'LIKE'), 'OR');
    $nameCriteria->add(new Criteria('last_name', '%' . $name . '%', 'LIKE'), 'OR');
    $criteria->add($nameCriteria);
}

// Graduation year range
if ($yearFrom > 0) {
    $criteria->add(new Criteria('graduation_year', $yearFrom, '>='));
}
if ($yearTo > 0) {
    $criteria->add(new Criteria('graduation_year', $yearTo, '<='));
}

// Academic fields
if (! empty($major)) {
    $criteria->add(new Criteria('major', '%' . $major . '%', 'LIKE'));
}
if (! empty($degree)) {
    $criteria->add(new Criteria('degree', '%' . $degree . '%', 'LIKE'));
}

// Industry
if (! empty($industry)) {
    $criteria->add(new Criteria('industry', $industry));
}

// Company
if (! empty($company)) {
    $criteria->add(new Criteria('current_company', '%' . $company . '%', 'LIKE'));
}

// Location
if (! empty($city)) {
    $criteria->add(new Criteria('city', '%' . $city . '%', 'LIKE'));
}
if (! empty($state)) {
    // state maps to location field (no dedicated state column)
    $criteria->add(new Criteria('location', '%' . $state . '%', 'LIKE'));
}
if (! empty($country)) {
    $criteria->add(new Criteria('country', '%' . $country . '%', 'LIKE'));
}

// Mentor filter
if ($mentor) {
    $criteria->add(new Criteria('allow_mentorship', 1));
}

// Skill-based search — uses getProfileSkills() join, so we search skill names
if (! empty($skills)) {
    // Search the skills table by name, then get profile IDs via join table
    $skillNameCriteria = new CriteriaCompo();
    $skillNameCriteria->add(new Criteria('name', '%' . $skills . '%', 'LIKE'));
    $matchedSkills = $skillHandler->getObjects($skillNameCriteria);

    $profileIds = [];
    foreach ($matchedSkills as $s) {
        // For each matching skill, find profiles that have it via link table
        $linkProfiles = $skillHandler->getProfileSkills($s->getVar('skill_id'));
        foreach ($linkProfiles as $lp) {
            $profileIds[] = (int) $lp->getVar('profile_id');
        }
    }

    if (! empty($profileIds)) {
        $profileIds = array_unique($profileIds);
        $criteria->add(new Criteria('profile_id', '(' . implode(',', $profileIds) . ')', 'IN'));
    } else {
        $criteria->add(new Criteria('profile_id', 0));  // no results
    }
}

// Get total count
$totalProfiles = $profileHandler->getCount($criteria);

// Set pagination
$criteria->setStart($start);
$criteria->setLimit($limit);
$criteria->setSort('updated');
$criteria->setOrder('DESC');

// Get profiles
$profiles = $profileHandler->getObjects($criteria);

// Prepare profiles for template
$resultsArray = [];
foreach ($profiles as $profile) {
    $resultsArray[] = Utility::formatProfileData($profile);
}

// Get filter options
$graduationYears = Utility::getGraduationYears();
$industries = Utility::getIndustryOptions();

// Pagination
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
$pagenav = new XoopsPageNav(
    $totalProfiles,
    $limit,
    $start,
    'start',
    'q=' . urlencode($keyword) .
    '&name=' . urlencode($name) .
    '&year_from=' . $yearFrom .
    '&year_to=' . $yearTo .
    '&major=' . urlencode($major) .
    '&degree=' . urlencode($degree) .
    '&industry=' . urlencode($industry) .
    '&company=' . urlencode($company) .
    '&city=' . urlencode($city) .
    '&state=' . urlencode($state) .
    '&country=' . urlencode($country) .
    '&skills=' . urlencode($skills) .
    '&mentor=' . $mentor
);

// Set template
$GLOBALS['xoopsOption']['template_main'] = 'db:alumni_search.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

// Assign data to template
$xoopsTpl->assign('results', $resultsArray);
$xoopsTpl->assign('profiles', $resultsArray); // back-compat alias
$xoopsTpl->assign('total_results', $totalProfiles);
$xoopsTpl->assign('total_profiles', $totalProfiles);
$xoopsTpl->assign('years', $graduationYears);
$xoopsTpl->assign('industries', $industries);
$xoopsTpl->assign('has_searched', $hasSearched);
$xoopsTpl->assign('pagenav', $pagenav->renderNav());
$xoopsTpl->assign('is_logged_in', Utility::isUserLoggedIn());

// Search form persistence — keys match template $search.* references
$xoopsTpl->assign('search', [
    'keyword'   => htmlspecialchars($keyword, ENT_QUOTES),
    'name'      => htmlspecialchars($name, ENT_QUOTES),
    'year_from' => $yearFrom,
    'year_to'   => $yearTo,
    'major'     => htmlspecialchars($major, ENT_QUOTES),
    'degree'    => htmlspecialchars($degree, ENT_QUOTES),
    'industry'  => htmlspecialchars($industry, ENT_QUOTES),
    'company'   => htmlspecialchars($company, ENT_QUOTES),
    'city'      => htmlspecialchars($city, ENT_QUOTES),
    'state'     => htmlspecialchars($state, ENT_QUOTES),
    'country'   => htmlspecialchars($country, ENT_QUOTES),
    'skills'    => htmlspecialchars($skills, ENT_QUOTES),
    'mentor'    => $mentor,
]);

// SEO
$xoopsTpl->assign('xoops_pagetitle', _MD_ALUMNI_ADVANCED_SEARCH . ' - ' . $xoopsConfig['sitename']);

require_once XOOPS_ROOT_PATH . '/footer.php';
