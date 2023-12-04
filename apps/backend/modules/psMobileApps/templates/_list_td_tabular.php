<td class="sf_admin_text sf_admin_list_td_username">
  <?php //if ($sf_user->hasCredential(array(  0 => 'PS_SYSTEM_USER_EDIT',))): ?>
  <?php //echo link_to($ps_mobile_apps->getUsername(), '@sf_guard_user_edit?id='.$ps_mobile_apps->getUserId()) ?>
  <?php //else:?>
  	<?php echo $ps_mobile_apps->getUsername();?>
  <?php //endif;?>
</td>
<td class="sf_admin_text sf_admin_list_td_full_name">
  <?php //if ($sf_user->hasCredential(array(  0 => 'PS_STUDENT_RELATIVE_EDIT',))): ?>
  <?php //echo link_to($ps_mobile_apps->getFullName(), '@ps_relatives_edit?id='. $ps_mobile_apps->getMemberId()) ?>
  <?php //else:?>
  	<?php echo $ps_mobile_apps->getFullName();?>
  <?php //endif;?>
</td>
<td class="sf_admin_text sf_admin_list_td_is_activated">
  <?php echo get_partial('psMobileApps/list_field_boolean', array('value' => $ps_mobile_apps->getIsActivated())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_user_type">
  <?php echo get_partial('psMobileApps/user_type', array('type' => 'list', 'ps_mobile_apps' => $ps_mobile_apps)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_device_id"><strong>OS:</strong> <?php echo $ps_mobile_apps->getOsname() ?  $ps_mobile_apps->getOsname() : $ps_mobile_apps->getUserOsname()?><br />
	<strong>Os vesion:</strong> <?php echo $ps_mobile_apps->getOsvesion() ? $ps_mobile_apps->getOsvesion() : $ps_mobile_apps->getUserOsvesion() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_active_created_at">
  <?php echo false !== strtotime($ps_mobile_apps->getActiveCreatedAt()) ? format_date($ps_mobile_apps->getActiveCreatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
