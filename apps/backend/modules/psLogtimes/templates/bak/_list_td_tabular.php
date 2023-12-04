<?php
$myclass_id = $ps_logtimes->getClassId ();

// Lay danh sach nguoi than duoc quyen don cua be
$list_relative = Doctrine::getTable ( 'RelativeStudent' )->findByStudentId ( $ps_logtimes->getStudentId (), $ps_logtimes->getPsCustomerId () );

?>

<td class="sf_admin_text sf_admin_list_td_view_img">
  <?php echo get_partial('psLogtimes/view_img', array('type' => 'list', 'ps_logtimes' => $ps_logtimes))?>
</td>
<td class="sf_admin_text sf_admin_list_td_student_name">
  <?php echo $ps_logtimes->getStudentName()?>
</td>
<?php if ($ps_logtimes->getId ()) :?>
<td class="sf_admin_text sf_admin_list_td_attendance">
  <?php echo get_partial('psLogtimes/td_attendance', array('type' => 'list', 'list_relative' => $list_relative, 'ps_logtimes' => $ps_logtimes,  'filter_value' => $filter_value))?>
</td>
<?php else :?>

<td class="sf_admin_text sf_admin_list_td_attendance">
  <?php echo get_partial('psLogtimes/td_attendance_2', array('type' => 'list', 'ps_logtimes' => $ps_logtimes,  'filter_value' => $filter_value))?>
</td>
<?php endif;?>

<td class="sf_admin_text sf_admin_list_td_login_infomation"
	style="width: 250px;">
  <?php echo get_partial('psLogtimes/td_login_infomation', array('type' => 'list', 'list_member' => $list_member, 'ps_logtimes' => $ps_logtimes,  'filter_value' => $filter_value, 'check_logtime' => $ps_logtimes->getId ()))?>
</td>
<td class="sf_admin_text sf_admin_list_td_logout_infomation"
	style="width: 250px;">
  <?php echo get_partial('psLogtimes/td_logout_infomation', array('type' => 'list', 'list_member' => $list_member,'ps_logtimes' => $ps_logtimes,  'filter_value' => $filter_value, 'check_logtime' => $check_logtime))?>
</td>
<td class="sf_admin_text sf_admin_list_td_service">
  <?php echo get_partial('psLogtimes/td_service', array('type' => 'list', 'ps_logtimes' => $ps_logtimes,'filter_value' => $filter_value, 'check_logtime' => $check_logtime))?>
</td>
<td class="sf_admin_text sf_admin_list_td_note">
  <?php echo get_partial('psLogtimes/td_note', array('type' => 'list', 'ps_logtimes' => $ps_logtimes,'filter_value' => $filter_value, 'check_logtime' => $check_logtime))?>
</td>

