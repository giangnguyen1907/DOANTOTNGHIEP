<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo $ps_working_time->getTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_customer_title">
  <?php echo $ps_working_time->getCustomerTitle() ?>
  <p>
		<small class="text-muted"><i> <?php echo $ps_working_time->getWorkplaceTitle() ?><i></i></i></small>
	</p>
</td>
<td class="sf_admin_text sf_admin_list_td_working_time">
  <?php echo false !== strtotime($ps_working_time->getStartTime()) ? date('H:i', strtotime($ps_working_time->getStartTime())) : '&nbsp;' ?> &rarr; <?php echo false !== strtotime($ps_working_time->getEndTime()) ? date('H:i', strtotime($ps_working_time->getEndTime())) : '&nbsp;' ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_activated">
  <?php echo get_partial('psWorkingTime/list_field_boolean', array('value' => $ps_working_time->getIsActivated())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $ps_working_time->getUpdatedBy() ?>
  <br>
  <?php echo false !== strtotime($ps_working_time->getUpdatedAt()) ? format_date($ps_working_time->getUpdatedAt(), "HH:mm  dd/MM/yyyy") : '&nbsp;' ?>
</td>
