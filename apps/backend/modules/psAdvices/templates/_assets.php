<?php include_partial('global/field_custom/_ps_assets') ?>
<?php include_partial('global/include/_box_modal');?>
<script type="text/javascript">
$(document).ready(function() {	
// BEGIN: filters
	$('#ps_advices_filters_ps_customer_id').change(function() {

		resetOptions('ps_advices_filters_ps_workplace_id');
		$('#ps_advices_filters_ps_workplace_id').select2('val','');
		resetOptions('ps_advices_filters_ps_class_id');
		$('#ps_advices_filters_ps_class_id').select2('val','');
		if ($(this).val() > 0) {

			$("#ps_advices_filters_ps_workplace_id").attr('disabled', 'disabled');
			$("#ps_advices_filters_ps_class_id").attr('disabled', 'disabled');
			
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

		    	$('#ps_advices_filters_ps_workplace_id').select2('val','');

				$("#ps_advices_filters_ps_workplace_id").html(msg);

				$("#ps_advices_filters_ps_workplace_id").attr('disabled', null);

				$("#ps_advices_filters_ps_class_id").attr('disabled', 'disabled');

				$.ajax({
					url: '<?php echo url_for('@ps_class_by_params') ?>',
			        type: "POST",
			        data: 'c_id=' + $('#ps_advices_filters_ps_customer_id').val() + '&w_id=' + $('#ps_advices_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_advices_filters_school_year_id').val(),
			        processResults: function (data, page) {
		          		return {
		            		results: data.items
		          		};
		        	},
			    }).done(function(msg) {
			    	$('#ps_advices_filters_ps_class_id').select2('val','');
					$("#ps_advices_filters_ps_class_id").html(msg);
					$("#ps_advices_filters_ps_class_id").attr('disabled', null);
			    });
		    });
		}		
	});
	 
	$('#ps_advices_filters_ps_workplace_id').change(function() {
		resetOptions('ps_advices_filters_ps_class_id');
		$('#ps_advices_filters_ps_class_id').select2('val','');
		
		if ($('#ps_advices_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#ps_advices_filters_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_advices_filters_ps_customer_id').val() + '&w_id=' + $('#ps_advices_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_advices_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_advices_filters_ps_class_id').select2('val','');
			$("#ps_advices_filters_ps_class_id").html(msg);
			$("#ps_advices_filters_ps_class_id").attr('disabled', null);
	    });
	});

	$('#ps_advices_filters_school_year_id').change(function() {
		
		resetOptions('ps_advices_filters_ps_class_id');
		$('#ps_advices_filters_ps_class_id').select2('val','');
		
		if ($('#ps_advices_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#ps_advices_filters_ps_class_id").attr('disabled', 'disabled');
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_advices_filters_ps_customer_id').val() + '&w_id=' + $('#ps_advices_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_advices_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_advices_filters_ps_class_id').select2('val','');
			$("#ps_advices_filters_ps_class_id").html(msg);
			$("#ps_advices_filters_ps_class_id").attr('disabled', null);
	    });
	});

	// END: filters

	// BEGIN: New form
	$('#ps_off_school_ps_workplace_id').change(function() {

		resetOptions('ps_off_school_ps_class_id');
		$('#ps_off_school_ps_class_id').select2('val','');

		resetOptions('ps_off_school_student_id');
		$('#ps_off_school_student_id').select2('val','');
		
		resetOptions('ps_off_school_relative_id');
		$('#ps_off_school_relative_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#ps_off_school_ps_class_id").attr('disabled', 'disabled');
			$("#ps_off_school_relative_id").attr('disabled', 'disabled');
			$("#ps_off_school_student_id").attr('disabled', 'disabled');
			
			$.ajax({
				url: '<?php echo url_for('@ps_class_by_params') ?>',
		        type: "POST",
		        data: 'w_id=' + $(this).val(),
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg1) {
		    	$('#ps_off_school_ps_class_id').select2('val','');
		    	
				$("#ps_off_school_ps_class_id").html(msg1);
				
				$("#ps_off_school_ps_class_id").attr('disabled', null);
			});
			
		}		
	});
	
	// END: New form
	$('#ps_advices_filters_start_at').datepicker({
		dateFormat : 'dd-mm-yy',
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
	})
	
	.on('change', function(e) {
		$('#ps-filter').formValidation('revalidateField', $(this).attr('name'));
	});
	$('#ps_advices_filters_stop_at').datepicker({
		dateFormat : 'dd-mm-yy',
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