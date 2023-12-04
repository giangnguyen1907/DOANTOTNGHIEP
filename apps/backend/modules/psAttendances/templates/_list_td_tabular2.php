<?php
// Trạng thái : 1 - Đi học; 0 - nghỉ có phép; 2 - Nghỉ không phép
$list_relative = Doctrine::getTable ( 'RelativeStudent' )->findByStudentId ( $ps_attendances->getStudentId (), $ps_attendances->getPsCustomerId () );

$list_service = Doctrine::getTable ( 'Service' )->getServicesDiaryByStudent ( $ps_attendances->getStudentId (), $ps_attendances->getClassId (), $tracked_at, $ps_attendances->getPsCustomerId () );
// $list_service = Doctrine::getTable ( 'Service' )->getServicesForStudentDiary ( $ps_attendances->getStudentId(),$ps_attendances->getClassId(), $ps_attendances->getPsCustomerId(), $filter_value['tracked_at'] );
?>
<td class="sf_admin_text sf_admin_list_td_view_img text-center">
  <?php echo get_partial('psAttendances/view_img', array('type' => 'list', 'ps_logtimes' => $ps_attendances)) ?>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_student_id">
  <?php echo $ps_attendances->getStudentName() ?>
  <br> <code><?php echo $ps_attendances->getStudentCode() ?></code>
</td>

<td class="sf_admin_text sf_admin_list_td_logout_infomation">
	<div id="ic-loading-<?php echo $ps_attendances->getStudentId();?>"
		style="display: none;">
		<i class="fa fa-spinner fa-2x fa-spin text-success"
			style="padding: 3px;"></i><?php echo __('Loading...')?>
    </div> <!-- chỗ này để lòa lại kết quả sau khi save -->
	<ul class="list-inline"
		id="box-<?php echo $ps_attendances->getStudentId() ?>">
		<?php echo get_partial('psAttendances/row_li_atten', array('list_relative' => $list_relative, 'list_student' => $ps_attendances,  'filter_value' => $filter_value, 'check_logtime' => $check_logtime,'list_member' => $list_member,'ps_off_school' => $ps_off_school))?>
	</ul>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_service">
  <?php echo get_partial('psAttendances/td_service_of_student', array('type' => 'list', 'ps_attendances' => $ps_attendances,'filter_value' => $filter_value, 'check_logtime' => $check_logtime, 'list_service' => $list_service))?>
</td>

<td class="sf_admin_text sf_admin_list_td_attendance text-center"><label
	class="radio no-margin no-padding "> <input class="radiobox style-0"
		type="radio"
		id="radiobox-<?php echo $ps_attendances->getStudentId();?>"
		name="student_logtime_<?php echo $ps_attendances->getStudentId();?>"
		<?php if ($ps_attendances->getLogValue() == 1 && $ps_attendances->getId() > 0) :?>
		checked="checked" <?php endif;?> value="1"
		onclick="javascript:setLogtime(<?php echo  $ps_attendances->getStudentId();?>,this);">
		<span></span>
</label></td>

<td class="sf_admin_text sf_admin_list_td_attendance text-center"><label
	class="radio no-margin no-padding "> <input class="radiobox style-0"
		type="radio"
		id="radiobox-<?php echo $ps_attendances->getStudentId();?>"
		name="student_logtime_<?php echo $ps_attendances->getStudentId();?>"
		<?php if ($ps_attendances->getLogValue() == 2 && $ps_attendances->getId() > 0){ ?>
		checked="checked"
		<?php

} else {
			foreach ( $ps_off_school as $off_school ) {
				if ($off_school->getIsActivated () != 1 && $off_school->getStudentId () == $ps_attendances->getStudentId ()) {
					?>
		checked="checked" <?php }} }?> value="2"
		onclick="javascript:setLogtime(<?php echo  $ps_attendances->getStudentId();?>,this);">
		<span></span>
</label></td>

<td class="sf_admin_text sf_admin_list_td_attendance text-center"><label
	class="radio no-margin no-padding "> <input class="radiobox style-0"
		type="radio"
		id="radiobox-<?php echo $ps_attendances->getStudentId();?>"
		name="student_logtime_<?php echo $ps_attendances->getStudentId();?>"
		<?php if ($ps_attendances->getLogValue() != '' && $ps_attendances->getLogValue() == 0 && $ps_attendances->getId() > 0){ ?>
		checked="checked"
		<?php

} else {
			foreach ( $ps_off_school as $off_school ) {
				if ($off_school->getIsActivated () == 1 && $off_school->getStudentId () == $ps_attendances->getStudentId ()) {
					?>
		checked="checked" <?php }} }?> value="0"
		onclick="javascript:setLogtime(<?php echo  $ps_attendances->getStudentId();?>,this);">
		<span></span>
</label></td>

<td class="sf_admin_foreignkey sf_admin_list_td_action">

	<button type="button"
		class="btn btn-default btn-success btn-sm btn-attendance"
		data-value="<?php echo $ps_attendances->getStudentId(); ?>">
		<i class="fa-fw fa fa-floppy-o" aria-hidden="true" title="Lưu lại"></i>
	</button>
  <?php if($ps_attendances->getId() > 0){$disable = '';}else{$disable = 'disabled';} ?>
  <a class="btn btn-default btn-danger btn-sm <?php echo $disable;?>"
	onclick="if (confirm('Bạn chắc chắn muốn xóa thông tin này?')) { var f = document.createElement('form'); f.style.display = 'none'; this.parentNode.appendChild(f); f.method = 'post'; f.action = this.href;var m = document.createElement('input'); m.setAttribute('type', 'hidden'); m.setAttribute('name', 'sf_method'); m.setAttribute('value', 'delete'); f.appendChild(m);var m = document.createElement('input'); m.setAttribute('type', 'hidden'); m.setAttribute('name', '_csrf_token'); m.setAttribute('value', 'c40dc565d417ba389379db939f39c16b'); f.appendChild(m);f.submit(); };return false;"
	href="<?php echo url_for('@ps_attendances_delete?id='.$ps_attendances->getId()) ;?>"><i
		class="fa-fw fa fa-trash-o txt-color-while" title="Xóa"></i></a>

</td>