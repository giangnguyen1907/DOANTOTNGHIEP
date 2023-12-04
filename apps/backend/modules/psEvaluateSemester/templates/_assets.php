<script>
$(document).ready(function(){

	// Filter
	
	$('#ps_evaluate_semester_filters_school_year_id').change(function() {
		
		resetOptions('ps_evaluate_semester_filters_ps_class_id');
		$('#ps_evaluate_semester_filters_ps_class_id').select2('val','');
		
		if ($('#ps_evaluate_semester_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#ps_evaluate_semester_filters_ps_class_id").attr('disabled', 'disabled');
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_evaluate_semester_filters_ps_customer_id').val() + '&w_id=' + $('#ps_evaluate_semester_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_evaluate_semester_filters_school_year_id').val(),
	        processResults: function (data, page) {
	      		return {
	        		results: data.items
	      		};
	    	},
	    }).done(function(msg) {
	    	$('#ps_evaluate_semester_filters_ps_class_id').select2('val','');
			$("#ps_evaluate_semester_filters_ps_class_id").html(msg);
			$("#ps_evaluate_semester_filters_ps_class_id").attr('disabled', null);
	    });
	});
	
	$('#ps_evaluate_semester_filters_ps_customer_id').change(function() {
        
    	resetOptions('ps_evaluate_semester_filters_ps_workplace_id');
    	$('#ps_evaluate_semester_filters_ps_workplace_id').select2('val','');
    	$("#ps_evaluate_semester_filters_ps_workplace_id").attr('disabled', 'disabled');
    	resetOptions('ps_evaluate_semester_filters_ps_class_id');
    	$('#ps_evaluate_semester_filters_ps_class_id').select2('val','');
    	$("#ps_evaluate_semester_filters_ps_class_id").attr('disabled', 'disabled');
    	
        if ($(this).val() > 0) {
        
        	$("#ps_evaluate_semester_filters_ps_workplace_id").attr('disabled', 'disabled');
        	$("#ps_evaluate_semester_filters_ps_class_id").attr('disabled', 'disabled');
        	
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
        
            	$('#ps_evaluate_semester_filters_ps_workplace_id').select2('val','');
        
        		$("#ps_evaluate_semester_filters_ps_workplace_id").html(msg);
        
        		$("#ps_evaluate_semester_filters_ps_workplace_id").attr('disabled', null);
        
        		$("#ps_evaluate_semester_filters_ps_class_id").attr('disabled', 'disabled');
        
            });
        }		
    });
    
    $('#ps_evaluate_semester_filters_ps_workplace_id').change(function() {
    
        $("#ps_evaluate_semester_filters_ps_class_id").attr('disabled', 'disabled');
        
        $.ajax({
        	url: '<?php echo url_for('@ps_class_by_params') ?>',
            type: "POST",
            data: 'c_id=' + $('#ps_evaluate_semester_filters_ps_customer_id').val() + '&w_id=' + $('#ps_evaluate_semester_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_evaluate_semester_filters_school_year_id').val(),
            processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
        }).done(function(msg) {
        	$('#ps_evaluate_semester_filters_ps_class_id').select2('val','');
        	$("#ps_evaluate_semester_filters_ps_class_id").html(msg);
        	$("#ps_evaluate_semester_filters_ps_class_id").attr('disabled', null);
        });
    });

    $('#ps_evaluate_semester_filters_ps_school_year_id').change(function() {
		
		resetOptions('ps_evaluate_semester_filters_ps_class_id');
		$('#ps_evaluate_semester_filters_ps_class_id').select2('val','');
		
		if ($('#ps_evaluate_semester_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#ps_evaluate_semester_filters_ps_class_id").attr('disabled', 'disabled');
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_evaluate_semester_filters_ps_customer_id').val() + '&w_id=' + $('#ps_evaluate_semester_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_evaluate_semester_filters_ps_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_evaluate_semester_filters_ps_class_id').select2('val','');
			$("#ps_evaluate_semester_filters_ps_class_id").html(msg);
			$("#ps_evaluate_semester_filters_ps_class_id").attr('disabled', null);
	    });
	});

    
//     $('#ps_evaluate_semester_filters_ps_class_id').change(function() {
//     	resetOptions('ps_evaluate_semester_filters_student_id');
// 		$('#ps_evaluate_semester_filters_student_id').select2('val','');
// 		if ($('#ps_evaluate_semester_filters_ps_customer_id').val() <= 0) {
// 			return;
// 		}

// 		$("#ps_evaluate_semester_filters_student_id").attr('disabled', 'disabled');
//         $.ajax({
//        	url: '<?php //echo url_for('@ps_students_by_class_id') ?>',
//             type: "POST",
//             data: 'c_id=' + $(this).val(),
//             processResults: function (data, page) {
//           		return {
//             		results: data.items
//           		};
//         	},
//         }).done(function(msg) {
//         	$('#ps_evaluate_semester_filters_student_id').select2('val','');
//         	$("#ps_evaluate_semester_filters_student_id").html(msg);
//         	$("#ps_evaluate_semester_filters_student_id").attr('disabled', null);
//         });
    
