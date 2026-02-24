<{* Alumni Module - Events Listing *}>
<{include file="db:alumni_header.tpl"}>

<div class="alumni-events-container">
    <!-- Page Header -->
    <div class="alumni-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="alumni-title"><{$smarty.const._MD_ALUMNI_EVENTS}></h1>
                <{if $total_events > 0}>
                    <p class="text-muted"><{$results_text}></p>
                <{/if}>
            </div>
            <div class="col-md-4 text-end">
                <{if $is_admin}>
                    <a href="<{$xoops_url}>/modules/alumni/admin/events.php?op=new" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Create Event
                    </a>
                <{/if}>
            </div>
        </div>
    </div>

    <!-- Event Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link <{if $filter == 'upcoming'}>active<{/if}>" href="?filter=upcoming">
                        <{$smarty.const._MD_ALUMNI_UPCOMING_EVENTS}>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link <{if $filter == 'past'}>active<{/if}>" href="?filter=past">
                        <{$smarty.const._MD_ALUMNI_PAST_EVENTS}>
                    </a>
                </li>
                <{if $is_logged_in}>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <{if $filter == 'my'}>active<{/if}>" href="?filter=my">
                            <{$smarty.const._MD_ALUMNI_MY_EVENTS}>
                        </a>
                    </li>
                <{/if}>
            </ul>
        </div>
    </div>

    <!-- Event Listings -->
    <{if $events|@count > 0}>
        <div class="events-list">
            <{foreach item=event from=$events}>
                <div class="card event-card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <!-- Event Date Badge -->
                            <div class="col-md-2 text-center">
                                <div class="event-date-badge">
                                    <div class="date-month"><{$event.month}></div>
                                    <div class="date-day"><{$event.day}></div>
                                    <div class="date-year"><{$event.year}></div>
                                </div>
                            </div>

                            <!-- Event Details -->
                            <div class="col-md-7">
                                <h3 class="event-title mb-2">
                                    <a href="<{$event.url}>"><{$event.title}></a>
                                </h3>

                                <div class="event-meta text-muted mb-3">
                                    <span class="me-3">
                                        <i class="fa fa-calendar"></i> <{$event.date_formatted}>
                                    </span>
                                    <span class="me-3">
                                        <i class="fa fa-clock"></i> <{$event.time}>
                                    </span>
                                    <{if $event.location}>
                                        <span class="me-3">
                                            <i class="fa fa-map-marker-alt"></i> <{$event.location}>
                                        </span>
                                    <{/if}>
                                </div>

                                <p class="event-description mb-3"><{$event.description}></p>

                                <div class="event-details">
                                    <{if $event.capacity > 0}>
                                        <span class="badge bg-secondary me-2">
                                            <i class="fa fa-users"></i> <{$event.attendees}>/<{$event.capacity}> Attending
                                        </span>
                                    <{else}>
                                        <span class="badge bg-secondary me-2">
                                            <i class="fa fa-users"></i> <{$event.attendees}> Attending
                                        </span>
                                    <{/if}>

                                    <{if $event.type}>
                                        <span class="badge bg-info me-2"><{$event.type}></span>
                                    <{/if}>

                                    <{if $event.is_upcoming}>
                                        <span class="badge bg-success me-2">
                                            <i class="fa fa-clock"></i> <{$event.days_until}> days away
                                        </span>
                                    <{/if}>
                                </div>
                            </div>

                            <!-- Event Actions -->
                            <div class="col-md-3 text-end">
                                <{if $event.is_upcoming}>
                                    <{if $is_logged_in}>
                                        <{if $event.user_rsvp_status == 'attending'}>
                                            <button class="btn btn-success btn-sm mb-2 w-100" disabled>
                                                <i class="fa fa-check"></i> <{$smarty.const._MD_ALUMNI_RSVP_YES}>
                                            </button>
                                            <button class="btn btn-outline-secondary btn-sm w-100" onclick="cancelRSVP(<{$event.id}>)">
                                                Cancel RSVP
                                            </button>
                                        <{elseif $event.user_rsvp_status == 'maybe'}>
                                            <button class="btn btn-warning btn-sm mb-2 w-100" disabled>
                                                <i class="fa fa-question"></i> <{$smarty.const._MD_ALUMNI_RSVP_MAYBE}>
                                            </button>
                                        <{elseif $event.is_full}>
                                            <button class="btn btn-secondary btn-sm w-100" disabled>
                                                <i class="fa fa-users"></i> Event Full
                                            </button>
                                        <{else}>
                                            <a href="<{$event.url}>" class="btn btn-primary btn-sm w-100 mb-2">
                                                <i class="fa fa-calendar-check"></i> <{$smarty.const._MD_ALUMNI_RSVP}>
                                            </a>
                                        <{/if}>
                                    <{/if}>
                                <{/if}>

                                <a href="<{$event.url}>" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="fa fa-eye"></i> <{$smarty.const._MD_ALUMNI_VIEW_EVENT}>
                                </a>
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
            <i class="fa fa-info-circle"></i> <{$smarty.const._MD_ALUMNI_NO_EVENTS}>
        </div>
    <{/if}>
</div>

<style>
.event-date-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px;
    border-radius: 10px;
    text-align: center;
}
.date-month {
    font-size: 14px;
    font-weight: bold;
    text-transform: uppercase;
}
.date-day {
    font-size: 36px;
    font-weight: bold;
    line-height: 1;
}
.date-year {
    font-size: 14px;
}
</style>

<{include file="db:alumni_footer.tpl"}>
