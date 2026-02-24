<?php
/**
 * Alumni Management System - Main Index
 *
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author       XOOPS Development Team
 */

use XoopsModules\Alumni\{Helper, Utility};
use Xmf\Request;

require_once __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/preloads/autoloader.php';

\XoopsModules\Alumni\Constants::define();

$helper = Helper::getInstance();
$profileHandler = $helper->getHandler('profile');
$categoryHandler = $helper->getHandler('category');

// Pagination
$limit = $helper->getConfig('per_page') ?? 20;
$start = Request::getInt('start', 0);

// Get filters from request
$graduationYear = Request::getInt('year', 0);
$industry = Request::getString('industry', '');
$location = Request::getString('loc', '');
$keyword = Request::getString('q', '');

// Build search filters
$filters = [];
if ($graduationYear > 0) {
    $filters['graduation_year'] = $graduationYear;
}
if (!empty($industry)) {
    $filters['industry'] = $industry;
}
if (!empty($location)) {
    $filters['location'] = $location;
}
if (!empty($keyword)) {
    $filters['keyword'] = $keyword;
}

// Get total count
$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('status', 'active'));
$totalProfiles = $profileHandler->getCount($criteria);

// Get profiles — signature: searchProfiles($keyword, $filters, $limit, $start)
$profiles = $profileHandler->searchProfiles($keyword, $filters, $limit, $start);

// Format profiles for template (field names must match what alumni_index.tpl expects)
$profilesArray = [];
foreach ($profiles as $profile) {
    $profilesArray[] = [
        'id'              => $profile->getVar('profile_id'),
        'user_id'         => $profile->getVar('user_id'),
        'full_name'       => Utility::sanitizeHtml($profile->getVar('first_name') . ' ' . $profile->getVar('last_name')),
        'photo'           => !empty($profile->getVar('photo'))
                                ? ALUMNI_UPLOAD_URL . '/photos/' . $profile->getVar('photo')
                                : ALUMNI_URL . '/assets/images/default-avatar.png',
        'graduation_year' => $profile->getVar('graduation_year'),
        'job_title'       => Utility::sanitizeHtml($profile->getVar('current_position')),
        'company'         => Utility::sanitizeHtml($profile->getVar('current_company')),
        'industry'        => Utility::sanitizeHtml($profile->getVar('industry')),
        'location'        => Utility::sanitizeHtml($profile->getVar('location')),
        'bio'             => Utility::truncate(strip_tags($profile->getVar('bio')), 200),
        'url'             => Utility::getProfileUrl($profile->getVar('profile_id')),
        'featured'        => (bool)$profile->getVar('featured'),
        'can_connect'     => (bool)$profile->getVar('allow_networking'),
    ];
}

// Get graduation years for filter
$currentYear = (int)date('Y');
$graduationYears = [];
for ($year = $currentYear; $year >= 1950; $year--) {
    $graduationYears[$year] = $year;
}

// Get industries for filter
$industries = [
    'Technology'              => _MD_ALUMNI_INDUSTRY_TECHNOLOGY,
    'Finance'                 => _MD_ALUMNI_INDUSTRY_FINANCE,
    'Healthcare'              => _MD_ALUMNI_INDUSTRY_HEALTHCARE,
    'Education'               => _MD_ALUMNI_INDUSTRY_EDUCATION,
    'Manufacturing'           => _MD_ALUMNI_INDUSTRY_MANUFACTURING,
    'Retail'                  => _MD_ALUMNI_INDUSTRY_RETAIL,
    'Hospitality'             => _MD_ALUMNI_INDUSTRY_HOSPITALITY,
    'Real Estate'             => _MD_ALUMNI_INDUSTRY_REALESTATE,
    'Media & Entertainment'   => _MD_ALUMNI_INDUSTRY_MEDIA,
    'Government & Public'     => _MD_ALUMNI_INDUSTRY_GOVERNMENT,
    'Non-Profit'              => _MD_ALUMNI_INDUSTRY_NONPROFIT,
    'Consulting'              => _MD_ALUMNI_INDUSTRY_CONSULTING,
    'Legal'                   => _MD_ALUMNI_INDUSTRY_LEGAL,
    'Transportation'          => _MD_ALUMNI_INDUSTRY_TRANSPORTATION,
    'Energy'                  => _MD_ALUMNI_INDUSTRY_ENERGY,
    'Other'                   => _MD_ALUMNI_INDUSTRY_OTHER
];

// Pagination
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
$pagenav = new \XoopsPageNav(
    $totalProfiles,
    $limit,
    $start,
    'start',
    'year=' . $graduationYear . '&industry=' . urlencode($industry) . '&loc=' . urlencode($location) . '&q=' . urlencode($keyword)
);

// Set template
$GLOBALS['xoopsOption']['template_main'] = 'alumni_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

// Assign to template
$xoopsTpl->assign('profiles', $profilesArray);
$xoopsTpl->assign('years', $graduationYears);          // template uses $years
$xoopsTpl->assign('industries', $industries);
$xoopsTpl->assign('total_profiles', $totalProfiles);
$xoopsTpl->assign('pagenav', $pagenav->renderNav());
$xoopsTpl->assign('results_text', sprintf(_MD_ALUMNI_RESULTS_FOUND, $totalProfiles));
$xoopsTpl->assign('is_logged_in', isset($GLOBALS['xoopsUser']) && is_object($GLOBALS['xoopsUser']));

// Current filter values
$xoopsTpl->assign('current_year', $graduationYear);
$xoopsTpl->assign('current_industry', $industry);
$xoopsTpl->assign('current_location', $location);
$xoopsTpl->assign('current_keyword', $keyword);

// SEO
$xoopsTpl->assign('xoops_pagetitle', _MD_ALUMNI_BROWSE_DIRECTORY . ' - ' . $xoopsConfig['sitename']);

require_once XOOPS_ROOT_PATH . '/footer.php';
