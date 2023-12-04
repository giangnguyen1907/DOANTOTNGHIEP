<?php include_partial('global/include/_box_modal_messages');?>
<style>
.datepicker {
	z-index: 1051 !important;
}

.ui-datepicker {
	z-index: 1051 !important;
}
</style>
<script>
	// msg
	var msg_select_ps_customer_id	= '<?php echo __('Please select School to filter the data.')?>';
	var msg_select_ps_class_id 		= '<?php echo __('Please select class to to filter the data.')?>';
	var msg_select_school_year 		= '<?php echo __('Please select school year to to filter the data.')?>';
	var msg_select_date 			= '<?php echo __('Please enter dates to filter the data.')?>';

	var msg_select_student 			= '<?php echo __('Please select students from the list of parties to enter data.')?>';
	var msg_select_login_relative 	= '<?php echo __('Please select relatives to enter data.')?>';
	var msg_select_teacher_received = '<?php echo __('Please select a teacher to pick up your child.')?>';
	
</script>

<script>
$(document).ready(function() {

	$('.btn-delay-logtime').click(function() {
		
		var student_id = $(this).attr('data-value');
		
		var note = $('#note_' + student_id).val();

		var relative = $('#select_' + student_id).val();

		var logout_at = $('#logout_at_' + student_id).val();

		var lt_id = $('#lt_id_' + student_id).val();

		var date_at = $('#ps_logtimes_delay_date_time').val();
		
		//alert(date_at);
		
		if (student_id <= 0 || logout_at == '' || relative <= 0) {
			alert('<?php echo __("Unknow relative")?>');
			return false;
		}

		$('#ic-loading-' + student_id).show();		
		$.ajax({
	        url: '<?php echo url_for('@ps_logtime_save_delay') ?>',
	        type: 'POST',
	        data: 'student_id=' + student_id + '&relative=' + relative + '&logout_at=' + logout_at + '&note=' + note + '&lt_id=' + lt_id + '&date_at=' + date_at,
	        success: function(data) {
	        	$('#ic-loading-' + student_id).hide();
	        	$('#box-' + student_id).html(data);
	        },
	        error: function (request, error) {
	            alert(" Can't do because: " + error);
	        },
		});
	    
  	});

	
	$('#logtimes_filter_ps_school_year_id').change(function() {

		resetOptions('logtimes_filter_year_month');
		$('#logtimes_filter_year_month').select2('val','');
		if ($(this).val() > 0) {
				
		$("#logtimes_filter_year_month").attr('disabled', 'disabled');
		$("#ps_logtimes_filters_ps_class_id").attr('disabled', 'disabled');

		$.ajax({
			url: '<?php echo url_for('@ps_year_month?ym_id=') ?>' + $(this).val(),
	        type: "POST",
	        data: {'ym_id': $(this).val()},
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
		    }).done(function(msg) {
		    	$('#logtimes_filter_year_month').select2('val','');
				$("#logtimes_filter_year_month").html(msg);
				$("#logtimes_filter_year_month").attr('disabled', null);
		    });
		}
	});

	// BEGIN: filters
	$('#ps_logtimes_filters_ps_customer_id').change(function() {
	
		resetOptions('ps_logtimes_filters_ps_workplace_id');
		$('#ps_logtimes_filters_ps_workplace_id').select2('val','');
		resetOptions('ps_logtimes_filters_ps_class_id');
		$('#ps_logtimes_filters_ps_class_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#ps_logtimes_filters_ps_workplace_id").attr('disabled', 'disabled');
			$("#ps_logtimes_filters_ps_class_id").attr('disabled', 'disabled');
			
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

		    	$('#ps_logtimes_filters_ps_workplace_id').select2('val','');

				$("#ps_logtimes_filters_ps_workplace_id").html(msg);

				$("#ps_logtimes_filters_ps_workplace_id").attr('disabled', null);

				$("#ps_logtimes_filters_ps_class_id").attr('disabled', 'disabled');

				$.ajax({
					url: '<?php echo url_for('@ps_class_by_params') ?>',
			        type: "POST",
			        data: 'c_id=' + $('#ps_logtimes_filters_ps_customer_id').val() + '&w_id=' + $('#ps_logtimes_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_logtimes_filters_school_year_id').val(),
			        processResults: function (data, page) {
		          		return {
		            		results: data.items
		          		};
		        	},
			    }).done(function(msg) {
			    	$('#ps_logtimes_filters_ps_class_id').select2('val','');
					$("#ps_logtimes_filters_ps_class_id").html(msg);
					$("#ps_logtimes_filters_ps_class_id").attr('disabled', null);
			    });
		    });
		}		
	});
	 
	$('#ps_logtimes_filters_ps_workplace_id').change(function() {
		resetOptions('ps_logtimes_filters_ps_class_id');
		$('#ps_logtimes_filters_ps_class_id').select2('val','');
		
		if ($('#ps_logtimes_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#ps_logtimes_filters_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_logtimes_filters_ps_customer_id').val() + '&w_id=' + $('#ps_logtimes_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_logtimes_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_logtimes_filters_ps_class_id').select2('val','');
			$("#ps_logtimes_filters_ps_class_id").html(msg);
			$("#ps_logtimes_filters_ps_class_id").attr('disabled', null);
	    });
	});

	$('#ps_logtimes_filters_school_year_id').change(function() {
		
		resetOptions('ps_logtimes_filters_ps_class_id');
		$('#ps_logtimes_filters_ps_class_id').select2('val','');
		
		if ($('#ps_logtimes_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#ps_logtimes_filters_ps_class_id").attr('disabled', 'disabled');
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_logtimes_filters_ps_customer_id').val() + '&w_id=' + $('#ps_logtimes_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_logtimes_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_logtimes_filters_ps_class_id').select2('val','');
			$("#ps_logtimes_filters_ps_class_id").html(msg);
			$("#ps_logtimes_filters_ps_class_id").attr('disabled', null);
	    });
	});

	// END: filters
	// filter statistic
	$('#logtimes_filter_ps_customer_id').change(function() {

		resetOptions('logtimes_filter_ps_workplace_id');
		$('#logtimes_filter_ps_workplace_id').select2('val','');
		$("#logtimes_filter_ps_workplace_id").attr('disabled', 'disabled');
		resetOptions('logtimes_filter_class_id');
		$('#logtimes_filter_class_id').select2('val','');
		$("#logtimes_filter_class_id").attr('disabled', 'disabled');
		
	if ($(this).val() > 0) {

		$("#logtimes_filter_ps_workplace_id").attr('disabled', 'disabled');
		$("#logtimes_filter_class_id").attr('disabled', 'disabled');
		
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

	    	$('#logtimes_filter_ps_workplace_id').select2('val','');

			$("#logtimes_filter_ps_workplace_id").html(msg);

			$("#logtimes_filter_ps_workplace_id").attr('disabled', null);

			$("#logtimes_filter_class_id").attr('disabled', 'disabled');

	    });
	}		
});
 
