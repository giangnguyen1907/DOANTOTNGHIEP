<td colspan="4">
  <?php echo __('%%title%% - %%app_permission_code%% - %%user_updated_id%% - %%updated_at%%', array('%%title%%' => link_to($ps_app_permission->getTitle(), 'ps_app_permission_edit', $ps_app_permission), '%%app_permission_code%%' => $ps_app_permission->getAppPermissionCode(), '%%user_updated_id%%' => $ps_app_permission->getUserUpdatedId(), '%%updated_at%%' => false !== strtotime($ps_app_permission->getUpdatedAt()) ? format_date($ps_app_permission->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;'), 'messages') ?>
</td>
