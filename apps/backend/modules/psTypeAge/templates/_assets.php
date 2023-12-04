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
	$('#ps_type_age_filters_ps_customer_id').change(function() {

		resetOptions('ps_type_age_filters_ps_workplace_id');
	    $('#ps_type_age_filters_ps_workplace_id').select2('val','');
	    $("#ps_type_age_filters_ps_workplace_id").attr('disabled', 'disabled');
    	
	    $.ajax({
	    	url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' +$(this).val(),
	    	type: 'POST',
	    	data: 'psc_id='+$(this).val(),
	    	success: function(data){
	    		$('#ps_type_age_filters_ps_workplace_id').show();
	    		$('#ps_type_age_filters_ps_workplace_id').html(data);
	    		$("#ps_type_age_filters_ps_workplace_id").attr('disabled', null);
	    	}
	    });		

    });

     //xử lí trong form
    $('#ps_type_age_ps_customer_id').change(function() {

		resetOptions('ps_type_age_ps_workplace_id');
	    $('#ps_type_age_ps_workplace_id').select2('val','');
	    $("#ps_type_age_ps_workplace_id").attr('disabled', 'disabled');
    	
	    $.ajax({
	    	url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' +$(this).val(),
	    	type: 'POST',
	    	data: 'psc_id='+$(this).val(),
	    	success: function(data){
	    		$('#ps_type_age_ps_workplace_id').show();
	    		$('#ps_type_age_ps_workplace_id').html(data);
	    		$("#ps_type_age_ps_workplace_id").attr('disabled', null);
	    	}
	    });		

    });
    
});
</script>
<?php include_partial('global/include/_box_modal')?>