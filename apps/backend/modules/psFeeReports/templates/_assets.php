<?php use_helper('I18N', 'Date') ?>
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

	$('#ps_fee_reports_filters_ps_school_year_id,#ps_fee_reports_filters_ps_customer_id,#ps_fee_reports_filters_ps_workplace_id,#ps_fee_reports_filters_ps_class_id,#ps_fee_reports_filters_ps_month,#ps_fee_reports_filters_ps_year').change(function() {
		
		$(".btn-add-receivable-month,.batch_action_batchProcessFeeReports").attr('disabled', 'disabled');
		
	});	
	
	$('#ps_fee_reports_filters_ps_customer_id').change(function() {
		
		$("#ps_fee_reports_filters_ps_workplace_id").attr('disabled', 'disabled');

		resetOptions('ps_fee_reports_filters_ps_class_id');
		$('#ps_fee_reports_filters_ps_class_id').select2('val','');
		
		$.ajax({
	        url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
	        success: function(data) {
	        	$('#ps_fee_reports_filters_ps_workplace_id').select2('val','');
				$('#ps_fee_reports_filters_ps_workplace_id').html(data);
				$("#ps_fee_reports_filters_ps_workplace_id").attr('disabled', null);
				
	        }
		});
	});

	$('#ps_fee_reports_filters_ps_workplace_id').change(function() {
		resetOptions('ps_fee_reports_filters_ps_class_id');
		$('#ps_fee_reports_filters_ps_class_id').select2('val','');
		
		if ($('#ps_fee_reports_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#ps_fee_reports_filters_ps_class_id").attr('disabled', 'disabled');

		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_fee_reports_filters_ps_customer_id').val() + '&w_id=' + $('#ps_fee_reports_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_fee_reports_filters_ps_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_fee_reports_filters_ps_class_id').select2('val','');
			$("#ps_fee_reports_filters_ps_class_id").html(msg);
			$("#ps_fee_reports_filters_ps_class_id").attr('disabled', null);
	    });
	});
	// END: filters	
});
</script>

<script type="text/javascript">
function setItem(id) {
	var row_id = 'item-' + id;
	var rt_text = $("#" + row_id).attr('data-item-text');
	$("#item_id").val(id);			
	$("#modal-body-text").html("<?php echo __("You sure want to delete receivable")?>: <strong>" + rt_text + "</strong> <?php echo __('of the class')?>?");
}

function checkSetStudent(){
	var boxes = $('input[name="ids[]"]:checked');
	return boxes.length;
}	

$(document).ready(function() {
	
	$('#batch_action_batchDelete').click(function(){
		var value = $(this).attr("data-action");
    	$('#batch_action').val($(this).attr("data-action"));
		$('#frm_batch').submit();
		return true;
	});

	$('#action_batchPublishReceipts').click(function(){

		var boxes = $('input[name="ids[]"]:checked');

    	if (boxes.length <= 0) {			
			$("#warningModal").modal();
			$(".modal-body #errors").html("<?php echo __('You do not select students to perform')?>");
			return false;
        }        		

    	alert($(this).attr("data-action"));

    	$('#batch_action').val('batchPublishReceipts');

    	$('#frm_batch').submit();

		return true;
	});

	// OK save to temp
	$('#btn-get-receivable').click(function(){
		$('#list_receivable_temp').hide();
		$('#ic-loading').show();
		
		$.ajax({
			url: '<?php echo url_for('@ps_receivable_month_put_save')?>',
	        type: "POST",
	        data: $("#ps-filter-receivable").serialize(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#list_receivable_temp').show();
	    	$("#list_receivable_temp").html(msg);
			$('#ic-loading').hide();
	    });		
	});

	// OK delete temp
	$('.btn-ok-delete').click(function(e){

		if ($("#item_id").val() > 0) {
		
			$('#list_receivable_temp').hide();
			$('#ic-loading').show();	
			
			$.ajax({
				url: '<?php echo url_for('@ps_receivable_month_put_delete')?>',
		        type: "POST",
		        data: $("#ps-filter-fee-reports").serialize() + '&' + $("#ps-form-rt-delete").serialize(),
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {
		    	$('#list_receivable_temp').show();
		    	$("#list_receivable_temp").html(msg);
				$('#ic-loading').hide();
		    });
	    }
	});

	// Run bao phi su dung
	$('.batch_action_batchProcessFeeReports').click(function(){
		var value = $(this).attr("data-action");
    	$('#batch_action').val($(this).attr("data-action"));

    	var boxes = $('input[name="ids[]"]:checked');

    	if (boxes.length <= 0) {			
			$("#warningModal").modal();
			$(".modal-body #errors").html("<?php echo __('You do not select students to perform')?>");
			return false;
        }

        // Call to action process fee
    	$('#ic_status_processing').show();	

		$.ajax({
			url: '<?php echo url_for('@ps_fee_reports_batch_process_fee')?>',
	        type: "POST",
	        data: $("#ps-filter-fee-reports").serialize() + '&' + $("#frm_batch").serialize(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ic_status_processing').hide();
	    	//$('#dt_basic').show();
	    	$("#table-ps-fee-reports").html(msg);	    	
	    });		
	});

	// Run bao phi su dung
	$('.batch_action_batchExportFeeReports').click(function(){
		var value = $(this).attr("data-action");
    	$('#batch_action').val($(this).attr("data-action"));

    	var boxes = $('input[name="ids[]"]:checked');

    	if (boxes.length <= 0) {			
			$("#warningModal").modal();
			$(".modal-body #errors").html("<?php echo __('You do not select students to perform')?>");
			return false;
        }

        // Call to action process fee
    	$('#ic_status_processing').show();	

		$.ajax({
			url: '<?php echo url_for('@ps_fee_reports_batch_process_fee')?>',
	        type: "POST",
	        data: $("#ps-filter-fee-reports").serialize() + '&' + $("#frm_batch").serialize(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ic_status_processing').hide();
	    	//$('#dt_basic').show();
	    	$("#table-ps-fee-reports").html(msg);	    	
	    });		
	});
	
	// Export data of class
	$('.batchExportFeeReports').click(function(){

		var classId = null;
    	if (classId <= 0) {			
			$("#warningModal").modal();
			$(".modal-body #errors").html("<?php echo __('Please select the class to export the data.')?>");
			return false;
        }        
    	$('#batch_action').val('<?php echo url_for('@ps_fee_reports_batch_export_fee_for_class')?>');
    	$('#frm_batch').submit();		
		return true;		
	});

	// So no
	$('#btnExportStatisticFeeDebt').click(function() {

		var classId = '<?php echo (isset($filters['ps_class_id'])) ? $filters['ps_class_id']->getValue() : null;?>';
		
    	if (classId <= 0) {			
			$("#warningModal").modal();
			$(".modal-body #errors").html("<?php echo __('Please select the class to export the data.')?>");
			return false;
        }
                        
    	var action = $('#ps-filter-fee-reports').attr('action');
    	       
    	$('#ps-filter-fee-reports').attr('action','<?php echo url_for('@ps_fee_reports_batch_export_fee_debt_for_class')?>');

    	$('#ps-filter-fee-reports').submit();
    	
		$('#ps-filter-fee-reports').attr('action', action);

		return true;
	});		
});
</script>
<?php include_partial('global/include/_box_modal_messages');?>
<?php include_partial('global/include/_box_modal_errors');?>
<?php include_partial('global/include/_box_modal_warning');?>
<?php include_partial('global/include/_box_modal');?>
<?php include_partial('psFeeReports/box_modal_confirm_remover_receivable_student');?>
