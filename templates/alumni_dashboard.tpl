<{* Alumni Module - User Dashboard *}>
<{include file="db:alumni_header.tpl"}>

<div class="alumni-dashboard-container">
    <h1 class="mb-4"><{$smarty.const._MD_ALUMNI_MY_DASHBOARD}></h1>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card text-center">
                <div class="card-body">
                    <i class="fa fa-user-friends fa-3x text-primary mb-2"></i>
                    <h3 class="mb-0"><{$stats.connections}></h3>
                    <p class="text-muted mb-0"><{$smarty.const._MD_ALUMNI_CONNECTIONS}></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card text-center">
                <div class="card-body">
                    <i class="fa fa-calendar-check fa-3x text-success mb-2"></i>
                    <h3 class="mb-0"><{$stats.events}></h3>
                    <p class="text-muted mb-0"><{$smarty.const._MD_ALUMNI_MY_EVENTS}></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card text-center">
                <div class="card-body">
                    <i class="fa fa-eye fa-3x text-info mb-2"></i>
                    <h3 class="mb-0"><{$stats.profile_views}></h3>
                    <p class="text-muted mb-0"><{$smarty.const._MD_ALUMNI_PROFILE_VIEWS}></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card text-center">
                <div class="card-body">
                    <i class="fa fa-graduation-cap fa-3x text-warning mb-2"></i>
                    <h3 class="mb-0"><{$stats.mentorships}></h3>
                    <p class="text-muted mb-0"><{$smarty.const._MD_ALUMNI_MENTORSHIP}></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Connection Requests -->
            <{if $connection_requests|@count > 0}>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fa fa-user-plus"></i> <{$smarty.const._MD_ALUMNI_CONNECTION_REQUESTS}>
                            <span class="badge bg-primary"><{$connection_requests|@count}></span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <{foreach item=request from=$connection_requests}>
                            <div class="connection-request-item mb-3 pb-3 border-bottom">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <img src="<{$request.photo}>" alt="<{$request.name}>" class="rounded-circle" style="width: 60px; height: 60px;">
                                    </div>
                                    <div class="col">
                                        <h6 class="mb-1">
                                            <a href="<{$request.profile_url}>"><{$request.name}></a>
                                        </h6>
                                        <p class="text-muted mb-0 small">
                                            <{if $request.job_title}><{$request.job_title}> at <{$request.company}><{/if}>
                                        </p>
                                        <p class="text-muted mb-0 small">
                                            <i class="fa fa-clock"></i> <{$request.time_ago}>
                                        </p>
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-sm btn-success" onclick="acceptConnection(<{$request.connection_id}>)">
                                            <i class="fa fa-check"></i> <{$smarty.const._MD_ALUMNI_ACCEPT}>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="declineConnection(<{$request.connection_id}>)">
                                            <i class="fa fa-times"></i> <{$smarty.const._MD_ALUMNI_DECLINE}>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <{/foreach}>
                    </div>
                </div>
            <{/if}>

            <!-- Upcoming Events -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-calendar"></i> <{$smarty.const._MD_ALUMNI_MY_RSVPS}>
                    </h5>
                </div>
                <div class="card-body">
                    <{if $my_events|@count > 0}>
                        <div class="list-group list-group-flush">
                            <{foreach item=event from=$my_events}>
                                <a href="<{$event.url}>" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><{$event.title}></h6>
                                        <small><{$event.days_until}> days</small>
                                    </div>
                                    <p class="mb-1 small text-muted">
                                        <i class="fa fa-calendar"></i> <{$event.date_formatted}>
                                        <{if $event.location}>
                                            | <i class="fa fa-map-marker-alt"></i> <{$event.location}>
                                        <{/if}>
                                    </p>
                                    <span class="badge bg-success"><{$event.rsvp_status}></span>
                                </a>
                            <{/foreach}>
                        </div>
                    <{else}>
                        <p class="text-muted mb-0">No upcoming events</p>
                    <{/if}>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-bell"></i> <{$smarty.const._MD_ALUMNI_RECENT_ACTIVITY}>
                    </h5>
                </div>
                <div class="card-body">
                    <{if $recent_activity|@count > 0}>
                        <div class="activity-feed">
                            <{foreach item=activity from=$recent_activity}>
                                <div class="activity-item mb-3">
                                    <div class="d-flex">
                                        <div class="activity-icon me-3">
                                            <i class="fa <{$activity.icon}> text-<{$activity.color}>"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-1"><{$activity.message}></p>
                                            <small class="text-muted"><{$activity.time_ago}></small>
                                        </div>
                                    </div>
                                </div>
                            <{/foreach}>
                        </div>
                    <{else}>
                        <p class="text-muted mb-0">No recent activity</p>
                    <{/if}>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Profile Quick View -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="<{$user_profile.photo}>" alt="<{$user_profile.name}>" class="rounded-circle img-fluid mb-3" style="max-width: 120px;">
                    <h5 class="mb-1"><{$user_profile.name}></h5>
                    <p class="text-muted mb-3"><{$user_profile.graduation_year}></p>

                    <a href="<{$xoops_url}>/modules/alumni/profile.php?id=<{$user_profile.id}>" class="btn btn-primary btn-sm w-100 mb-2">
                        <i class="fa fa-eye"></i> <{$smarty.const._MD_ALUMNI_VIEW_PROFILE}>
                    </a>
                    <a href="<{$xoops_url}>/modules/alumni/profile.php?op=edit" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fa fa-edit"></i> <{$smarty.const._MD_ALUMNI_EDIT_PROFILE}>
                    </a>
                </div>
            </div>

            <!-- My Connections -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <{$smarty.const._MD_ALUMNI_MY_CONNECTIONS}>
                        <span class="badge bg-secondary"><{$my_connections|@count}></span>
                    </h5>
                </div>
                <div class="card-body">
                    <{if $my_connections|@count > 0}>
                        <div class="row g-2">
                            <{foreach item=connection from=$my_connections name=conn_loop}>
                                <{if $smarty.foreach.conn_loop.index < 12}>
                                    <div class="col-4">
                                        <a href="<{$connection.profile_url}>" class="text-center d-block" title="<{$connection.name}>">
                                            <img src="<{$connection.photo}>" alt="<{$connection.name}>" class="rounded-circle img-fluid">
                                        </a>
                                    </div>
                                <{/if}>
                            <{/foreach}>
                        </div>
                        <{if $my_connections|@count > 12}>
                            <div class="text-center mt-3">
                                <a href="<{$xoops_url}>/modules/alumni/connections.php" class="btn btn-sm btn-outline-primary">
                                    View All (<{$my_connections|@count}>)
                                </a>
                            </div>
                        <{/if}>
                    <{else}>
                        <p class="text-muted mb-0"><{$smarty.const._MD_ALUMNI_NO_CONNECTIONS}></p>
                    <{/if}>
                </div>
            </div>

            <!-- Mentorship Status -->
            <{if $user_profile.available_as_mentor || $mentorship_requests|@count > 0}>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fa fa-graduation-cap"></i> <{$smarty.const._MD_ALUMNI_MENTORSHIP}>
                        </h5>
                    </div>
                    <div class="card-body">
                        <{if $user_profile.available_as_mentor}>
                            <div class="alert alert-success mb-3">
                                <i class="fa fa-check-circle"></i> You are available as a mentor
                            </div>
                        <{/if}>

                        <{if $mentorship_requests|@count > 0}>
                            <h6 class="mb-2">Pending Requests (<{$mentorship_requests|@count}>)</h6>
                            <div class="list-group list-group-flush">
                                <{foreach item=request from=$mentorship_requests}>
                                    <div class="list-group-item px-0">
                                        <small><{$request.name}></small>
                                    </div>
                                <{/foreach}>
                            </div>
                        <{/if}>

                        <a href="<{$xoops_url}>/modules/alumni/mentorship.php" class="btn btn-sm btn-outline-primary w-100 mt-2">
                            Manage Mentorships
                        </a>
                    </div>
                </div>
            <{/if}>
        </div>
    </div>
</div>

<{include file="db:alumni_footer.tpl"}>
