<style>
.input-group .form-control {
	z-index: 1 !important
}
</style>
<td class="sf_admin_foreignkey sf_admin_list_td_member_id">
  <?php echo $ps_timesheet->getMemberName() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_io text-center">

  <?php $logout_at = date('H:i', strtotime("now")); ?>
  
  <div class="input-group" style="width: 80%">
		<span class="input-group-addon"><i class="icon-append fa fa-clock-o"></i></span>
		<input id="input_date_<?php echo $ps_timesheet->getMemberId() ?>"
			name="student_logtime[<?php echo $ps_timesheet->getMemberId() ?>][logout_at]"
			class="time_picker form-control input-sm_<?php echo $ps_timesheet->getMemberId() ?>_logout"
			maxlength="5" value="<?php echo $logout_at ?>">
	</div>

</td>
<td class="text-center"><span class="btn-album-item"
	id="box-io-<?php echo $ps_timesheet->getMemberId() ?>"
	data-value="<?php echo $ps_timesheet->getMemberId() ?>">
  <?php echo get_partial('psTimesheet/index_timesheet', array('value' => $ps_timesheet)) ?>
  </span></td>

<td class="sf_admin_date sf_admin_list_td_time_at text-center"><span
	id="box-io-time-<?php echo $ps_timesheet->getMemberId() ?>">
    <?php echo get_partial('psTimesheet/list_field_time', array('ps_timesheet' => $ps_timesheet)) ?>
 </span></td>

<td class="sf_admin_date sf_admin_list_td_timesheet_at text-center">
  <?php echo false !== strtotime($ps_timesheet->getTimesheetAt()) ? format_date($ps_timesheet->getTimesheetAt(), "dd/MM/yyyy") : '&nbsp;' ?>
</td>
<script>
$(document).ready(function() {
	$('.time_picker').timepicker({
		timeFormat : 'HH:mm',
		showMeridian : false,
		defaultTime : null
	});
});
</script>