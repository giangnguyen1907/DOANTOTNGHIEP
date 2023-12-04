<td class="sf_admin_foreignkey sf_admin_list_td_ps_customer_id">
  <?php echo $ps_advice_categories->getCusTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo $ps_advice_categories->getTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_note">
  <?php echo $ps_advice_categories->getNote() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_activated">
  <?php echo get_partial('psAdviceCategories/list_field_boolean', array('value' => $ps_advice_categories->getIsActivated())) ?>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_user_created_id">
  <?php echo $ps_advice_categories->getCreatorBy() ?>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_user_updated_id">
  <?php echo $ps_advice_categories->getUpdatedBy() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_created_at">
  <?php echo date('d-m-Y', strtotime($ps_advice_categories->getCreatedAt())) ?>
</td>
