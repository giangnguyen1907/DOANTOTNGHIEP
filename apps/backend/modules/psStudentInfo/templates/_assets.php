<script type="text/javascript">
$(document).ready(function() {
	// BEGIN: filters
	$('#student_filters_ps_customer_id').change(function() {

		resetOptions('student_filters_ps_workplace_id');
		$('#student_filters_ps_workplace_id').select2('val','');
		resetOptions('student_filters_ps_class_id');
		$('#student_filters_ps_class_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#student_filters_ps_workplace_id").attr('disabled', 'disabled');
			$("#student_filters_ps_class_id").attr('disabled', 'disabled');
			
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

		    	$('#student_filters_ps_workplace_id').select2('val','');

				$("#student_filters_ps_workplace_id").html(msg);

				$("#student_filters_ps_workplace_id").attr('disabled', null);

				$("#student_filters_ps_class_id").attr('disabled', 'disabled');

				$.ajax({
					url: '<?php echo url_for('@ps_class_by_params') ?>',
			        type: "POST",
			        data: 'c_id=' + $('#student_filters_ps_customer_id').val() + '&w_id=' + $('#student_filters_ps_workplace_id').val() + '&y_id=' + $('#student_filters_school_year_id').val(),
			        processResults: function (data, page) {
		          		return {
		            		results: data.items
		          		};
		        	},
			    }).done(function(msg) {
			    	$('#student_filters_ps_class_id').select2('val','');
					$("#student_filters_ps_class_id").html(msg);
					$("#student_filters_ps_class_id").attr('disabled', null);
			    });
		    });
		}		
	});
	 
	$('#student_filters_ps_workplace_id').change(function() {
		resetOptions('student_filters_ps_class_id');
		$('#student_filters_ps_class_id').select2('val','');
		
		if ($('#student_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#student_filters_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#student_filters_ps_customer_id').val() + '&w_id=' + $('#student_filters_ps_workplace_id').val() + '&y_id=' + $('#student_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#student_filters_ps_class_id').select2('val','');
			$("#student_filters_ps_class_id").html(msg);
			$("#student_filters_ps_class_id").attr('disabled', null);
	    });
	});

	$('#student_filters_school_year_id').change(function() {
		
		resetOptions('student_filters_ps_class_id');
		$('#student_filters_ps_class_id').select2('val','');
		
		if ($('#student_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#student_filters_ps_class_id").attr('disabled', 'disabled');
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#student_filters_ps_customer_id').val() + '&w_id=' + $('#student_filters_ps_workplace_id').val() + '&y_id=' + $('#student_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#student_filters_ps_class_id').select2('val','');
			$("#student_filters_ps_class_id").html(msg);
			$("#student_filters_ps_class_id").attr('disabled', null);
	    });
	});

	// END: filters
	});
</script>