<?php
// Lay danh sach nguoi than duoc quyen don cua studentId
$list_relative = Doctrine::getTable ( 'RelativeStudent' )->findByStudentId ( $ps_attendances->getStudentId (), $ps_attendances->getPsCustomerId () );

$list_service = Doctrine::getTable ( 'Service' )->getServicesDiaryByStudent ( $ps_attendances->getStudentId (), $ps_attendances->getClassId (), $tracked_at, $ps_attendances->getPsCustomerId () );

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
		id="logout-<?php echo $ps_attendances->getStudentId() ?>">
		<?php echo get_partial('psAttendances/row_li_logout', array('list_relative' => $list_relative, 'list_student' => $ps_attendances,'list_member' => $list_member))?>
	</ul>
</td>

<td class="sf_admin_foreignkey sf_admin_list_td_student_id">
  <?php echo ($ps_attendances->getUpdatedBy()) ? $ps_attendances->getUpdatedBy().'<br>'.$ps_attendances->getUpdatedAt() : '';?>
</td>

<td class="sf_admin_foreignkey sf_admin_list_td_action text-center">
	<button type="button" disabled class="btn btn-default btn-xs"
		data-value="<?php echo $ps_attendances->getStudentId(); ?>">
		<i class="fa-fw fa fa-floppy-o txt-color-blue" aria-hidden="true"
			title="<?php echo __('Save')?>"></i>
	</button> <input type="hidden" class="filter form-control"
	value="<?php echo $ps_attendances->getId() ?>" name="lt_id"
	id="lt_id_<?php echo $ps_attendances->getStudentId() ?>" />
</td>
