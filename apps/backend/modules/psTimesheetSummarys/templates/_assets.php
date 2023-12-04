<script>
$(document).ready(function() {

	$('#timesheet_filter_ps_school_year_id').change(function() {
		if ($(this).val() > 0) {
		$("#timesheet_filter_year_month").attr('disabled', 'disabled');

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
		    	$('#timesheet_filter_year_month').select2('val','');
				$("#timesheet_filter_year_month").html(msg);
				$("#timesheet_filter_year_month").attr('disabled', null);
		    });
		}
	});

	//BEGIN: man hinh thong ke
    $('#timesheet_filter_ps_customer_id').change(function() {
    
    	resetOptions('timesheet_filter_ps_workplace_id');
    	$('#timesheet_filter_ps_workplace_id').select2('val','');
    	resetOptions('timesheet_filter_ps_department_id');
    	$('#timesheet_filter_ps_department_id').select2('val','');
    	resetOptions('timesheet_filter_member_id');
    	$('#timesheet_filter_member_id').select2('val','');
    	
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

// man hinh tong hop
    $('#timesheet_summary_ps_school_year_id').change(function() {
		if ($(this).val() > 0) {
		$("#timesheet_summary_year_month").attr('disabled', 'disabled');

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
		    	$('#timesheet_summary_year_month').select2('val','');
				$("#timesheet_summary_year_month").html(msg);
				$("#timesheet_summary_year_month").attr('disabled', null);
		    });
		}
	});

	//BEGIN: man hinh thong ke
    $('#timesheet_summary_ps_customer_id').change(function() {
    
    	resetOptions('timesheet_summary_ps_workplace_id');
    	$('#timesheet_summary_ps_workplace_id').select2('val','');
    	resetOptions('timesheet_summary_ps_department_id');
    	$('#timesheet_summary_ps_department_id').select2('val','');
    	
    	if ($(this).val() > 0) {
    
    		$("#timesheet_summary_ps_workplace_id").attr('disabled', 'disabled');
    		
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
    
    	    	$('#timesheet_summary_ps_workplace_id').select2('val','');
    
    			$("#timesheet_summary_ps_workplace_id").html(msg);
    
    			$("#timesheet_summary_ps_workplace_id").attr('disabled', null);
    	    });
    	    
    		$("#timesheet_summary_ps_department_id").attr('disabled', 'disabled');
    		$.ajax({
    			url: '<?php echo url_for('@ps_department_workplace') ?>',
    	        type: "POST",
    	        data: 'c_id=' + $('#timesheet_summary_ps_customer_id').val() + '&w_id=' + $('#timesheet_summary_ps_workplace_id').val(),
    	        processResults: function (data, page) {
              		return {
                		results: data.items
              		};
            	},
    	    }).done(function(msg) {
    	    	$('#timesheet_summary_ps_department_id').select2('val','');
    			$("#timesheet_summary_ps_department_id").html(msg);
    			$("#timesheet_summary_ps_department_id").attr('disabled', null);
    		});
    	}		
    });
    
    $('#timesheet_summary_ps_workplace_id').change(function() {
    
    	resetOptions('timesheet_summary_ps_department_id');
    	$('#timesheet_summary_ps_department_id').select2('val','');
    	
    	if ($(this).val() > 0) {
    
    		$("#timesheet_summary_ps_department_id").attr('disabled', 'disabled');
    		
    		$.ajax({
    			url: '<?php echo url_for('@ps_department_workplace') ?>',
    	        type: "POST",
    	        data: 'c_id=' + $('#timesheet_summary_ps_customer_id').val() + '&w_id=' + $('#timesheet_summary_ps_workplace_id').val(),
    	        processResults: function (data, page) {
              		return {
                		results: data.items
              		};
            	},
    	    }).done(function(msg) {
    	    	$('#timesheet_summary_ps_department_id').select2('val','');
    			$("#timesheet_summary_ps_department_id").html(msg);
    			$("#timesheet_summary_ps_department_id").attr('disabled', null);
    		});
    		
    	}		
    });

    
    

});

</script>