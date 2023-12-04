<td class="sf_admin_text sf_admin_list_td_name">
  <?php echo link_to($ps_district->getName(), 'ps_district_edit', $ps_district) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_province_name">
  <?php echo $ps_district->getProvinceName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_iorder">
  <?php echo $ps_district->getIorder() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_activated">
  <?php echo get_partial('global/list_field_boolean', array('value' => $ps_district->getIsActivated())) ?>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_updated_by">
  <?php echo $ps_district->getUpdatedBy() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($ps_district->getUpdatedAt()) ? format_date($ps_district->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
