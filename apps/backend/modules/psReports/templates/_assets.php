<?php use_helper('I18N', 'Number')?>
<script type="text/javascript">
$(document).ready(function() {
// BEGIN: filters
	$('#student_statictis_ps_customer_id').change(function() {

		resetOptions('student_statictis_ps_workplace_id');
		$('#student_statictis_ps_workplace_id').select2('val','');
		if ($(this).val() > 0) {

			$("#student_statictis_ps_workplace_id").attr('disabled', 'disabled');
			
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

		    	$('#student_statictis_ps_workplace_id').select2('val','');

				$("#student_statictis_ps_workplace_id").html(msg);

				$("#student_statictis_ps_workplace_id").attr('disabled', null);
				
		}		
	});
	});
	// END: filters
});
</script>