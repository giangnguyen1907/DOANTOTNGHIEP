<?php use_helper('I18N', 'Number')?>
<?php $app_upload_max_size = (int)sfConfig::get('app_upload_max_size');?>
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
tr.info th{color: #333;line-height: 35px!important;}
</style>

<script type="text/javascript">
	var msg_file_invalid = '<?php echo __('The image file must be in the format: jpg, png, gif. File size less than %value%KB.', array('%value%' => $app_upload_max_size))?>';
	var PsMaxSizeFile 	 = '<?php echo $app_upload_max_size;?>';

$(document).ready(function() {
	
	$('#ps_review_date_at').datepicker({
		dateFormat : 'dd-mm-yy',
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
	})
	
	.on('change', function(e) {
		$('#ps-filter').formValidation('revalidateField', $(this).attr('name'));
	});

	// BEGIN: filters in Index Form
	$('#ps_review_filters_school_year_id').change(function() {

		resetOptions('ps_review_filters_ps_class_id');
		$('#ps_review_filters_ps_class_id').select2('val','');
	    
		if ($(this).val() <= 0) {
			return;
		}

		$("#ps_review_filters_ps_workplace_id").trigger('change');
	});
	
	//customer
	$('#ps_review_filters_ps_customer_id').change(function() {

		resetOptions('ps_review_filters_ps_workplace_id');
		$('#ps_review_filters_ps_workplace_id').select2('val','');

		resetOptions('ps_review_filters_ps_class_id');
		$('#ps_review_filters_ps_class_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#ps_review_filters_ps_workplace_id").attr('disabled', 'disabled');
			$("#ps_review_filters_ps_class_id").attr('disabled', 'disabled');
			
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

		    	$('#ps_review_filters_ps_workplace_id').select2('val','');

				$("#ps_review_filters_ps_workplace_id").html(msg);

				$("#ps_review_filters_ps_workplace_id").attr('disabled', null);

				$.ajax({
					url: '<?php echo url_for('@ps_class_by_params') ?>',
			        type: "POST",
			        data: 'c_id=' + $('#ps_review_filters_ps_customer_id').val() + '&w_id=' + $('#ps_review_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_review_filters_school_year_id').val(),
			        processResults: function (data, page) {
		          		return {
		            		results: data.items
		          		};
		        	},
			    }).done(function(msg) {
			    	$('#ps_review_filters_ps_class_id').select2('val','');
					$("#ps_review_filters_ps_class_id").html(msg);
					$("#ps_review_filters_ps_class_id").attr('disabled', null);
			    });

		    });
		}
	
	});
	//end-customer

	//workplace
	$('#ps_review_filters_ps_workplace_id').change(function() {

		resetOptions('ps_review_filters_ps_class_id');
		$('#ps_review_filters_ps_class_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#ps_review_filters_ps_class_id").attr('disabled', 'disabled');
			
			$.ajax({
				url: '<?php echo url_for('@ps_class_by_params') ?>',
		        type: "POST",
		        data: 'c_id=' + $('#ps_review_filters_ps_customer_id').val() + '&w_id=' + $('#ps_review_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_review_filters_school_year_id').val(),
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {
		    	$('#ps_review_filters_ps_class_id').select2('val','');
				$("#ps_review_filters_ps_class_id").html(msg);
				$("#ps_review_filters_ps_class_id").attr('disabled', null);
		    });
			
		}		
	});
	//end-workplace
	//END: filter

	//BEGIN: Filter in New Form
	
	//workplace
	<?php $ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()->fetchOne ()->getId (); ?>
	$('#ps_review_ps_workplace_id').change(function() {

		

		// resetOptions('ps_review_ps_class_id');
		$('#ps_review_ps_class_id').select2('val','');

		// resetOptions('ps_review_student_id');
		$('#ps_review_student_id').select2('val','');
		
		// resetOptions('ps_review_relative_id');
		$('#ps_review_relative_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#ps_review_ps_class_id").attr('disabled', 'disabled');
			$("#ps_review_relative_id").attr('disabled', 'disabled');
			$("#ps_review_student_id").attr('disabled', 'disabled');
			
			$.ajax({
				url: '<?php echo url_for('@ps_class_by_params') ?>',
		        type: "POST",
		        data: 'w_id=' + $(this).val() + '&y_id=' + <?php echo $ps_school_year_id; ?>,
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg1) {
		    	$('#ps_review_ps_class_id').select2('val','');
		    	
				$("#ps_review_ps_class_id").html(msg1);
				
				$("#ps_review_ps_class_id").attr('disabled', null);
			});
			
		}		
	});
	//end-workplace
	
	//class
	$('#ps_review_ps_class_id').change(function() {

		// resetOptions('ps_review_relative_id');
		$('#ps_review_relative_id').select2('val','');

		// resetOptions('ps_review_student_id');
		$('#ps_review_student_id').select2('val','');
		
		if ($(this).val() > 0) {

 			//$("#ps_review_ps_class_id").attr('disabled', 'disabled');
			
			$.ajax({
				url: '<?php echo url_for('@ps_students_by_class_id') ?>',
		        type: "POST",
		        data: 'c_id=' + $(this).val(),
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg2) {
		    	$('#ps_review_student_id').select2('val','');
		    	
				$("#ps_review_student_id").html(msg2);
				
				$("#ps_review_student_id").attr('disabled', null);
			});
			
		}		
	});
	
	//end-class
	
	//student
	
	$('#ps_review_student_id').change(function() {

    	// resetOptions('ps_review_relative_id');
		$('#ps_review_relative_id').select2('val','');
// 		$("#ps_review_relative_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_relative_students_id') ?>',
	        type: "POST",
	        data: 's_id=' + $(this).val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_review_relative_id').select2('val','');
			$("#ps_review_relative_id").html(msg);
			$("#ps_review_relative_id").attr('disabled', null);
		});

	});


	// END: filters

	$('#ps_review_filters_ps_customer_id').change(function() {

		resetOptions('ps_review_filters_ps_workplace_id');
	    $('#ps_review_filters_ps_workplace_id').select2('val','');
	    $("#ps_review_filters_ps_workplace_id").attr('disabled', 'disabled');
    	
	    $.ajax({
	    	url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' +$(this).val(),
	    	type: 'POST',
	    	data: 'psc_id='+$(this).val(),
	    	success: function(data){
	    		$('#ps_review_filters_ps_workplace_id').show();
	    		$('#ps_review_filters_ps_workplace_id').html(data);
	    		$("#ps_review_filters_ps_workplace_id").attr('disabled', null);
	    	}
	    });		

    });

    //xử lí trong form
    $('#ps_review_ps_customer_id').change(function() {

		resetOptions('ps_review_ps_workplace_id');
	    $('#ps_review_ps_workplace_id').select2('val','');
	    $("#ps_review_ps_workplace_id").attr('disabled', 'disabled');
    	
	    $.ajax({
	    	url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' +$(this).val(),
	    	type: 'POST',
	    	data: 'psc_id='+$(this).val(),
	    	success: function(data){
	    		$('#ps_review_ps_workplace_id').show();
	    		$('#ps_review_ps_workplace_id').html(data);
	    		$("#ps_review_ps_workplace_id").attr('disabled', null);
	    	}
	    });		

    });
    
});
</script>
<?php include_partial('global/include/_box_modal')?>