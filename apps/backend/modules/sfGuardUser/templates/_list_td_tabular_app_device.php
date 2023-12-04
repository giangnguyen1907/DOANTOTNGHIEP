<td class="sf_admin_text sf_admin_list_td_username">
	<?php if ($sf_user->hasCredential(array(  0 => 'PS_SYSTEM_USER_EDIT',))): ?>
  <?php echo link_to($sf_guard_user->getUsername(), 'sf_guard_user_edit', $sf_guard_user) ?>
  <?php else:?>
  	<?php echo $sf_guard_user->getUsername();?>
  <?php endif;?>
</td>
<td class="sf_admin_text sf_admin_list_td_first_name">
  <?php echo $sf_guard_user->getFirstName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_last_name">
  <?php echo $sf_guard_user->getLastName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_user_type">
  <?php echo get_partial('sfGuardUser/user_type', array('type' => 'list', 'sf_guard_user' => $sf_guard_user)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_field_user_activated">
  <?php echo get_partial('sfGuardUser/field_user_activated2', array('type' => 'list', 'sf_guard_user' => $sf_guard_user)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $sf_guard_user->getUpdatedBy() ?>
  <br>
  <?php echo false !== strtotime($sf_guard_user->getUpdatedAt()) ? format_date($sf_guard_user->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
<td class="sf_admin_date sf_admin_list_td_last_login">
  <?php if ($sf_guard_user->getUserType() == PreSchool::USER_TYPE_TEACHER):?>
  	<?php echo false !== strtotime($sf_guard_user->getLastLogin()) ? format_date($sf_guard_user->getLastLogin(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
  <?php else:?>
  	<?php echo false !== strtotime($sf_guard_user->getTokenLastLogin()) ? format_date($sf_guard_user->getTokenLastLogin(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
  <?php endif;?>
</td>
<td class="sf_admin_text sf_admin_list_td_app_device_id"><strong>OS: </strong><?php echo $sf_guard_user->getOsname() ?><br />
	<strong>Os vesion: </strong><?php echo $sf_guard_user->getOsvesion() ?>
	<strong>App login: </strong><?php echo false !== strtotime($sf_guard_user->getTokenLastLogin()) ? format_date($sf_guard_user->getTokenLastLogin(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>  
</td>