<?php include_partial('global/include/_box_modal_messages');?>
<?php include_partial('global/include/_box_modal');?>
<style>
#s2id_ps_comment_week_filters_ps_week {
	min-width: 250px;
}
</style>
<script type="text/javascript">

// msg
var msg_select_ps_customer_id	= '<?php echo __('Please select School to filter the data.')?>';
var msg_select_ps_workplace_id	= '<?php echo __('Please select workplace to filter the data.')?>';

</script>
<script>
$(document).ready(function() {

	$('.push_notication').click(function() {
		var comment_id = $(this).attr('data-value');
		var student_id = $(this).attr('value');
		
		$('#ic-loading-' + comment_id).show();	

		$.ajax({
	        url: '<?php echo url_for('@ps_comment_week_send_notication') ?>',
	        type: 'POST',
	        data: 'comment_id=' + comment_id + '&student_id=' + student_id,
	        success: function(data) {
				// console.log(data);
	        	$('#ic-loading-' + comment_id).hide();
	        	$('#box-' + comment_id).html(data);
	        },
	        error: function (request, error) {
	            alert(" Can't do because: " + error);
	        },
		});
	});

	// BEGIN: filters
	$('#ps_comment_week_filters_ps_customer_id').change(function() {
	
		resetOptions('ps_comment_week_filters_ps_workplace_id');
		$('#ps_comment_week_filters_ps_workplace_id').select2('val','');
		resetOptions('ps_comment_week_filters_ps_class_id');
		$('#ps_comment_week_filters_ps_class_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#ps_comment_week_filters_ps_workplace_id").attr('disabled', 'disabled');
			$("#ps_comment_week_filters_ps_class_id").attr('disabled', 'disabled');
			
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

		    	$('#ps_comment_week_filters_ps_workplace_id').select2('val','');

				$("#ps_comment_week_filters_ps_workplace_id").html(msg);

				$("#ps_comment_week_filters_ps_workplace_id").attr('disabled', null);

				$("#ps_comment_week_filters_ps_class_id").attr('disabled', 'disabled');

				$.ajax({
					url: '<?php echo url_for('@ps_class_by_params') ?>',
			        type: "POST",
			        data: 'c_id=' + $('#ps_comment_week_filters_ps_customer_id').val() + '&w_id=' + $('#ps_comment_week_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_comment_week_filters_school_year_id').val(),
			        processResults: function (data, page) {
		          		return {
		            		results: data.items
		          		};
		        	},
			    }).done(function(msg) {
			    	$('#ps_comment_week_filters_ps_class_id').select2('val','');
					$("#ps_comment_week_filters_ps_class_id").html(msg);
					$("#ps_comment_week_filters_ps_class_id").attr('disabled', null);
			    });
		    });
		}		
	});
	 
	$('#ps_comment_week_filters_ps_workplace_id').change(function() {
		resetOptions('ps_comment_week_filters_ps_class_id');
		$('#ps_comment_week_filters_ps_class_id').select2('val','');
		
		if ($('#ps_comment_week_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#ps_comment_week_filters_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_comment_week_filters_ps_customer_id').val() + '&w_id=' + $('#ps_comment_week_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_comment_week_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_comment_week_filters_ps_class_id').select2('val','');
			$("#ps_comment_week_filters_ps_class_id").html(msg);
			$("#ps_comment_week_filters_ps_class_id").attr('disabled', null);
	    });
	});

	$('#ps_comment_week_filters_school_year_id').change(function() {
		
		resetOptions('ps_comment_week_filters_ps_class_id');
		$('#ps_comment_week_filters_ps_class_id').select2('val','');
		
		if ($('#ps_comment_week_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#ps_comment_week_filters_ps_class_id").attr('disabled', 'disabled');
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_comment_week_filters_ps_customer_id').val() + '&w_id=' + $('#ps_comment_week_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_comment_week_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_comment_week_filters_ps_class_id').select2('val','');
			$("#ps_comment_week_filters_ps_class_id").html(msg);
			$("#ps_comment_week_filters_ps_class_id").attr('disabled', null);
	    });
	});


    $('#ps_comment_week_filters_ps_year').change(function() {

    	$("#ps_comment_week_filters_ps_week").attr('disabled', 'disabled');
    	resetOptions('ps_comment_week_filters_ps_week');
    	
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
  			 $('#ps_comment_week_filters_ps_week').select2('val','');
 			 $("#ps_comment_week_filters_ps_week").html(msg);
  			 $("#ps_comment_week_filters_ps_week").attr('disabled', null);

  			$('#ps_comment_week_filters_ps_week').val(1);
			$('#ps_comment_week_filters_ps_week').change();
  		});
    });

    $('#ps_comment_week_filters_ps_month').change(function() {

    	if ($(this).val() > 0) {
    		$("#ps_comment_week_filters_ps_week").attr('disabled', 'disabled');
        }else{
        	$("#ps_comment_week_filters_ps_week").attr('disabled', false);
        }
    	
    });

	// end filter

	$('#ps-filter').formValidation({
    	framework : 'bootstrap',
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
			
            "ps_comment_week_filters[ps_customer_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_customer_id,
                        		  en_US: msg_select_ps_customer_id
                        }
                    }
                }
            },
            
            "ps_comment_week_filters[ps_workplace_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_workplace_id,
                        		  en_US: msg_select_ps_workplace_id
                        }
                    }
                }
            },
		}
    }).on('err.form.fv', function(e) {
    	$('#messageModal').modal('show');
    });
    $('#ps-filter').formValidation('setLocale', PS_CULTURE);

});
</script>