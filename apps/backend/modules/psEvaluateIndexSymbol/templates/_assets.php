<?php include_partial('global/field_custom/_ps_assets') ?>

<style>
#ps-filter .has-error {
	/* To make the feedback icon visible */
	z-index: 9999;
	color: #b94a48;
}

.datepicker {
	z-index: 1051 !important;
}

.ui-datepicker {
	z-index: 1051 !important;
}

.select2-container {
	width: 100% !important;
	padding: 0;
}
</style>

<script type="text/javascript">

$(document).ready(function() {

	//Filter
	
	$('#ps_evaluate_index_symbol_filters_ps_customer_id').change(function() {      
		
		resetOptions('ps_evaluate_index_symbol_filters_ps_workplace_id');
		$('#ps_evaluate_index_symbol_filters_ps_workplace_id').select2('val','');
		
		if ($(this).val() <= 0) {
			return;
		}

		$("#ps_evaluate_index_symbol_filters_ps_workplace_id").attr('disabled', 'disabled');

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

	    	$('#ps_evaluate_index_symbol_filters_ps_workplace_id').select2('val','');

			$("#ps_evaluate_index_symbol_filters_ps_workplace_id").html(msg);

			$("#ps_evaluate_index_symbol_filters_ps_workplace_id").attr('disabled', null);
			
	    });

    });

    //End filter
    
	 //Form 
	
    $('#ps_evaluate_index_symbol_ps_customer_id').change(function() {      
		
		resetOptions('ps_evaluate_index_symbol_ps_workplace_id');
		$('#ps_evaluate_index_symbol_ps_workplace_id').select2('val','');
		
		if ($(this).val() <= 0) {
			return;
		}

		$("#ps_evaluate_index_symbol_ps_workplace_id").attr('disabled', 'disabled');

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

	    	$('#ps_evaluate_index_symbol_ps_workplace_id').select2('val','');

			$("#ps_evaluate_index_symbol_ps_workplace_id").html(msg);

			$("#ps_evaluate_index_symbol_ps_workplace_id").attr('disabled', null);
			
	    });
           
    });
});
</script>