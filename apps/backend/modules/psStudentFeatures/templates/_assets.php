<?php include_partial('global/include/_box_modal_messages');?>
<script type="text/javascript">
$(document).ready(function() {

	// BEGIN: filters
	$('#student_feature_filters_ps_customer_id').change(function() {

		resetOptions('student_feature_filters_feature_branch_id');
		$('#student_feature_filters_feature_branch_id').select2('val','');

		resetOptions('student_feature_filters_ps_workplace_id');
		$('#student_feature_filters_ps_workplace_id').select2('val','');
		$("#student_feature_filters_ps_workplace_id").attr('disabled', 'disabled');

		resetOptions('student_feature_filters_ps_class_id');
		$('#student_feature_filters_ps_class_id').select2('val','');
		
		if ($(this).val() <= 0) {
			return;
		}
		
		if ($(this).val() > 0) {

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

		    	$('#student_feature_filters_ps_workplace_id').select2('val','');

				$("#student_feature_filters_ps_workplace_id").html(msg);

				$("#student_feature_filters_ps_workplace_id").attr('disabled', null);
		    });	
		}		
	});
	 
	$('#student_feature_filters_ps_workplace_id').change(function() {
		
		resetOptions('student_feature_filters_feature_branch_id');
		$('#student_feature_filters_feature_branch_id').select2('val','');
		
		resetOptions('student_feature_filters_ps_class_id');
		$('#student_feature_filters_ps_class_id').select2('val','');

		$("#student_feature_filters_ps_class_id").attr('disabled', 'disabled');

		if ($('#student_feature_filters_ps_customer_id').val() <= 0 || $(this).val() <= 0) {
			return;
		}
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#student_feature_filters_ps_customer_id').val() + '&w_id=' + $('#student_feature_filters_ps_workplace_id').val() + '&y_id=' + $('#student_feature_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#student_feature_filters_ps_class_id').select2('val','');
			$("#student_feature_filters_ps_class_id").html(msg);
			$("#student_feature_filters_ps_class_id").attr('disabled', null);
	    });
	});

	$('#student_feature_filters_ps_class_id').change(function() {
		
		resetOptions('student_feature_filters_feature_branch_id');
		
		$('#student_feature_filters_feature_branch_id').select2('val','');
		
		$("#student_feature_filters_feature_branch_id").attr('disabled', 'disabled');
		
		if ($('#student_feature_filters_ps_customer_id').val() <= 0 || $(this).val() <= 0) {
			return;
		}
				
		$.ajax({
			url: '<?php echo url_for('@ps_feature_branch_by_class_params') ?>',
			type: "POST",
			data: $("#ps-filter-student-features").serialize(),
			processResults: function (data, page) {
				return {
				  results: data.items  
				};
			},
		}).done(function(msg) {
			$('#student_feature_filters_feature_branch_id').select2('val','');
			$("#student_feature_filters_feature_branch_id").html(msg);
			$("#student_feature_filters_feature_branch_id").attr('disabled', null);               
		});		
	});
	
	$('#student_feature_filters_school_year_id').change(function() {
		
		resetOptions('student_feature_filters_ps_class_id');
		
		$('#student_feature_filters_ps_class_id').select2('val','');
		
		if ($('#student_feature_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#student_feature_filters_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#student_feature_filters_ps_customer_id').val() + '&w_id=' + $('#student_feature_filters_ps_workplace_id').val() + '&y_id=' + $('#student_feature_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#student_feature_filters_ps_class_id').select2('val','');
	    	
			$("#student_feature_filters_ps_class_id").html(msg);

			$("#student_feature_filters_ps_class_id").attr('disabled', null);
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

	resetOptions('history_filter_class_id');
	$('#history_filter_class_id').select2('val','');

	$("#history_filter_class_id").attr('disabled', 'disabled');

	if ($('#history_filter_ps_customer_id').val() <= 0 || $(this).val() <= 0) {
		return;
	}
	
	//$("#history_filter_class_id").attr('disabled', 'disabled');
	
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
// get my class filter by school year
    
});

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
