<{* Alumni Module - Profile View Page *}>
<{include file="db:alumni_header.tpl"}>

<div class="alumni-profile-container">
    <div class="row">
        <!-- Left Sidebar -->
        <div class="col-lg-4">
            <div class="card alumni-profile-card mb-4">
                <div class="card-body text-center">
                    <!-- Profile Photo -->
                    <div class="profile-photo-wrapper mb-3">
                        <img src="<{$profile.photo}>" alt="<{$profile.full_name}>" class="profile-photo rounded-circle img-fluid">
                        <{if $is_owner}>
                            <a href="<{$xoops_url}>/modules/alumni/profile.php?op=edit" class="btn btn-sm btn-secondary mt-2">
                                <i class="fa fa-camera"></i> <{$smarty.const._MD_ALUMNI_CHANGE_PHOTO}>
                            </a>
                        <{/if}>
                    </div>

                    <!-- Name & Year -->
                    <h2 class="mb-1"><{$profile.full_name}></h2>
                    <div class="text-muted mb-3">
                        <{$smarty.const._MD_ALUMNI_GRADUATION_YEAR}>: <strong><{$profile.graduation_year}></strong>
                    </div>

                    <!-- Current Position -->
                    <{if $profile.job_title}>
                        <div class="mb-3">
                            <h5 class="mb-1"><{$profile.job_title}></h5>
                            <{if $profile.company}>
                                <p class="text-muted mb-0"><{$profile.company}></p>
                            <{/if}>
                        </div>
                    <{/if}>

                    <!-- Location -->
                    <{if $profile.location}>
                        <p class="text-muted mb-3">
                            <i class="fa fa-map-marker-alt"></i> <{$profile.location}>
                        </p>
                    <{/if}>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 mb-3">
                        <{if $is_owner}>
                            <a href="<{$xoops_url}>/modules/alumni/profile.php?op=edit" class="btn btn-primary">
                                <i class="fa fa-edit"></i> <{$smarty.const._MD_ALUMNI_EDIT_PROFILE}>
                            </a>
                        <{else}>
                            <{if $is_logged_in}>
                                <{if $connection_status == 'none'}>
                                    <button class="btn btn-primary" onclick="sendConnectionRequest(<{$profile.id}>)">
                                        <i class="fa fa-user-plus"></i> <{$smarty.const._MD_ALUMNI_CONNECT}>
                                    </button>
                                <{elseif $connection_status == 'pending'}>
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fa fa-clock"></i> <{$smarty.const._MD_ALUMNI_PENDING}>
                                    </button>
                                <{elseif $connection_status == 'accepted'}>
                                    <button class="btn btn-success" disabled>
                                        <i class="fa fa-check"></i> <{$smarty.const._MD_ALUMNI_CONNECTED}>
                                    </button>
                                    <button class="btn btn-outline-primary" onclick="sendMessage(<{$profile.id}>)">
                                        <i class="fa fa-envelope"></i> <{$smarty.const._MD_ALUMNI_MESSAGE}>
                                    </button>
                                <{/if}>
                            <{/if}>
                        <{/if}>
                    </div>

                    <!-- Stats -->
                    <div class="profile-stats">
                        <div class="row text-center">
                            <div class="col-4">
                                <strong><{$profile.connections_count}></strong>
                                <br><small class="text-muted"><{$smarty.const._MD_ALUMNI_CONNECTIONS}></small>
                            </div>
                            <div class="col-4">
                                <strong><{$profile.profile_views}></strong>
                                <br><small class="text-muted"><{$smarty.const._MD_ALUMNI_PROFILE_VIEWS}></small>
                            </div>
                            <div class="col-4">
                                <strong><{$profile.events_count}></strong>
                                <br><small class="text-muted"><{$smarty.const._MD_ALUMNI_EVENTS}></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <{if $profile.email || $profile.phone || $profile.website}>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><{$smarty.const._MD_ALUMNI_CONTACT}></h5>
                    </div>
                    <div class="card-body">
                        <{if $profile.email}>
                            <p class="mb-2">
                                <i class="fa fa-envelope fa-fw"></i>
                                <a href="mailto:<{$profile.email}>"><{$profile.email}></a>
                            </p>
                        <{/if}>
                        <{if $profile.phone}>
                            <p class="mb-2">
                                <i class="fa fa-phone fa-fw"></i> <{$profile.phone}>
                            </p>
                        <{/if}>
                        <{if $profile.website}>
                            <p class="mb-0">
                                <i class="fa fa-globe fa-fw"></i>
                                <a href="<{$profile.website}>" target="_blank" rel="noopener"><{$profile.website}></a>
                            </p>
                        <{/if}>
                    </div>
                </div>
            <{/if}>

            <!-- Social Links -->
            <{if $profile.linkedin || $profile.twitter || $profile.facebook}>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><{$smarty.const._MD_ALUMNI_SOCIAL_LINKS}></h5>
                    </div>
                    <div class="card-body">
                        <{if $profile.linkedin}>
                            <a href="<{$profile.linkedin}>" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm me-2 mb-2">
                                <i class="fab fa-linkedin"></i> LinkedIn
                            </a>
                        <{/if}>
                        <{if $profile.twitter}>
                            <a href="<{$profile.twitter}>" target="_blank" rel="noopener" class="btn btn-outline-info btn-sm me-2 mb-2">
                                <i class="fab fa-twitter"></i> Twitter
                            </a>
                        <{/if}>
                        <{if $profile.facebook}>
                            <a href="<{$profile.facebook}>" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm mb-2">
                                <i class="fab fa-facebook"></i> Facebook
                            </a>
                        <{/if}>
                    </div>
                </div>
            <{/if}>
        </div>

        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- About Section -->
            <{if $profile.bio}>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><{$smarty.const._MD_ALUMNI_ABOUT}></h5>
                    </div>
                    <div class="card-body">
                        <p><{$profile.bio}></p>
                    </div>
                </div>
            <{/if}>

            <!-- Academic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><{$smarty.const._MD_ALUMNI_GRADUATION_YEAR}></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <{if $profile.graduation_year}>
                            <div class="col-md-6 mb-3">
                                <strong><{$smarty.const._MD_ALUMNI_GRADUATION_YEAR}>:</strong><br>
                                <{$profile.graduation_year}>
                            </div>
                        <{/if}>
                        <{if $profile.degree}>
                            <div class="col-md-6 mb-3">
                                <strong><{$smarty.const._MD_ALUMNI_DEGREE}>:</strong><br>
                                <{$profile.degree}>
                            </div>
                        <{/if}>
                        <{if $profile.major}>
                            <div class="col-md-6 mb-3">
                                <strong><{$smarty.const._MD_ALUMNI_MAJOR}>:</strong><br>
                                <{$profile.major}>
                            </div>
                        <{/if}>
                        <{if $profile.department}>
                            <div class="col-md-6 mb-3">
                                <strong><{$smarty.const._MD_ALUMNI_DEPARTMENT}>:</strong><br>
                                <{$profile.department}>
                            </div>
                        <{/if}>
                    </div>
                </div>
            </div>

            <!-- Professional Information -->
            <{if $profile.job_title || $profile.industry}>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><{$smarty.const._MD_ALUMNI_CURRENT_POSITION}></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <{if $profile.job_title}>
                                <div class="col-md-6 mb-3">
                                    <strong><{$smarty.const._MD_ALUMNI_JOB_TITLE}>:</strong><br>
                                    <{$profile.job_title}>
                                </div>
                            <{/if}>
                            <{if $profile.company}>
                                <div class="col-md-6 mb-3">
                                    <strong><{$smarty.const._MD_ALUMNI_COMPANY}>:</strong><br>
                                    <{$profile.company}>
                                </div>
                            <{/if}>
                            <{if $profile.industry}>
                                <div class="col-md-6 mb-3">
                                    <strong><{$smarty.const._MD_ALUMNI_INDUSTRY}>:</strong><br>
                                    <{$profile.industry}>
                                </div>
                            <{/if}>
                        </div>
                    </div>
                </div>
            <{/if}>

            <!-- Skills -->
            <{if $profile.skills|@count > 0}>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><{$smarty.const._MD_ALUMNI_SKILLS}></h5>
                    </div>
                    <div class="card-body">
                        <{foreach item=skill from=$profile.skills}>
                            <span class="badge bg-primary me-1 mb-1"><{$skill}></span>
                        <{/foreach}>
                    </div>
                </div>
            <{/if}>

            <!-- Mentorship -->
            <{if $profile.available_as_mentor}>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><{$smarty.const._MD_ALUMNI_MENTORSHIP}></h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success mb-0">
                            <i class="fa fa-graduation-cap"></i> <{$smarty.const._MD_ALUMNI_AVAILABLE_AS_MENTOR}>
                            <{if !$is_owner && $is_logged_in}>
                                <a href="<{$xoops_url}>/modules/alumni/mentorship.php?op=request&mentor_id=<{$profile.id}>" class="btn btn-sm btn-success float-end">
                                    <{$smarty.const._MD_ALUMNI_REQUEST_MENTORSHIP}>
                                </a>
                            <{/if}>
                        </div>
                    </div>
                </div>
            <{/if}>
        </div>
    </div>
</div>

<{include file="db:alumni_footer.tpl"}>
