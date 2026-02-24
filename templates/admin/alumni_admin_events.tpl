<{* Alumni Admin - Events List *}>

<div class="alumni-admin">

  <{* ── Filter form ─────────────────────────────────────── *}>
  <form method="get" action="events.php" class="xm-toolbar">
    <input type="hidden" name="op" value="list">

    <label><{$smarty.const._AM_ALUMNI_FILTER_STATUS}>:
      <select name="status" class="xm-form-control">
        <option value=""><{$smarty.const._AM_ALUMNI_FILTER_ALL}></option>
        <option value="published"<{if $filter_status === 'published'}> selected<{/if}>><{$smarty.const._MD_ALUMNI_STATUS_PUBLISHED}></option>
        <option value="draft"<{if $filter_status === 'draft'}> selected<{/if}>><{$smarty.const._MD_ALUMNI_STATUS_DRAFT}></option>
        <option value="cancelled"<{if $filter_status === 'cancelled'}> selected<{/if}>><{$smarty.const._MD_ALUMNI_STATUS_CANCELLED}></option>
      </select>
    </label>

    <label><{$smarty.const._AM_ALUMNI_FILTER_CATEGORY}>:
      <select name="category_id" class="xm-form-control">
        <option value="0"><{$smarty.const._AM_ALUMNI_FILTER_ALL}></option>
        <{foreach from=$categories item=cat}>
        <option value="<{$cat.id}>"<{if $filter_category_id == $cat.id}> selected<{/if}>><{$cat.name}></option>
        <{/foreach}>
      </select>
    </label>

    <button type="submit" class="xm-btn xm-btn--primary"><{$smarty.const._AM_ALUMNI_ACTION_FILTER}></button>
    <a href="events.php?op=list" class="xm-btn xm-btn--secondary"><{$smarty.const._AM_ALUMNI_ACTION_RESET}></a>
  </form>

  <{* ── Events table ───────────────────────────────────── *}>
  <div class="xm-table-wrap">
    <table class="xm-table xm-sortable">
      <thead>
        <tr>
          <th><{$smarty.const._AM_ALUMNI_TH_ID}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_TITLE}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_CATEGORY}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_EVENT_DATE}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_LOCATION}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_RSVPS}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_STATUS}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_FEATURED}></th>
          <th class="sorter-false xm-text-right"><{$smarty.const._AM_ALUMNI_TH_ACTIONS}></th>
        </tr>
      </thead>
      <tbody>
        <{if $events}>
          <{foreach from=$events item=event}>
          <tr>
            <td><{$event.id}></td>
            <td><{$event.title}></td>
            <td><{$event.category_name}></td>
            <td><{$event.start_date}></td>
            <td><{$event.location}></td>
            <td><{$event.rsvp_count}></td>
            <td><span class="xm-badge xm-badge--<{$event.status}>"><{$event.status_label}></span></td>
            <td><{if $event.featured}><span class="xm-badge xm-badge--featured"><{$smarty.const._YES}></span><{else}><{$smarty.const._NO}><{/if}></td>
            <td class="xm-actions xm-text-right">
              <a href="events.php?op=edit&amp;event_id=<{$event.id}>" class="xm-btn xm-btn--default xm-btn--xs"><{$smarty.const._AM_ALUMNI_ACTION_EDIT}></a>
              <a href="events.php?op=feature&amp;event_id=<{$event.id}>" class="xm-btn xm-btn--default xm-btn--xs"><{if $event.featured}><{$smarty.const._AM_ALUMNI_ACTION_UNFEATURE}><{else}><{$smarty.const._AM_ALUMNI_ACTION_FEATURE}><{/if}></a>
              <a href="events.php?op=duplicate&amp;event_id=<{$event.id}>" class="xm-btn xm-btn--secondary xm-btn--xs"><{$smarty.const._AM_ALUMNI_ACTION_DUPLICATE}></a>
              <a href="events.php?op=delete&amp;event_id=<{$event.id}>" class="xm-btn xm-btn--danger xm-btn--xs" onclick="return confirm('<{$smarty.const._AM_ALUMNI_CONFIRM_DELETE_EVENT}>')"><{$smarty.const._AM_ALUMNI_ACTION_DELETE}></a>
            </td>
          </tr>
          <{/foreach}>
        <{else}>
          <tr><td colspan="9" class="xm-empty"><{$smarty.const._AM_ALUMNI_INFO_NO_EVENTS}></td></tr>
        <{/if}>
      </tbody>
    </table>
  </div>

  <{if $pagenav}><div class="xm-text-center xm-mt"><{$pagenav nofilter}></div><{/if}>

</div>