$('#logtimes_filter_ps_workplace_id').change(function() {
	
	$("#logtimes_filter_class_id").attr('disabled', 'disabled');
	
	$.ajax({
		url: '<?php echo url_for('@ps_class_by_params') ?>',
        type: "POST",
        data: 'c_id=' + $('#logtimes_filter_ps_customer_id').val() + '&w_id=' + $('#logtimes_filter_ps_workplace_id').val() + '&y_id=' + $('#logtimes_filter_ps_school_year_id').val(),
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
    }).done(function(msg) {
    	$('#logtimes_filter_class_id').select2('val','');
		$("#logtimes_filter_class_id").html(msg);
		$("#logtimes_filter_class_id").attr('disabled', null);
    });
});

$('#logtimes_filter_ps_school_year_id').change(function() {
	
	resetOptions('logtimes_filter_class_id');
	$('#logtimes_filter_class_id').select2('val','');
	
	if ($('#logtimes_filter_ps_customer_id').val() <= 0) {
		return;
	}

	$("#logtimes_filter_class_id").attr('disabled', 'disabled');
	$.ajax({
		url: '<?php echo url_for('@ps_class_by_params') ?>',
        type: "POST",
        data: 'c_id=' + $('#logtimes_filter_ps_customer_id').val() + '&w_id=' + $('#logtimes_filter_ps_workplace_id').val() + '&y_id=' + $('#logtimes_filter_ps_school_year_id').val(),
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
    }).done(function(msg) {
    	$('#logtimes_filter_class_id').select2('val','');
		$("#logtimes_filter_class_id").html(msg);
		$("#logtimes_filter_class_id").attr('disabled', null);
    });
});


