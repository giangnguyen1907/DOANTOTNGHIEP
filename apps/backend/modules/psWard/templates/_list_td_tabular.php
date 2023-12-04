<td class="sf_admin_text sf_admin_list_td_s_code">
  <?php echo link_to($ps_ward->getSCode(), 'ps_ward_edit', $ps_ward) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_name">
  <?php echo link_to($ps_ward->getName(), 'ps_ward_edit', $ps_ward) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_district_name">
  <?php echo $ps_ward->getDistrictName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_iorder">
  <?php echo $ps_ward->getIorder() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_activated">
  <?php echo get_partial('psWard/list_field_boolean', array('value' => $ps_ward->getIsActivated())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $ps_ward->getUpdatedBy() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($ps_ward->getUpdatedAt()) ? format_date($ps_ward->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
