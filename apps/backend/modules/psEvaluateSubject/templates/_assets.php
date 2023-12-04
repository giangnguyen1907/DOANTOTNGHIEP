<?php include_partial('global/field_custom/_ps_assets') ?>


<script type="text/javascript">

$(document).ready(function() {
	
	//filters
	$('#ps_evaluate_subject_filters_ps_customer_id').change(function() {

		resetOptions('ps_evaluate_subject_filters_ps_workplace_id');
		$('#ps_evaluate_subject_filters_ps_workplace_id').select2('val','');

		if ($(this).val() <= 0) {
			return false;
		}

			$("#ps_evaluate_subject_filters_ps_workplace_id").attr('disabled', 'disabled');
			
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

		    	$('#ps_evaluate_subject_filters_ps_workplace_id').select2('val','');

				$("#ps_evaluate_subject_filters_ps_workplace_id").html(msg);

				$("#ps_evaluate_subject_filters_ps_workplace_id").attr('disabled', null);

		    });
		
	});
// End filters

// Form edit và create
	$('#ps_evaluate_subject_ps_customer_id').change(function() {

		resetOptions('ps_evaluate_subject_ps_workplace_id');
		$('#ps_evaluate_subject_ps_workplace_id').select2('val','');

		if ($(this).val() <= 0) {
			return false;
		}

			$("#ps_evaluate_subject_ps_workplace_id").attr('disabled', 'disabled');
			
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

		    	$('#ps_evaluate_subject_ps_workplace_id').select2('val','');

				$("#ps_evaluate_subject_ps_workplace_id").html(msg);

				$("#ps_evaluate_subject_ps_workplace_id").attr('disabled', null);

		    });
		
	});
	//end-Form edit và create
});
</script>