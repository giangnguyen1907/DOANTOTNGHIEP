<td class="sf_admin_text sf_admin_list_td_student_name">
  <?php echo $receipt->getStudentName();?><br /> <code><?php echo $receipt->getStudentCode();?></code>
</td>

<td class="sf_admin_text sf_admin_list_td_td_birthday text-center">
  <?php echo get_partial('global/field_custom/_field_birthday_student', array('value' => $receipt->getBirthday())) ?>
  <?php //echo get_partial('psReceipts/td_birthday', array('type' => 'list', 'receipt' => $receipt)) ?>
</td>

<td><?php echo $receipt->getClassName() ?></td>

<td class="sf_admin_text sf_admin_list_td_receivable_at text-center">
  <?php echo false !== strtotime($receipt->getReceivableAt()) ? format_date($receipt->getReceivableAt(), "dd-MM-yyyy") : '&nbsp;' ?>
</td>

<td class="sf_admin_text sf_admin_list_td_receipt_no text-center"><small><?php echo $receipt->getReceiptNo() ?></small></td>

<td class="sf_admin_text sf_admin_list_td_td_expected text-right">
  <?php echo get_partial('psReceipts/td_expected', array('type' => 'list', 'receipt' => $receipt)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_receipt_no text-center"><small>
	<?php echo PreNumber::number_format($receipt->getChietkhau()) ?></small>
</td>

<td class="text-right"><?php echo ($receipt->getPrimaryKey() > 0) ? PreNumber::number_format($receipt->getLatePaymentAmount()) : '';?></td>
<td class="sf_admin_text sf_admin_list_td_collected_amount text-right">
  <?php echo ($receipt->getPrimaryKey() > 0) ? PreNumber::number_format($receipt->getCollectedAmount()) : '';?>
</td>

<td class="sf_admin_text sf_admin_list_td_balance_amount text-right">
  <?php echo ($receipt->getPrimaryKey() > 0) ? PreNumber::number_format($receipt->getBalanceAmount()) : '';?>
</td>

<td class="sf_admin_text sf_admin_list_td_td_payment_status text-center">
  <?php echo get_partial('global/field_custom/_field_payment_status', array('value' => $receipt->getPaymentStatus())) ?>
</td>

<td class="sf_admin_text sf_admin_list_td_payment_date">
  <?php echo false !== strtotime($receipt->getPaymentDate()) ? format_date($receipt->getPaymentDate(), "dd-MM-yyyy") : '&nbsp;' ?>
</td>

<td class="sf_admin_boolean sf_admin_list_td_is_public text-center">
  <?php echo ($receipt->getPrimaryKey() > 0) ? get_partial('psReceipts/list_field_boolean', array('value' => $receipt->getIsPublic())) : '' ?>
</td>

<td class="sf_admin_text sf_admin_list_td_number_push_notication">
	<?php if ($sf_user->hasCredential(array('PS_FEE_REPORT_PUSH'))): ?>
	<div id="ic-loading-<?php echo $receipt->getReId();?>"
		style="display: none;">
		<i class="fa fa-spinner fa-2x fa-spin text-success"
			style="padding: 3px;"></i><?php echo __('Loading...')?>
    </div>
	<?php if($receipt->getPrimaryKey() > 0){?>
    
	<a
	class="btn btn-labeled btn-success <?php if($receipt->getIsPublic() > 0){ echo 'push_notication';}else{ echo 'not_relative_see disabled';} ?>"
	id="push_notication-<?php echo $receipt->getReId() ?>"
	href="javascript:;" value="<?php echo $receipt->getStudentId() ?>"
	data-value="<?php echo $receipt->getReId() ?>"> <span
		class="btn-label  list-inline"
		id="box-<?php echo $receipt->getReId() ?>">
    		<?php echo get_partial('psReceipts/load_number_notication', array('receipt' => $receipt))?>
    	</span> <span class="btn-control"> <i class="fa fa-bell"></i>
	</span>
</a>
    
	<?php }?>
	<?php endif;?>
</td>
