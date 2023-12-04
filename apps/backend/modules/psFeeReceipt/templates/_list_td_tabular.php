<td class="sf_admin_foreignkey sf_admin_list_td_student_id">
  <?php echo $ps_fee_receipt->getStudentName() ?><br /> <code><?php echo $ps_fee_receipt->getStudentCode();?></code>
</td>
<td class="sf_admin_text sf_admin_list_td_receipt_no">
  <?php echo $ps_fee_receipt->getReceiptNo() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_receivable_amount text-right">
  <?php echo PreNumber::number_format($ps_fee_receipt->getReceivableAmount()) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_collected_amount text-right">
  <?php echo PreNumber::number_format($ps_fee_receipt->getCollectedAmount()) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_balance_amount text-right">
  <?php echo PreNumber::number_format($ps_fee_receipt->getBalanceAmount()) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_receipt_date text-center">
  <?php echo false !== strtotime($ps_fee_receipt->getReceiptDate()) ? format_date($ps_fee_receipt->getReceiptDate(), "MM-yyyy") : '&nbsp;' ?>
</td>
<td class="sf_admin_text sf_admin_list_td_payment_status text-center">
  <?php echo get_partial('global/field_custom/_field_payment_status', array('value' => $ps_fee_receipt->getPaymentStatus())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_payment_date text-center">
  <?php echo false !== strtotime($ps_fee_receipt->getPaymentDate()) ? format_date($ps_fee_receipt->getPaymentDate(), "dd-MM-yyyy") : '&nbsp;' ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_public text-center">
  <?php echo get_partial('psFeeReceipt/list_field_boolean', array('value' => $ps_fee_receipt->getIsPublic())) ?>
</td>
<td
	class="sf_admin_text sf_admin_list_td_number_push_notication text-center">
  	<?php if ($sf_user->hasCredential(array('PS_FEE_RECEIPT_NOTICATION_PUSH'))): ?>
  	<div id="ic-loading-<?php echo $ps_fee_receipt->getId();?>"
		style="display: none;">
		<i class="fa fa-spinner fa-2x fa-spin text-success"
			style="padding: 3px;"></i><?php echo __('Loading...')?>
    </div> <a
	class="btn btn-labeled btn-success <?php if($ps_fee_receipt->getIsPublic() > 0){ echo 'push_notication';}else{ echo 'not_relative_see disabled';} ?>"
	id="push_notication-<?php echo $ps_fee_receipt->getId() ?>"
	href="javascript:;"
	value="<?php echo $ps_fee_receipt->getStudentId() ?>"
	data-value="<?php echo $ps_fee_receipt->getId() ?>"> <span
		class="btn-label list-inline"
		id="box-<?php echo $ps_fee_receipt->getId() ?>">
    		<?php echo get_partial('psFeeReceipt/load_number_notication', array('ps_fee_receipt' => $ps_fee_receipt))?>
    	</span> <span class="btn-control"> <i class="fa fa-bell"></i>
	</span>
</a>
    
	<?php endif;?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by text-center">
  <?php echo $ps_fee_receipt->getUpdatedBy() ?>
<br>
  <?php echo false !== strtotime($ps_fee_receipt->getUpdatedAt()) ? format_date($ps_fee_receipt->getUpdatedAt(), "HH:mm  dd-MM-yyyy") : '&nbsp;' ?>
</td>
