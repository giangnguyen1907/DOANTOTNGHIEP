<script type="text/javascript">
$(document).ready(function() {
	//form
	$('#ps_member_absents_ps_workplace_id').change(function() {

		resetOptions('ps_member_absents_ps_department_id');
		$('#ps_member_absents_ps_department_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#ps_member_absents_ps_department_id").attr('disabled', 'disabled');
			
			$.ajax({
				url: '<?php echo url_for('@ps_department_workplace') ?>',
		        type: "POST",
		        data: 'w_id=' + $('#ps_member_absents_ps_workplace_id').val(),
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {
		    	$('#ps_member_absents_ps_department_id').select2('val','');
				$("#ps_member_absents_ps_department_id").html(msg);
				$("#ps_member_absents_ps_department_id").attr('disabled', null);
			});
			
		}		
	});

	$('#ps_member_absents_ps_department_id').change(function() {

		resetOptions('ps_member_absents_member_id');
		$('#ps_member_absents_member_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#ps_member_absents_member_id").attr('disabled', 'disabled');
			
			$.ajax({
				url: '<?php echo url_for('@ps_member_department') ?>',
		        type: "POST",
		        data: 'd_id=' + $('#ps_member_absents_ps_department_id').val(),
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {
		    	$('#ps_member_absents_member_id').select2('val','');
				$("#ps_member_absents_member_id").html(msg);
				$("#ps_member_absents_member_id").attr('disabled', null);
			});
			
		}		
	});

	//filter
	$('#ps_member_absents_filters_ps_customer_id').change(function() {

		resetOptions('ps_member_absents_filters_ps_workplace_id');
		$('#ps_member_absents_filters_ps_workplace_id').select2('val','');
		$("#ps_member_absents_filters_ps_workplace_id").attr('disabled', 'disabled');

		resetOptions('ps_member_absents_filters_ps_department_id');
		$('#ps_member_absents_filters_ps_department_id').select2('val','');
		$("#ps_member_absents_filters_ps_department_id").attr('disabled', 'disabled');

		resetOptions('ps_member_absents_filters_member_id');
		$('#ps_member_absents_filters_member_id').select2('val','');
		$("#ps_member_absents_filters_member_id").attr('disabled', 'disabled');

		if ($(this).val() > 0) {
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

		    	$('#ps_member_absents_filters_ps_workplace_id').select2('val','');

				$("#ps_member_absents_filters_ps_workplace_id").html(msg);

				$("#ps_member_absents_filters_ps_workplace_id").attr('disabled', null);

		    });
		}		
	});
	
	$('#ps_member_absents_filters_ps_workplace_id').change(function() {

		resetOptions('ps_member_absents_filters_ps_department_id');
		$('#ps_member_absents_filters_ps_department_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#ps_member_absents_filters_ps_department_id").attr('disabled', 'disabled');
			
			$.ajax({
				url: '<?php echo url_for('@ps_department_workplace') ?>',
		        type: "POST",
		        data: 'c_id=' + $('#ps_member_absents_filters_ps_customer_id').val() + '&w_id=' + $('#ps_member_absents_filters_ps_workplace_id').val(),
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {
		    	$('#ps_member_absents_filters_ps_department_id').select2('val','');
				$("#ps_member_absents_filters_ps_department_id").html(msg);
				$("#ps_member_absents_filters_ps_department_id").attr('disabled', null);
			});
			
		}		
	});

	$('#ps_member_absents_filters_ps_department_id').change(function() {

		resetOptions('ps_member_absents_filters_member_id');
		$('#ps_member_absents_filters_member_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#ps_member_absents_filters_member_id").attr('disabled', 'disabled');
			
			$.ajax({
				url: '<?php echo url_for('@ps_member_department') ?>',
		        type: "POST",
		        data: 'd_id=' + $('#ps_member_absents_filters_ps_department_id').val(),
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {
		    	$('#ps_member_absents_filters_member_id').select2('val','');
				$("#ps_member_absents_filters_member_id").html(msg);
				$("#ps_member_absents_filters_member_id").attr('disabled', null);
			});
			
		}		
	});
	
	$('#ps_member_absents_absent_at').datepicker({
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
		dateFormat : 'dd-mm-yy'
	})
	
	.on('change', function(e) {
		$('#ps-form').formValidation('revalidateField', $(this).attr('name'));
	});

});

</script>