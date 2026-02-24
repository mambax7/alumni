<{* Alumni Admin - Categories List *}>

<div class="alumni-admin">

  <div class="xm-table-wrap">
    <table class="xm-table xm-sortable">
      <thead>
        <tr>
          <th><{$smarty.const._AM_ALUMNI_TH_ID}></th>
          <th><{$smarty.const._AM_ALUMNI_CATEGORY_NAME}></th>
          <th><{$smarty.const._AM_ALUMNI_CATEGORY_DESCRIPTION}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_WEIGHT}></th>
          <th><{$smarty.const._AM_ALUMNI_TH_EVENTS}></th>
          <th class="sorter-false xm-text-right"><{$smarty.const._AM_ALUMNI_TH_ACTIONS}></th>
        </tr>
      </thead>
      <tbody>
        <{if $categories}>
          <{foreach from=$categories item=category}>
          <tr>
            <td><{$category.id}></td>
            <td><{$category.name}></td>
            <td><{$category.description}></td>
            <td><{$category.display_order}></td>
            <td><{$category.event_count}></td>
            <td class="xm-actions xm-text-right">
              <a href="categories.php?op=edit&amp;category_id=<{$category.id}>" class="xm-btn xm-btn--default xm-btn--xs"><{$smarty.const._AM_ALUMNI_ACTION_EDIT}></a>
              <a href="categories.php?op=delete&amp;category_id=<{$category.id}>" class="xm-btn xm-btn--danger xm-btn--xs" onclick="return confirm('<{$smarty.const._AM_ALUMNI_CONFIRM_DELETE_CATEGORY}>')"><{$smarty.const._AM_ALUMNI_ACTION_DELETE}></a>
            </td>
          </tr>
          <{/foreach}>
        <{else}>
          <tr><td colspan="6" class="xm-empty"><{$smarty.const._AM_ALUMNI_INFO_NO_CATEGORIES}></td></tr>
        <{/if}>
      </tbody>
    </table>
  </div>

</div>
