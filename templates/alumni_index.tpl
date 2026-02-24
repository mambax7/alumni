<{* Alumni Module - Directory Listing *}>
<{include file="db:alumni_header.tpl"}>

<div class="alumni-container">
    <!-- Page Header -->
    <div class="alumni-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="alumni-title"><{$smarty.const._MD_ALUMNI_DIRECTORY}></h1>
                <{if $total_profiles > 0}>
                    <p class="text-muted">
                        <{$results_text}>
                    </p>
                <{/if}>
            </div>
            <div class="col-md-4 text-end">
                <{if $is_logged_in}>
                    <a href="<{$xoops_url}>/modules/alumni/profile.php?op=edit" class="btn btn-primary">
                        <i class="fa fa-user-plus"></i> <{$smarty.const._MD_ALUMNI_CREATE_PROFILE}>
                    </a>
                <{/if}>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="alumni-filters card mb-4">
        <div class="card-body">
            <form method="get" action="index.php" class="row g-3">
                <!-- Search Keyword -->
                <div class="col-md-4">
                    <label class="form-label"><{$smarty.const._MD_ALUMNI_SEARCH_KEYWORD}></label>
                    <input type="text" name="q" class="form-control" placeholder="<{$smarty.const._MD_ALUMNI_SEARCH_PLACEHOLDER}>" value="<{$current_keyword}>">
                </div>

                <!-- Graduation Year -->
                <div class="col-md-2">
                    <label class="form-label"><{$smarty.const._MD_ALUMNI_GRADUATION_YEAR}></label>
                    <select name="year" class="form-select">
                        <option value=""><{$smarty.const._MD_ALUMNI_ALL}></option>
                        <{foreach item=year from=$years}>
                            <option value="<{$year}>" <{if $current_year == $year}>selected<{/if}>><{$year}></option>
                        <{/foreach}>
                    </select>
                </div>

                <!-- Industry -->
                <div class="col-md-3">
                    <label class="form-label"><{$smarty.const._MD_ALUMNI_INDUSTRY}></label>
                    <select name="industry" class="form-select">
                        <option value=""><{$smarty.const._MD_ALUMNI_ALL}></option>
                        <{foreach key=key item=label from=$industries}>
                            <option value="<{$key}>" <{if $current_industry == $key}>selected<{/if}>"><{$label}></option>
                        <{/foreach}>
                    </select>
                </div>

                <!-- Location -->
                <div class="col-md-2">
                    <label class="form-label"><{$smarty.const._MD_ALUMNI_LOCATION}></label>
                    <input type="text" name="loc" class="form-control" placeholder="<{$smarty.const._MD_ALUMNI_CITY}>" value="<{$current_location}>">
                </div>

                <!-- Submit -->
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alumni Profiles Grid -->
    <{if $profiles|@count > 0}>
        <div class="alumni-grid row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <{foreach item=profile from=$profiles}>
                <div class="col">
                    <div class="card alumni-card h-100 <{if $profile.featured}>alumni-featured<{/if}>">
                        <{if $profile.featured}>
                            <div class="featured-badge">
                                <span class="badge bg-warning text-dark">
                                    <i class="fa fa-star"></i> <{$smarty.const._MD_ALUMNI_FEATURED}>
                                </span>
                            </div>
                        <{/if}>

                        <div class="card-body text-center">
                            <!-- Profile Photo -->
                            <div class="alumni-photo-wrapper mb-3">
                                <img src="<{$profile.photo}>" alt="<{$profile.full_name}>" class="alumni-photo rounded-circle img-fluid">
                            </div>

                            <!-- Name -->
                            <h5 class="card-title mb-1">
                                <a href="<{$profile.url}>"><{$profile.full_name}></a>
                            </h5>

                            <!-- Graduation Year -->
                            <div class="text-muted mb-2">
                                <{$smarty.const._MD_ALUMNI_GRADUATION_YEAR}>: <strong><{$profile.graduation_year}></strong>
                            </div>

                            <!-- Job Info -->
                            <{if $profile.job_title}>
                                <div class="alumni-job mb-2">
                                    <strong><{$profile.job_title}></strong>
                                    <{if $profile.company}>
                                        <br><span class="text-muted"><{$profile.company}></span>
                                    <{/if}>
                                </div>
                            <{/if}>

                            <!-- Location -->
                            <{if $profile.location}>
                                <div class="alumni-location text-muted mb-3">
                                    <i class="fa fa-map-marker-alt"></i> <{$profile.location}>
                                </div>
                            <{/if}>

                            <!-- Bio Preview -->
                            <{if $profile.bio}>
                                <p class="card-text small text-muted"><{$profile.bio}></p>
                            <{/if}>

                            <!-- Actions -->
                            <div class="alumni-actions mt-3">
                                <a href="<{$profile.url}>" class="btn btn-sm btn-primary">
                                    <i class="fa fa-eye"></i> <{$smarty.const._MD_ALUMNI_VIEW_PROFILE}>
                                </a>
                                <{if $is_logged_in && $profile.can_connect}>
                                    <button class="btn btn-sm btn-outline-primary" onclick="connectWith(<{$profile.id}>)">
                                        <i class="fa fa-user-plus"></i> <{$smarty.const._MD_ALUMNI_CONNECT}>
                                    </button>
                                <{/if}>
                            </div>
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
            <i class="fa fa-info-circle"></i> <{$smarty.const._MD_ALUMNI_NO_ALUMNI}>
        </div>
    <{/if}>
</div>

<{include file="db:alumni_footer.tpl"}>
