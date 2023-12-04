<?php
// Trạng thái : 1 - Đi học; 0 - nghỉ có phép; 2 - Nghỉ không phép
$list_relative = Doctrine::getTable ( 'RelativeStudent' )->findByStudentId ( $ps_attendances->getStudentId (), $ps_attendances->getPsCustomerId () );
$list_service = Doctrine::getTable ( 'Service' )->getServicesDiaryByStudent ( $ps_attendances->getStudentId (), $ps_attendances->getClassId (), $tracked_at, $ps_attendances->getPsCustomerId (), 0 );
$disable = ($check_logtime) ? 'disabled' : '';
$disable = 'disabled';
if ($ps_attendances->getId () > 0) {
	$disable = '';
}
$selected = '';
?>

<td class="sf_admin_text sf_admin_list_td_view_img text-center">
  <?php echo get_partial('psAttendances/view_img', array('type' => 'list', 'ps_logtimes' => $ps_attendances)) ?>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_student_id">
  <?php echo $ps_attendances->getStudentName() ?>
  <br> <code><?php echo $ps_attendances->getStudentCode() ?></code>
</td>

<td class="sf_admin_text sf_admin_list_td_attendance text-center">
	<?php if ($ps_attendances->getLogValue() == 1 && $ps_attendances->getId() > 0){ 
		
	} else {
		foreach ( $ps_off_school as $off_school ) {
			if ($off_school->getIsActivated () != 1 && $off_school->getStudentId () == $ps_attendances->getStudentId ()) {
				$selected = 'selected';
			}
		}
	}?>
  <select class="select2" required name="student_logtime[<?php echo $ps_attendances->getStudentId() ?>][log_code]" onclick="javascript:setLogtimeBySymbol(<?php echo  $ps_attendances->getStudentId();?>,this);" id="select_<?php echo $ps_attendances->getStudentId() ?>_log_code">
		<option value="">-Chọn điểm danh-</option>
		<?php foreach($array_diemdanh as $key => $diemdanh){ 
			if($ps_attendances->getLogCode() == $key){ $selected = 'selected';}else{ $selected = '';}
			?>
			<option value="<?=$key?>" <?=$selected?>><?=$diemdanh?></option>
		<?php } ?>
	</select>

</td>


<td class="sf_admin_text sf_admin_list_td_logout_infomation">
	<div id="ic-loading-<?php echo $ps_attendances->getStudentId();?>"
		style="display: none;">
		<i class="fa fa-spinner fa-2x fa-spin text-success"
			style="padding: 3px;"></i><?php echo __('Loading...')?>
    </div> <!-- chỗ này để lòa lại kết quả sau khi save -->
	<ul class="list-inline"
		id="box-<?php echo $ps_attendances->getStudentId() ?>">
		<?php echo get_partial('psAttendances/row_li_atten_new', array('list_relative' => $list_relative, 'list_student' => $ps_attendances, 'check_logtime' => $check_logtime,'list_member' => $list_member,'ps_off_school' => $ps_off_school))?>
	</ul>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_service">
  <?php echo get_partial('psAttendances/td_service_of_baochau', array('type' => 'list', 'ps_attendances' => $ps_attendances,'tracked_at' => $tracked_at, 'list_service' => $list_service,'array_dichvu'=>$array_dichvu))?>
</td>

<td class="text-center"><small>
  <?php echo $ps_attendances->getCreatedBy();?><br>
  <?php echo $ps_attendances->getCreatedAt();?>
  </small>
	<div style="clear: both"></div> <small><?php echo ($ps_attendances->getUpdatedBy() != '') ? $ps_attendances->getUpdatedBy().'<br>'.$ps_attendances->getUpdatedAt() : '';?></small>
</td>

<td class="sf_admin_foreignkey sf_admin_list_td_action text-center">
	<div class="btn-group">
	  <?php if($ps_attendances->getId() > 0) {$disable = '';} else{$disable = 'disabled';} ?>
	  <button <?php echo $disable?> style="margin-right: 5px;"
			type="button"
			id="btn-attendance_<?php echo $ps_attendances->getStudentId(); ?>"
			class="btn btn-default btn-xs btn-attendance"
			data-value="<?php echo $ps_attendances->getStudentId(); ?>">
			<i class="fa-fw fa fa-floppy-o" aria-hidden="true" title="Lưu lại"></i>
		</button>
		<a class="btn btn-default btn-xs <?php echo $disable;?>"
			onclick="if (confirm('Bạn chắc chắn muốn xóa thông tin này?')) { var f = document.createElement('form'); f.style.display = 'none'; this.parentNode.appendChild(f); f.method = 'post'; f.action = this.href;var m = document.createElement('input'); m.setAttribute('type', 'hidden'); m.setAttribute('name', 'sf_method'); m.setAttribute('value', 'delete'); f.appendChild(m);var m = document.createElement('input'); m.setAttribute('type', 'hidden'); m.setAttribute('name', '_csrf_token'); m.setAttribute('value', 'c40dc565d417ba389379db939f39c16b'); f.appendChild(m);f.submit(); };return false;"
			href="<?php echo url_for('@ps_attendances_delete?id='.$ps_attendances->getId()) ;?>"><i
			class="fa-fw fa fa-times txt-color-red" title="Xóa"></i></a>
	</div>
</td>

<!-- <td class="text-center"><input type="checkbox" name="students[]" id="stdnt_<?=$ps_attendances->getStudentId () ?>" value="<?=$ps_attendances->getStudentId () ?>"></td> -->