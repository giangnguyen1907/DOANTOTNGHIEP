<?php use_helper('I18N', 'Number') ?>
<?php include_partial('global/include/_box_modal')?>

<?php
// Su dung bien global
sfConfig::set ( 'enableRollText', PreSchool::loadPsRoll () );
?>
<style>
<!--
#ui-datepicker-div {
	z-index: 100;
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

<?php if (myUser::credentialPsCustomers('PS_STUDENT_SUBJECT_FILTER_SCHOOL')):?>
<script type="text/javascript">

$(document).ready(function() {
		
		$('#service_filters_ps_customer_id').change(function() {

			resetOptions('service_filters_ps_workplace_id');
	    	$('#service_filters_ps_workplace_id').select2('val','');
	    	$("#service_filters_ps_workplace_id").attr('disabled', 'disabled');

			resetOptions('service_filters_service_group_id');
			$('#service_filters_service_group_id').select2('val','');
			$("#service_filters_service_group_id").attr('disabled', 'disabled');
			
			$.ajax({
		        url: '<?php echo url_for('@ps_service_service_group?psc_id=') ?>' + $(this).val(),
		        type: 'POST',
		        data: 'psc_id=' + $(this).val(),
		        success: function(data) {
		            $('#service_filters_service_group_id').show();
		            $('#service_filters_service_group_id').html(data);
		            $("#service_filters_service_group_id").attr('disabled', null);
		        }
		    });

			$.ajax({
		    	url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' +$(this).val(),
		    	type: 'POST',
		    	data: 'psc_id='+$(this).val(),
		    	success: function(data){
		    		$('#service_filters_ps_workplace_id').html(data);
		    		$("#service_filters_ps_workplace_id").attr('disabled', null);
		    	}
		    });
		    
	    });
		$('#service_ps_customer_id').change(function() {
			resetOptions('service_service_group_id');
	    	$('#service_service_group_id').select2('val','');
	    	$("#service_service_group_id").attr('disabled', 'disabled');

	    	resetOptions('service_ps_workplace_id');
	    	$('#service_ps_workplace_id').select2('val','');
	    	$("#service_ps_workplace_id").attr('disabled', 'disabled');
	    	
	    	$.ajax({
		        url: '<?php echo url_for('@ps_service_service_group?psc_id=') ?>' + $(this).val(),
		        type: 'POST',
		        data: 'psc_id=' + $(this).val(),
		        success: function(data) {
		            $('#service_service_group_id').show();
		            $('#service_service_group_id').html(data);
		            $("#service_service_group_id").attr('disabled', null);
		        }
		    }).done(function(msg) {

		    	$('#service_service_group_id').select2('val','');

				$("#service_service_group_id").html(msg);

				$("#service_service_group_id").attr('disabled', null);
				
		    });
	    	$.ajax({
				url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
		        type: "POST",
		        data: {'psc_id': $(this).val()},
		        success: function(data) {
		            $('#service_ps_workplace_id').show();
		            $('#service_ps_workplace_id').html(data);
		            $("#service_ps_workplace_id").attr('disabled', null);
		        }
		    }).done(function(msg) {

		    	$('#service_ps_workplace_id').select2('val','');

				$("#service_ps_workplace_id").html(msg);

				$("#service_ps_workplace_id").attr('disabled', null);
				
		    });	 	
	    });
		
	    
	    
});
</script>
<?php endif;?>