<?php include_partial('global/include/_box_modal_messages');?>
<style>
.datepicker {
	z-index: 1051 !important;
}

.ui-datepicker {
	z-index: 1051 !important;
}

.list-inline {
	margin-left: 0;
}
</style>

<script>
	var msg_select_ps_customer_id	= '<?php echo __('Please select School to filter the data.')?>';
	var msg_select_ps_workplace_id	= '<?php echo __('Please select workplace to filter the data.')?>';
	var msg_select_ps_class_id 		= '<?php echo __('Please select class to to filter the data.')?>';
	var msg_select_school_year 		= '<?php echo __('Please select school year to to filter the data.')?>';
	var msg_select_date 			= '<?php echo __('Please enter dates to filter the data.')?>';
	
	var msg_select_month 			= '<?php echo __('Please enter month to filter the data.')?>';
	var msg_select_year 			= '<?php echo __('Please enter year to filter the data.')?>';

$(document).ready(function() {
	
	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});

	
// filter
$('#ps_fee_receipt_filters_ps_customer_id').change(function() {
	resetOptions('ps_fee_receipt_filters_ps_workplace_id');
	$('#ps_fee_receipt_filters_ps_workplace_id').select2('val','');
	$("#ps_fee_receipt_filters_ps_workplace_id").attr('disabled', 'disabled');
	resetOptions('ps_fee_receipt_filters_ps_class_id');
	$('#ps_fee_receipt_filters_ps_class_id').select2('val','');
	$("#ps_fee_receipt_filters_ps_class_id").attr('disabled', 'disabled');
		
	if ($(this).val() > 0) {

		$("#ps_fee_receipt_filters_ps_workplace_id").attr('disabled', 'disabled');
		$("#ps_fee_receipt_filters_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: "POST",
	        data: {'psc_id': $(this).val()},
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {

	    	$('#ps_fee_receipt_filters_ps_workplace_id').select2('val','');

			$("#ps_fee_receipt_filters_ps_workplace_id").html(msg);

			$("#ps_fee_receipt_filters_ps_workplace_id").attr('disabled', null);

			$("#ps_fee_receipt_filters_ps_class_id").attr('disabled', 'disabled');

	    });
	}		
});
 
$('#ps_fee_receipt_filters_ps_workplace_id').change(function() {
	
	$("#ps_fee_receipt_filters_ps_class_id").attr('disabled', 'disabled');
	
	$.ajax({
		url: '<?php echo url_for('@ps_class_by_params') ?>',
        type: "POST",
        data: 'c_id=' + $('#ps_fee_receipt_filters_ps_customer_id').val() + '&w_id=' + $('#ps_fee_receipt_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_fee_receipt_filters_school_year_id').val(),
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
    }).done(function(msg) {
    	$('#ps_fee_receipt_filters_ps_class_id').select2('val','');
		$("#ps_fee_receipt_filters_ps_class_id").html(msg);
		$("#ps_fee_receipt_filters_ps_class_id").attr('disabled', null);
    });
});

$('#ps_fee_receipt_filters_school_year_id').change(function() {
	
	resetOptions('ps_fee_receipt_filters_ps_class_id');
	$('#ps_fee_receipt_filters_ps_class_id').select2('val','');
	
	if ($('#ps_fee_receipt_filters_school_year_id').val() <= 0) {
		return;
	}

	$("#ps_fee_receipt_filters_ps_class_id").attr('disabled', 'disabled');
	$("#ps_fee_receipt_filters_ps_month").attr('disabled', 'disabled');
	$.ajax({
		url: '<?php echo url_for('@ps_class_by_params') ?>',
        type: "POST",
        data: 'c_id=' + $('#ps_fee_receipt_filters_ps_customer_id').val() + '&w_id=' + $('#ps_fee_receipt_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_fee_receipt_filters_school_year_id').val(),
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
    }).done(function(msg) {
    	$('#ps_fee_receipt_filters_ps_class_id').select2('val','');
		$("#ps_fee_receipt_filters_ps_class_id").html(msg);
		$("#ps_fee_receipt_filters_ps_class_id").attr('disabled', null);
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
	    	$('#ps_fee_receipt_filters_ps_month').select2('val','');
			$("#ps_fee_receipt_filters_ps_month").html(msg);
			$("#ps_fee_receipt_filters_ps_month").attr('disabled', null);
	    });
});

// form

$('#ps_fee_receipt_ps_customer_id').change(function() {
	resetOptions('ps_fee_receipt_ps_workplace_id');
	$('#ps_fee_receipt_ps_workplace_id').select2('val','');
	$("#ps_fee_receipt_ps_workplace_id").attr('disabled', 'disabled');
	resetOptions('ps_fee_receipt_ps_class_id');
	$('#ps_fee_receipt_ps_class_id').select2('val','');
	$("#ps_fee_receipt_ps_class_id").attr('disabled', 'disabled');
		
	if ($(this).val() > 0) {

		$("#ps_fee_receipt_ps_workplace_id").attr('disabled', 'disabled');
		$("#ps_fee_receipt_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: "POST",
	        data: {'psc_id': $(this).val()},
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {

	    	$('#ps_fee_receipt_ps_workplace_id').select2('val','');

			$("#ps_fee_receipt_ps_workplace_id").html(msg);

			$("#ps_fee_receipt_ps_workplace_id").attr('disabled', null);

			$("#ps_fee_receipt_ps_class_id").attr('disabled', 'disabled');

	    });
	}		
});
 
