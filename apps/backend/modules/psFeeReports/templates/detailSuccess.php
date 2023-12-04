<?php use_helper('I18N', 'Date', 'Number')?>
<script type="text/javascript">
$(document).ready(function() {

	$('.exportStatisticFeeReports').click(function() {
		if ($("#frm_detail #id").val() > 0) {		
			document.frm_detail.action = '<?php echo url_for('@ps_fee_reports_export?id='.$ps_fee_reports->getId())?>';
			$("#action_type").val('statistic');
			document.frm_detail.submit();
	    }
	});

	$('.exportFeeReports').click(function() {
		if ($("#frm_detail #id").val() > 0) {		
			document.frm_detail.action = '<?php echo url_for('@ps_fee_reports_export?id='.$ps_fee_reports->getId())?>';
			$("#action_type").val('notice');
			document.frm_detail.submit();
	    }
	});

	$('.exportFeeReceipt').click(function() {
		if ($("#frm_detail #id").val() > 0) {		
			document.frm_detail.action = '<?php echo url_for('@ps_fee_receipt_export?id='.$ps_fee_reports->getId())?>';
			document.frm_detail.submit();
	    }
	});
});
</script>
<style>
@media ( min-width : 992px) .modal-lg {
	min-width
	:
	 
	900
	px
	;
	
	    
	width
	:
	 
	1200
	px
	;
	
	
}

.modal-lg {
	min-width: 90%;
	width: 95%;
}
</style>
<div class="modal-header text-right">
		<?php include_partial('psFeeReports/box_button/_list_action_detail', array('receipt' => $receipt));?>
	</div>
<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel" style="line-height: 0.7">
			<?php echo __('Report fee month %%month%%, %%first_name%% %%last_name%%', array('%%first_name%%' => $student->getFirstName(), '%%last_name%%' => $student->getLastName(), '%%month%%' => format_date($ps_fee_reports->getReceivableAt(), "MM-yyyy"))) ?>
			<small>
			(<?php if (false !== strtotime($student->getBirthday())) echo format_date($student->getBirthday(), "dd-MM-yyyy").'<code>'.PreSchool::getAge($student->getBirthday(),false).'</code>';?>)
			</small>
	</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
				<?php include_partial('psFeeReports/box/_list_receivable_student', array('receivable_student' => $receivable_student, 'ps_fee_reports' => $ps_fee_reports ,'receivable_at' => $receivable_at, 'balanceAmount' => $balanceAmount, 'totalAmountReceivableAt' => $totalAmountReceivableAt,'totalAmount' => $totalAmount)) ?>
			</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<?php include_partial('psFeeReports/box/_payment_invoice', array('receipt' => $receipt, 'ps_fee_reports' => $ps_fee_reports,'balanceAmount' => $balanceAmount, 'collectedAmount' => $collectedAmount,'totalAmountReceivableAt' => $totalAmountReceivableAt,'totalAmount' => $totalAmount, 'form' => $form));?>
			</div>
	</div>
</div>
<div class="modal-footer">	
		<?php include_partial('psFeeReports/box_button/_list_action_detail', array('receipt' => $receipt));?>
	</div>
