<?php use_helper('I18N', 'Number') ?>
<?php include_partial('global/include/_box_modal')?>
<?php
// Su dung bien global
sfConfig::set ( 'enableRollText', PreSchool::loadPsRoll () );
?>
<style>
<!--
#ui-datepicker-div {
	z-index: 9999 !important;
}
-->
</style>

<script>
	// msg
	var msg_invalid_amount 		= '<?php echo __('The price must be a numeric number')?>';
	var msg_invalid_number 		= '<?php echo __('Please enter a valid float number')?>';
	var msg_invalid_month_year 	= '<?php echo __('Value is invalid')?>';
	var currentText_datepicker 	= '<?php echo __('This month')?>';
	var closeText_datepicker 	= '<?php echo __('Choose')?>';

	var msg_invalid_by_number_between = '<?php echo __('The quantity must be between 1 and 100')?>';

	var msg_start_date_invalid 	= '<?php echo __('The start date is not a valid')?>';
	var msg_end_date_invalid 	= '<?php echo __('The end date is not a valid')?>';
	
	var monthNameTypeNumber = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
	
</script>

<?php if (myUser::credentialPsCustomers('PS_STUDENT_SERVICE_FILTER_SCHOOL')):?>
<script type="text/javascript">
$(document).ready(function() {
	
	if ($('#service_service_type').val() == 2) {
		$('#service_service_month').attr("disabled", false);
	}else{
		$('#service_service_month').attr("disabled", true);
	}

	$('#service_service_type').change(function() {
		if($(this).val() == 2){
			$('#service_service_month').attr("disabled", false);
		}else{
			$('#service_service_month').attr("disabled", true);
		}
	});

	$("#detail_title").on("contextmenu",function(e){
		return false;
	});		
	$('#service_filters_ps_customer_id').change(function() {
		resetOptions('service_filters_service_group_id');
		$('#service_filters_service_group_id').select2('val','');
    	
    	resetOptions('service_filters_ps_workplace_id');
    	$('#service_filters_ps_workplace_id').select2('val','');

    	$.ajax({
	        url: '<?php echo url_for('@ps_service_service_group?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'psc_id=' + $(this).val(),
	        success: function(data) {
	            $('#service_filters_service_group_id').show();
	            //$('#ajax-loader').hide();
	            $('#service_filters_service_group_id').html(data);		            		            
	        }
	    });		 	

	    $.ajax({
	    	url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' +$(this).val(),
	    	type: 'POST',
	    	data: 'psc_id='+$(this).val(),
	    	success: function(data){
	    		$('#service_filters_ps_workplace_id').html(data);
	    	}
	    });
    });

	$('#service_ps_customer_id').change(function() {
		resetOptions('service_service_group_id');
		$('#service_service_group_id').select2('val','');
    	//$('#service_filters_service_group_id').hide();
    	//$('#ajax-loader').show();
    	resetOptions('service_ps_workplace_id');
	    $('#service_ps_workplace_id').select2('val','');
    	$.ajax({
	        url: '<?php echo url_for('@ps_service_service_group?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'psc_id=' + $(this).val(),
	        success: function(data) {
	            $('#service_service_group_id').show();
	            //$('#ajax-loader').hide();
	            $('#service_service_group_id').html(data);		            		            
	        }
	    });
	    $.ajax({
	    	url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' +$(this).val(),
	    	type: 'POST',
	    	data: 'psc_id='+$(this).val(),
	    	success: function(data){
	    		$('#service_ps_workplace_id').show();
	    		$('#service_ps_workplace_id').html(data);
	    	}
	    });		

    });

	$('#service_is_kidsschool_1').change(function() {
		
		if ($('#service_is_kidsschool_1').is(":checked")) {
			$("#service_enable_roll_1").attr('checked', true);
			$("#service_enable_roll_0").attr('checked', false);
		}
		
    });
    
});
</script>
<?php endif;?>