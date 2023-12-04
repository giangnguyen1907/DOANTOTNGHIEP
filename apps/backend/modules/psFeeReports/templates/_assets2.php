<?php include_partial('global/include/_box_modal_messages');?>
<?php include_partial('global/include/_box_modal');?>
<div id="errors"></div>
<style>
.checkbox_list li input[type="checkbox"] {
	width: 15px;
	height: 15px;
}
</style>
<script type="text/javascript">

var msg_select_ps_customer_id	= '<?php echo __('Please select School.')?>';

var msg_select_ps_workplace_id	= '<?php echo __('Please select Workplace.')?>';

var msg_normal_day_invalid 		= '<?php echo __('Please enter a value for a valid normal number of days.')?>';

var msg_saturday_day_invalid 	= '<?php echo __('Please enter a value for the number of days that have a valid Saturday.')?>';

$(document).ready(function() {

	$('#control_filter_ps_customer_id').change(function() {

		resetOptions('control_filter_ps_workplace_id');
		
		$("#control_filter_ps_workplace_id").attr('disabled', 'disabled');

		$('#control_filter_ps_class_id').select2('val','');
		
		$.ajax({

	        url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
	        success: function(data) {
	        	$('#control_filter_ps_workplace_id').select2('val','');
				$('#control_filter_ps_workplace_id').html(data);
				$("#control_filter_ps_workplace_id").attr('disabled', null);				
	        }
		});
	});

	/*
	$('#control_filter_year_month').change(function() {
		
		if (!$('#control_filter_year_month').val())
			return false;
		
		$.ajax({
	        //url: '<?php //echo url_for('@ps_fee_reports_filter_number_day_by_month');?>',
	        type: 'POST',
	        data: 'to_month=' + $('#control_filter_year_month').val(),
	        success: function(data) {
	        	$('#number_day').html(data);				
	        }
		});
	});
	*/
	
	$('.btn-prev-step2').click(function(){
		var value = $('#ps-filter-form').attr("action", "<?php echo url_for('@ps_fee_reports_control_step1')?>");
    	$('#ps-filter-form').submit();
		return true;
	});

	$('.btn-prev-step3').click(function(){
		var value = $('#fstep3').attr("action", "<?php echo url_for('@ps_fee_reports_control_step2')?>");
    	//$('#fstep3').submit();
		return true;
	});

	$('.btn-prev-step4').click(function(){
		var value = $('#fstep4').attr("action", "<?php echo url_for('@ps_fee_reports_control_step3')?>");
    	return true;
	});

	$('#fstep4 .btn-next').click(function(){

		var value = $('#fstep4').attr("action", "<?php echo url_for('@ps_fee_reports_control_step5')?>");
		

    	return true;
	});

	// Run bao phi su dung
	$('#fstep4 .btn-next').click(function(){
		
		// Call to action process fee
    	$('#ic_status_processing').show();	

    	//$('#box_content').style.opacity = 0.5;
    	//var element = document.getElementById('box_content');
    	//element.style.opacity = "0.5";    	
    	//$('#box_content').hide();

    	$.ajax({
			url: '<?php echo url_for('@ps_fee_reports_control_step5')?>',
	        type: "POST",
	        data: $("#fstep4").serialize(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ic_status_processing').hide();
	    	$("#box_content").html(msg);   	
	    });		
	});
	
});
</script>