<?php include_partial('global/field_custom/_ps_assets') ?>
<script>
$(document).ready(function() {
	
	$('#ps_history_screen_relatives_filters_ps_customer_id').change(function() {
		
		resetOptions('ps_history_screen_relatives_filters_user_id');
		$('#ps_history_screen_relatives_filters_user_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#ps_history_screen_relatives_filters_user_id").attr('disabled', 'disabled');
			
			$.ajax({
				url: '<?php echo url_for('@ps_relative_by_customer?psc_id=') ?>' + $(this).val(),
		        type: "POST",
		        data: {'psc_id': $(this).val()},
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {

		    	$('#ps_history_screen_relatives_filters_user_id').select2('val','');

				$("#ps_history_screen_relatives_filters_user_id").html(msg);

				$("#ps_history_screen_relatives_filters_user_id").attr('disabled', null);

		    });
		}		
	});
});

</script>