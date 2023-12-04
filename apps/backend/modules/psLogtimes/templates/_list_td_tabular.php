<?php
// Lay danh sach nguoi than duoc quyen don cua studentId
$list_relative = Doctrine::getTable ( 'RelativeStudent' )->findByStudentId ( $ps_logtimes->getStudentId (), $ps_logtimes->getPsCustomerId () );

$list_service = Doctrine::getTable ( 'Service' )->getServicesDiaryByStudent ( $ps_logtimes->getStudentId (), $ps_logtimes->getClassId (), $filter_value ['tracked_at'], $ps_logtimes->getPsCustomerId () );
// $list_service = Doctrine::getTable ( 'Service' )->getServicesForStudentDiary ( $ps_logtimes->getStudentId(),$ps_logtimes->getClassId(), $ps_logtimes->getPsCustomerId(), $filter_value['tracked_at'] );
?>

<td class="sf_admin_text sf_admin_list_td_view_img">
  <?php echo get_partial('psLogtimes/view_img', array('type' => 'list', 'ps_logtimes' => $ps_logtimes))?>
</td>

<td class="sf_admin_text sf_admin_list_td_student_name">
  <?php echo $ps_logtimes->getStudentName()?>
</td>

<td class="sf_admin_text sf_admin_list_td_attendance text-center">
  <?php echo get_partial('psLogtimes/td_attendance', array('type' => 'list', 'ps_logtimes' => $ps_logtimes,  'filter_value' => $filter_value, 'check_logtime' => $check_logtime))?>
</td>

<td class="sf_admin_text sf_admin_list_td_login_infomation">
  <?php echo get_partial('psLogtimes/td_login_infomation', array('type' => 'list', 'list_relative' => $list_relative, 'list_member' => $list_member, 'ps_logtimes' => $ps_logtimes,  'filter_value' => $filter_value, 'check_logtime' => $check_logtime, 'ps_constant_option' => $ps_constant_option))?>
</td>

<td
	class="sf_admin_text sf_admin_list_td_logout_infomation <?php if ($ps_logtimes->getId() && $ps_logtimes->getLogoutRelativeId() <= 0) echo 'bg-color-orange';?>">
  <?php echo get_partial('psLogtimes/td_logout_infomation', array('type' => 'list','list_relative' => $list_relative, 'list_member' => $list_member,'ps_logtimes' => $ps_logtimes,  'filter_value' => $filter_value, 'check_logtime' => $check_logtime, 'ps_constant_option' => $ps_constant_option))?>
</td>

<td class="sf_admin_text sf_admin_list_td_service">
  <?php echo get_partial('psLogtimes/td_service_of_student', array('type' => 'list', 'ps_logtimes' => $ps_logtimes,'filter_value' => $filter_value, 'check_logtime' => $check_logtime, 'list_service' => $list_service))?>
</td>

<td class="sf_admin_text sf_admin_list_td_note">
  <?php echo get_partial('psLogtimes/td_note', array('type' => 'list', 'ps_logtimes' => $ps_logtimes,'filter_value' => $filter_value, 'check_logtime' => $check_logtime,))?>
</td>

