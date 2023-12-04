<?php include_partial('global/field_custom/_ps_assets') ?>
<script type="text/javascript">
$(document).ready(function() {
	// Lay co so dao tao theo nha truong
	$('#ps_meals_filters_ps_customer_id').change(function() {
		
		$("#ps_meals_filters_ps_workplace_id").attr('disabled', 'disabled');
		
		$.ajax({
	        url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
	        success: function(data) {
	        	$("#ps_meals_filters_ps_workplace_id").val(null).trigger("change");
	        	$('#ps_meals_filters_ps_workplace_id').html(data);
				$("#ps_meals_filters_ps_workplace_id").attr('disabled', null);
	        }
		});
	});

	// Lay co so dao tao theo nha truong
	$('#ps_meals_ps_customer_id').change(function() {
		
		$("#ps_meals_ps_workplace_id").attr('disabled', 'disabled');
		
		$.ajax({
	        url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
	        success: function(data) {
	        	$("#ps_meals_ps_workplace_id").val(null).trigger("change");
	        	$('#ps_meals_ps_workplace_id').html(data);
				$("#ps_meals_ps_workplace_id").attr('disabled', null);
	        }
		});
	});	
});
</script>