<?php use_helper('I18N', 'Date', 'Number')?>
<script type="text/javascript">
	$(document).ready(function() {	

		var action = $('#frm_detail').attr('action');
		
		$('.exportStatisticFeeReports').click(function() {
			if ($("#frm_detail #id").val() > 0) {		
				document.frm_detail.action = '<?php echo url_for('@ps_fee_reports_export?id='.$ps_fee_reports->getId())?>';
				$("#action_type").val('statistic');
				document.frm_detail.submit();
		    }
			$('#ps-filter').attr('action', action);
			return true;
		});
	
		$('.exportFeeReports').click(function() {
			if ($("#frm_detail #id").val() > 0) {		
				document.frm_detail.action = '<?php echo url_for('@ps_fee_reports_export?id='.$ps_fee_reports->getId())?>';
				$("#action_type").val('notice');
				document.frm_detail.submit();
		    }
			$('#ps-filter').attr('action', action);
			return true;
		});
	
		$('.exportFeeReceipt').click(function() {
			if ($("#frm_detail #id").val() > 0) {
				document.frm_detail.action = '<?php echo url_for('@ps_fee_receipt_export?id='.$ps_fee_reports->getId())?>';
				document.frm_detail.submit();
		    }
			$('#ps-filter').attr('action', action);
			return true;
		});

		// in phieu thu
		$('.printFeeReceipt').click(function() {
			if ($("#frm_detail #id").val() > 0) {
				$('#frm_detail').attr('target', '_blank');
				document.frm_detail.action = '<?php echo url_for('@ps_fee_receipt_print?id='.$ps_fee_reports->getId())?>';
				$("#action_type").val('notice');
				document.frm_detail.submit();
		    }
		});
		$('.printFeeReports').click(function() {
			if ($("#frm_detail #id").val() > 0) {
				$('#frm_detail').attr('target', '_blank');
				document.frm_detail.action = '<?php echo url_for('@ps_fee_reports_print?id='.$ps_fee_reports->getId())?>';
				$("#action_type").val('notice');
				document.frm_detail.submit();
		    }
			$('#ps-filter').attr('action', action);
			return true;
		});
	
		$('#receipt_relative_id').change(function() {
			if ($(this).val() > 0) {
				$("#rec_relative_name").attr('readonly', true);
			} else {
				$("#rec_relative_name").attr('readonly', false);
			}
			$('#rec_relative_name').val($( "#receipt_relative_id option:selected" ).val());		
		});	
		$('#ps-filter').attr('action', action);
		return true;
		$( "#receipt_payment_date" ).focusout(function() {
			
			var chietkhau =	$('#rec_chietkhau').val();

		  	var date_at =	$('#receipt_payment_date').val();

		  	var receipt_no =	$('#rec_receipt_no').val();

		  	var receipt_id =	$('#rec_receipt_id').val();

		  	if(date_at != "--/--/----"){
			  	
    		  	var check = checkDate(date_at);
    			var currentdate = new Date().toLocaleDateString(); // lấy ngày hiện tại
    			
    			if(check == true){ // nếu đúng định dạng ngày tháng
        			
    			  	var startDate = parseDate(date_at).getTime();
    			    var endDate = parseDate(currentdate).getTime();
    			    
    			    if (endDate >= startDate) { // ngày nhập vào nhỏ hơn ngày hiện tại
    			    	$('#ic-loading').show();
    					$.ajax({
    				        url: '<?php echo url_for('@ps_receipts_load_amount') ?>',
    				        type: 'POST',
    				        data: 'receipt_no=' + receipt_no + '&date_at=' + date_at + '&chietkhau='+chietkhau,
    				        success: function(data) {
    				        	$('#ic-loading').hide();
    				        	$('#load-amount').html(data);
    				        },
    				        error: function (request, error) {
    				            //alert("<?php //echo __('check again') ?>");
    				            $('#ic-loading').hide();
    				        },
    					});
    					$('.btn-save-receipt').attr('disabled', false);
    			    } else {
    			    	alert("<?php echo __('Date payment bigger today') ?>");
    			    	$('.btn-save-receipt').attr('disabled', 'disabled');
    			    }
    			}else{
    				alert("<?php echo __('Date format fail') ?>");
    				$('.btn-save-receipt').attr('disabled', 'disabled');	
    			}
		  	}else{
		  		$('.btn-save-receipt').attr('disabled', false);
			}
		});
	});
	
	function checkChietKhau(){
		var ck = $('#rec_chietkhau').val();
		var tong = $('#tongtienthanhtoan').val();
		var phaithanhtoan = tong - ck;
		const nFormat = new Intl.NumberFormat();
		
		$('#rec_collected_amount').val(phaithanhtoan);
		$('#rec_total_payment').val(phaithanhtoan);
		//$('#sotiencannop').html(nFormat.format(phaithanhtoan));

		var text = to_vietnamese(phaithanhtoan);
		$('#collected_amount_text').html(text);

		//alert(phaithanhtoan);
	}

	function checkDate(strDate) {
	    var comp = strDate.split('/')
	    var d = parseInt(comp[0], 10)
	    var m = parseInt(comp[1], 10)
	    var y = parseInt(comp[2], 10)
	    var date = new Date(y,m-1,d);
	    if (date.getFullYear() == y && date.getMonth() + 1 == m && date.getDate() == d) {
	      return true
	    }
	    return false
	}

	function parseDate(str) {
	  var mdy = str.split('/');
	  return new Date(mdy[2], mdy[1], mdy[0]);
	}
	
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
	<?php include_partial('psReceipts/box_button/_list_action_detail', array('receipt' => $receipt));?>
