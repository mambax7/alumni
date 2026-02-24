<{* Alumni Module - Connections Management *}>
<{include file="db:alumni_header.tpl"}>

<div class="alumni-connections-container">
    <h1 class="mb-4"><{$smarty.const._MD_ALUMNI_MY_CONNECTIONS}></h1>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link <{if $tab == 'connections'}>active<{/if}>" href="?tab=connections">
                <{$smarty.const._MD_ALUMNI_CONNECTIONS}> <span class="badge bg-secondary"><{$connections|@count}></span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link <{if $tab == 'requests'}>active<{/if}>" href="?tab=requests">
                <{$smarty.const._MD_ALUMNI_CONNECTION_REQUESTS}>
                <{if $pending_requests|@count > 0}>
                    <span class="badge bg-danger"><{$pending_requests|@count}></span>
                <{/if}>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link <{if $tab == 'sent'}>active<{/if}>" href="?tab=sent">
                <{$smarty.const._MD_ALUMNI_REQUEST_SENT}> <span class="badge bg-secondary"><{$sent_requests|@count}></span>
            </a>
        </li>
    </ul>

    <!-- Connections Tab -->
    <{if $tab == 'connections'}>
        <{if $connections|@count > 0}>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <{foreach item=conn from=$connections}>
                    <div class="col">
                        <div class="card connection-card h-100">
                            <div class="card-body text-center">
                                <img src="<{$conn.photo}>" alt="<{$conn.name}>" class="rounded-circle img-fluid mb-3" style="max-width: 100px;">

                                <h5 class="card-title mb-1">
                                    <a href="<{$conn.profile_url}>"><{$conn.name}></a>
                                </h5>

                                <div class="text-muted mb-2">
                                    <{$smarty.const._MD_ALUMNI_GRADUATION_YEAR}>: <{$conn.graduation_year}>
                                </div>

                                <{if $conn.job_title}>
                                    <p class="small mb-2">
                                        <strong><{$conn.job_title}></strong>
                                        <{if $conn.company}>
                                            <br><span class="text-muted"><{$conn.company}></span>
                                        <{/if}>
                                    </p>
                                <{/if}>

                                <{if $conn.location}>
                                    <p class="text-muted small mb-3">
                                        <i class="fa fa-map-marker-alt"></i> <{$conn.location}>
                                    </p>
                                <{/if}>

                                <div class="connection-actions">
                                    <a href="<{$conn.profile_url}>" class="btn btn-sm btn-primary mb-2 w-100">
                                        <i class="fa fa-eye"></i> <{$smarty.const._MD_ALUMNI_VIEW_PROFILE}>
                                    </a>
                                    <button class="btn btn-sm btn-outline-primary mb-2 w-100" onclick="sendMessage(<{$conn.user_id}>)">
                                        <i class="fa fa-envelope"></i> <{$smarty.const._MD_ALUMNI_MESSAGE}>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger w-100" onclick="removeConnection(<{$conn.connection_id}>)">
                                        <i class="fa fa-user-times"></i> <{$smarty.const._MD_ALUMNI_DISCONNECT}>
                                    </button>
                                </div>

                                <div class="connection-meta mt-3 small text-muted">
                                    Connected <{$conn.connected_since}>
                                </div>
                            </div>
                        </div>
                    </div>
                <{/foreach}>
            </div>
        <{else}>
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> <{$smarty.const._MD_ALUMNI_NO_CONNECTIONS}>
            </div>
        <{/if}>
    <{/if}>

    <!-- Pending Requests Tab -->
    <{if $tab == 'requests'}>
        <{if $pending_requests|@count > 0}>
            <div class="list-group">
                <{foreach item=request from=$pending_requests}>
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <img src="<{$request.photo}>" alt="<{$request.name}>" class="rounded-circle" style="width: 80px; height: 80px;">
                            </div>
                            <div class="col">
                                <h5 class="mb-1">
                                    <a href="<{$request.profile_url}>"><{$request.name}></a>
                                </h5>
                                <p class="mb-1">
                                    <{if $request.job_title}>
                                        <strong><{$request.job_title}></strong>
                                        <{if $request.company}>at <{$request.company}><{/if}>
                                    <{/if}>
                                </p>
                                <p class="text-muted mb-2 small">
                                    <i class="fa fa-graduation-cap"></i> Class of <{$request.graduation_year}>
                                    <{if $request.location}>
                                        | <i class="fa fa-map-marker-alt"></i> <{$request.location}>
                                    <{/if}>
                                </p>
                                <small class="text-muted">
                                    <i class="fa fa-clock"></i> Request sent <{$request.time_ago}>
                                </small>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-success mb-2" onclick="acceptConnection(<{$request.connection_id}>)">
                                    <i class="fa fa-check"></i> <{$smarty.const._MD_ALUMNI_ACCEPT}>
                                </button>
                                <button class="btn btn-outline-danger" onclick="declineConnection(<{$request.connection_id}>)">
                                    <i class="fa fa-times"></i> <{$smarty.const._MD_ALUMNI_DECLINE}>
                                </button>
                            </div>
                        </div>
                    </div>
                <{/foreach}>
            </div>
        <{else}>
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> No pending connection requests
            </div>
        <{/if}>
    <{/if}>

    <!-- Sent Requests Tab -->
    <{if $tab == 'sent'}>
        <{if $sent_requests|@count > 0}>
            <div class="list-group">
                <{foreach item=request from=$sent_requests}>
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <img src="<{$request.photo}>" alt="<{$request.name}>" class="rounded-circle" style="width: 80px; height: 80px;">
                            </div>
                            <div class="col">
                                <h5 class="mb-1">
                                    <a href="<{$request.profile_url}>"><{$request.name}></a>
                                </h5>
                                <p class="mb-1">
                                    <{if $request.job_title}>
                                        <strong><{$request.job_title}></strong>
                                        <{if $request.company}>at <{$request.company}><{/if}>
                                    <{/if}>
                                </p>
                                <p class="text-muted mb-2 small">
                                    <i class="fa fa-graduation-cap"></i> Class of <{$request.graduation_year}>
                                </p>
                                <small class="text-muted">
                                    <i class="fa fa-clock"></i> Sent <{$request.time_ago}>
                                </small>
                            </div>
                            <div class="col-auto">
                                <span class="badge bg-warning text-dark">
                                    <i class="fa fa-clock"></i> <{$smarty.const._MD_ALUMNI_PENDING}>
                                </span>
                                <button class="btn btn-sm btn-outline-danger mt-2" onclick="cancelRequest(<{$request.connection_id}>)">
                                    <i class="fa fa-times"></i> Cancel Request
                                </button>
                            </div>
                        </div>
                    </div>
                <{/foreach}>
            </div>
        <{else}>
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> No pending sent requests
            </div>
        <{/if}>
    <{/if}>
</div>

<{include file="db:alumni_footer.tpl"}>
