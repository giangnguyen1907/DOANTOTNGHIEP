<?php include_partial('global/field_custom/_ps_assets') ?>
<?php include_partial('global/include/_box_modal');?>
<script type="text/javascript">
$(document).ready(function() {
// BEGIN: filters
	$('#ps_advice_categories_filters_ps_customer_id').change(function() {

		resetOptions('ps_advice_categories_filters_ps_workplace_id');
		$('#ps_advice_categories_filters_ps_workplace_id').select2('val','');
		resetOptions('ps_advice_categories_filters_ps_class_id');
		$('#ps_advice_categories_filters_ps_class_id').select2('val','');
		if ($(this).val() > 0) {

			$("#ps_advice_categories_filters_ps_workplace_id").attr('disabled', 'disabled');
			$("#ps_advice_categories_filters_ps_class_id").attr('disabled', 'disabled');
			
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

		    	$('#ps_advice_categories_filters_ps_workplace_id').select2('val','');

				$("#ps_advice_categories_filters_ps_workplace_id").html(msg);

				$("#ps_advice_categories_filters_ps_workplace_id").attr('disabled', null);

				$("#ps_advice_categories_filters_ps_class_id").attr('disabled', 'disabled');

				$.ajax({
					url: '<?php echo url_for('@ps_class_by_params') ?>',
			        type: "POST",
			        data: 'c_id=' + $('#ps_advice_categories_filters_ps_customer_id').val() + '&w_id=' + $('#ps_advice_categories_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_advice_categories_filters_school_year_id').val(),
			        processResults: function (data, page) {
		          		return {
		            		results: data.items
		          		};
		        	},
			    }).done(function(msg) {
			    	$('#ps_advice_categories_filters_ps_class_id').select2('val','');
					$("#ps_advice_categories_filters_ps_class_id").html(msg);
					$("#ps_advice_categories_filters_ps_class_id").attr('disabled', null);
			    });
		    });
		}		
	});
	 
	$('#ps_advice_categories_filters_ps_workplace_id').change(function() {
		resetOptions('ps_advice_categories_filters_ps_class_id');
		$('#ps_advice_categories_filters_ps_class_id').select2('val','');
		
		if ($('#ps_advice_categories_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#ps_advice_categories_filters_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_advice_categories_filters_ps_customer_id').val() + '&w_id=' + $('#ps_advice_categories_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_advice_categories_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_advice_categories_filters_ps_class_id').select2('val','');
			$("#ps_advice_categories_filters_ps_class_id").html(msg);
			$("#ps_advice_categories_filters_ps_class_id").attr('disabled', null);
	    });
	});

	$('#ps_advice_categories_filters_school_year_id').change(function() {
		
		resetOptions('ps_advice_categories_filters_ps_class_id');
		$('#ps_advice_categories_filters_ps_class_id').select2('val','');
		
		if ($('#ps_advice_categories_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#ps_advice_categories_filters_ps_class_id").attr('disabled', 'disabled');
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_advice_categories_filters_ps_customer_id').val() + '&w_id=' + $('#ps_advice_categories_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_advice_categories_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_advice_categories_filters_ps_class_id').select2('val','');
			$("#ps_advice_categories_filters_ps_class_id").html(msg);
			$("#ps_advice_categories_filters_ps_class_id").attr('disabled', null);
	    });
	});

	// END: filters
});
</script>