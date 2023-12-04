<td class="sf_admin_foreignkey sf_admin_list_td_ps_customer_id">
  <?php echo $ps_salary->getSchoolName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_basic_salary text-right">
  <?php echo link_to(PreNumber::number_format($ps_salary->getTitle()), 'ps_salary_edit', $ps_salary) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_note">
  <?php echo $ps_salary->getNote() ?>
</td>
<td
	class="sf_admin_text sf_admin_list_td_day_work_per_month text-center">
  <?php echo $ps_salary->getDayWorkPerMonth() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_activated">
  <?php echo get_partial('psSalary/list_field_boolean', array('value' => $ps_salary->getIsActivated())) ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo $ps_salary->getUpdatedBy()?>
  <?php echo '<br>'?>
  <?php echo (false !== strtotime($ps_salary->getUpdatedAt())) ? format_date($ps_salary->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '';?>
</td>
