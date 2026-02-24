<{* Alumni Admin - Connections List *}>

<div class="alumni-admin">

  <{* ── Statistics ─────────────────────────────────────── *}>
  <div class="xm-stat-list">
    <div class="xm-stat-row xm-stat-row--blue">
      <span class="xm-stat-row__label"><{$smarty.const._AM_ALUMNI_TOTAL}></span>
      <span class="xm-stat-row__value"><{$stats.total}></span>
    </div>
    <div class="xm-stat-row xm-stat-row--green">
      <span class="xm-stat-row__label"><{$smarty.const._AM_ALUMNI_CONNECTION_ACTIVE}></span>
      <span class="xm-stat-row__value"><{$stats.accepted}></span>
    </div>
    <div class="xm-stat-row xm-stat-row--orange">
      <span class="xm-stat-row__label"><{$smarty.const._AM_ALUMNI_CONNECTION_PENDING}></span>
      <span class="xm-stat-row__value"><{$stats.pending}></span>
    </div>
    <div class="xm-stat-row xm-stat-row--gray">
      <span class="xm-stat-row__label"><{$smarty.const._MD_ALUMNI_CONNECTION_DECLINED}></span>
      <span class="xm-stat-row__value"><{$stats.declined}></span>
    </div>
  </div>

  <{* ── Filter form ─────────────────────────────────────── *}>
  <form method="get" action="connections.php" class="xm-toolbar">
    <input type="hidden" name="op" value="list">

    <label><{$smarty.const._AM_ALUMNI_FILTER_STATUS}>:
      <select name="status" class="xm-form-control">
        <option value=""><{$smarty.const._AM_ALUMNI_FILTER_ALL}></option>
        <option value="accepted"<{if $filter_status === 'accepted'}> selected<{/if}>><{$smarty.const._MD_ALUMNI_CONNECTION_ACCEPTED}></option>
        <option value="pending"<{if $filter_status === 'pending'}> selected<{/if}>><{$smarty.const._MD_ALUMNI_CONNECTION_PENDING}></option>
        <option value="declined"<{if $filter_status === 'declined'}> selected<{/if}>><{$smarty.const._MD_ALUMNI_CONNECTION_DECLINED}></option>
        <option value="blocked"<{if $filter_status === 'blocked'}> selected<{/if}>><{$smarty.const._MD_ALUMNI_CONNECTION_BLOCKED}></option>
      </select>
    </label>

    <button type="submit" class="xm-btn xm-btn--primary"><{$smarty.const._AM_ALUMNI_ACTION_FILTER}></button>
    <a href="connections.php?op=list" class="xm-btn xm-btn--secondary"><{$smarty.const._AM_ALUMNI_ACTION_RESET}></a>
  </form>

  <{* ── Connections table ──────────────────────────────── *}>
  <div class="xm-table-wrap">
    <table class="xm-table xm-sortable">
      <thead>
        <tr>
          <th><{$smarty.const._AM_ALUMNI_TH_ID}></th>
          <th><{$smarty.const._MD_ALUMNI_REQUESTER}></th>
          <th><{$smarty.const._MD_ALUMNI_RECIPIENT}></th>
          <th><{$smarty.const._AM_ALUMNI_FORM_STATUS}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_DATE_ADDED}></th>
          <th class="sorter-false xm-text-right"><{$smarty.const._AM_ALUMNI_TH_ACTIONS}></th>
        </tr>
      </thead>
      <tbody>
        <{if $connections}>
          <{foreach from=$connections item=conn}>
          <tr>
            <td><{$conn.id}></td>
            <td><{$conn.requester}></td>
            <td><{$conn.recipient}></td>
            <td><span class="xm-badge xm-badge--<{$conn.status}>"><{$conn.status_label}></span></td>
            <td><{$conn.created}></td>
            <td class="xm-actions xm-text-right">
              <a href="connections.php?op=view&amp;connection_id=<{$conn.id}>" class="xm-btn xm-btn--default xm-btn--xs"><{$smarty.const._AM_ALUMNI_ACTION_VIEW}></a>
              <a href="connections.php?op=delete&amp;connection_id=<{$conn.id}>" class="xm-btn xm-btn--danger xm-btn--xs" onclick="return confirm('<{$smarty.const._AM_ALUMNI_CONFIRM_DELETE}>')"><{$smarty.const._AM_ALUMNI_ACTION_DELETE}></a>
            </td>
          </tr>
          <{/foreach}>
        <{else}>
          <tr><td colspan="6" class="xm-empty"><{$smarty.const._AM_ALUMNI_INFO_NO_CONNECTIONS}></td></tr>
        <{/if}>
      </tbody>
    </table>
  </div>

  <{if $pagenav}><div class="xm-text-center xm-mt"><{$pagenav nofilter}></div><{/if}>

</div>
