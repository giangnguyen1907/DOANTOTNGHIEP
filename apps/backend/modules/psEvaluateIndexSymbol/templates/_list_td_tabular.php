<td class="sf_admin_text sf_admin_list_td_school_year_title">
  <?php echo (false != $ps_evaluate_index_symbol->getSchoolYearTitle()) ? $ps_evaluate_index_symbol->getSchoolYearTitle() : __('Apply for all courses') ?>
</td>
<td class="sf_admin_text sf_admin_list_td_school_name">
  <?php echo $ps_evaluate_index_symbol->getSchoolName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_symbol_code">
  <?php echo $ps_evaluate_index_symbol->getSymbolCode() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo $ps_evaluate_index_symbol->getTitle() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_activated">
  <?php echo get_partial('psEvaluateIndexSymbol/list_field_boolean', array('value' => $ps_evaluate_index_symbol->getIsActivated())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $ps_evaluate_index_symbol->getUpdatedBy() ?>
  <br>
  <?php echo false !== strtotime($ps_evaluate_index_symbol->getUpdatedAt()) ? format_date($ps_evaluate_index_symbol->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
