<{* Alumni Admin - Mentorships List *}>

<div class="alumni-admin">

  <{* ── Statistics ─────────────────────────────────────── *}>
  <div class="xm-stat-list">
    <div class="xm-stat-row xm-stat-row--blue">
      <span class="xm-stat-row__label"><{$smarty.const._AM_ALUMNI_TOTAL}></span>
      <span class="xm-stat-row__value"><{$stats.total}></span>
    </div>
    <div class="xm-stat-row xm-stat-row--green">
      <span class="xm-stat-row__label"><{$smarty.const._AM_ALUMNI_MENTORSHIP_ACTIVE}></span>
      <span class="xm-stat-row__value"><{$stats.active}></span>
    </div>
    <div class="xm-stat-row xm-stat-row--orange">
      <span class="xm-stat-row__label"><{$smarty.const._AM_ALUMNI_MENTORSHIP_PENDING}></span>
      <span class="xm-stat-row__value"><{$stats.pending}></span>
    </div>
    <div class="xm-stat-row xm-stat-row--teal">
      <span class="xm-stat-row__label"><{$smarty.const._AM_ALUMNI_MENTORSHIP_COMPLETED}></span>
      <span class="xm-stat-row__value"><{$stats.completed}></span>
    </div>
  </div>

  <{* ── Filter form ─────────────────────────────────────── *}>
  <form method="get" action="mentorship.php" class="xm-toolbar">
    <input type="hidden" name="op" value="list">

    <label><{$smarty.const._AM_ALUMNI_FILTER_STATUS}>:
      <select name="status" class="xm-form-control">
        <option value=""><{$smarty.const._AM_ALUMNI_FILTER_ALL}></option>
        <option value="active"<{if $filter_status === 'active'}> selected<{/if}>><{$smarty.const._MD_ALUMNI_MENTORSHIP_ACTIVE}></option>
        <option value="pending"<{if $filter_status === 'pending'}> selected<{/if}>><{$smarty.const._MD_ALUMNI_MENTORSHIP_PENDING}></option>
        <option value="completed"<{if $filter_status === 'completed'}> selected<{/if}>><{$smarty.const._MD_ALUMNI_MENTORSHIP_COMPLETED}></option>
        <option value="declined"<{if $filter_status === 'declined'}> selected<{/if}>><{$smarty.const._MD_ALUMNI_MENTORSHIP_DECLINED}></option>
      </select>
    </label>

    <button type="submit" class="xm-btn xm-btn--primary"><{$smarty.const._AM_ALUMNI_ACTION_FILTER}></button>
    <a href="mentorship.php?op=list" class="xm-btn xm-btn--secondary"><{$smarty.const._AM_ALUMNI_ACTION_RESET}></a>
  </form>

  <{* ── Mentorships table ──────────────────────────────── *}>
  <div class="xm-table-wrap">
    <table class="xm-table xm-sortable">
      <thead>
        <tr>
          <th><{$smarty.const._AM_ALUMNI_TH_ID}></th>
          <th><{$smarty.const._MD_ALUMNI_MENTOR}></th>
          <th><{$smarty.const._MD_ALUMNI_MENTEE}></th>
          <th><{$smarty.const._MD_ALUMNI_MENTORSHIP_AREA}></th>
          <th><{$smarty.const._AM_ALUMNI_FORM_STATUS}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_DATE_ADDED}></th>
          <th class="sorter-false xm-text-right"><{$smarty.const._AM_ALUMNI_TH_ACTIONS}></th>
        </tr>
      </thead>
      <tbody>
        <{if $mentorships}>
          <{foreach from=$mentorships item=m}>
          <tr>
            <td><{$m.id}></td>
            <td><{$m.mentor}></td>
            <td><{$m.mentee}></td>
            <td><{$m.area}></td>
            <td><span class="xm-badge xm-badge--<{$m.status}>"><{$m.status_label}></span></td>
            <td><{$m.created}></td>
            <td class="xm-actions xm-text-right">
              <a href="mentorship.php?op=view&amp;mentorship_id=<{$m.id}>" class="xm-btn xm-btn--default xm-btn--xs"><{$smarty.const._AM_ALUMNI_ACTION_VIEW}></a>
              <{if $m.status === 'pending'}>
              <a href="mentorship.php?op=activate&amp;mentorship_id=<{$m.id}>" class="xm-btn xm-btn--primary xm-btn--xs"><{$smarty.const._AM_ALUMNI_ACTION_ACTIVATE}></a>
              <{/if}>
              <{if $m.status === 'active'}>
              <a href="mentorship.php?op=complete&amp;mentorship_id=<{$m.id}>" class="xm-btn xm-btn--default xm-btn--xs"><{$smarty.const._AM_ALUMNI_MENTORSHIP_COMPLETE}></a>
              <{/if}>
              <a href="mentorship.php?op=delete&amp;mentorship_id=<{$m.id}>" class="xm-btn xm-btn--danger xm-btn--xs" onclick="return confirm('<{$smarty.const._AM_ALUMNI_CONFIRM_DELETE}>')"><{$smarty.const._AM_ALUMNI_ACTION_DELETE}></a>
            </td>
          </tr>
          <{/foreach}>
        <{else}>
          <tr><td colspan="7" class="xm-empty"><{$smarty.const._AM_ALUMNI_INFO_NO_MENTORSHIPS}></td></tr>
        <{/if}>
      </tbody>
    </table>
  </div>

  <{if $pagenav}><div class="xm-text-center xm-mt"><{$pagenav nofilter}></div><{/if}>

</div>
