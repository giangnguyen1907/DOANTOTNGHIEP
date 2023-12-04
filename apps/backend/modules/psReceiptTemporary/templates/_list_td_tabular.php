<td class="sf_admin_text sf_admin_list_td_student_name">
  <?php echo $ps_receipt_temporary->getStudentName() ?><br /> <code><?php echo $ps_receipt_temporary->getStudentCode() ?></code>
</td>
<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo $ps_receipt_temporary->getTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_receipt_date text-center">
  <?php echo $ps_receipt_temporary->getReceiptDate() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_receivable text-right">
  <?php echo $ps_receipt_temporary->getReceivable() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_collected_amount text-right">
  <?php echo $ps_receipt_temporary->getCollectedAmount() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_balance_amount text-right">
  <?php echo $ps_receipt_temporary->getBalanceAmount() ?>
</td>
<td
	class="sf_admin_list_td_is_deploy sf_admin_list_td_is_activated text-center">
  <?php echo get_partial('psReceiptTemporary/list_field_boolean', array('value' => $ps_receipt_temporary->getIsImport())) ?>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_relative_id">
  <?php echo $ps_receipt_temporary->getRelativeName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_note">
  <?php echo $ps_receipt_temporary->getNote() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by text-center">
  <?php echo $ps_receipt_temporary->getUpdatedBy() ?>
</td>
