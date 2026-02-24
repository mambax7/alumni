<{* Alumni Admin Dashboard *}>

<div class="alumni-admin">

  <h1 class="xm-admin-title"><{$smarty.const._AM_ALUMNI_DASHBOARD}></h1>

  <{if $xm_testdata_buttons}><{$xm_testdata_buttons nofilter}><{/if}>

  <{* ── Profiles ────────────────────────────────────────── *}>
  <h2 class="xm-stats-group-label"><{$smarty.const._AM_ALUMNI_STATISTICS}> — <{$smarty.const._AM_ALUMNI_STAT_TOTAL_PROFILES}></h2>
  <div class="xm-stat-list">

    <div class="xm-stat-row xm-stat-row--blue">
      <span class="xm-stat-row__icon" aria-hidden="true">&#x1F393;</span>
      <span class="xm-stat-row__label"><{$smarty.const._AM_ALUMNI_STAT_TOTAL_PROFILES}></span>
      <span class="xm-stat-row__value"><{$stats.total_profiles}></span>
      <a href="profiles.php" class="xm-stat-row__link"><{$smarty.const._AM_ALUMNI_PROFILE_LIST}></a>
    </div>

    <div class="xm-stat-row xm-stat-row--green">
      <span class="xm-stat-row__icon" aria-hidden="true">✅</span>
      <span class="xm-stat-row__label"><{$smarty.const._AM_ALUMNI_STAT_ACTIVE_PROFILES}></span>
      <span class="xm-stat-row__value"><{$stats.active_profiles}></span>
      <a href="profiles.php?status=active" class="xm-stat-row__link"><{$smarty.const._AM_ALUMNI_PROFILE_LIST}></a>
    </div>

    <div class="xm-stat-row xm-stat-row--orange">
      <span class="xm-stat-row__icon" aria-hidden="true">⏳</span>
      <span class="xm-stat-row__label"><{$smarty.const._AM_ALUMNI_STAT_PENDING_PROFILES}></span>
      <span class="xm-stat-row__value"><{$stats.pending_profiles}></span>
      <a href="profiles.php?status=pending" class="xm-stat-row__link"><{$smarty.const._AM_ALUMNI_PROFILE_LIST}></a>
    </div>

    <div class="xm-stat-row xm-stat-row--purple">
      <span class="xm-stat-row__icon" aria-hidden="true">⭐</span>
      <span class="xm-stat-row__label"><{$smarty.const._AM_ALUMNI_STAT_RECENT_PROFILES}></span>
      <span class="xm-stat-row__value"><{$stats.recent_profiles}></span>
    </div>

  </div>

  <{* ── Events ─────────────────────────────────────────── *}>
  <h2 class="xm-stats-group-label"><{$smarty.const._AM_ALUMNI_STAT_TOTAL_EVENTS}></h2>
  <div class="xm-stat-list">

    <div class="xm-stat-row xm-stat-row--blue">
      <span class="xm-stat-row__icon" aria-hidden="true">&#x1F4C5;</span>
      <span class="xm-stat-row__label"><{$smarty.const._AM_ALUMNI_STAT_TOTAL_EVENTS}></span>
      <span class="xm-stat-row__value"><{$stats.total_events}></span>
      <a href="events.php" class="xm-stat-row__link"><{$smarty.const._AM_ALUMNI_EVENT_LIST}></a>
    </div>

    <div class="xm-stat-row xm-stat-row--green">
      <span class="xm-stat-row__icon" aria-hidden="true">&#x1F51C;</span>
      <span class="xm-stat-row__label"><{$smarty.const._AM_ALUMNI_STAT_UPCOMING_EVENTS}></span>
      <span class="xm-stat-row__value"><{$stats.upcoming_events}></span>
    </div>

    <div class="xm-stat-row xm-stat-row--gray">
      <span class="xm-stat-row__icon" aria-hidden="true">&#x1F51A;</span>
      <span class="xm-stat-row__label"><{$smarty.const._AM_ALUMNI_STAT_PAST_EVENTS}></span>
      <span class="xm-stat-row__value"><{$stats.past_events}></span>
    </div>

    <div class="xm-stat-row xm-stat-row--teal">
      <span class="xm-stat-row__icon" aria-hidden="true">&#x1F3AB;</span>
      <span class="xm-stat-row__label"><{$smarty.const._AM_ALUMNI_STAT_TOTAL_RSVPS}></span>
      <span class="xm-stat-row__value"><{$stats.total_rsvps}></span>
      <a href="rsvps.php" class="xm-stat-row__link"><{$smarty.const._AM_ALUMNI_RSVP_LIST}></a>
    </div>

  </div>

  <{* ── Network ────────────────────────────────────────── *}>
  <h2 class="xm-stats-group-label"><{$smarty.const._AM_ALUMNI_STAT_TOTAL_CONNECTIONS}></h2>
  <div class="xm-stat-list">

    <div class="xm-stat-row xm-stat-row--blue">
      <span class="xm-stat-row__icon" aria-hidden="true">&#x1F91D;</span>
      <span class="xm-stat-row__label"><{$smarty.const._AM_ALUMNI_STAT_TOTAL_CONNECTIONS}></span>
      <span class="xm-stat-row__value"><{$stats.total_connections}></span>
      <a href="connections.php" class="xm-stat-row__link"><{$smarty.const._AM_ALUMNI_CONNECTIONS_LIST}></a>
    </div>

    <div class="xm-stat-row xm-stat-row--green">
      <span class="xm-stat-row__icon" aria-hidden="true">✔️</span>
      <span class="xm-stat-row__label"><{$smarty.const._AM_ALUMNI_STAT_ACTIVE_CONNECTIONS}></span>
      <span class="xm-stat-row__value"><{$stats.active_connections}></span>
    </div>

    <div class="xm-stat-row xm-stat-row--purple">
      <span class="xm-stat-row__icon" aria-hidden="true">&#x1F468;&#x200D;&#x1F3EB;</span>
      <span class="xm-stat-row__label"><{$smarty.const._AM_ALUMNI_STAT_TOTAL_MENTORSHIPS}></span>
      <span class="xm-stat-row__value"><{$stats.total_mentorships}></span>
      <a href="mentorship.php" class="xm-stat-row__link"><{$smarty.const._AM_ALUMNI_MENTORSHIPS_LIST}></a>
    </div>

    <div class="xm-stat-row xm-stat-row--indigo">
      <span class="xm-stat-row__icon" aria-hidden="true">&#x1F4DA;</span>
      <span class="xm-stat-row__label"><{$smarty.const._AM_ALUMNI_STAT_ACTIVE_MENTORSHIPS}></span>
      <span class="xm-stat-row__value"><{$stats.active_mentorships}></span>
    </div>

  </div>

  <{* ── Recent Profiles ───────────────────────────────── *}>
  <h2 class="xm-stats-group-label"><{$smarty.const._AM_ALUMNI_RECENT_ACTIVITY}> — <{$smarty.const._AM_ALUMNI_PROFILES}></h2>

  <{if $recent_profiles}>
  <div class="xm-table-wrap">
    <table class="xm-table xm-sortable">
      <thead>
        <tr>
          <th><{$smarty.const._AM_ALUMNI_TH_NAME}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_GRADUATION_YEAR}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_COMPANY}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_STATUS}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_DATE_ADDED}></th>
          <th class="sorter-false xm-text-right"><{$smarty.const._AM_ALUMNI_TH_ACTIONS}></th>
        </tr>
      </thead>
      <tbody>
        <{foreach from=$recent_profiles item=profile}>
        <tr>
          <td><{$profile.name}></td>
          <td><{$profile.graduation_year}></td>
          <td><{$profile.company}></td>
          <td><span class="xm-badge xm-badge--<{$profile.status}>"><{$profile.status_label}></span></td>
          <td><{$profile.created_formatted}></td>
          <td class="xm-actions xm-text-right">
            <a href="profiles.php?op=edit&profile_id=<{$profile.id}>" class="xm-btn xm-btn--default xm-btn--xs"><{$smarty.const._AM_ALUMNI_ACTION_EDIT}></a>
          </td>
        </tr>
        <{/foreach}>
      </tbody>
    </table>
  </div>
  <{else}>
  <p class="xm-empty"><{$smarty.const._AM_ALUMNI_INFO_NO_PROFILES}></p>
  <{/if}>

  <{* ── Recent Events ─────────────────────────────────── *}>
  <h2 class="xm-stats-group-label"><{$smarty.const._AM_ALUMNI_RECENT_ACTIVITY}> — <{$smarty.const._AM_ALUMNI_EVENTS}></h2>

  <{if $recent_events}>
  <div class="xm-table-wrap">
    <table class="xm-table xm-sortable">
      <thead>
        <tr>
          <th><{$smarty.const._AM_ALUMNI_TH_TITLE}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_EVENT_DATE}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_RSVPS}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_STATUS}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_CREATED_BY}></th>
          <th class="sorter-false xm-text-right"><{$smarty.const._AM_ALUMNI_TH_ACTIONS}></th>
        </tr>
      </thead>
      <tbody>
        <{foreach from=$recent_events item=event}>
        <tr>
          <td><{$event.title}></td>
          <td><{$event.start_date_formatted}></td>
          <td><{$event.rsvp_count}></td>
          <td><span class="xm-badge xm-badge--<{$event.status}>"><{$event.status_label}></span></td>
          <td><{$event.created_by_name}></td>
          <td class="xm-actions xm-text-right">
            <a href="events.php?op=edit&event_id=<{$event.id}>" class="xm-btn xm-btn--default xm-btn--xs"><{$smarty.const._AM_ALUMNI_ACTION_EDIT}></a>
          </td>
        </tr>
        <{/foreach}>
      </tbody>
    </table>
  </div>
  <{else}>
  <p class="xm-empty"><{$smarty.const._AM_ALUMNI_INFO_NO_EVENTS}></p>
  <{/if}>

</div>
