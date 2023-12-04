<?php use_helper('I18N', 'Number')?>
<style>
.datepicker, .bootstrap-timepicker-widget, .ui-datepicker-calendar {
	z-index: 9999 !important;
}

.ui-datepicker {
	z-index: 1051 !important;
}

.select2-container {
	width: 100% !important;
	padding: 0;
}
</style>
<script>
	// msg
	var msg_select_ps_customer_id	= '<?php echo __('Please select School to filter the data.')?>';
	var msg_select_school_year 		= '<?php echo __('Please select school year to to filter the data.')?>';

	var msg_select_student 			= '<?php echo __('Please select students from the list of parties to enter data.')?>';
	var msg_select_login_relative 	= '<?php echo __('Please select relatives to enter data.')?>';
	var msg_select_teacher_received = '<?php echo __('Please select a teacher to pick up your child.')?>';
	
</script>
<script type="text/javascript">

$(document).ready(function() {
	
	$(".widget-body-toolbar a, .btn-group a, .btn-filter-reset").on("contextmenu",function(){
	    return false;
	});

	$('#feature_branch_times_filters_ps_customer_id').change(function() {

		resetOptions('feature_branch_times_filters_ps_workplace_id');
		$('#feature_branch_times_filters_ps_workplace_id').select2('val','');
		resetOptions('feature_branch_times_filters_ps_class_id');
		$('#feature_branch_times_filters_ps_class_id').select2('val','');
		if ($(this).val() > 0) {

			$("#feature_branch_times_filters_ps_workplace_id").attr('disabled', 'disabled');
			$("#feature_branch_times_filters_ps_class_id").attr('disabled', 'disabled');
			
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

		    	$('#feature_branch_times_filters_ps_workplace_id').select2('val','');

				$("#feature_branch_times_filters_ps_workplace_id").html(msg);

				$("#feature_branch_times_filters_ps_workplace_id").attr('disabled', null);

				$("#feature_branch_times_filters_ps_class_id").attr('disabled', 'disabled');

				$.ajax({
					url: '<?php echo url_for('@ps_class_by_params') ?>',
			        type: "POST",
			        data: 'c_id=' + $('#feature_branch_times_filters_ps_customer_id').val() + '&w_id=' + $('#feature_branch_times_filters_ps_workplace_id').val() + '&y_id=' + $('#feature_branch_times_filters_school_year_id').val(),
			        processResults: function (data, page) {
		          		return {
		            		results: data.items
		          		};
		        	},
			    }).done(function(msg) {
			    	$('#feature_branch_times_filters_ps_class_id').select2('val','');
					$("#feature_branch_times_filters_ps_class_id").html(msg);
					$("#feature_branch_times_filters_ps_class_id").attr('disabled', null);
			    });
		    });
		}		
	});

	$('#feature_branch_times_filters_ps_workplace_id').change(function() {
		resetOptions('feature_branch_times_filters_ps_class_id');
		$('#feature_branch_times_filters_ps_class_id').select2('val','');
		
		if ($('#feature_branch_times_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#feature_branch_times_filters_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#feature_branch_times_filters_ps_customer_id').val() + '&w_id=' + $('#feature_branch_times_filters_ps_workplace_id').val() + '&y_id=' + $('#feature_branch_times_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#feature_branch_times_filters_ps_class_id').select2('val','');
			$("#feature_branch_times_filters_ps_class_id").html(msg);
			$("#feature_branch_times_filters_ps_class_id").attr('disabled', null);
	    });
	});

	$('#feature_branch_times_filters_school_year_id').change(function() {

// 		resetOptions('feature_branch_times_filters_date_at_from');
// 		resetOptions('feature_branch_times_filters_date_at_to');

		if ($(this).val() <= 0) {
			$("#feature_branch_times_filters_date_at_from").attr('disabled', 'disabled');
			$("#feature_branch_times_filters_date_at_to").attr('disabled', 'disabled');
			return;
		}
		
		$("#feature_branch_times_filters_ps_class_id").attr('disabled', 'disabled');

		$.ajax({
				url: '<?php echo url_for('@ps_start_end_year') ?>',
		        type: "POST",
		        data: {'y_id': $(this).val()},
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
			    }).done(function(msg) {
			    	 $("#feature_branch_times_filters_ps_workplace_id").trigger("change");
					 $('#feature_branch_times_filters_date_at_from').datepicker('option', {minDate: $(msg).first().text(), maxDate: $(msg).last().text()});
					 $('#feature_branch_times_filters_date_at_to').datepicker('option', {minDate: $(msg).first().text(), maxDate: $(msg).last().text()});
					 
			    });
		    
			$.ajax({
				url: '<?php echo url_for('@ps_class_by_params') ?>',
		        type: "POST",
		        data: 'c_id=' + $('#feature_branch_times_filters_ps_customer_id').val() + '&w_id=' + $('#feature_branch_times_filters_ps_workplace_id').val() + '&y_id=' + $('#feature_branch_times_filters_school_year_id').val(),
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {
		    	$('#feature_branch_times_filters_ps_class_id').select2('val','');
				$("#feature_branch_times_filters_ps_class_id").html(msg);
				$("#feature_branch_times_filters_ps_class_id").attr('disabled', null);
		    });
		
	});


    $('#feature_branch_times_filters_ps_year').change(function() {

    	if ($(this).val() <= 0) {
			return;
		}
		
    	$("#feature_branch_times_filters_ps_week").attr('disabled', 'disabled');
    	$.ajax({
            url: '<?php echo url_for('@ps_menus_weeks_year') ?>',
            type: "POST",
            data: {'ps_year': $(this).val()},
            processResults: function (data, page) {
                return {
                  results: data.items  
                };
            },
  		}).done(function(msg) {
  			 $('#feature_branch_times_filters_ps_week').select2('val','');
 			 $("#feature_branch_times_filters_ps_week").html(msg);
  			 $("#feature_branch_times_filters_ps_week").attr('disabled', null);

  			$('#feature_branch_times_filters_ps_week').val(1);
			$('#feature_branch_times_filters_ps_week').change();
  		});
    });
	
	
	$('#menus_filter_school_year_id').change(function() {

		resetOptions('menus_filter_date_at');
		$('#menus_filter_date_at').select2('val','');

		//resetOptions('menus_filter_ps_class_id');
		//$('#menus_filter_ps_class_id').select2('val','');

		if ($(this).val() <= 0) {
			return;
		}

		$.ajax({
			url: '<?php echo url_for('@ps_start_end_year') ?>',
	        type: "POST",
	        data: '&y_id=' + $(this).val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#menus_filter_date_at').datepicker('option', {minDate: $(msg).first().text(), maxDate: $(msg).last().text()});
	    	$("#menus_filter_ps_workplace_id").trigger("change");
	    });

	});

	<?php if (myUser::credentialPsCustomers('PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL')):?>

	$('#menus_filter_ps_customer_id').change(function() {      
    	
		resetOptions('menus_filter_ps_workplace_id');
		$('#menus_filter_ps_workplace_id').select2('val','');

		resetOptions('menus_filter_ps_class_id');
		$('#menus_filter_ps_class_id').select2('val','');
		
		$("#menus_filter_ps_workplace_id").attr('disabled', 'disabled');
		$("#menus_filter_ps_class_id").attr('disabled', 'disabled');

		if ($(this).val() <= 0) {
			return;
		}
		
		$.ajax({
			url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: "POST",
	        //async: false,
	        data: {'psc_id': $(this).val()},
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {

	    	$('#menus_filter_ps_workplace_id').select2('val','');

			$("#menus_filter_ps_workplace_id").html(msg);

			$("#menus_filter_ps_workplace_id").attr('disabled', null);


			$.ajax({
				url: '<?php echo url_for('@ps_class_by_params') ?>',
		        type: "POST",
		        data: 'c_id=' + $('#menus_filter_ps_customer_id').val() + '&w_id=' + $('#menus_filter_ps_workplace_id').val() + '&y_id=' + $('#menus_filter_school_year_id').val(),
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {
		    	$('#menus_filter_ps_class_id').select2('val','');
				$("#menus_filter_ps_class_id").html(msg);
				$("#menus_filter_ps_class_id").attr('disabled', null);
		    });
	    });

    });
    
	<?php endif;?>

	// Load feature by customer
    $('#feature_branch_ps_customer_id').change(function() {      

    	resetOptions('feature_branch_ps_workplace_id');
		$('#feature_branch_ps_workplace_id').select2('val','');

		$("#feature_branch_ps_workplace_id").attr('disabled', 'disabled');
		
    	$("#feature_branch_feature_id").attr('disabled', 'disabled');

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

	    	$('#feature_branch_ps_workplace_id').select2('val','');
	    	$("#feature_branch_ps_workplace_id").html(msg);
	    	$("#feature_branch_ps_workplace_id").attr('disabled', null);

	    	$.ajax({
				url: '<?php echo url_for('@ps_class_by_params') ?>',
		        type: "POST",
		        data: 'c_id=' + $('#menus_filter_ps_customer_id').val() + '&w_id=' + $('#menus_filter_ps_workplace_id').val() + '&y_id=' + $('#menus_filter_school_year_id').val(),
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {
		    	$('#menus_filter_ps_class_id').select2('val','');
				$("#menus_filter_ps_class_id").html(msg);
				$("#menus_filter_ps_class_id").attr('disabled', null);
		    });
	    });

    });

    $('#menus_filter_ps_workplace_id').change(function() {      

		resetOptions('menus_filter_ps_class_id');
		$('#menus_filter_ps_class_id').select2('val','');

		if ($(this).val() <= 0) {
			return;
		}
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#menus_filter_ps_customer_id').val() + '&w_id=' + $('#menus_filter_ps_workplace_id').val() + '&y_id=' + $('#menus_filter_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#menus_filter_ps_class_id').select2('val','');
			$("#menus_filter_ps_class_id").html(msg);
			$("#menus_filter_ps_class_id").attr('disabled', null);
	    });
                
    });


    $('#menus_filter_ps_customer_id, #menus_filter_ps_workplace_id ,#menus_filter_ps_class_id, #menus_filter_date_at').change(function() {

    	if($('#menus_filter_date_at') <= 0){
        	return;
    	}
    	$("#ic-loading").show();
    	$("#tbl-menu").html('');
    	
    	$.ajax({
	          url: '<?php echo url_for('@ps_feature_branch_times_week');?>',
	          type: "POST",
	          data: $("#psnew-filter").serialize(),
	          processResults: function (data, page) {
	              return {
	                results: data.items  
	              };
	          },
			}).done(function(msg) {
				 //alert(msg);
				 $("#ic-loading").hide();
				 $("#tbl-menu").html(msg);				 	
			});
    });

});
</script>
<?php include_partial('global/include/_box_modal')?>