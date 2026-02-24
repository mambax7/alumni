<{* Alumni Module - Events Block *}>
<div class="alumni-block alumni-block-events">
    <{if $block.events|@count > 0}>
        <div class="events-list">
            <{foreach item=event from=$block.events}>
                <div class="event-item mb-3 pb-3 border-bottom">
                    <h6 class="mb-2">
                        <a href="<{$event.url}>"><{$event.title}></a>
                    </h6>
                    <div class="small text-muted mb-2">
                        <div>
                            <i class="fa fa-calendar"></i> <{$event.date_formatted}>
                        </div>
                        <div>
                            <i class="fa fa-clock"></i> <{$event.time}>
                        </div>
                        <{if $event.location}>
                            <div>
                                <i class="fa fa-map-marker-alt"></i> <{$event.location}>
                            </div>
                        <{/if}>
                    </div>
                    <{if $event.days_until <= 7}>
                        <span class="badge bg-warning text-dark">
                            <i class="fa fa-exclamation-circle"></i> <{$event.days_until}> days away
                        </span>
                    <{/if}>
                    <span class="badge bg-secondary">
                        <i class="fa fa-users"></i> <{$event.attendees}> attending
                    </span>
                </div>
            <{/foreach}>
        </div>
        <div class="text-center mt-3">
            <a href="<{$xoops_url}>/modules/alumni/events.php" class="btn btn-sm btn-primary">
                <{$smarty.const._MB_ALUMNI_VIEW_ALL}>
            </a>
        </div>
    <{else}>
        <p class="text-muted mb-0"><{$smarty.const._MB_ALUMNI_EVENTS_EMPTY}></p>
    <{/if}>
</div>
