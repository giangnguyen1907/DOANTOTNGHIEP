<td class="sf_admin_text sf_admin_list_td_view_img">
  <?php echo get_partial('psHrDepartments/view_img', array('type' => 'list', 'ps_member' => $ps_member)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_member_code">
  <?php echo $ps_member->getMemberCode() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_first_name">
  <?php echo $ps_member->getFirstName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_last_name">
  <?php echo $ps_member->getLastName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_birthday">
  <?php echo false !== strtotime($ps_member->getBirthday()) ? format_date($ps_member->getBirthday(), "dd-MM-yyyy") : '&nbsp;' ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_sex">
  <?php echo get_partial('psHrDepartments/sex', array('type' => 'list', 'ps_member' => $ps_member)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_mobile">
    <div><i class="fa fa-phone"></i> <?php echo $ps_member->getMobile() ?></div>
  <div><i class="fa fa-envelope-o"></i> <?php echo '<a href="mailto:'.$ps_member->getEmail().'">'.$ps_member->getEmail().'</a>';?></div>
</td>
<td class="sf_admin_text sf_admin_list_td_username">
<?php
	if ($ps_member->getUserId () > 0)
		echo link_to ( $ps_member->getUsername (), '@ps_hr_departments_edit?id=' . $ps_member->getUserId (), array (
				'data-original-title' => __('Edit user'),
				'rel' => 'tooltip',
				'target' => '_blank' ) );
	else {
		// Add new account
		echo link_to ( '<i class="fa fa-user-plus txt-color-green"></i> ', '@ps_user_departments_new?id=' . $ps_member->getId (), array (
				'data-original-title' => __('New user'),
				'rel' => 'tooltip',
				'target' => '_blank',
				'data-placement' => "bottom",
				'class' => 'btn btn-xs btn-default btn-add-td-action') );
	}
?>
  
</td>
<td class="sf_admin_text sf_admin_list_td_is_status">  
<?php
	if (isset ( $_status [$ps_member->getIsStatus ()] )) {
		$class = '';
		if ($ps_member->getIsStatus () == PreSchool::HR_STATUS_LEAVE) {
			$class = 'label-danger';
		} elseif ($ps_member->getIsStatus () == PreSchool::HR_STATUS_WORKING) {
			$class = 'label-success';
		}
		echo '<span class="label ' . $class . '">' . __ ( $_status [$ps_member->getIsStatus ()] ) . '</span>';
	}
?> 
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
<?php echo $ps_member->getUpdatedBy();?><br/>
<?php echo false !== strtotime($ps_member->getUpdatedAt()) ? format_date($ps_member->getUpdatedAt(), "HH:mm dd-MM-yyyy") : '&nbsp;'?>
</td>
