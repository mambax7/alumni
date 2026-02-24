<{* Alumni Module - Advanced Search *}>
<{include file="db:alumni_header.tpl"}>

<div class="alumni-search-container">
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="mb-0"><{$smarty.const._MD_ALUMNI_ADVANCED_SEARCH}></h3>
        </div>
        <div class="card-body">
            <form method="get" action="search.php" id="alumni-search-form">
                <div class="row g-3">
                    <!-- Keyword Search -->
                    <div class="col-md-12">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_SEARCH_KEYWORD}></label>
                        <input type="text" name="q" class="form-control form-control-lg" placeholder="<{$smarty.const._MD_ALUMNI_SEARCH_PLACEHOLDER}>" value="<{$search.keyword}>">
                    </div>

                    <!-- Name -->
                    <div class="col-md-6">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_FULL_NAME}></label>
                        <input type="text" name="name" class="form-control" value="<{$search.name}>">
                    </div>

                    <!-- Graduation Year -->
                    <div class="col-md-3">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_GRADUATION_YEAR}> (<{$smarty.const._MD_ALUMNI_FROM}>)</label>
                        <select name="year_from" class="form-select">
                            <option value="">Any</option>
                            <{foreach item=year from=$years}>
                                <option value="<{$year}>" <{if $search.year_from == $year}>selected<{/if}>><{$year}></option>
                            <{/foreach}>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_GRADUATION_YEAR}> (<{$smarty.const._MD_ALUMNI_TO}>)</label>
                        <select name="year_to" class="form-select">
                            <option value="">Any</option>
                            <{foreach item=year from=$years}>
                                <option value="<{$year}>" <{if $search.year_to == $year}>selected<{/if}>><{$year}></option>
                            <{/foreach}>
                        </select>
                    </div>

                    <!-- Major/Degree -->
                    <div class="col-md-6">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_MAJOR}></label>
                        <input type="text" name="major" class="form-control" value="<{$search.major}>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_DEGREE}></label>
                        <input type="text" name="degree" class="form-control" value="<{$search.degree}>">
                    </div>

                    <!-- Industry -->
                    <div class="col-md-6">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_INDUSTRY}></label>
                        <select name="industry" class="form-select">
                            <option value="">All Industries</option>
                            <{foreach key=key item=label from=$industries}>
                                <option value="<{$key}>" <{if $search.industry == $key}>selected<{/if}>"><{$label}></option>
                            <{/foreach}>
                        </select>
                    </div>

                    <!-- Company -->
                    <div class="col-md-6">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_COMPANY}></label>
                        <input type="text" name="company" class="form-control" value="<{$search.company}>">
                    </div>

                    <!-- Location -->
                    <div class="col-md-4">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_CITY}></label>
                        <input type="text" name="city" class="form-control" value="<{$search.city}>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_STATE}></label>
                        <input type="text" name="state" class="form-control" value="<{$search.state}>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_COUNTRY}></label>
                        <input type="text" name="country" class="form-control" value="<{$search.country}>">
                    </div>

                    <!-- Skills -->
                    <div class="col-md-12">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_SKILLS}></label>
                        <input type="text" name="skills" class="form-control" value="<{$search.skills}>" placeholder="e.g., PHP, JavaScript, Marketing">
                    </div>

                    <!-- Mentorship -->
                    <div class="col-md-12">
                        <div class="form-check">
                            <input type="checkbox" name="mentor" class="form-check-input" id="mentor-filter" value="1" <{if $search.mentor}>checked<{/if}>>
                            <label class="form-check-label" for="mentor-filter">
                                <{$smarty.const._MD_ALUMNI_AVAILABLE_AS_MENTOR}>
                            </label>
                        </div>
                    </div>

                    <!-- Search/Reset Buttons -->
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-lg me-2">
                            <i class="fa fa-search"></i> <{$smarty.const._MD_ALUMNI_SEARCH}>
                        </button>
                        <a href="search.php" class="btn btn-outline-secondary btn-lg">
                            <i class="fa fa-undo"></i> <{$smarty.const._MD_ALUMNI_CLEAR_FILTERS}>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Search Results -->
    <{if $has_searched}>
        <div class="search-results">
            <h4 class="mb-3">
                <{$smarty.const._MD_ALUMNI_SEARCH_RESULTS}>
                <{if $total_results > 0}>
                    <span class="text-muted">(<{$total_results}> found)</span>
                <{/if}>
            </h4>

            <{if $results|@count > 0}>
                <div class="alumni-grid row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <{foreach item=profile from=$results}>
                        <div class="col">
                            <div class="card alumni-card h-100">
                                <div class="card-body text-center">
                                    <img src="<{$profile.photo}>" alt="<{$profile.full_name}>" class="alumni-photo rounded-circle img-fluid mb-3" style="max-width: 100px;">

                                    <h5 class="card-title mb-1">
                                        <a href="<{$profile.url}>"><{$profile.full_name}></a>
                                    </h5>

                                    <div class="text-muted mb-2">
                                        <{$smarty.const._MD_ALUMNI_GRADUATION_YEAR}>: <strong><{$profile.graduation_year}></strong>
                                    </div>

                                    <{if $profile.job_title}>
                                        <div class="mb-2">
                                            <strong><{$profile.job_title}></strong>
                                            <{if $profile.company}>
                                                <br><span class="text-muted"><{$profile.company}></span>
                                            <{/if}>
                                        </div>
                                    <{/if}>

                                    <{if $profile.location}>
                                        <div class="text-muted mb-3">
                                            <i class="fa fa-map-marker-alt"></i> <{$profile.location}>
                                        </div>
                                    <{/if}>

                                    <a href="<{$profile.url}>" class="btn btn-sm btn-primary">
                                        <i class="fa fa-eye"></i> <{$smarty.const._MD_ALUMNI_VIEW_PROFILE}>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <{/foreach}>
                </div>

                <!-- Pagination -->
                <{if $pagenav}>
                    <div class="alumni-pagination mt-4">
                        <{$pagenav}>
                    </div>
                <{/if}>
            <{else}>
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> <{$smarty.const._MD_ALUMNI_NO_RESULTS}>
                </div>
            <{/if}>
        </div>
    <{/if}>
</div>

<{include file="db:alumni_footer.tpl"}>
