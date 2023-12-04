<script>
$(document).ready(function() {

	

	$('.btn-album-item').click(function() {
		
		var member_id = $(this).attr('data-value');
		
		var input_date = $('#input_date_' + member_id).val();

		var time_at = $('#ps_timesheet_filters_date_time').val();

		var absent_type = $('#absent_type_' + member_id).val();
		
		//alert(time_at);
		$.ajax({
	        url: '<?php echo url_for('@ps_logtime_member') ?>',
	        type: 'POST',
	        data: 'mb_id=' + member_id + '&input_date=' + input_date + '&absent_type=' + absent_type + '&time_at=' + time_at,
	        success: function(data) {
	        	$('#box-io-' + member_id).html(data);
	        	$('#box-io-time-' + member_id).load('<?php echo url_for('@ps_logtime_member_time') ?>',{mb_id: member_id});
	        }
		});
	    
  	});

	$('#ps_timesheet_filters_school_year_id').change(function() {
		if ($(this).val() > 0) {
		$("#logtimes_filter_year_month").attr('disabled', 'disabled');

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

	// BEGIN: man hinh cham cong
	$('#ps_timesheet_filters_ps_customer_id').change(function() {
	
		resetOptions('ps_timesheet_filters_ps_workplace_id');
		$('#ps_timesheet_filters_ps_workplace_id').select2('val','');
		resetOptions('ps_timesheet_filters_ps_department_id');
		$('#ps_timesheet_filters_ps_department_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#ps_timesheet_filters_ps_workplace_id").attr('disabled', 'disabled');
			
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

		    	$('#ps_timesheet_filters_ps_workplace_id').select2('val','');

				$("#ps_timesheet_filters_ps_workplace_id").html(msg);

				$("#ps_timesheet_filters_ps_workplace_id").attr('disabled', null);
		    });
		    
			$("#ps_timesheet_filters_ps_department_id").attr('disabled', 'disabled');
			$.ajax({
				url: '<?php echo url_for('@ps_department_workplace') ?>',
		        type: "POST",
		        data: 'c_id=' + $('#ps_timesheet_filters_ps_customer_id').val() + '&w_id=' + $('#ps_timesheet_filters_ps_workplace_id').val(),
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {
		    	$('#ps_timesheet_filters_ps_department_id').select2('val','');
				$("#ps_timesheet_filters_ps_department_id").html(msg);
				$("#ps_timesheet_filters_ps_department_id").attr('disabled', null);
			});
		}		
	});

	$('#ps_timesheet_filters_ps_workplace_id').change(function() {

		resetOptions('ps_timesheet_filters_ps_department_id');
		$('#ps_timesheet_filters_ps_department_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#ps_timesheet_filters_ps_department_id").attr('disabled', 'disabled');
			
			$.ajax({
				url: '<?php echo url_for('@ps_department_workplace') ?>',
		        type: "POST",
		        data: 'c_id=' + $('#ps_timesheet_filters_ps_customer_id').val() + '&w_id=' + $('#ps_timesheet_filters_ps_workplace_id').val(),
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {
		    	$('#ps_timesheet_filters_ps_department_id').select2('val','');
				$("#ps_timesheet_filters_ps_department_id").html(msg);
				$("#ps_timesheet_filters_ps_department_id").attr('disabled', null);
			});
			
		}		
	});
	
	// END: filters
	
	//BEGIN: man hinh xem lai
    $('#timesheet_filter_ps_customer_id').change(function() {
    
    	resetOptions('timesheet_filter_ps_workplace_id');
    	$('#timesheet_filter_ps_workplace_id').select2('val','');
    	resetOptions('timesheet_filter_ps_department_id');
    	$('#timesheet_filter_ps_department_id').select2('val','');
    	
    	if ($(this).val() > 0) {
    
    		$("#timesheet_filter_ps_workplace_id").attr('disabled', 'disabled');
    		
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
    
    	    	$('#timesheet_filter_ps_workplace_id').select2('val','');
    
    			$("#timesheet_filter_ps_workplace_id").html(msg);
    
    			$("#timesheet_filter_ps_workplace_id").attr('disabled', null);
    	    });
    	    
    		$("#timesheet_filter_ps_department_id").attr('disabled', 'disabled');
    		$.ajax({
    			url: '<?php echo url_for('@ps_department_workplace') ?>',
    	        type: "POST",
    	        data: 'c_id=' + $('#timesheet_filter_ps_customer_id').val() + '&w_id=' + $('#timesheet_filter_ps_workplace_id').val(),
    	        processResults: function (data, page) {
              		return {
                		results: data.items
              		};
            	},
    	    }).done(function(msg) {
    	    	$('#timesheet_filter_ps_department_id').select2('val','');
    			$("#timesheet_filter_ps_department_id").html(msg);
    			$("#timesheet_filter_ps_department_id").attr('disabled', null);
    		});
    	}		
    });
    
    $('#timesheet_filter_ps_workplace_id').change(function() {
    
    	resetOptions('timesheet_filter_ps_department_id');
    	$('#timesheet_filter_ps_department_id').select2('val','');
    	
    	if ($(this).val() > 0) {
    
    		$("#timesheet_filter_ps_department_id").attr('disabled', 'disabled');
    		
    		$.ajax({
    			url: '<?php echo url_for('@ps_department_workplace') ?>',
    	        type: "POST",
    	        data: 'c_id=' + $('#timesheet_filter_ps_customer_id').val() + '&w_id=' + $('#timesheet_filter_ps_workplace_id').val(),
    	        processResults: function (data, page) {
              		return {
                		results: data.items
              		};
            	},
    	    }).done(function(msg) {
    	    	$('#timesheet_filter_ps_department_id').select2('val','');
    			$("#timesheet_filter_ps_department_id").html(msg);
    			$("#timesheet_filter_ps_department_id").attr('disabled', null);
    		});
    		
    	}		
    });

    $('#timesheet_filter_ps_department_id').change(function() {
        
    	resetOptions('timesheet_filter_member_id');
    	$('#timesheet_filter_member_id').select2('val','');
    	
    	if ($(this).val() > 0) {
    
    		$("#timesheet_filter_member_id").attr('disabled', 'disabled');
    		
    		$.ajax({
    			url: '<?php echo url_for('@ps_member_department') ?>',
    	        type: "POST",
    	        data: 'd_id=' + $('#timesheet_filter_ps_department_id').val(),
    	        processResults: function (data, page) {
              		return {
                		results: data.items
              		};
            	},
    	    }).done(function(msg) {
    	    	$('#timesheet_filter_member_id').select2('val','');
    			$("#timesheet_filter_member_id").html(msg);
    			$("#timesheet_filter_member_id").attr('disabled', null);
    		});
    		
    	}		
    });
    
    // END: filters
    $('#ps_timesheet_filters_time_at').datepicker({
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
	
});

</script>