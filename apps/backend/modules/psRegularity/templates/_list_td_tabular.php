<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo $ps_regularity->getTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_number">
  <?php echo $ps_regularity->getNumber() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_discount">
  <?php echo $ps_regularity->getDiscount() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_type">
  <?php echo get_partial('psRegularity/list_field_boolean', array('value' => $ps_regularity->getIsType())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_is_default">
  <?php echo get_partial('psRegularity/list_field_default', array('value' => $ps_regularity->getIsDefault())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $ps_regularity->getUpdatedBy() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($ps_regularity->getUpdatedAt()) ? format_date($ps_regularity->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
