<{* Alumni Module - Event Detail Page *}>
<{include file="db:alumni_header.tpl"}>

<div class="alumni-event-detail-container">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card event-detail-card mb-4">
                <!-- Event Header -->
                <div class="card-header event-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h2 class="mb-2"><{$event.title}></h2>
                    <div class="event-meta">
                        <span class="me-3">
                            <i class="fa fa-calendar"></i> <{$event.date_formatted}>
                        </span>
                        <span class="me-3">
                            <i class="fa fa-clock"></i> <{$event.time}>
                        </span>
                        <{if $event.location}>
                            <span>
                                <i class="fa fa-map-marker-alt"></i> <{$event.location}>
                            </span>
                        <{/if}>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Event Description -->
                    <div class="mb-4">
                        <h4><{$smarty.const._MD_ALUMNI_EVENT_DESCRIPTION}></h4>
                        <p><{$event.description}></p>
                    </div>

                    <!-- Event Details -->
                    <div class="row mb-4">
                        <{if $event.venue}>
                            <div class="col-md-6 mb-3">
                                <strong><i class="fa fa-building fa-fw"></i> <{$smarty.const._MD_ALUMNI_VENUE}>:</strong><br>
                                <{$event.venue}>
                            </div>
                        <{/if}>

                        <{if $event.organizer}>
                            <div class="col-md-6 mb-3">
                                <strong><i class="fa fa-user fa-fw"></i> <{$smarty.const._MD_ALUMNI_EVENT_ORGANIZER}>:</strong><br>
                                <{$event.organizer}>
                            </div>
                        <{/if}>

                        <{if $event.contact_email}>
                            <div class="col-md-6 mb-3">
                                <strong><i class="fa fa-envelope fa-fw"></i> <{$smarty.const._MD_ALUMNI_CONTACT_EMAIL}>:</strong><br>
                                <a href="mailto:<{$event.contact_email}>"><{$event.contact_email}></a>
                            </div>
                        <{/if}>

                        <{if $event.contact_phone}>
                            <div class="col-md-6 mb-3">
                                <strong><i class="fa fa-phone fa-fw"></i> <{$smarty.const._MD_ALUMNI_CONTACT_PHONE}>:</strong><br>
                                <{$event.contact_phone}>
                            </div>
                        <{/if}>

                        <{if $event.meeting_url}>
                            <div class="col-md-12 mb-3">
                                <strong><i class="fa fa-video fa-fw"></i> <{$smarty.const._MD_ALUMNI_MEETING_URL}>:</strong><br>
                                <a href="<{$event.meeting_url}>" target="_blank" rel="noopener" class="btn btn-sm btn-primary">
                                    <i class="fa fa-external-link-alt"></i> Join Meeting
                                </a>
                            </div>
                        <{/if}>
                    </div>

                    <!-- Attendees -->
                    <{if $attendees|@count > 0}>
                        <div class="mb-4">
                            <h4><{$smarty.const._MD_ALUMNI_EVENT_ATTENDEES}> (<{$attendees|@count}>)</h4>
                            <div class="attendees-list">
                                <div class="row row-cols-2 row-cols-md-4 g-3">
                                    <{foreach item=attendee from=$attendees}>
                                        <div class="col">
                                            <div class="attendee-card text-center">
                                                <img src="<{$attendee.photo}>" alt="<{$attendee.name}>" class="rounded-circle img-fluid mb-2" style="max-width: 80px;">
                                                <div class="small">
                                                    <a href="<{$attendee.profile_url}>"><{$attendee.name}></a>
                                                </div>
                                            </div>
                                        </div>
                                    <{/foreach}>
                                </div>
                            </div>
                        </div>
                    <{/if}>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- RSVP Card -->
            <{if $event.is_upcoming}>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><{$smarty.const._MD_ALUMNI_RSVP}></h5>
                    </div>
                    <div class="card-body">
                        <{if $is_logged_in}>
                            <{if $user_rsvp}>
                                <div class="alert alert-success">
                                    <i class="fa fa-check-circle"></i> You are attending this event
                                    <{if $user_rsvp.guests > 0}>
                                        <br><small>Guests: <{$user_rsvp.guests}></small>
                                    <{/if}>
                                </div>

                                <form method="post" action="event.php" id="rsvp-form">
                                    <input type="hidden" name="op" value="update_rsvp">
                                    <input type="hidden" name="event_id" value="<{$event.id}>">
                                    <input type="hidden" name="XOOPS_TOKEN_REQUEST" value="<{$token}>">

                                    <div class="mb-3">
                                        <label class="form-label"><{$smarty.const._MD_ALUMNI_RSVP_STATUS}></label>
                                        <select name="status" class="form-select">
                                            <option value="attending" <{if $user_rsvp.status == 'attending'}>selected<{/if}>><{$smarty.const._MD_ALUMNI_RSVP_YES}></option>
                                            <option value="maybe" <{if $user_rsvp.status == 'maybe'}>selected<{/if}>><{$smarty.const._MD_ALUMNI_RSVP_MAYBE}></option>
                                            <option value="not_attending" <{if $user_rsvp.status == 'not_attending'}>selected<{/if}>><{$smarty.const._MD_ALUMNI_RSVP_NO}></option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label"><{$smarty.const._MD_ALUMNI_GUESTS}></label>
                                        <input type="number" name="guests" class="form-control" value="<{$user_rsvp.guests}>" min="0" max="10">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label"><{$smarty.const._MD_ALUMNI_NOTES}></label>
                                        <textarea name="notes" class="form-control" rows="2"><{$user_rsvp.notes}></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 mb-2">
                                        <i class="fa fa-save"></i> Update RSVP
                                    </button>
                                    <button type="button" class="btn btn-outline-danger w-100" onclick="cancelRSVP(<{$event.id}>)">
                                        <i class="fa fa-times"></i> Cancel RSVP
                                    </button>
                                </form>
                            <{elseif $event.is_full}>
                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-triangle"></i> This event is full
                                </div>
                            <{elseif $event.registration_closed}>
                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-triangle"></i> Registration is closed
                                </div>
                            <{else}>
                                <form method="post" action="event.php" id="rsvp-form">
                                    <input type="hidden" name="op" value="rsvp">
                                    <input type="hidden" name="event_id" value="<{$event.id}>">
                                    <input type="hidden" name="XOOPS_TOKEN_REQUEST" value="<{$token}>">

                                    <div class="mb-3">
                                        <label class="form-label"><{$smarty.const._MD_ALUMNI_RSVP_STATUS}></label>
                                        <select name="status" class="form-select" required>
                                            <option value="attending"><{$smarty.const._MD_ALUMNI_RSVP_YES}></option>
                                            <option value="maybe"><{$smarty.const._MD_ALUMNI_RSVP_MAYBE}></option>
                                            <option value="not_attending"><{$smarty.const._MD_ALUMNI_RSVP_NO}></option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label"><{$smarty.const._MD_ALUMNI_GUESTS}></label>
                                        <input type="number" name="guests" class="form-control" value="0" min="0" max="10">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label"><{$smarty.const._MD_ALUMNI_NOTES}> (<{$smarty.const._MD_ALUMNI_OPTIONAL}>)</label>
                                        <textarea name="notes" class="form-control" rows="2"></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-calendar-check"></i> <{$smarty.const._MD_ALUMNI_RSVP_NOW}>
                                    </button>
                                </form>
                            <{/if}>
                        <{else}>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> Please log in to RSVP
                            </div>
                            <a href="<{$xoops_url}>/user.php" class="btn btn-primary w-100">
                                <i class="fa fa-sign-in-alt"></i> Log In
                            </a>
                        <{/if}>
                    </div>
                </div>
            <{else}>
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="alert alert-secondary mb-0">
                            <i class="fa fa-clock"></i> This event has ended
                        </div>
                    </div>
                </div>
            <{/if}>

            <!-- Event Info Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Event Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Status:</strong><br>
                        <{if $event.is_upcoming}>
                            <span class="badge bg-success">Upcoming</span>
                        <{else}>
                            <span class="badge bg-secondary">Past Event</span>
                        <{/if}>
                    </div>

                    <{if $event.capacity > 0}>
                        <div class="mb-3">
                            <strong>Capacity:</strong><br>
                            <{$event.attendees}> / <{$event.capacity}>
                            <div class="progress mt-1" style="height: 10px;">
                                <div class="progress-bar" role="progressbar" style="width: <{$event.capacity_percent}>%"></div>
                            </div>
                        </div>
                    <{else}>
                        <div class="mb-3">
                            <strong>Attendees:</strong><br>
                            <{$event.attendees}> attending
                        </div>
                    <{/if}>

                    <{if $event.registration_deadline}>
                        <div class="mb-3">
                            <strong><{$smarty.const._MD_ALUMNI_REGISTRATION_DEADLINE}>:</strong><br>
                            <{$event.registration_deadline_formatted}>
                        </div>
                    <{/if}>

                    <{if $event.is_upcoming && $event.days_until}>
                        <div class="mb-0">
                            <strong>Time Until Event:</strong><br>
                            <{$event.days_until}> days
                        </div>
                    <{/if}>
                </div>
            </div>

            <!-- Share Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><{$smarty.const._MD_ALUMNI_SHARE}></h5>
                </div>
                <div class="card-body">
                    <button class="btn btn-outline-primary btn-sm w-100 mb-2" onclick="shareEvent('facebook')">
                        <i class="fab fa-facebook"></i> Share on Facebook
                    </button>
                    <button class="btn btn-outline-info btn-sm w-100 mb-2" onclick="shareEvent('twitter')">
                        <i class="fab fa-twitter"></i> Share on Twitter
                    </button>
                    <button class="btn btn-outline-secondary btn-sm w-100" onclick="copyEventLink()">
                        <i class="fa fa-link"></i> Copy Link
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<{include file="db:alumni_footer.tpl"}>
