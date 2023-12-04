<td class="sf_admin_foreignkey sf_admin_list_td_ps_customer_id">
  <?php echo $ps_allowance->getSchoolName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo $ps_allowance->getTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_allowance_value text-right">
  <?php echo PreNumber::number_format($ps_allowance->getAllowanceValue()) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_note">
  <?php echo $ps_allowance->getNote() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_activated">
  <?php echo get_partial('psAllowance/list_field_boolean', array('value' => $ps_allowance->getIsActivated())) ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo $ps_allowance->getUpdatedBy()?>
  <?php echo '<br>'?>
  <?php echo (false !== strtotime($ps_allowance->getUpdatedAt())) ? format_date($ps_allowance->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '';?>
</td>