$('#ps_fee_receipt_ps_workplace_id').change(function() {
	
	$("#ps_fee_receipt_ps_class_id").attr('disabled', 'disabled');
	
	$.ajax({
		url: '<?php echo url_for('@ps_class_by_params') ?>',
        type: "POST",
        data: 'c_id=' + $('#ps_fee_receipt_ps_customer_id').val() + '&w_id=' + $('#ps_fee_receipt_ps_workplace_id').val() + '&y_id=' + $('#ps_fee_receipt_school_year_id').val(),
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
    }).done(function(msg) {
    	$('#ps_fee_receipt_ps_class_id').select2('val','');
		$("#ps_fee_receipt_ps_class_id").html(msg);
		$("#ps_fee_receipt_ps_class_id").attr('disabled', null);
    });
});

$('#ps_fee_receipt_school_year_id').change(function() {
	
	$("#ps_fee_receipt_ps_class_id").attr('disabled', 'disabled');
    
	$.ajax({
		url: '<?php echo url_for('@ps_class_by_params') ?>',
        type: "POST",
        data: 'c_id=' + $('#ps_fee_receipt_ps_customer_id').val() + '&w_id=' + $('#ps_fee_receipt_ps_workplace_id').val() + '&y_id=' + $('#ps_fee_receipt_school_year_id').val(),
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
    }).done(function(msg) {
    	$('#ps_fee_receipt_ps_class_id').select2('val','');
		$("#ps_fee_receipt_ps_class_id").html(msg);
		$("#ps_fee_receipt_ps_class_id").attr('disabled', null);
    });

});
$('#ps_fee_receipt_ps_class_id').change(function() {
	
	$.ajax({
		url: '<?php echo url_for('@ps_students_by_class_id') ?>',
        type: "POST",
        data: 'c_id=' + $(this).val(),
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
    }).done(function(msg) {
    	$('#ps_fee_receipt_student_id').select2('val','');
		$("#ps_fee_receipt_student_id").html(msg);
		$("#ps_fee_receipt_student_id").attr('disabled', null);
	});
});

$('#ps_fee_receipt_student_id').change(function() {
	//alert($('#ps_fee_receipt_student_id').val());
	resetOptions('ps_fee_receipt_payment_relative');
	$('#ps_fee_receipt_payment_relative').select2('val','');
	$.ajax({
		url: '<?php echo url_for('@ps_relative_by_students') ?>',
        type: "POST",
        data: 'c_id=' + $('#ps_fee_receipt_ps_customer_id').val() + '&s_id=' + $('#ps_fee_receipt_student_id').val(),
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
    }).done(function(msg) {
    	$('#ps_fee_receipt_payment_relative').select2('val','');
		$("#ps_fee_receipt_payment_relative").html(msg);
		$("#ps_fee_receipt_payment_relative").attr('disabled', null);
	});

});

$('#ps_fee_receipt_receipt_date').datepicker({
	dateFormat : 'dd-mm-yy',
	prevText : '<i class="fa fa-chevron-left"></i>',
	nextText : '<i class="fa fa-chevron-right"></i>',
	changeMonth : true,
	changeYear : true,

}).on('change', function(e) {
	$('#ps-form').formValidation('revalidateField', $(this).attr('name'));
});

$('#ps_fee_receipt_payment_date').datepicker({
	dateFormat : 'dd-mm-yy',
	prevText : '<i class="fa fa-chevron-left"></i>',
	nextText : '<i class="fa fa-chevron-right"></i>',
	changeMonth : true,
	changeYear : true,

}).on('change', function(e) {
	$('#ps-form').formValidation('revalidateField', $(this).attr('name'));
});

$('.btn-delete-item ').click(function() {
	var item_id = $(this).attr('data-item');		
	$('#ps-form-delete').attr('action', '<?php echo url_for('@ps_fee_receivable_student')?>/' + item_id);
});

$('.push_notication').click(function() {
	var receipt_id = $(this).attr('data-value');
	var student_id = $(this).attr('value');
	
	$('#ic-loading-' + receipt_id).show();		
	$.ajax({
        url: '<?php echo url_for('@ps_fee_receipt_send_notication') ?>',
        type: 'POST',
        data: 'receipt_id=' + receipt_id + '&student_id=' + student_id,
        success: function(data) {
        	$('#ic-loading-' + receipt_id).hide();
        	$('#box-' + receipt_id).html(data);
        },
        error: function (request, error) {
            alert(" Can't do because: " + error);
        },
	});
});

});
</script>
<?php include_partial('global/include/_box_modal')?>
<?php include_partial('psFeeReceipt/box_modal_confirm_remover');?>