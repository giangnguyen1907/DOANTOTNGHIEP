<?php use_helper('I18N', 'Date') ?>
<style>
.list-inline {
	margin-left: 0;
}
#dt_basic_receivable tr th {color: #333; line-height: 25px;}
</style>
<script type="text/javascript">
var msg_select_ps_customer_id	= '<?php echo __('Please select School to filter the data.')?>';
var msg_select_ps_workplace_id	= '<?php echo __('Please select workplace to filter the data.')?>';
var msg_select_ps_class_id 		= '<?php echo __('Please select class to to filter the data.')?>';
var msg_select_school_year 		= '<?php echo __('Please select school year to to filter the data.')?>';
var msg_select_date 			= '<?php echo __('Please enter dates to filter the data.')?>';

var msg_select_month 			= '<?php echo __('Please enter month to filter the data.')?>';
var msg_select_year 			= '<?php echo __('Please enter year to filter the data.')?>';

$(document).ready(function() {

	$(".widget-body-toolbar a, .btn-group a, .sf_admin_list_td_name a").on("contextmenu",function(){
	       return false;
	});

	$('#receipt_filters_ps_customer_id').change(function() {
		
		$("#receipt_filters_ps_workplace_id").attr('disabled', 'disabled');

		resetOptions('receipt_filters_ps_class_id');
		$('#receipt_filters_ps_class_id').select2('val','');
		
		$.ajax({
	        url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
	        success: function(data) {
	        	$('#receipt_filters_ps_workplace_id').select2('val','');
				$('#receipt_filters_ps_workplace_id').html(data);
				$("#receipt_filters_ps_workplace_id").attr('disabled', null);
				
	        }
		});
	});

	$('#receipt_filters_ps_workplace_id').change(function() {
		resetOptions('receipt_filters_ps_class_id');
		$('#receipt_filters_ps_class_id').select2('val','');
		
		if ($('#receipt_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#receipt_filters_ps_class_id").attr('disabled', 'disabled');

		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#receipt_filters_ps_customer_id').val() + '&w_id=' + $('#receipt_filters_ps_workplace_id').val() + '&y_id=' + $('#receipt_filters_ps_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#receipt_filters_ps_class_id').select2('val','');
			$("#receipt_filters_ps_class_id").html(msg);
			$("#receipt_filters_ps_class_id").attr('disabled', null);
	    });
	});

	$('#receipt_filters_ps_school_year_id').change(function() {
		
		resetOptions('receipt_filters_ps_class_id');
		$('#receipt_filters_ps_class_id').select2('val','');
		
		if ($('#receipt_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#receipt_filters_ps_class_id").attr('disabled', 'disabled');
		$("#receipt_filters_ps_year_month").attr('disabled', 'disabled');
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#receipt_filters_ps_customer_id').val() + '&w_id=' + $('#receipt_filters_ps_workplace_id').val() + '&y_id=' + $('#receipt_filters_ps_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#receipt_filters_ps_class_id').select2('val','');
			$("#receipt_filters_ps_class_id").html(msg);
			$("#receipt_filters_ps_class_id").attr('disabled', null);
	    });
		$.ajax({
			url: '<?php echo url_for('@ps_year_month?ym_id=') ?>' + $(this).val(),
	        type: "POST",
	        data: {'ym_id': $(this).val()},
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
		    }).done(function(msg) {
		    	$('#receipt_filters_ps_year_month').select2('val','');
				$("#receipt_filters_ps_year_month").html(msg);
				$("#receipt_filters_ps_year_month").attr('disabled', null);
		    });
	});
	
	// END: filters
	
	$('#receipt_relative_id').change(function() {
		if ($(this).val() > 0) {
			$("#receipt_payment_relative_name").attr('readonly', true);
		} else {
			$("#receipt_payment_relative_name").attr('readonly', false);
		}
		$('#receipt_payment_relative_name').val($( "#receipt_relative_id option:selected" ).text());		
	});	
});
</script>

<script type="text/javascript">

function checkSetStudent(){
	var boxes = $('input[name="ids[]"]:checked');
	return boxes.length;
}

