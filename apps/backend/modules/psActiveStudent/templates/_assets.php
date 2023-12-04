<?php use_helper('I18N', 'Number')?>
<?php $app_upload_max_size = (int)sfConfig::get('app_upload_max_size');?>
<style>
#ps-filter .has-error {
	/* To make the feedback icon visible */
	z-index: 9999;
	color: #b94a48;
}

.datepicker {
	z-index: 1051 !important;
}

.ui-datepicker {
	z-index: 1051 !important;
}

.select2-container {
	width: 100% !important;
	padding: 0;
}
tr.info th{color: #333;line-height: 35px!important;}
</style>

<script type="text/javascript">

	var msg_file_invalid = '<?php echo __('The image file must be in the format: jpg, png, gif. File size less than %value%KB.', array('%value%' => $app_upload_max_size))?>';
	var PsMaxSizeFile 	 = '<?php echo $app_upload_max_size;?>';
	
$(document).ready(function() {
	// END: filters

	$('#ps_symbol_filters_ps_customer_id').change(function() {

		resetOptions('ps_symbol_filters_ps_workplace_id');
	    $('#ps_symbol_filters_ps_workplace_id').select2('val','');
	    $("#ps_symbol_filters_ps_workplace_id").attr('disabled', 'disabled');
    	
	    $.ajax({
	    	url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' +$(this).val(),
	    	type: 'POST',
	    	data: 'psc_id='+$(this).val(),
	    	success: function(data){
	    		$('#ps_symbol_filters_ps_workplace_id').show();
	    		$('#ps_symbol_filters_ps_workplace_id').html(data);
	    		$("#ps_symbol_filters_ps_workplace_id").attr('disabled', null);
	    	}
	    });		

    });

     //xử lí trong form
    $('#ps_symbol_ps_customer_id').change(function() {

		resetOptions('ps_symbol_ps_workplace_id');
	    $('#ps_symbol_ps_workplace_id').select2('val','');
	    $("#ps_symbol_ps_workplace_id").attr('disabled', 'disabled');
    	
	    $.ajax({
	    	url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' +$(this).val(),
	    	type: 'POST',
	    	data: 'psc_id='+$(this).val(),
	    	success: function(data){
	    		$('#ps_symbol_ps_workplace_id').show();
	    		$('#ps_symbol_ps_workplace_id').html(data);
	    		$("#ps_symbol_ps_workplace_id").attr('disabled', null);
	    	}
	    });		

    });

    $('#ps_active_student_filters_start_at').datepicker({
    		dateFormat : 'dd-mm-yy',
    		prevText : '<i class="fa fa-chevron-left"></i>',
    		nextText : '<i class="fa fa-chevron-right"></i>',
    		changeMonth : true,
    		changeYear : true,
    	})
    	
    	.on('change', function(e) {
    		$('#ps-filter').formValidation('revalidateField', $(this).attr('name'));
    	});

   	$('#ps_active_student_filters_end_at').datepicker({
    		dateFormat : 'dd-mm-yy',
    		prevText : '<i class="fa fa-chevron-left"></i>',
    		nextText : '<i class="fa fa-chevron-right"></i>',
    		changeMonth : true,
    		changeYear : true,
    	})
    	
    	.on('change', function(e) {
    		$('#ps-filter').formValidation('revalidateField', $(this).attr('name'));
    	});
	$('#ps_active_student_start_at').datepicker({
		dateFormat : 'dd-mm-yy',
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
	})
	
	.on('change', function(e) {
		$('#ps-filter').formValidation('revalidateField', $(this).attr('name'));
		var start_at =	$( '#ps_active_student_start_at' ).val();
		$( '#ps_active_student_end_at' ).val(start_at);
	});
	

	$('#ps_active_student_end_at').datepicker({
		dateFormat : 'dd-mm-yy',
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
	})
	
	.on('change', function(e) {
		$('#ps-filter').formValidation('revalidateField', $(this).attr('name'));
	});
	
    
});
</script>
<?php include_partial('global/include/_box_modal')?>