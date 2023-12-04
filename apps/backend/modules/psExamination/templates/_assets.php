<?php include_partial('global/field_custom/_ps_assets') ?>

<script type="text/javascript">

$(document).ready(function() {

	// BEGIN: filters
	$('#ps_examination_filters_ps_customer_id').change(function() {

		resetOptions('ps_examination_filters_ps_workplace_id');
		$('#ps_examination_filters_ps_workplace_id').select2('val','');

		if ($(this).val() > 0) {

			$("#ps_examination_filters_ps_workplace_id").attr('disabled', 'disabled');
			
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

		    	$('#ps_examination_filters_ps_workplace_id').select2('val','');

				$("#ps_examination_filters_ps_workplace_id").html(msg);

				$("#ps_examination_filters_ps_workplace_id").attr('disabled', null);

		    });
		}
	
	});

	$('#ps_examination_ps_customer_id').change(function() {

		resetOptions('ps_examination_ps_workplace_id');
		$('#ps_examination_ps_workplace_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#ps_examination_ps_workplace_id").attr('disabled', 'disabled');
			
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

		    	$('#ps_examination_ps_workplace_id').select2('val','');

				$("#ps_examination_ps_workplace_id").html(msg);

				$("#ps_examination_ps_workplace_id").attr('disabled', null);

		    });
		}
	
	});

	$('#ps_examination_input_date_at').datepicker({
		dateFormat : 'dd-mm-yy',
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,

	}).on('change', function(e) {
		$('#ps-form').formValidation('revalidateField', $(this).attr('name'));
	});
});

</script>

