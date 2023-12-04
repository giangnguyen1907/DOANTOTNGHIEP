<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo $ps_symbol_group->getTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_group_symbol">
  <?php echo $ps_symbol_group->getGroupSymbol() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_payment">
  <?php echo get_partial('psSymbolGroup/list_field_boolean', array('value' => $ps_symbol_group->getIsPayment())) ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_type">
  <?php echo get_partial('psSymbolGroup/list_field_boolean_type', array('value' => $ps_symbol_group->getIsType())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $ps_symbol_group->getUpdatedBy() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($ps_symbol_group->getUpdatedAt()) ? format_date($ps_symbol_group->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