//     });

	// end filter

	// Form
	<?php $ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()->fetchOne ()->getId (); ?>
	$('#ps_evaluate_semester_ps_customer_id').change(function() {
        
    	resetOptions('ps_evaluate_semester_ps_workplace_id');
    	$('#ps_evaluate_semester_ps_workplace_id').select2('val','');
    	$("#ps_evaluate_semester_ps_workplace_id").attr('disabled', 'disabled');
    	resetOptions('ps_evaluate_semester_ps_class_id');
    	$('#ps_evaluate_semester_ps_class_id').select2('val','');
    	$("#ps_evaluate_semester_ps_class_id").attr('disabled', 'disabled');
    	
        if ($(this).val() > 0) {
        
        	$("#ps_evaluate_semester_ps_workplace_id").attr('disabled', 'disabled');
        	$("#ps_evaluate_semester_ps_class_id").attr('disabled', 'disabled');
        	
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
        
            	$('#ps_evaluate_semester_ps_workplace_id').select2('val','');
        
        		$("#ps_evaluate_semester_ps_workplace_id").html(msg);
        
        		$("#ps_evaluate_semester_ps_workplace_id").attr('disabled', null);
        
        		$("#ps_evaluate_semester_ps_class_id").attr('disabled', 'disabled');
        
            });
        }		
    });
    
    $('#ps_evaluate_semester_ps_workplace_id').change(function() {
    
        $("#ps_evaluate_semester_ps_class_id").attr('disabled', 'disabled');
        
        $.ajax({
        	url: '<?php echo url_for('@ps_class_by_params') ?>',
            type: "POST",
            data: 'c_id=' + $('#ps_evaluate_semester_ps_customer_id').val() + '&w_id=' + $('#ps_evaluate_semester_ps_workplace_id').val() + '&y_id=' + <?php echo $ps_school_year_id; ?>,
            processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
        }).done(function(msg) {
        	$('#ps_evaluate_semester_ps_class_id').select2('val','');
        	$("#ps_evaluate_semester_ps_class_id").html(msg);
        	$("#ps_evaluate_semester_ps_class_id").attr('disabled', null);
        });
    });

    $('#ps_evaluate_semester_ps_school_year_id').change(function() {
		
		resetOptions('ps_evaluate_semester_ps_class_id');
		$('#ps_evaluate_semester_ps_class_id').select2('val','');
		
		if ($('#ps_evaluate_semester_ps_customer_id').val() <= 0) {
			return;
		}

		$("#ps_evaluate_semester_ps_class_id").attr('disabled', 'disabled');
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_evaluate_semester_ps_customer_id').val() + '&w_id=' + $('#ps_evaluate_semester_ps_workplace_id').val() + '&y_id=' + $('#ps_evaluate_semester_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_evaluate_semester_ps_class_id').select2('val','');
			$("#ps_evaluate_semester_ps_class_id").html(msg);
			$("#ps_evaluate_semester_ps_class_id").attr('disabled', null);
	    });
	});

    
    $('#ps_evaluate_semester_ps_class_id').change(function() {
    	resetOptions('ps_evaluate_semester_student_id');
		$('#ps_evaluate_semester_student_id').select2('val','');
		if ($('#ps_evaluate_semester_ps_customer_id').val() <= 0) {
			return;
		}

		$("#ps_evaluate_semester_student_id").attr('disabled', 'disabled');
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
        	$('#ps_evaluate_semester_student_id').select2('val','');
        	$("#ps_evaluate_semester_student_id").html(msg);
        	$("#ps_evaluate_semester_student_id").attr('disabled', null);
        });
    
    });

	<?php $upload_max_size = 5000; ?>
    var msg_name_file_invalid 	= '<?php

echo __ ( 'The excel file must be in the format file. File size less than %value%KB.', array (
						'%value%' => $upload_max_size ) )?>';
    var PsMaxSizeFile = '<?php echo $upload_max_size;?>';

    $('#ps-form').formValidation({
    	framework : 'bootstrap',
    	excluded : [ ':disabled' ],
    	addOns : {
    		i18n : {}
    	},
    	errorElement : "div",
    	errorClass : "help-block with-errors",
    	message : {
    		vi_VN : 'This value is not valid'
    	},
    	fields : {
    		'ps_evaluate_semester[file]' : {
    			validators : {
    				file : {
    					extension : 'xls,xlsx,jpg,png,gif,pdf,doc,docx',
    					maxSize : PsMaxSizeFile * 1024,
    					message : {
    						en_US : msg_name_file_invalid,
    						vi_VN : msg_name_file_invalid
    					}
    				}
    			}
    		}
    	}
    });
    $('#ps-form').formValidation('setLocale', PS_CULTURE);
     
    // end form
    
});
</script>