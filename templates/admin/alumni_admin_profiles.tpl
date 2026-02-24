<{* Alumni Admin - Profiles List *}>

<div class="alumni-admin">

  <{* ── Filter form ─────────────────────────────────────── *}>
  <form method="get" action="profiles.php" class="xm-toolbar">
    <input type="hidden" name="op" value="list">

    <label><{$smarty.const._AM_ALUMNI_FILTER_STATUS}>:
      <select name="status" class="xm-form-control">
        <option value=""><{$smarty.const._AM_ALUMNI_FILTER_ALL}></option>
        <option value="active"<{if $filter_status === 'active'}> selected<{/if}>><{$smarty.const._MD_ALUMNI_STATUS_ACTIVE}></option>
        <option value="pending"<{if $filter_status === 'pending'}> selected<{/if}>><{$smarty.const._MD_ALUMNI_STATUS_PENDING}></option>
        <option value="inactive"<{if $filter_status === 'inactive'}> selected<{/if}>><{$smarty.const._MD_ALUMNI_STATUS_INACTIVE}></option>
      </select>
    </label>

    <label><{$smarty.const._AM_ALUMNI_FILTER_FEATURED}>:
      <select name="featured" class="xm-form-control">
        <option value="-1"><{$smarty.const._AM_ALUMNI_FILTER_ALL}></option>
        <option value="1"<{if $filter_featured == 1}> selected<{/if}>><{$smarty.const._YES}></option>
        <option value="0"<{if $filter_featured == 0}> selected<{/if}>><{$smarty.const._NO}></option>
      </select>
    </label>

    <button type="submit" class="xm-btn xm-btn--primary"><{$smarty.const._AM_ALUMNI_ACTION_FILTER}></button>
    <a href="profiles.php?op=list" class="xm-btn xm-btn--secondary"><{$smarty.const._AM_ALUMNI_ACTION_RESET}></a>
  </form>

  <{* ── Profiles table ─────────────────────────────────── *}>
  <div class="xm-table-wrap">
    <table class="xm-table xm-sortable">
      <thead>
        <tr>
          <th><{$smarty.const._AM_ALUMNI_TH_ID}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_NAME}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_GRADUATION_YEAR}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_COMPANY}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_LOCATION}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_STATUS}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_FEATURED}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_DATE_ADDED}></th>
          <th class="sorter-false xm-text-right"><{$smarty.const._AM_ALUMNI_TH_ACTIONS}></th>
        </tr>
      </thead>
      <tbody>
        <{if $profiles}>
          <{foreach from=$profiles item=profile}>
          <tr>
            <td><{$profile.id}></td>
            <td><{$profile.name}></td>
            <td><{$profile.graduation_year}></td>
            <td><{$profile.company}></td>
            <td><{$profile.location}></td>
            <td><span class="xm-badge xm-badge--<{$profile.status}>"><{$profile.status_label}></span></td>
            <td><{if $profile.featured}><span class="xm-badge xm-badge--featured"><{$smarty.const._YES}></span><{else}><{$smarty.const._NO}><{/if}></td>
            <td><{$profile.created}></td>
            <td class="xm-actions xm-text-right">
              <a href="profiles.php?op=edit&amp;profile_id=<{$profile.id}>" class="xm-btn xm-btn--default xm-btn--xs"><{$smarty.const._AM_ALUMNI_ACTION_EDIT}></a>
              <{if $profile.status === 'pending'}>
              <a href="profiles.php?op=approve&amp;profile_id=<{$profile.id}>" class="xm-btn xm-btn--primary xm-btn--xs"><{$smarty.const._AM_ALUMNI_ACTION_APPROVE}></a>
              <{/if}>
              <a href="profiles.php?op=feature&amp;profile_id=<{$profile.id}>" class="xm-btn xm-btn--default xm-btn--xs"><{if $profile.featured}><{$smarty.const._AM_ALUMNI_ACTION_UNFEATURE}><{else}><{$smarty.const._AM_ALUMNI_ACTION_FEATURE}><{/if}></a>
              <a href="profiles.php?op=delete&amp;profile_id=<{$profile.id}>" class="xm-btn xm-btn--danger xm-btn--xs" onclick="return confirm('<{$smarty.const._AM_ALUMNI_CONFIRM_DELETE_PROFILE}>')"><{$smarty.const._AM_ALUMNI_ACTION_DELETE}></a>
            </td>
          </tr>
          <{/foreach}>
        <{else}>
          <tr><td colspan="9" class="xm-empty"><{$smarty.const._AM_ALUMNI_INFO_NO_PROFILES}></td></tr>
        <{/if}>
      </tbody>
    </table>
  </div>

  <{if $pagenav}><div class="xm-text-center xm-mt"><{$pagenav nofilter}></div><{/if}>

</div>
