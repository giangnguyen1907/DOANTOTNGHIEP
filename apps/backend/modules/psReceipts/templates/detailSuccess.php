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
<?php if (!$this->error):?>
<div class="modal-header text-right">
		<?php include_partial('psReceipts/box_button/_list_action_detail', array('receipt' => $receipt));?>
	</div>
<div class="modal-body">
	<div class="row" style="padding-bottom: 10px;">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<strong><?php echo __('Report fee month %%month%%, %%first_name%% %%last_name%%', array('%%first_name%%' => $student->getFirstName(), '%%last_name%%' => $student->getLastName().'<code>'.$student->getStudentCode().'</code>', '%%month%%' => format_date($ps_fee_reports->getReceivableAt(), "MM-yyyy"))) ?>
				<small>
				(<?php if (false !== strtotime($student->getBirthday())) echo format_date($student->getBirthday(), "dd-MM-yyyy").'<code>'.PreSchool::getAge($student->getBirthday(),false).'</code>';?>)
				</small> </strong>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<?php include_partial('psReceipts/box/_list_receivable_student', array('receivable_student' => $receivable_student, 'receivable_at' => $receivable_at, 'balanceAmount' => $balanceAmount, 'totalAmountReceivableAt' => $totalAmountReceivableAt,'totalAmount' => $totalAmount)) ?>
			</div>
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<?php include_partial('psReceipts/box/_payment_invoice', array('receipt' => $receipt, 'ps_fee_reports' => $ps_fee_reports,'balanceAmount' => $balanceAmount, 'collectedAmount' => $collectedAmount,'totalAmountReceivableAt' => $totalAmountReceivableAt,'totalAmount' => $totalAmount, 'psLatePayment' => $psConfigLatePayment, $receiptOfStudentNextMonth => $receiptOfStudentNextMonth, 'relatives' => $relatives, 'psChargePaylate' => $psChargePaylate, 'pricePaymentLate' => $pricePaymentLate));?>
			</div>
	</div>
</div>
<div class="modal-footer">	
		<?php include_partial('psReceipts/box_button/_list_action_detail', array('receipt' => $receipt));?>
	</div>
<?php else:?>
<div class="modal-header text-right">
	<button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close') ?></button>
</div>

<div class="modal-body">
	<div class="row">		
			<?php include_partial('psReceipts/flashes')?>
		</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close') ?></button>
</div>
<?php endif;?>
