<td class="sf_admin_text sf_admin_list_td_image text-center">
<?php
if ($ps_member->getImage () != '') {
	//$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_TEACHER . '/' . $ps_member->getSchoolCode () . '/' . $ps_member->getYearData () . '/' . $ps_member->getImage ();
	$path_file = sfConfig::get ( 'app_file_src' ).'/'.'PSM'.PreSchool::renderCode("%05s", $ps_member->getPsCustomerId()).'/member/'. $ps_member->getImage();
	echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
}
?>
</td>
<?php if ($sf_user->hasCredential([['PS_HR_HR_DETAIL','PS_HR_HR_EDIT','PS_HR_HR_ADD','PS_HR_HR_DELETE']])):?>
<td class="sf_admin_text sf_admin_list_td_member_code"><a
	data-backdrop="static" data-toggle="modal" data-target="#remoteModal"
	href="<?php echo url_for('@ps_member_detail?id='.$ps_member->getId())?>"><?php echo $ps_member->getMemberCode(); ?></a>
</td>
<td class="sf_admin_text sf_admin_list_td_first_name"><a
	data-backdrop="static" data-toggle="modal" data-target="#remoteModal"
	href="<?php echo url_for('@ps_member_detail?id='.$ps_member->getId())?>"><?php echo $ps_member->getFirstName(); ?></a>
</td>
<td class="sf_admin_text sf_admin_list_td_last_name"><a
	data-backdrop="static" data-toggle="modal" data-target="#remoteModal"
	href="<?php echo url_for('@ps_member_detail?id='.$ps_member->getId())?>"><?php echo $ps_member->getLastName(); ?></a>
</td>
<?php else:?>
<td class="sf_admin_text sf_admin_list_td_member_code">
   <?php echo $ps_member->getMemberCode();?>
</td>
<td class="sf_admin_text sf_admin_list_td_first_name">
  <?php echo $ps_member->getFirstName();?>
</td>
<td class="sf_admin_text sf_admin_list_td_last_name">
  <?php echo $ps_member->getLastName();?>
</td>
<?php endif;?>

<td class="sf_admin_text sf_admin_list_td_birthday">
  <?php echo false !== strtotime($ps_member->getBirthday()) ? format_date($ps_member->getBirthday(), "dd/MM/yyyy") : '&nbsp;' ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_sex">
  <?php echo get_partial('psMember/sex', array('type' => 'list', 'ps_member' => $ps_member)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_mobile">
	<div>
		<i class="fa fa-phone"></i> <?php echo $ps_member->getMobile() ?></div>
	<div>
		<i class="fa fa-envelope-o"></i> <?php echo '<a href="mailto:'.$ps_member->getEmail().'">'.$ps_member->getEmail().'</a>';?></div>
</td>

<td class="sf_admin_text sf_admin_list_td_username">
  <?php
		if ($ps_member->getUserId () > 0)
			echo link_to ( $ps_member->getUsername (), '@sf_guard_user_edit?id=' . $ps_member->getUserId (), array (
					'data-original-title' => 'Edit user member',
					'rel' => 'tooltip',
					'target' => '_blank' ) );
		else {
			// Add new account
			echo link_to ( '<i class="fa fa-user-plus txt-color-green"></i> ', '@sf_guard_user_new', array (
					'data-original-title' => 'New user member',
					'rel' => 'tooltip',
					'target' => '_blank',
					'data-placement' => "bottom",
					'class' => 'btn btn-xs btn-default btn-add-td-action',
					'query_string' => 'utype=T&mid=' . $ps_member->getId () ) );
		}
		?>
</td>
<td class="sf_admin_text sf_admin_list_td_department_function">
  <?php echo $ps_member->getFunction() ?>
  <br> <small class='text-muted'><i><?php echo $ps_member->getDepartment() ?></i></small></span>
</td>
<td class="sf_admin_text sf_admin_list_td_rank">
  <?php echo (isset($rank[$ps_member->getRank()])) ? __($rank[$ps_member->getRank()]) : ''; ?>
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
<?php echo $ps_member->getUpdatedBy() ?><br />
  <?php echo false !== strtotime($ps_member->getUpdatedAt()) ? format_date($ps_member->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;'?>
</td>