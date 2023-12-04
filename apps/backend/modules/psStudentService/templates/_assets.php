<?php include_partial('global/include/_box_modal_messages');?>
<script>
$(document).ready(function() {
// filter statistic
	$('#student_filter_ps_customer_id').change(function() {

		resetOptions('student_filter_ps_workplace_id');
		$('#student_filter_ps_workplace_id').select2('val','');
		$("#student_filter_ps_workplace_id").attr('disabled', 'disabled');
		resetOptions('student_filter_class_id');
		$('#student_filter_class_id').select2('val','');
		$("#student_filter_class_id").attr('disabled', 'disabled');
		
	if ($(this).val() > 0) {

		$("#student_filter_ps_workplace_id").attr('disabled', 'disabled');
		$("#student_filter_class_id").attr('disabled', 'disabled');
		
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

	    	$('#student_filter_ps_workplace_id').select2('val','');

			$("#student_filter_ps_workplace_id").html(msg);

			$("#student_filter_ps_workplace_id").attr('disabled', null);

			$("#student_filter_class_id").attr('disabled', 'disabled');

	    });
	}		
});
 
    $('#student_filter_ps_workplace_id').change(function() {
    	
    	$("#student_filter_class_id").attr('disabled', 'disabled');
    	
    	$.ajax({
    		url: '<?php echo url_for('@ps_class_by_params') ?>',
            type: "POST",
            data: 'c_id=' + $('#student_filter_ps_customer_id').val() + '&w_id=' + $('#student_filter_ps_workplace_id').val() + '&y_id=' + $('#student_filter_ps_school_year_id').val(),
            processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
        }).done(function(msg) {
        	$('#student_filter_class_id').select2('val','');
    		$("#student_filter_class_id").html(msg);
    		$("#student_filter_class_id").attr('disabled', null);
        });
    });
    
    $('#student_filter_ps_school_year_id').change(function() {
    	
    	resetOptions('student_filter_class_id');
    	$('#student_filter_class_id').select2('val','');
    	
    	if ($('#student_filter_ps_customer_id').val() <= 0) {
    		return;
    	}
    
    	$("#student_filter_class_id").attr('disabled', 'disabled');
    	$.ajax({
    		url: '<?php echo url_for('@ps_class_by_params') ?>',
            type: "POST",
            data: 'c_id=' + $('#student_filter_ps_customer_id').val() + '&w_id=' + $('#student_filter_ps_workplace_id').val() + '&y_id=' + $('#student_filter_ps_school_year_id').val(),
            processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
        }).done(function(msg) {
        	$('#student_filter_class_id').select2('val','');
    		$("#student_filter_class_id").html(msg);
    		$("#student_filter_class_id").attr('disabled', null);
        });
	});

    $('.btn-delete-service').click(function() {
		var item_id = $(this).attr('data-item');		
		$('#ps-form-delete-service').attr('action', '<?php echo url_for('@ps_student_service')?>/' + item_id);
	});
	
    $('#student_service_filters_ps_customer_id').change(function() {

		resetOptions('student_service_filters_ps_workplace_id');
		$('#student_service_filters_ps_workplace_id').select2('val','');
		$("#student_service_filters_ps_workplace_id").attr('disabled', 'disabled');
		resetOptions('student_service_filters_ps_class_id');
		$('#student_service_filters_ps_class_id').select2('val','');
		$("#student_service_filters_ps_class_id").attr('disabled', 'disabled');
		resetOptions('student_service_filters_service_id');
		$('#student_service_filters_service_id').select2('val','');
		$("#student_service_filters_service_id").attr('disabled', 'disabled');
		if ($(this).val() > 0) {
	
			$("#student_service_filters_ps_workplace_id").attr('disabled', 'disabled');
			$("#student_service_filters_ps_class_id").attr('disabled', 'disabled');
			$("#student_service_filters_service_id").attr('disabled', 'disabled');
			
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
	
		    	$('#student_service_filters_ps_workplace_id').select2('val','');
	
				$("#student_service_filters_ps_workplace_id").html(msg);
	
				$("#student_service_filters_ps_workplace_id").attr('disabled', null);
	
				$("#student_service_filters_class_id").attr('disabled', 'disabled');
	
		    });

			$.ajax({
	    		url: '<?php echo url_for('@ps_student_service_load_ajax') ?>',
	            type: "POST",
	            data: 'c_id=' + $('#student_service_filters_ps_customer_id').val() + '&w_id=' + $('#student_service_filters_ps_workplace_id').val() + '&y_id=' + $('#student_service_filters_school_year_id').val(),
	            processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
	        }).done(function(msg) {
	        	$('#student_service_filters_service_id').select2('val','');
	    		$("#student_service_filters_service_id").html(msg);
	    		$("#student_service_filters_service_id").attr('disabled', null);
	        });
	        
			$.ajax({
	    		url: '<?php echo url_for('@ps_class_by_params') ?>',
	            type: "POST",
	            data: 'c_id=' + $('#student_service_filters_ps_customer_id').val() + '&w_id=' + $('#student_service_filters_ps_workplace_id').val() + '&y_id=' + $('#student_service_filters_school_year_id').val(),
	            processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
	        }).done(function(msg) {
	        	$('#student_service_filters_ps_class_id').select2('val','');
	    		$("#student_service_filters_ps_class_id").html(msg);
	    		$("#student_service_filters_ps_class_id").attr('disabled', null);
	        });
		}		
	});

	$('#student_service_filters_ps_workplace_id').change(function() {
    	
    	$("#student_service_filters_ps_class_id").attr('disabled', 'disabled');
    	
    	$.ajax({
    		url: '<?php echo url_for('@ps_class_by_params') ?>',
            type: "POST",
            data: 'c_id=' + $('#student_service_filters_ps_customer_id').val() + '&w_id=' + $('#student_service_filters_ps_workplace_id').val() + '&y_id=' + $('#student_service_filters_school_year_id').val(),
            processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
        }).done(function(msg) {
        	$('#student_service_filters_ps_class_id').select2('val','');
    		$("#student_service_filters_ps_class_id").html(msg);
    		$("#student_service_filters_ps_class_id").attr('disabled', null);
        });

    });

	$('#student_service_filters_school_year_id').change(function() {
    	
    	resetOptions('student_service_filters_ps_class_id');
    	$('#student_service_filters_ps_class_id').select2('val','');
    	
    	if ($('#student_service_filters_ps_customer_id').val() <= 0) {
    		return;
    	}
    
    	$("#student_service_filters_ps_class_id").attr('disabled', 'disabled');
    	$.ajax({
    		url: '<?php echo url_for('@ps_class_by_params') ?>',
            type: "POST",
            data: 'c_id=' + $('#student_service_filters_ps_customer_id').val() + '&w_id=' + $('#student_service_filters_ps_workplace_id').val() + '&y_id=' + $('#student_service_filters_school_year_id').val(),
            processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
        }).done(function(msg) {
        	$('#student_service_filters_ps_class_id').select2('val','');
    		$("#student_service_filters_ps_class_id").html(msg);
    		$("#student_service_filters_ps_class_id").attr('disabled', null);
        });
	});

	var msg_select_ps_customer_id	= '<?php echo __('Please select school to filter the data.')?>';
	var msg_select_ps_service_id 	= '<?php echo __('Please select service to filter the data.')?>';
	var msg_select_ps_schoolyear_id	= '<?php echo __('Please select school year to filter the data.')?>';
	
	$('#ps-filter').formValidation({
		framework : 'bootstrap',
		excluded: [':disabled', ':hidden', ':not(:visible)'],
		addOns : {
			i18n : {}
		},
		err : {
			container: '#errors'
		},
		message : {
			vi_VN : 'This value is not valid'
		},
		icon : {},
		fields : {
			"student_service_filters[ps_customer_id]": {
				validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_customer_id,
                        		  en_US: msg_select_ps_customer_id
                        }
                    }
                }
            },
            
            "student_service_filters[school_year_id]": {
            	validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_schoolyear_id,
                        		  en_US: msg_select_ps_schoolyear_id
                        }
                    }
                }
            },
            
            "student_service_filters[service_id]": {
            	validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_service_id,
                        		  en_US: msg_select_ps_service_id
                        }
                    }
                }
            },
            
		}
	})
	.on('err.form.fv', function(e) {
		// Show the message modal
		$('#messageModal').modal('show');
	});

	$('#ps-filter').formValidation('setLocale', PS_CULTURE);
	
});
</script>
<?php include_partial('psStudentService/box_modal_confirm_remover_service');?>