//filter deleted

$('#logtimes_deleted_ps_customer_id').change(function() {
        
        	resetOptions('logtimes_deleted_ps_workplace_id');
        	$('#logtimes_deleted_ps_workplace_id').select2('val','');
        	$("#logtimes_deleted_ps_workplace_id").attr('disabled', 'disabled');
        	resetOptions('logtimes_deleted_class_id');
        	$('#logtimes_deleted_class_id').select2('val','');
        	$("#logtimes_deleted_class_id").attr('disabled', 'disabled');
        	
            if ($(this).val() > 0) {
            
            	$("#logtimes_deleted_ps_workplace_id").attr('disabled', 'disabled');
            	$("#logtimes_deleted_class_id").attr('disabled', 'disabled');
            	
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
            
                	$('#logtimes_deleted_ps_workplace_id').select2('val','');
            
            		$("#logtimes_deleted_ps_workplace_id").html(msg);
            
            		$("#logtimes_deleted_ps_workplace_id").attr('disabled', null);
            
            		$("#logtimes_deleted_class_id").attr('disabled', 'disabled');
            
                });
            }		
        });
        
        $('#logtimes_deleted_ps_workplace_id').change(function() {
        
            $("#logtimes_deleted_class_id").attr('disabled', 'disabled');
            
            $.ajax({
            	url: '<?php echo url_for('@ps_class_by_params') ?>',
                type: "POST",
                data: 'c_id=' + $('#logtimes_deleted_ps_customer_id').val() + '&w_id=' + $('#logtimes_deleted_ps_workplace_id').val() + '&y_id=' + $('#logtimes_deleted_ps_school_year_id').val(),
                processResults: function (data, page) {
              		return {
                		results: data.items
              		};
            	},
            }).done(function(msg) {
            	$('#logtimes_deleted_class_id').select2('val','');
            	$("#logtimes_deleted_class_id").html(msg);
            	$("#logtimes_deleted_class_id").attr('disabled', null);
            });
        });

        $('#logtimes_deleted_ps_school_year_id').change(function() {
    		
    		resetOptions('logtimes_deleted_class_id');
    		$('#logtimes_deleted_class_id').select2('val','');
    		
    		if ($('#logtimes_deleted_ps_customer_id').val() <= 0) {
    			return;
    		}

    		$("#logtimes_deleted_class_id").attr('disabled', 'disabled');
    		$.ajax({
    			url: '<?php echo url_for('@ps_class_by_params') ?>',
    	        type: "POST",
    	        data: 'c_id=' + $('#logtimes_deleted_ps_customer_id').val() + '&w_id=' + $('#logtimes_deleted_ps_workplace_id').val() + '&y_id=' + $('#logtimes_deleted_ps_school_year_id').val(),
    	        processResults: function (data, page) {
              		return {
                		results: data.items
              		};
            	},
    	    }).done(function(msg) {
    	    	$('#logtimes_deleted_class_id').select2('val','');
    			$("#logtimes_deleted_class_id").html(msg);
    			$("#logtimes_deleted_class_id").attr('disabled', null);
    	    });
    	});

        $('#logtimes_deleted_class_id').change(function() {
        	resetOptions('logtimes_deleted_student_id');
    		$('#logtimes_deleted_student_id').select2('val','');
    		if ($('#logtimes_deleted_ps_customer_id').val() <= 0) {
    			return;
    		}

    		$("#logtimes_deleted_student_id").attr('disabled', 'disabled');
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
            	$('#logtimes_deleted_student_id').select2('val','');
            	$("#logtimes_deleted_student_id").html(msg);
            	$("#logtimes_deleted_student_id").attr('disabled', null);
            });
        
        });

        $('#logtimes_deleted_date_at').datepicker({
    		dateFormat : 'dd-mm-yy',
    		maxDate : new Date(),
    		prevText : '<i class="fa fa-chevron-left"></i>',
    		nextText : '<i class="fa fa-chevron-right"></i>',
    		changeMonth : true,
    		changeYear : true,
    	})


