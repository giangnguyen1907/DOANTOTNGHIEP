<td class="sf_admin_text sf_admin_list_td_student_name">
  <?php echo $ps_fee_reports->getStudentName();?><br /> <code><?php echo $ps_fee_reports->getStudentCode();?></code>
</td>

<td class="sf_admin_text sf_admin_list_td_birthday text-center">
  <?php echo get_partial('psFeeReports/birthday', array('type' => 'list', 'ps_fee_reports' => $ps_fee_reports)) ?>
</td>

<td class="sf_admin_text sf_admin_list_td_receivable_at text-center">
  <?php echo false !== strtotime($ps_fee_reports->getReceivableAt()) ? format_date($ps_fee_reports->getReceivableAt(), "dd-MM-yyyy") : '&nbsp;' ?>
</td>

<td class="sf_admin_text sf_admin_list_td_expected text-right">
  <?php echo get_partial('psFeeReports/expected', array('type' => 'list', 'ps_fee_reports' => $ps_fee_reports));?>
</td>

<td class="sf_admin_date sf_admin_list_td_updated_at text-center">
	<?php echo $ps_fee_reports->getUpdatedBy();?><br />
	<?php echo false !== strtotime($ps_fee_reports->getUpdatedAt()) ? format_date($ps_fee_reports->getUpdatedAt(), "HH:mm dd-MM-yyyy") : '&nbsp;' ?>
</td>

<td class="sf_admin_text sf_admin_list_td_receipt_no">
  <?php echo $ps_fee_reports->getReceiptNo();?>
</td>

<td class="sf_admin_text sf_admin_list_td_collected_amount text-right">
  <?php echo ($ps_fee_reports->getCollectedAmount()!= '') ? PreNumber::number_format($ps_fee_reports->getCollectedAmount()) : ''?>
</td>

<td class="sf_admin_text sf_admin_list_td_balance_amount text-right">
  <?php echo ($ps_fee_reports->getBalanceAmount() != '') ? PreNumber::number_format($ps_fee_reports->getBalanceAmount()) : ''?>
</td>

<td class="sf_admin_text sf_admin_list_td_payment_status text-right">
  <?php echo get_partial('global/field_custom/_field_payment_status', array('value' => $ps_fee_reports->getPaymentStatus())) ?>
</td>

<td class="sf_admin_date sf_admin_list_td_payment_date text-center">
	<?php echo false !== strtotime($ps_fee_reports->getPaymentDate()) ? format_date($ps_fee_reports->getPaymentDate(), "HH:mm dd-MM-yyyy") : '&nbsp;' ?>
</td>

<td class="sf_admin_text sf_admin_list_td_is_public text-right">
  <?php echo get_partial('psFeeReports/field_is_public', array('value' => $ps_fee_reports->getIsPublic())) ?>
</td>