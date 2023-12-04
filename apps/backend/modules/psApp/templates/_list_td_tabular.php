<?php if ($ps_app->getPsAppRoot() == null) {?>

<td class="sf_admin_foreignkey sf_admin_list_td_ps_app_root"
	style="border-right: none; font-weight: bold;">
  <?php echo $ps_app->get('ps_app_root_title') ?>
</td>
<td class="sf_admin_text sf_admin_list_td_title"
	style="border-left: none;">&nbsp;</td>

<?php } else {?>
<td class="sf_admin_foreignkey sf_admin_list_td_ps_app_root"
	style="border-bottom: none; border-top: none;">&nbsp;</td>
<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo '|--'.link_to($ps_app->getTitle(), 'ps_app_edit', $ps_app) ?>
</td>
<?php }?>

<td class="sf_admin_text sf_admin_list_td_app_code">
  <?php echo $ps_app->getAppCode()?>
</td>
<td
	class="sf_admin_text sf_admin_list_td_is_activated sf_admin_list_td_is_active">
  <?php echo get_partial('psApp/list_field_boolean', array('value' => $ps_app->getIsActivated())) ?>
</td>

<td class="sf_admin_text sf_admin_list_td_iorder">
  <?php echo get_partial('psApp/iorder', array('type' => 'list', 'ps_app' => $ps_app)) ?>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_updated_by">
  <?php echo $ps_app->getUpdatedBy() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($ps_app->getUpdatedAt()) ? format_date($ps_app->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
