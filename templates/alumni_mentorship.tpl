<{* Alumni Module - Mentorship Management *}>
<{include file="db:alumni_header.tpl"}>

<div class="alumni-mentorship-container">
    <h1 class="mb-4"><{$smarty.const._MD_ALUMNI_MENTORSHIP_PROGRAM}></h1>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link <{if $tab == 'overview'}>active<{/if}>" href="?tab=overview">
                <i class="fa fa-info-circle"></i> <{$smarty.const._MD_ALUMNI_OVERVIEW}>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link <{if $tab == 'mentors'}>active<{/if}>" href="?tab=mentors">
                <i class="fa fa-user-tie"></i> <{$smarty.const._MD_ALUMNI_FIND_MENTOR}>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link <{if $tab == 'my_mentors'}>active<{/if}>" href="?tab=my_mentors">
                <i class="fa fa-graduation-cap"></i> <{$smarty.const._MD_ALUMNI_MY_MENTORS}> <span class="badge bg-secondary"><{$my_mentors|@count}></span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link <{if $tab == 'my_mentees'}>active<{/if}>" href="?tab=my_mentees">
                <i class="fa fa-users"></i> <{$smarty.const._MD_ALUMNI_MY_MENTEES}> <span class="badge bg-secondary"><{$my_mentees|@count}></span>
            </a>
        </li>
        <{if $mentorship_requests|@count > 0}>
            <li class="nav-item" role="presentation">
                <a class="nav-link <{if $tab == 'requests'}>active<{/if}>" href="?tab=requests">
                    <i class="fa fa-bell"></i> Requests <span class="badge bg-danger"><{$mentorship_requests|@count}></span>
                </a>
            </li>
        <{/if}>
    </ul>

    <!-- Overview Tab -->
    <{if $tab == 'overview'}>
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h3 class="mb-3">About the Mentorship Program</h3>
                        <p>Connect with experienced alumni mentors to guide your career journey, or become a mentor and help fellow alumni achieve their goals.</p>

                        <h5 class="mt-4 mb-3">Benefits of Being a Mentee</h5>
                        <ul>
                            <li>Receive guidance from experienced professionals</li>
                            <li>Expand your professional network</li>
                            <li>Gain insights into career paths and industries</li>
                            <li>Develop new skills and competencies</li>
                        </ul>

                        <h5 class="mt-4 mb-3">Benefits of Being a Mentor</h5>
                        <ul>
                            <li>Give back to the alumni community</li>
                            <li>Develop leadership and coaching skills</li>
                            <li>Stay connected with emerging trends</li>
                            <li>Expand your professional network</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Your Mentorship Status</h5>
                    </div>
                    <div class="card-body">
                        <{if $user_is_mentor}>
                            <div class="alert alert-success">
                                <i class="fa fa-check-circle"></i> You are available as a mentor
                            </div>
                        <{else}>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> You are not currently a mentor
                            </div>
                            <a href="<{$xoops_url}>/modules/alumni/profile.php?op=edit" class="btn btn-primary w-100">
                                <i class="fa fa-graduation-cap"></i> <{$smarty.const._MD_ALUMNI_BECOME_MENTOR}>
                            </a>
                        <{/if}>

                        <hr>

                        <div class="stats">
                            <div class="stat-item mb-3">
                                <h4 class="mb-0"><{$my_mentors|@count}></h4>
                                <small class="text-muted"><{$smarty.const._MD_ALUMNI_MY_MENTORS}></small>
                            </div>
                            <div class="stat-item mb-3">
                                <h4 class="mb-0"><{$my_mentees|@count}></h4>
                                <small class="text-muted"><{$smarty.const._MD_ALUMNI_MY_MENTEES}></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <{/if}>

    <!-- Find Mentors Tab -->
    <{if $tab == 'mentors'}>
        <div class="card mb-4">
            <div class="card-body">
                <form method="get" action="mentorship.php" class="row g-3">
                    <input type="hidden" name="tab" value="mentors">
                    <div class="col-md-4">
                        <input type="text" name="q" class="form-control" placeholder="Search mentors..." value="<{$search_keyword}>">
                    </div>
                    <div class="col-md-3">
                        <select name="industry" class="form-select">
                            <option value="">All Industries</option>
                            <{foreach key=key item=label from=$industries}>
                                <option value="<{$key}>" <{if $search_industry == $key}>selected<{/if}>"><{$label}></option>
                            <{/foreach}>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="skills" class="form-control" placeholder="Skills..." value="<{$search_skills}>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa fa-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <{if $available_mentors|@count > 0}>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <{foreach item=mentor from=$available_mentors}>
                    <div class="col">
                        <div class="card mentor-card h-100">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto">
                                        <img src="<{$mentor.photo}>" alt="<{$mentor.name}>" class="rounded-circle" style="width: 100px; height: 100px;">
                                    </div>
                                    <div class="col">
                                        <h5 class="mb-1">
                                            <a href="<{$mentor.profile_url}>"><{$mentor.name}></a>
                                        </h5>
                                        <div class="text-muted small mb-2">Class of <{$mentor.graduation_year}></div>

                                        <{if $mentor.job_title}>
                                            <p class="mb-2">
                                                <strong><{$mentor.job_title}></strong>
                                                <{if $mentor.company}>
                                                    <br><span class="text-muted"><{$mentor.company}></span>
                                                <{/if}>
                                            </p>
                                        <{/if}>

                                        <{if $mentor.industry}>
                                            <span class="badge bg-primary me-1 mb-1"><{$mentor.industry}></span>
                                        <{/if}>

                                        <{if $mentor.skills|@count > 0}>
                                            <div class="mt-2">
                                                <{foreach item=skill from=$mentor.skills}>
                                                    <span class="badge bg-secondary me-1 mb-1"><{$skill}></span>
                                                <{/foreach}>
                                            </div>
                                        <{/if}>

                                        <{if $mentor.bio}>
                                            <p class="small mt-2 mb-0"><{$mentor.bio}></p>
                                        <{/if}>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <{if $mentor.has_requested}>
                                        <button class="btn btn-warning btn-sm" disabled>
                                            <i class="fa fa-clock"></i> Request Pending
                                        </button>
                                    <{elseif $mentor.is_mentor}>
                                        <button class="btn btn-success btn-sm" disabled>
                                            <i class="fa fa-check"></i> Your Mentor
                                        </button>
                                    <{else}>
                                        <button class="btn btn-primary btn-sm" onclick="requestMentor(<{$mentor.user_id}>)">
                                            <i class="fa fa-user-plus"></i> <{$smarty.const._MD_ALUMNI_REQUEST_MENTORSHIP}>
                                        </button>
                                    <{/if}>
                                    <a href="<{$mentor.profile_url}>" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-eye"></i> View Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <{/foreach}>
            </div>
        <{else}>
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> No mentors found matching your criteria
            </div>
        <{/if}>
    <{/if}>

    <!-- My Mentors Tab -->
    <{if $tab == 'my_mentors'}>
        <{if $my_mentors|@count > 0}>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <{foreach item=mentor from=$my_mentors}>
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto">
                                        <img src="<{$mentor.photo}>" alt="<{$mentor.name}>" class="rounded-circle" style="width: 80px; height: 80px;">
                                    </div>
                                    <div class="col">
                                        <h5 class="mb-1">
                                            <a href="<{$mentor.profile_url}>"><{$mentor.name}></a>
                                        </h5>
                                        <p class="small mb-2">
                                            <strong><{$mentor.job_title}></strong>
                                            <{if $mentor.company}><br><{$mentor.company}><{/if}>
                                        </p>
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-primary" onclick="sendMessage(<{$mentor.user_id}>)">
                                        <i class="fa fa-envelope"></i> Message
                                    </button>
                                    <a href="<{$mentor.profile_url}>" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-eye"></i> View Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <{/foreach}>
            </div>
        <{else}>
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> You don't have any mentors yet
                <a href="?tab=mentors" class="alert-link">Find a mentor</a>
            </div>
        <{/if}>
    <{/if}>

    <!-- My Mentees Tab -->
    <{if $tab == 'my_mentees'}>
        <{if $my_mentees|@count > 0}>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <{foreach item=mentee from=$my_mentees}>
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto">
                                        <img src="<{$mentee.photo}>" alt="<{$mentee.name}>" class="rounded-circle" style="width: 80px; height: 80px;">
                                    </div>
                                    <div class="col">
                                        <h5 class="mb-1">
                                            <a href="<{$mentee.profile_url}>"><{$mentee.name}></a>
                                        </h5>
                                        <p class="small mb-2">
                                            <{if $mentee.job_title}>
                                                <strong><{$mentee.job_title}></strong>
                                            <{/if}>
                                        </p>
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-primary" onclick="sendMessage(<{$mentee.user_id}>)">
                                        <i class="fa fa-envelope"></i> Message
                                    </button>
                                    <a href="<{$mentee.profile_url}>" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-eye"></i> View Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <{/foreach}>
            </div>
        <{else}>
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> You don't have any mentees yet
            </div>
        <{/if}>
    <{/if}>

    <!-- Mentorship Requests Tab -->
    <{if $tab == 'requests'}>
        <{if $mentorship_requests|@count > 0}>
            <div class="list-group">
                <{foreach item=request from=$mentorship_requests}>
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <img src="<{$request.photo}>" alt="<{$request.name}>" class="rounded-circle" style="width: 80px; height: 80px;">
                            </div>
                            <div class="col">
                                <h5 class="mb-1">
                                    <a href="<{$request.profile_url}>"><{$request.name}></a>
                                </h5>
                                <p class="mb-1 small">
                                    <{if $request.job_title}><{$request.job_title}><{/if}>
                                    | Class of <{$request.graduation_year}>
                                </p>
                                <small class="text-muted">
                                    <i class="fa fa-clock"></i> Requested <{$request.time_ago}>
                                </small>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-success" onclick="acceptMentorship(<{$request.mentorship_id}>)">
                                    <i class="fa fa-check"></i> Accept
                                </button>
                                <button class="btn btn-outline-danger" onclick="declineMentorship(<{$request.mentorship_id}>)">
                                    <i class="fa fa-times"></i> Decline
                                </button>
                            </div>
                        </div>
                    </div>
                <{/foreach}>
            </div>
        <{else}>
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> No pending mentorship requests
            </div>
        <{/if}>
    <{/if}>
</div>

<{include file="db:alumni_footer.tpl"}>
