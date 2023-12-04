<?php //include_partial('global/field_custom/_ps_assets') ?>
<script type="text/javascript">
$(document).ready(function() {
	//form
	<?php $ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()->fetchOne ()->getId (); ?>
	$('#ps_service_saturday_ps_workplace_id').change(function() {

		resetOptions('ps_service_saturday_ps_class_id');
		$('#ps_service_saturday_ps_class_id').select2('val','');

		resetOptions('ps_service_saturday_student_id');
		$('#ps_service_saturday_student_id').select2('val','');
		
		resetOptions('ps_service_saturday_relative_id');
		$('#ps_service_saturday_relative_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#ps_service_saturday_ps_class_id").attr('disabled', 'disabled');
			
			$.ajax({
				url: '<?php echo url_for('@ps_class_by_params') ?>',
		        type: "POST",
		        data: 'w_id=' + $('#ps_service_saturday_ps_workplace_id').val() + '&y_id=' + <?php echo $ps_school_year_id; ?>,
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {
		    	$('#ps_service_saturday_ps_class_id').select2('val','');
				$("#ps_service_saturday_ps_class_id").html(msg);
				$("#ps_service_saturday_ps_class_id").attr('disabled', null);
			});
			
		}		
	});

    $('#ps_service_saturday_ps_class_id').change(function() {
    		
    		$.ajax({
    			url: '<?php echo url_for('@ps_students_not_saturday') ?>',
    	        type: "POST",
    	        data: 'c_id=' + $(this).val(),
    	        processResults: function (data, page) {
              		return {
                		results: data.items
              		};
            	},
    	    }).done(function(msg) {
    	    	$('#ps_service_saturday_student_id').select2('val','');
    			$("#ps_service_saturday_student_id").html(msg);
    			$("#ps_service_saturday_student_id").attr('disabled', null);
    		});
    
    	});

    $('#ps_service_saturday_student_id').change(function() {

    	resetOptions('ps_service_saturday_relative_id');
		$('#ps_service_saturday_relative_id').select2('val','');
		
		$.ajax({
			url: '<?php echo url_for('@ps_relative_students_id') ?>',
	        type: "POST",
	        data: 's_id=' + $(this).val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_service_saturday_relative_id').select2('val','');
			$("#ps_service_saturday_relative_id").html(msg);
			$("#ps_service_saturday_relative_id").attr('disabled', null);
		});

	});

//filter

    	$('#ps_service_saturday_filters_ps_customer_id').change(function() {

    		resetOptions('ps_service_saturday_filters_ps_workplace_id');
    		$('#ps_service_saturday_filters_ps_workplace_id').select2('val','');
    		$("#ps_service_saturday_filters_ps_workplace_id").attr('disabled', 'disabled');

    		resetOptions('ps_service_saturday_filters_ps_class_id');
    		$('#ps_service_saturday_filters_ps_class_id').select2('val','');
    		$("#ps_service_saturday_filters_ps_class_id").attr('disabled', 'disabled');
        	
    	if ($(this).val() > 0) {
    
    		$("#ps_service_saturday_filters_ps_workplace_id").attr('disabled', 'disabled');
    		$("#ps_service_saturday_filters_ps_class_id").attr('disabled', 'disabled');
    		
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
    
    	    	$('#ps_service_saturday_filters_ps_workplace_id').select2('val','');
    
    			$("#ps_service_saturday_filters_ps_workplace_id").html(msg);
    
    			$("#ps_service_saturday_filters_ps_workplace_id").attr('disabled', null);
    
    			$("#ps_service_saturday_filters_ps_class_id").attr('disabled', 'disabled');
    
    	    });
    	}		
    });
     
    $('#ps_service_saturday_filters_ps_workplace_id').change(function() {
        
    	resetOptions('ps_service_saturday_filters_ps_class_id');
		$('#ps_service_saturday_filters_ps_class_id').select2('val','');
		$("#ps_service_saturday_filters_ps_class_id").attr('disabled', 'disabled');
		
    	$.ajax({
    		url: '<?php echo url_for('@ps_class_by_params') ?>',
            type: "POST",
            data: 'c_id=' + $('#ps_service_saturday_filters_ps_customer_id').val() + '&w_id=' + $('#ps_service_saturday_filters_ps_workplace_id').val()+ '&y_id=' + $('#ps_service_saturday_filters_school_year_id').val(),
            processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
        }).done(function(msg) {
        	$('#ps_service_saturday_filters_ps_class_id').select2('val','');
    		$("#ps_service_saturday_filters_ps_class_id").html(msg);
    		$("#ps_service_saturday_filters_ps_class_id").attr('disabled', null);
        });
    });


  //filter statistic

	$('#saturday_filter_ps_customer_id').change(function() {

		resetOptions('saturday_filter_ps_workplace_id');
		$('#saturday_filter_ps_workplace_id').select2('val','');
		$("#saturday_filter_ps_workplace_id").attr('disabled', 'disabled');

		resetOptions('saturday_filter_class_id');
		$('#saturday_filter_class_id').select2('val','');
		$("#saturday_filter_class_id").attr('disabled', 'disabled');
    	
	if ($(this).val() > 0) {

		$("#saturday_filter_ps_workplace_id").attr('disabled', 'disabled');
		$("#saturday_filter_class_id").attr('disabled', 'disabled');
		
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

	    	$('#saturday_filter_ps_workplace_id').select2('val','');

			$("#saturday_filter_ps_workplace_id").html(msg);

			$("#saturday_filter_ps_workplace_id").attr('disabled', null);

			$("#saturday_filter_class_id").attr('disabled', 'disabled');

	    });
	}		
});
 
$('#saturday_filter_ps_workplace_id').change(function() {
    
	resetOptions('saturday_filter_class_id');
	$('#saturday_filter_class_id').select2('val','');
	$("#saturday_filter_class_id").attr('disabled', 'disabled');
	
	$.ajax({
		url: '<?php echo url_for('@ps_class_by_params') ?>',
        type: "POST",
        data: 'c_id=' + $('#saturday_filter_ps_customer_id').val() + '&w_id=' + $('#saturday_filter_ps_workplace_id').val()+ '&y_id=' + $('#saturday_filter_ps_school_year_id').val(),
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
    }).done(function(msg) {
    	$('#saturday_filter_class_id').select2('val','');
		$("#saturday_filter_class_id").html(msg);
		$("#saturday_filter_class_id").attr('disabled', null);
    });
});

    $('#ps_service_saturday_input_date_at').datepicker({
		dateFormat : 'dd-mm-yy',
		maxDate : new Date(),
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,

	})
	
	.on('change', function(e) {
		$('#ps-form').formValidation('revalidateField', $(this).attr('name'));
	});

	

});

</script>