$(document).ready(function() {

	$('.push_notication').click(function() {
		var receipt_id = $(this).attr('data-value');
		var student_id = $(this).attr('value');
		
		$('#ic-loading-' + receipt_id).show();		
		$.ajax({
	        url: '<?php echo url_for('@ps_receipt_send_notication') ?>',
	        type: 'POST',
	        data: 'receipt_id=' + receipt_id + '&student_id=' + student_id,
	        success: function(data) {
				// console.log(data);
	        	$('#ic-loading-' + receipt_id).hide();
	        	$('#box-' + receipt_id).html(data);
	        },
	        error: function (request, error) {
	            alert(" Can't do because: " + error);
	        },
		});
	});
	
	$('.not_relative_see').click(function() {
		alert('Cần chuyển trạng thái hiển thị ra app rồi mới gửi thông báo đi');
	});

	$('.btn-receivable-student').click(function() {
		var rs_id = $(this).attr('data-value');		
		$.ajax({
	        url: '<?php echo url_for('@ps_receivable_students_in_receipt_save')?>',
	        type: 'POST',
	        data: 'rs_id=' + rs_id,
	        success: function(data) {
	        	//$('#ic-loading-' + rs_id).hide();
	        	$('#tr_rs_receivable_student_' + rs_id).html(data);
	        },
	        error: function (request, error) {
	            alert("<?php echo __('Save have error')?>: " + error);
	        },
		});
	});

	$('.btn-receivable-student-current').click(function() {
		var rs_id = $(this).attr('data-value');		
		$.ajax({
			url: '<?php echo url_for('@ps_receivable_students_in_receipt_save')?>',
	        type: 'POST',
	        data: 'rs_id=' + rs_id + '&current=1',
	        success: function(data) {
	        	//$('#ic-loading-' + rs_id).hide();
	        	$('#tr_rs_receivable_student_' + rs_id).html(data);
	        },
	        error: function (request, error) {
	            alert("<?php echo __('Save have error')?>: " + error);
	        },
		});
	});

	// Xuất biểu mẫu import phiếu thanh toán
	$('.btn-export-student-payment').click(function() {
		
		if ($('#receipt_filters_ps_workplace_id').val() <= 0) {
			alert('<?php echo __('Select workplace')?>');
			return false;
		}

		//Get action hien tai
		$action = $('#ps-filter').attr('action');
		$('#ps-filter').attr('action', '<?php echo url_for('@ps_receipts_statistic_payment') ?>');
		$('#ps-filter').submit();
		
		//Tra lai action ban dau
		$('#ps-filter').attr('action', $action);
		return true;		
    });


	// Xuất biểu mẫu import số dư đầu kỳ
	$('.btn-export-student-balance-month').click(function() {
		
		if ($('#receipt_filters_ps_workplace_id').val() <= 0) {
			alert('<?php echo __('Select workplace')?>');
			return false;
		}

		//Get action hien tai
		$action = $('#ps-filter').attr('action');
		$('#ps-filter').attr('action', '<?php echo url_for('@ps_receipts_balance_last_month') ?>');
		$('#ps-filter').submit();
		
		//Tra lai action ban dau
		$('#ps-filter').attr('action', $action);
		return true;		
    });
	

	// In phieu thu theo lop
	$('.btn-print-fee-receipt-class').click(function() {
		
		if ($('#receipt_filters_ps_class_id').val() <= 0) {
			alert('<?php echo __('Select class')?>');
			return false;
		}

		//Get action hien tai
		$action = $('#ps-filter').attr('action');
		$('#ps-filter').attr('target', '_blank');
		$('#ps-filter').attr('action', '<?php echo url_for('@ps_fee_receipt_by_class_print') ?>');
		$('#ps-filter').submit();
		
		//Tra lai action ban dau
		$('#ps-filter').attr('action', $action);
		return true;
    });

	// In phieu bao theo lop
	$('.btn-print-fee-report-class').click(function() {
		
		if ($('#receipt_filters_ps_class_id').val() <= 0) {
			alert('<?php echo __('Select class')?>');
			return false;
		}

		//Get action hien tai
		$action = $('#ps-filter').attr('action');
		$('#ps-filter').attr('target', '_blank');
		$('#ps-filter').attr('action', '<?php echo url_for('@ps_fee_reports_by_class_print') ?>');
		$('#ps-filter').submit();
		
		//Tra lai action ban dau
		$('#ps-filter').attr('action', $action);
		return true;
    });


	// In phieu thu theo co so
	$('.btn-print-fee-receipt-workplace').click(function() {
		
		if ($('#receipt_filters_ps_workplace_id').val() <= 0) {
			alert('<?php echo __('Select workplace')?>');
			return false;
		}

		//Get action hien tai
		$action = $('#ps-filter').attr('action');
		$('#ps-filter').attr('target', '_blank');
		$('#ps-filter').attr('action', '<?php echo url_for('@ps_fee_receipt_by_workplace_print') ?>');
		$('#ps-filter').submit();
		
		//Tra lai action ban dau
		$('#ps-filter').attr('action', $action);
		return true;
    });

	// In phieu bao theo co so
	$('.btn-print-fee-report-workplace').click(function() {
		
		if ($('#receipt_filters_ps_workplace_id').val() <= 0) {
			alert('<?php echo __('Select workplace')?>');
			return false;
		}

		//Get action hien tai
		$action = $('#ps-filter').attr('action');
		$('#ps-filter').attr('target', '_blank');
		$('#ps-filter').attr('action', '<?php echo url_for('@ps_fee_reports_by_workplace_print') ?>');
		$('#ps-filter').submit();
		
		//Tra lai action ban dau
		$('#ps-filter').attr('action', $action);
		return true;
    });
	
	
	// xuat phieu thu theo lop
	$('.btn-export-fee-receipt').click(function() {
		
		if ($('#receipt_filters_ps_class_id').val() <= 0) {
			alert('<?php echo __('Select class')?>');
			return false;
		}

		//Get action hien tai
		$action = $('#ps-filter').attr('action');
		$('#ps-filter').attr('action', '<?php echo url_for('@ps_fee_reports_batch_export_fee_receipt_for_class') ?>');
		$('#ps-filter').submit();
		
		//Tra lai action ban dau
		$('#ps-filter').attr('action', $action);
		return true;		
    });

	// xuat phieu bao theo lop
	$('.btn-export-fee-report').click(function() {
		
		if ($('#receipt_filters_ps_class_id').val() <= 0) {
			alert('<?php echo __('Select class')?>');
			return false;
		}

		//Get action hien tai
		$action = $('#ps-filter').attr('action');
		$('#ps-filter').attr('action', '<?php echo url_for('@ps_fee_reports_batch_export_fee_for_class') ?>');
		$('#ps-filter').submit();
		
		//Tra lai action ban dau
		$('#ps-filter').attr('action', $action);
		return true;		
    });

	// xuat phieu thu theo co so
	$('.btn-export-fee-receipt-workplace').click(function() {
		
		if ($('#receipt_filters_ps_workplace_id').val() <= 0) {
			alert('<?php echo __('Select workplace')?>');
			return false;
		}

		//Get action hien tai
		$action = $('#ps-filter').attr('action');
		$('#ps-filter').attr('action', '<?php echo url_for('@ps_fee_reports_batch_export_fee_receipt_for_workplace') ?>');
		$('#ps-filter').submit();
		
		//Tra lai action ban dau
		$('#ps-filter').attr('action', $action);
		return true;		
    });

	// xuat phieu bao theo co so
	$('.btn-export-fee-report-workplace').click(function() {
		
		if ($('#receipt_filters_ps_workplace_id').val() <= 0) {
			alert('<?php echo __('Select workplace')?>');
			return false;
		}

		//Get action hien tai
		$action = $('#ps-filter').attr('action');
		$('#ps-filter').attr('action', '<?php echo url_for('@ps_fee_reports_batch_export_fee_report_for_workplace') ?>');
		$('#ps-filter').submit();
		
		//Tra lai action ban dau
		$('#ps-filter').attr('action', $action);
		return true;		
    });

});
</script>
<script type="text/javascript">
$(function () {
	$('#batch-actions button').click(function(){

		var boxes = $('input[name="ids[]"]:checked');

    	if (boxes.length <= 0) {			
			$("#warningModal").modal();
			$(".modal-body #errors").html("<?php echo __('You do not select students to perform')?>");
			return false;
        }

		var value = $(this).attr("data-action");

    	$('#batch_action').val($(this).attr("data-action"));

		$('#frm_batch').submit();

		return true;
	});

	
});
</script>

<?php include_partial('global/include/_box_modal_messages');?>
<?php include_partial('global/include/_box_modal_warning');?>
<?php include_partial('global/include/_box_modal');?>
