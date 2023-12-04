<?php use_helper('I18N', 'Number')?>
<?php include_partial('global/include/_box_modal_messages');?>
<?php include_partial('global/include/_box_modal');?>
<script type="text/javascript">
//msg
var msg_select_ps_customer_id	= '<?php echo __('Please select school to filter the data.')?>';
var msg_select_ps_service_id 	= '<?php echo __('Please select subject to to filter the data.')?>';
var msg_ps_service_course_id 	= '<?php echo __('Please select course to to filter the data.')?>';
var msg_select_date 			= '<?php echo __('Please enter date to filter the data.')?>';
var msg_select_ps_service_course_schedule_id	= '<?php echo __('Please select schedule to to filter the data.')?>';

$(document).ready(function() {
	// BEGIN: filters
	
	<?php if (myUser::credentialPsCustomers('PS_STUDENT_SERVICE_COURSE_COMMENT_FILTER_SCHOOL')):?>
	$('#student_service_course_comment_filters_ps_customer_id').change(function() {
		
		resetOptions('student_service_course_comment_filters_ps_service_id');
		
        	if ($('#student_service_course_comment_filters_ps_customer_id').val() <= 0) {
    			return;
    		}
    		
    		$("#student_service_course_comment_filters_ps_service_id").attr('disabled', 'disabled');			
    		$('#student_service_course_comment_filters_ps_workplace_id').attr('disabled', 'disabled');

    		$.ajax({
    	        url: '<?php echo url_for('@ps_service_courses_by_ps_customer') ?>',
    	        type: "POST",
    	        data: {'psc_id': $(this).val()},
    	        processResults: function (data, page) {
              		return {
                		results: data.items
              		};
            	},
    	    }).done(function(msg) {
    	    	$('#student_service_course_comment_filters_ps_service_id').select2('val','');
    			$("#student_service_course_comment_filters_ps_service_id").html(msg);
    			$("#student_service_course_comment_filters_ps_service_id").attr('disabled', null);
    	    });

    	    $.ajax({
    	    	url: '<?php echo url_for('@ps_work_places_by_customer') ?>',
    	    	type: 'POST',
    	    	data: {'psc_id': $(this).val()}
    	    }).done(function(msg){
    	    	$('#student_service_course_comment_filters_ps_workplace_id').select2('val','');
    	    	$('#student_service_course_comment_filters_ps_workplace_id').html(msg);
    	    	$('#student_service_course_comment_filters_ps_workplace_id').attr('disabled', null);
    	    });
    	});
	<?php endif;?>

	$('#student_service_course_comment_filters_ps_service_id').change(function() {

		resetOptions('student_service_course_comment_filters_ps_service_course_id');

		if ($('#student_service_course_comment_filters_ps_service_id').val() <= 0) {
     		return;
 		}
		
 		$("#student_service_course_comment_filter_ps_service_course_id").attr('disabled', 'disabled');
 		
 		$.ajax({
 	        url: '<?php echo url_for('@ps_service_course_by_ps_service?sid=') ?>' + $(this).val(),
 	        type: 'POST',
 	        data: 'f=<?php echo md5(time().time().time().time())?>&sid=' + $(this).val(),
 	        success: function(data) {
 	        	$('#student_service_course_comment_filters_ps_service_course_id').select2('val','');
 				$('#student_service_course_comment_filters_ps_service_course_id').html(data);
 				$("#student_service_course_comment_filters_ps_service_course_id").attr('disabled',null);
 	        }
 		});
 	});

	 $('#student_service_course_comment_filters_ps_service_course_id').change(function() {
		    
     	resetOptions('student_service_course_comment_filters_ps_service_course_schedule_id');
     	
 		if ($('#student_service_course_comment_filters_ps_service_course_id').val() <= 0) {	     			
 			return;
 		}

 		$("#student_service_course_comment_filters_ps_service_course_schedule_id").attr('disabled', 'disabled');
	 		
 		$.ajax({
 	        url: '<?php echo url_for('@ps_service_course_schedule_by_ps_service_course?sc_id=') ?>' + $('#student_service_course_comment_filters_ps_service_course_id').val()+'&tracked_at='+$('#student_service_course_comment_filters_tracked_at').val(),
 	        type: 'POST',
 	        data: 'f=<?php echo md5(time().time().time().time())?>sid=' + $('#student_service_course_comment_filters_ps_service_course_id').val()+'&tracked_at='+$('#student_service_course_comment_filters_tracked_at').val(),
 	        success: function(data) {
 	        	$('#student_service_course_comment_filters_ps_service_course_schedule_id').select2('val','');
 				$('#student_service_course_comment_filters_ps_service_course_schedule_id').html(data);
 				$("#student_service_course_comment_filters_ps_service_course_schedule_id").attr('disabled',null);
 	        }
 		});
	 });
	
	 $('#student_service_course_comment_filters_tracked_at').change(function() {
		    
	    resetOptions('student_service_course_comment_filters_ps_service_course_schedule_id');
	    
	    $("#student_service_course_comment_filters_ps_service_course_schedule_id").attr('disabled', 'disabled');

	 	$.ajax({
	 	        url: '<?php echo url_for('@ps_service_course_schedule_by_ps_service_course?sc_id=') ?>' + $('#student_service_course_comment_filters_ps_service_course_id').val()+'&tracked_at='+$('#student_service_course_comment_filters_tracked_at').val(),
	 	        type: 'POST',
	 	        data: 'f=<?php echo md5(time().time().time().time())?>sid=' + $('#student_service_course_comment_filters_ps_service_course_id').val()+'&tracked_at='+$('#student_service_course_comment_filters_tracked_at').val(),
	 	        success: function(data) {
	 	        	//$('#student_service_course_comment_filters_ps_service_course_schedule_id').select2('val','');
	 				$('#student_service_course_comment_filters_ps_service_course_schedule_id').html(data);
	 				$("#student_service_course_comment_filters_ps_service_course_schedule_id").attr('disabled',null);
	 	        }
	 		});	 		
	 	});

	 	$('#ps_service_id').change(function(){
	     	resetOptions('ps_service_course_id');
	     	
	 		//$('#student_service_course_comment_filters_ps_service_course_schedule_id').select2('val','');
	 	
	 		if ($('#ps_service_id').val() <= 0) {	     			
	 			return;
	 		}

			$("#ps_service_course_id").attr('disabled', 'disabled');
	 		
	 		$.ajax({
	 	        url: '<?php echo url_for('@ps_service_course_by_ps_service?sid=') ?>' + $(this).val(),
	 	        type: 'POST',
	 	        data: 'f=<?php echo md5(time().time().time().time())?>sid=' + $(this).val(),
	 	        success: function(data) {
	 	        	$('#ps_service_course_id').select2('val','');
	 				$('#ps_service_course_id').html(data);
	 				$("#ps_service_course_id").attr('disabled',null);
	 	        }
	 		});

	 	}); 

		// END: filters	
		
		$('#history_filter_ps_customer_id').change(function() {

			resetOptions('history_filter_ps_workplace_id');
			$('#history_filter_ps_workplace_id').select2('val','');
			$("#history_filter_ps_workplace_id").attr('disabled', 'disabled');
			resetOptions('history_filter_class_id');
			$('#history_filter_class_id').select2('val','');
			$("#history_filter_class_id").attr('disabled', 'disabled');
			
		if ($(this).val() > 0) {

			$("#history_filter_ps_workplace_id").attr('disabled', 'disabled');
			$("#history_filter_class_id").attr('disabled', 'disabled');
			
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

		    	$('#history_filter_ps_workplace_id').select2('val','');

				$("#history_filter_ps_workplace_id").html(msg);

				$("#history_filter_ps_workplace_id").attr('disabled', null);

				$("#history_filter_class_id").attr('disabled', 'disabled');

		    });
		}		
	});
	 
	$('#history_filter_ps_workplace_id').change(function() {
		
		$("#history_filter_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#history_filter_ps_customer_id').val() + '&w_id=' + $('#history_filter_ps_workplace_id').val() + '&y_id=' + $('#history_filter_ps_school_year_id').val(),
	        processResults: function (data, page) {
	      		return {
	        		results: data.items
	      		};
	    	},
	    }).done(function(msg) {
	    	$('#history_filter_class_id').select2('val','');
			$("#history_filter_class_id").html(msg);
			$("#history_filter_class_id").attr('disabled', null);
	    });
	});

	$('#history_filter_class_id').change(function() {
		
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
	    	$('#history_filter_student_id').select2('val','');
			$("#history_filter_student_id").html(msg);
			$("#history_filter_student_id").attr('disabled', null);
		});

	});   
});

function resetOptions(select_id) {
	var selectBox = document.getElementById(select_id);

	for (var i = selectBox.length - 1; i >= 0; --i) {
		if (selectBox[i].value > 0) {
			selectBox.remove(i);
		}
	}

	$('#' + select_id).select2('val', '');
}

function toggleText_radio(bid,value){

	if (value.checked) {
		$('#textbox_radio_' + bid ).attr('disabled', false);
	} else {
		$('#textbox_radio_' + bid ).attr('disabled', true);
	}	
}

function submit_Click() {	

	if (!CheckTextbox()) {    		
		
		$("#errors").html("<?php echo __('Comment is required !')?>");

	    $('#warningModal').modal({show: true,backdrop:'static'});

	    return false;
	}
}

function CheckTextbox() {
	
	var boxes = document.getElementsByTagName('input');
	
	for (i = 0; i < boxes.length; i++ ) {		 
			box = boxes[i];
			if ( box.type == 'text' && box.title == '<?php echo __('Enter comment')?>' && box.disabled == false ) {
				 if(box.value == '')
					 return false;		
				
		  	}
		  }
  return true;		   
}
</script>
