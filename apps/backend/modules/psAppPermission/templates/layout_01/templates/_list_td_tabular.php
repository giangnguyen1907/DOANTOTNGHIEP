<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo link_to($ps_app_permission->getTitle(), 'ps_app_permission_edit', $ps_app_permission) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_app_permission_code">
  <?php echo $ps_app_permission->getAppPermissionCode() ?>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_ps_app_id">
  <?php echo $ps_app_permission->getPsAppId() ?>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_updated_by">
  <?php echo $ps_app_permission->getUserUpdatedId() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($ps_app_permission->getUpdatedAt()) ? format_date($ps_app_permission->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
