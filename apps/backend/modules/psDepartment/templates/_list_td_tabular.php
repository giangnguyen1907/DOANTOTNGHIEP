<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo $ps_department->getTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_customer_title">
  <?php echo $ps_department->getCustomerTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_description">
  <?php echo $ps_department->getDescription() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_iorder">
  <?php echo $ps_department->getIorder() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_activated">
  <?php echo get_partial('psDepartment/list_field_boolean', array('value' => $ps_department->getIsActivated())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $ps_department->getUpdatedBy() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($ps_department->getUpdatedAt()) ? format_date($ps_department->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
