<td class="sf_admin_text sf_admin_list_td_ps_workplace_title">
  <?php echo $ps_config_late_fees->getPsWorkplaceTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_from_minute">
  <?php echo $ps_config_late_fees->getFromMinute() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_to_minute">
  <?php echo $ps_config_late_fees->getToMinute() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_price">
  <?php echo $ps_config_late_fees->getPrice() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_note">
  <?php echo $ps_config_late_fees->getNote() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_activated">
  <?php echo get_partial('psConfigLateFees/list_field_boolean', array('value' => $ps_config_late_fees->getIsActivated())) ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
	<?php echo $ps_config_late_fees->getUpdatedBy() ?><br />
	<?php echo false !== strtotime($ps_config_late_fees->getUpdatedAt()) ? format_date($ps_config_late_fees->getUpdatedAt(), "HH:mm dd-MM-yyyy") : '&nbsp;' ?>
</td>