</div>
<?php include_partial('psReceipts/flashes') ?>

<div class="modal-body">
	<div class="row" style="padding-bottom: 10px;">

		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<strong><?php echo __('Report fee month %%month%%, %%first_name%% %%last_name%%', array('%%first_name%%' => $student->getFirstName(), '%%last_name%%' => $student->getLastName().'<code>'.$student->getStudentCode().'</code>', '%%month%%' => format_date($receipt->getReceiptDate(), "MM-yyyy"))) ?>
				<small>
				(<?php if (false !== strtotime($student->getBirthday())) echo format_date($student->getBirthday(), "dd-MM-yyyy").'<code>'.PreSchool::getAge($student->getBirthday(),false).'</code>';?>)
				</small> </strong>
			<div>
				<?php if ($receiptOfStudentNextMonth && $receipt->getPaymentStatus () != PreSchool::ACTIVE):?>
					<div class="alert alert-warning fade in"
					style="margin-bottom: 0px;">
					<i class="fa-fw fa fa-warning ps-fa-2x" aria-hidden="true"></i> <?php echo __('Receipts were transferred to the following month')?>
					</div>
				<?php endif;?>
				</div>
		</div>

	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
		<?php
		if ($exportData == PreSchool::PS_TEMPLATE_RECEIPT_04) {
			include_partial ( 'psReceipts/fees/_list_receivable_student2', array (
					'receivable_student' => $receivable_student,
					'receivable_at' => $receivable_at,
					'balanceAmount' => $balanceAmount,
					'collectedAmount' => $collectedAmount,
					'ps_fee_reports' => $ps_fee_reports,
					'receipt' => $receipt,
					'receiptOfStudentNextMonth' => $receiptOfStudentNextMonth,
					'balance_last_month_amount' => $balance_last_month_amount,
					'old_balance_amount' => $old_balance_amount ) );
		} else {
			include_partial ( 'psReceipts/fees/_list_receivable_student', array (
					'receivable_student' => $receivable_student,
					'receivable_at' => $receivable_at,
					'balanceAmount' => $balanceAmount,
					'collectedAmount' => $collectedAmount,
					'ps_fee_reports' => $ps_fee_reports,
					'receipt' => $receipt,
					'receiptOfStudentNextMonth' => $receiptOfStudentNextMonth,
					'balance_last_month_amount' => $balance_last_month_amount,
					'old_balance_amount' => $old_balance_amount,
					'psWorkPlace' => $psWorkPlace ) );
		}
		?>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<form class="form-horizontal" id="frm_detail" name="frm_detail"
				method="post"
				action="<?php echo url_for('@ps_receipts_payment?id='.$receipt->getId())?>">
				
				<?php

	include_partial ( 'psReceipts/fees/_payment_invoice', array (
			'receipt' => $receipt,
			'ps_fee_reports' => $ps_fee_reports,
			'balanceAmount' => $balanceAmount,
			'collectedAmount' => $collectedAmount,
			'psLatePayment' => $psConfigLatePayment,
			'relatives' => $relatives,
			'psChargePaylate' => $psChargePaylate,
			'pricePaymentLate' => $pricePaymentLate,
			'balance_last_month_amount' => $balance_last_month_amount,
			'btnPayment' => $btnPayment ) );

	?>
				
				<?php if ($sf_user->hasCredential(array(0 => array(0 => 'PS_FEE_REPORT_CASHIER', 1 => 'PS_FEE_REPORT_EDIT')))): ?>			
				<button type="submit"
					class="btn btn-default btn-success btn-sm bg-color-greenDark txt-color-white btn-save-receipt">
					<i class="fa-fw fa fa-floppy-o" aria-hidden="true"
						title="<?php echo __('Payment') ?>"></i> <?php echo __('Payment') ?></button>
				<?php endif;?>
				</form>
		</div>
	</div>
</div>
<div class="modal-footer">	
		<?php include_partial('psReceipts/box_button/_list_action_detail', array('receipt' => $receipt));?>
</div>
