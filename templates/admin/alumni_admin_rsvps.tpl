<{* Alumni Admin - RSVPs List *}>

<div class="alumni-admin">

  <{* ── Filter form ─────────────────────────────────────── *}>
  <form method="get" action="rsvps.php" class="xm-toolbar">
    <input type="hidden" name="op" value="list">

    <label><{$smarty.const._AM_ALUMNI_TH_EVENT_NAME}>:
      <select name="event_id" class="xm-form-control">
        <option value="0"><{$smarty.const._AM_ALUMNI_FILTER_ALL}></option>
        <{foreach from=$events item=evt}>
        <option value="<{$evt.id}>"<{if $filter_event_id == $evt.id}> selected<{/if}>><{$evt.title}></option>
        <{/foreach}>
      </select>
    </label>

    <label><{$smarty.const._AM_ALUMNI_FORM_STATUS}>:
      <select name="rsvp_status" class="xm-form-control">
        <option value=""><{$smarty.const._AM_ALUMNI_FILTER_ALL}></option>
        <option value="attending"<{if $filter_rsvp_status === 'attending'}> selected<{/if}>><{$smarty.const._MD_ALUMNI_RSVP_ATTENDING}></option>
        <option value="not_attending"<{if $filter_rsvp_status === 'not_attending'}> selected<{/if}>><{$smarty.const._MD_ALUMNI_RSVP_NOT_ATTENDING}></option>
        <option value="maybe"<{if $filter_rsvp_status === 'maybe'}> selected<{/if}>><{$smarty.const._MD_ALUMNI_RSVP_MAYBE}></option>
      </select>
    </label>

    <button type="submit" class="xm-btn xm-btn--primary"><{$smarty.const._AM_ALUMNI_ACTION_FILTER}></button>
    <a href="rsvps.php?op=list" class="xm-btn xm-btn--secondary"><{$smarty.const._AM_ALUMNI_ACTION_RESET}></a>
    <{if $filter_event_id > 0}>
    <a href="rsvps.php?op=export&amp;event_id=<{$filter_event_id}>" class="xm-btn xm-btn--primary"><{$smarty.const._AM_ALUMNI_ACTION_EXPORT}></a>
    <{/if}>
  </form>

  <{* ── Event summary ──────────────────────────────────── *}>
  <{if $event_summary}>
  <div class="xm-notice xm-notice--info">
    <strong><{$event_summary.title}></strong><br>
    <{$smarty.const._AM_ALUMNI_RSVP_SUMMARY}>: <{$event_summary.attending_count}> <{$smarty.const._AM_ALUMNI_RSVP_ATTENDING}>
  </div>
  <{/if}>

  <{* ── RSVPs table ────────────────────────────────────── *}>
  <div class="xm-table-wrap">
    <table class="xm-table xm-sortable">
      <thead>
        <tr>
          <th><{$smarty.const._AM_ALUMNI_TH_ID}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_EVENT_NAME}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_NAME}></th>
          <th><{$smarty.const._AM_ALUMNI_FORM_STATUS}></th>
          <th><{$smarty.const._MD_ALUMNI_GUESTS}></th>
          <th><{$smarty.const._MD_ALUMNI_NOTES}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_DATE_ADDED}></th>
          <th class="sorter-false xm-text-right"><{$smarty.const._AM_ALUMNI_TH_ACTIONS}></th>
        </tr>
      </thead>
      <tbody>
        <{if $rsvps}>
          <{foreach from=$rsvps item=rsvp}>
          <tr>
            <td><{$rsvp.id}></td>
            <td><{$rsvp.event_title}></td>
            <td><{$rsvp.username}></td>
            <td><span class="xm-badge xm-badge--<{$rsvp.status}>"><{$rsvp.status_label}></span></td>
            <td><{$rsvp.guests}></td>
            <td><{$rsvp.notes_short}></td>
            <td><{$rsvp.created}></td>
            <td class="xm-actions xm-text-right">
              <a href="rsvps.php?op=view&amp;rsvp_id=<{$rsvp.id}>" class="xm-btn xm-btn--default xm-btn--xs"><{$smarty.const._AM_ALUMNI_ACTION_VIEW}></a>
              <a href="rsvps.php?op=delete&amp;rsvp_id=<{$rsvp.id}>" class="xm-btn xm-btn--danger xm-btn--xs" onclick="return confirm('<{$smarty.const._AM_ALUMNI_CONFIRM_DELETE}>')"><{$smarty.const._AM_ALUMNI_ACTION_DELETE}></a>
            </td>
          </tr>
          <{/foreach}>
        <{else}>
          <tr><td colspan="8" class="xm-empty"><{$smarty.const._AM_ALUMNI_INFO_NO_RSVPS}></td></tr>
        <{/if}>
      </tbody>
    </table>
  </div>

  <{if $pagenav}><div class="xm-text-center xm-mt"><{$pagenav nofilter}></div><{/if}>

</div>
