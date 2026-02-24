<{* Alumni Admin - Skills List *}>

<div class="alumni-admin">

  <div class="xm-table-wrap">
    <table class="xm-table xm-sortable">
      <thead>
        <tr>
          <th><{$smarty.const._AM_ALUMNI_TH_ID}></th>
          <th><{$smarty.const._AM_ALUMNI_SKILL_NAME}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_PROFILE_COUNT}></th>
          <th class="sorter-false xm-text-right"><{$smarty.const._AM_ALUMNI_TH_ACTIONS}></th>
        </tr>
      </thead>
      <tbody>
        <{if $skills}>
          <{foreach from=$skills item=skill}>
          <tr>
            <td><{$skill.id}></td>
            <td><{$skill.name}></td>
            <td><{$skill.profile_count}></td>
            <td class="xm-actions xm-text-right">
              <a href="skills.php?op=edit&amp;skill_id=<{$skill.id}>" class="xm-btn xm-btn--default xm-btn--xs"><{$smarty.const._AM_ALUMNI_ACTION_EDIT}></a>
              <a href="skills.php?op=delete&amp;skill_id=<{$skill.id}>" class="xm-btn xm-btn--danger xm-btn--xs" onclick="return confirm('<{$smarty.const._AM_ALUMNI_CONFIRM_DELETE}>')"><{$smarty.const._AM_ALUMNI_ACTION_DELETE}></a>
            </td>
          </tr>
          <{/foreach}>
        <{else}>
          <tr><td colspan="4" class="xm-empty"><{$smarty.const._AM_ALUMNI_INFO_NO_SKILLS}></td></tr>
        <{/if}>
      </tbody>
    </table>
  </div>

  <{if $pagenav}><div class="xm-text-center xm-mt"><{$pagenav nofilter}></div><{/if}>

</div>