//BEGIN: filters_delay
$('#delay_filter_ps_customer_id').change(function() {

	resetOptions('delay_filter_ps_workplace_id');
	$('#delay_filter_ps_workplace_id').select2('val','');
	
	if ($(this).val() > 0) {

		$("#delay_filter_ps_workplace_id").attr('disabled', 'disabled');
		
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

	    	$('#delay_filter_ps_workplace_id').select2('val','');

			$('#delay_filter_ps_workplace_id').html(msg);

			$('#delay_filter_ps_workplace_id').attr('disabled', null);

	    });
	}		
});

// filter history

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

        $('#history_filter_ps_school_year_id').change(function() {
    		
    		resetOptions('history_filter_class_id');
    		$('#history_filter_class_id').select2('val','');
    		
    		if ($('#history_filter_ps_customer_id').val() <= 0) {
    			return;
    		}

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
        	resetOptions('history_filter_student_id');
    		$('#history_filter_student_id').select2('val','');
    		if ($('#history_filter_ps_customer_id').val() <= 0) {
    			return;
    		}

    		$("#history_filter_student_id").attr('disabled', 'disabled');
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

        $('#delay_filter_date_at').datepicker({
    		dateFormat : 'dd-mm-yy',
    		maxDate : new Date(),
    		prevText : '<i class="fa fa-chevron-left"></i>',
    		nextText : '<i class="fa fa-chevron-right"></i>',
    		changeMonth : true,
    		changeYear : true,
    	})
    	
    	.on('change', function(e) {
    		$('#ps-filter').formValidation('revalidateField', $(this).attr('name'));
    	});
        
$('#btn_search').click(function() {
	$("#ps-filter").submit();		
	return true;	
});

});

function setLogtime(student_id,ele) {

	 if (ele.checked) {
		
		
		$('#select_'+ student_id +'_relative_login').attr('disabled', false);
		$('#select_'+ student_id +'_relative_logout').attr('disabled', false);
		$('#select_'+ student_id +'_relative_login').prop("selectedIndex", 0);;
		$('#select_'+ student_id +'_relative_logout').prop("selectedIndex", 0);
		$('#select_'+ student_id +'_member_login').attr('disabled', false);
		$('#select_'+ student_id +'_member_logout').attr('disabled', false);
		$('#select_'+ student_id +'_member_login').prop("selectedIndex", 0);;
		$('#select_'+ student_id +'_member_logout').prop("selectedIndex", 0);
		$('.input-sm_'+ student_id +'_login').attr('disabled', false);
		$('.input-sm_'+ student_id +'_logout').attr('disabled', false);
		
		$('#block_student_service_'+ student_id + ' input[type="checkbox"]').attr('disabled', false);		
		$('.ss_id_'+ student_id + ' input[type="checkbox"]').prop('checked', true);
		$('.ss_id_'+ student_id + ' input[type="checkbox"]').attr('disabled', false);
		
	} else {
		

		$('#block_student_service_'+ student_id + ' input[type="checkbox"]').attr('disabled', 'disabled');
		$('.ss_id_'+ student_id + ' input[type="checkbox"]').prop('checked', false);
		$('.ss_id_'+ student_id + ' input[type="checkbox"]').attr('disabled', 'disabled');		

		$('#select_'+ student_id +'_relative_login').attr('disabled', 'disabled');
		$('#select_'+ student_id +'_relative_logout').attr('disabled', 'disabled');	
		$('#select_'+ student_id +'_member_login').attr('disabled', 'disabled');
		$('#select_'+ student_id +'_member_logout').attr('disabled', 'disabled');
		$('.input-sm_'+ student_id +'_login').attr('disabled', 'disabled');		
		$('.input-sm_'+ student_id +'_logout').attr('disabled', 'disabled');	
	
	}

	 $('#sf_admin_list_th_td_attendance').click(function() {
			
		var boxes = document.getElementsByTagName('input');
	
		for (var index = 0; index < boxes.length; index++) {
			box = boxes[index];			
			if (box.type == 'checkbox' && box.item_name == 'attendance[]')
				box.checked = $(this).is(":checked");
		}
	
		return true;
	});	
}// end function  setLogtime



</script>