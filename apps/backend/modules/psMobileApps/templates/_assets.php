<?php use_helper('I18N', 'Number')?>
<script type="text/javascript">

$(document).ready(function() {

	//BEGIN: filters
	$('#ps_mobile_apps_filters_school_year_id').change(function() {
		resetOptions('ps_mobile_apps_filters_ps_month');
		$('#ps_mobile_apps_filters_ps_month').select2('val','');
		
		if($(this).val() <= 0){
			return;
		}

		$("#ps_mobile_apps_filters_ps_month").attr('disabled', 'disabled');

		$.ajax({
			url: '<?php echo url_for('@ps_year_month?ym_id=') ?>' + $(this).val(),
	        type: "POST",
	        data: {'ym_id': $(this).val()},
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
		    }).done(function(msg) {
		    	$('#ps_mobile_apps_filters_ps_month').select2('val','');
				$("#ps_mobile_apps_filters_ps_month").html(msg);
				$("#ps_mobile_apps_filters_ps_month").attr('disabled', null);
		    });
	});
	
	//Load ajax choose customer show workplace
	$('#ps_mobile_apps_filters_ps_customer_id').change(function() {

		resetOptions('ps_mobile_apps_filters_ps_workplace_id');
		$('#ps_mobile_apps_filters_ps_workplace_id').select2('val','');

		if ($(this).val() > 0) {

			$("#ps_mobile_apps_filters_ps_workplace_id").attr('disabled', 'disabled');
			
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

		    	$('#ps_mobile_apps_filters_ps_workplace_id').select2('val','');

				$("#ps_mobile_apps_filters_ps_workplace_id").html(msg);

				$("#ps_mobile_apps_filters_ps_workplace_id").attr('disabled', null);

		    });
		}
	
	});
	// END: filters
	
	// Form cross checking
	$('#relative_cross_checking_school_year_id').change(function() {

		resetOptions('relative_cross_checking_ps_class_id');
		$('#relative_cross_checking_ps_class_id').select2('val','');

		
		if ($(this).val() <= 0) {
			return;
		}

		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#relative_cross_checking_ps_customer_id').val() + '&w_id=' + $('#relative_cross_checking_ps_workplace_id').val() + '&y_id=' + $('#relative_cross_checking_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#relative_cross_checking_ps_class_id').select2('val','');
			$("#relative_cross_checking_ps_class_id").html(msg);
			$("#relative_cross_checking_ps_class_id").attr('disabled', null);
	    });

		$("#relative_cross_checking_from_date").val("");
		$("#relative_cross_checking_to_date").val("");
		$.ajax({
			url: '<?php echo url_for('@ps_start_end_year') ?>',
	        type: "POST",
	        data: {'y_id': $(this).val()},
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
		    }).done(function(msg) {
			     var from_date = new Date($(msg).first().text().split("-").reverse().join("-"));
			     var to_date = new Date($(msg).last().text().split("-").reverse().join("-"));
			     var now = new Date();
			     if(now < to_date) {
				     to_date = now;
			     }
				 $('#relative_cross_checking_from_date').datepicker('option', {minDate: from_date, maxDate: to_date});
				 $('#relative_cross_checking_to_date').datepicker('option', {minDate: from_date, maxDate: to_date});

		    });
	    
	});
	
	$('#relative_cross_checking_ps_customer_id').change(function() {

		resetOptions('relative_cross_checking_ps_workplace_id');
		$('#relative_cross_checking_ps_workplace_id').select2('val','');

		resetOptions('relative_cross_checking_ps_class_id');
		$('#relative_cross_checking_ps_class_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#relative_cross_checking_ps_workplace_id").attr('disabled', 'disabled');
			
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

		    	$('#relative_cross_checking_ps_workplace_id').select2('val','');

				$("#relative_cross_checking_ps_workplace_id").html(msg);

				$("#relative_cross_checking_ps_workplace_id").attr('disabled', null);
				
				$("#relative_cross_checking_ps_class_id").attr('disabled', 'disabled');
				
				$.ajax({
					url: '<?php echo url_for('@ps_class_by_params') ?>',
			        type: "POST",
			        data: 'c_id=' + $('#relative_cross_checking_ps_customer_id').val() + '&w_id=' + $('#relative_cross_checking_ps_workplace_id').val() + '&y_id=' + $('#relative_cross_checking_school_year_id').val(),
			        processResults: function (data, page) {
		          		return {
		            		results: data.items
		          		};
		        	},
			    }).done(function(msg) {
			    	$('#relative_cross_checking_ps_class_id').select2('val','');
					$("#relative_cross_checking_ps_class_id").html(msg);
					$("#relative_cross_checking_ps_class_id").attr('disabled', null);
			    });
		    });
		}
	
	});

	$('#relative_cross_checking_ps_workplace_id').change(function() {

		resetOptions('relative_cross_checking_ps_class_id');
		$('#relative_cross_checking_ps_class_id').select2('val','');

		
		if ($(this).val() > 0) {

			$("#relative_cross_checking_ps_class_id").attr('disabled', 'disabled');
			
			$.ajax({
				url: '<?php echo url_for('@ps_class_by_params') ?>',
		        type: "POST",
		        data: 'c_id=' + $('#relative_cross_checking_ps_customer_id').val() + '&w_id=' + $('#relative_cross_checking_ps_workplace_id').val() + '&y_id=' + $('#relative_cross_checking_school_year_id').val(),
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {
		    	$('#relative_cross_checking_ps_class_id').select2('val','');
				$("#relative_cross_checking_ps_class_id").html(msg);
				$("#relative_cross_checking_ps_class_id").attr('disabled', null);
		    });
			
		}		
	});

	$('#relative_cross_checking_from_date').datepicker({	
		prevText : '<i class="fa fa-chevron-left"></i>',
	    nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy',
        maxDate: 0,
        onSelect : function(selectedDate) {$('#relative_cross_checking_to_date').datepicker('option','minDate',selectedDate);
	}}).on('changeDate', function(e) {
	     $('#relative_cross_checking_filters').formValidation('revalidateField', $(this).attr('name'));
	});

// 	$('#relative_cross_checking_from_date, #relative_cross_checking_to_date').change( function(){
// 		$('#relative_cross_checking_to_date').datepicker('option','minDate',$('#relative_cross_checking_from_date').val());
// 		$('#relative_cross_checking_from_date').datepicker('option','maxDate',$('#relative_cross_checking_from_date').val());
// 	});
	$('#relative_cross_checking_to_date').datepicker({	
		prevText : '<i class="fa fa-chevron-left"></i>',
	    nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy',
        maxDate: 0,
        onSelect : function(selectedDate) {$('#relative_cross_checking_from_date').datepicker('option','maxDate',selectedDate);
	}}).on('changeDate', function(e) {
	     $('#relative_cross_checking_filters').formValidation('revalidateField', $(this).attr('name'));
	});
	// END cross checking  

});

</script>
<?php include_partial('global/include/_box_modal') ?>