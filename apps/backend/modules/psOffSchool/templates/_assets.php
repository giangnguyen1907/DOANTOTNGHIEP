<?php use_helper('I18N', 'Number')?>
<?php include_partial('global/field_custom/_ps_assets') ?>
<?php //$config_time_receive_valid = Doctrine::getTable('PsOffSchool')->getTimeReceiveValid()?>
<?php //$time_receive_valid = $config_time_receive_valid->getConfigTimeReceiveValid() ? $config_time_receive_valid->getConfigTimeReceiveValid():''?>

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
.description-off-school{white-space: pre-line; word-break: break-all;}
</style>

<script type="text/javascript">

$(document).ready(function() {

	//$('#ps_off_school_reason_illegal').attr('disabled', 'disabled');
	
	$('.radiobox').click(function(){
		if ($(this).val() > 1) {
			$('.btn-submit-check').addClass("disabled");
    		$('.btn-submit-check').attr('disabled', 'disabled');
    		$('#ps_off_school_reason_illegal').attr('required', true);

    		$('#ps_off_school_reason_illegal').keyup(function(){

				if(this.value.length > 0){
					$('.btn-submit-check').attr('disabled', false);
    				$('.btn-submit-check').removeClass("disabled");
				}else{
        			$('.btn-submit-check').addClass("disabled");
            		$('.btn-submit-check').attr('disabled', 'disabled');
            		$('#ps_off_school_reason_illegal').attr('required', true);
            	}
      		});
		}else{
			$('#ps_off_school_reason_illegal').attr('required', false);
			$('.btn-submit-check').attr('disabled', false);
			$('.btn-submit-check').removeClass("disabled");
		}
		
	});
	
	// BEGIN: filters in Index Form
	$('#ps_off_school_filters_school_year_id').change(function() {

		resetOptions('ps_off_school_filters_ps_class_id');
		$('#ps_off_school_filters_ps_class_id').select2('val','');
	    
		if ($(this).val() <= 0) {
			return;
		}

		$("#ps_off_school_filters_ps_workplace_id").trigger('change');
	});
	
	//customer
	$('#ps_off_school_filters_ps_customer_id').change(function() {

		resetOptions('ps_off_school_filters_ps_workplace_id');
		$('#ps_off_school_filters_ps_workplace_id').select2('val','');

		resetOptions('ps_off_school_filters_ps_class_id');
		$('#ps_off_school_filters_ps_class_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#ps_off_school_filters_ps_workplace_id").attr('disabled', 'disabled');
			$("#ps_off_school_filters_ps_class_id").attr('disabled', 'disabled');
			
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

		    	$('#ps_off_school_filters_ps_workplace_id').select2('val','');

				$("#ps_off_school_filters_ps_workplace_id").html(msg);

				$("#ps_off_school_filters_ps_workplace_id").attr('disabled', null);

				$.ajax({
					url: '<?php echo url_for('@ps_class_by_params') ?>',
			        type: "POST",
			        data: 'c_id=' + $('#ps_off_school_filters_ps_customer_id').val() + '&w_id=' + $('#ps_off_school_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_off_school_filters_school_year_id').val(),
			        processResults: function (data, page) {
		          		return {
		            		results: data.items
		          		};
		        	},
			    }).done(function(msg) {
			    	$('#ps_off_school_filters_ps_class_id').select2('val','');
					$("#ps_off_school_filters_ps_class_id").html(msg);
					$("#ps_off_school_filters_ps_class_id").attr('disabled', null);
			    });

		    });
		}
	
	});
	//end-customer

	//workplace
	$('#ps_off_school_filters_ps_workplace_id').change(function() {

		resetOptions('ps_off_school_filters_ps_class_id');
		$('#ps_off_school_filters_ps_class_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#ps_off_school_filters_ps_class_id").attr('disabled', 'disabled');
			
			$.ajax({
				url: '<?php echo url_for('@ps_class_by_params') ?>',
		        type: "POST",
		        data: 'c_id=' + $('#ps_off_school_filters_ps_customer_id').val() + '&w_id=' + $('#ps_off_school_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_off_school_filters_school_year_id').val(),
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {
		    	$('#ps_off_school_filters_ps_class_id').select2('val','');
				$("#ps_off_school_filters_ps_class_id").html(msg);
				$("#ps_off_school_filters_ps_class_id").attr('disabled', null);
		    });
			
		}		
	});
	//end-workplace
	//END: filter

	//BEGIN: Filter in New Form
	
	//workplace
	<?php $ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()->fetchOne ()->getId (); ?>
	$('#ps_off_school_ps_workplace_id').change(function() {

		resetOptions('ps_off_school_ps_class_id');
		$('#ps_off_school_ps_class_id').select2('val','');

		resetOptions('ps_off_school_student_id');
		$('#ps_off_school_student_id').select2('val','');
		
		resetOptions('ps_off_school_relative_id');
		$('#ps_off_school_relative_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#ps_off_school_ps_class_id").attr('disabled', 'disabled');
			$("#ps_off_school_relative_id").attr('disabled', 'disabled');
			$("#ps_off_school_student_id").attr('disabled', 'disabled');
			
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
		    	$('#ps_off_school_ps_class_id').select2('val','');
		    	
				$("#ps_off_school_ps_class_id").html(msg1);
				
				$("#ps_off_school_ps_class_id").attr('disabled', null);
			});

// 			$.ajax({
//				url: '<?php //echo url_for('@ps_work_places_time_receive_valid?w_id=') ?>//'  + $(this).val(),
// 		        type: "POST",
// 		        data: 'w_id=' + $(this).val(),
// 		        success: function(data)
// 		        {
// 		        	$("#ps_off_school_config_time").attr('value', data);
// 		        },

// 		        error: function(XMLHttpRequest, textStatus, errorThrown)
// 		        {
// 		            alert('Error : ' + errorThrown);
// 		        }
// 			});
			
		}		
	});
	//end-workplace
	
	//class
	$('#ps_off_school_ps_class_id').change(function() {

		resetOptions('ps_off_school_relative_id');
		$('#ps_off_school_relative_id').select2('val','');

		resetOptions('ps_off_school_student_id');
		$('#ps_off_school_student_id').select2('val','');
		
		if ($(this).val() > 0) {

 			//$("#ps_off_school_ps_class_id").attr('disabled', 'disabled');
			
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
		    	$('#ps_off_school_student_id').select2('val','');
		    	
				$("#ps_off_school_student_id").html(msg2);
				
				$("#ps_off_school_student_id").attr('disabled', null);
			});
			
		}		
	});
	
	//end-class
	
	//student
	
	$('#ps_off_school_student_id').change(function() {

    	resetOptions('ps_off_school_relative_id');
		$('#ps_off_school_relative_id').select2('val','');
// 		$("#ps_off_school_relative_id").attr('disabled', 'disabled');
		
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
	    	$('#ps_off_school_relative_id').select2('val','');
			$("#ps_off_school_relative_id").html(msg);
			$("#ps_off_school_relative_id").attr('disabled', null);
		});

	});
	
	//END: filter in New Form
	$('#ps_off_school_filters_start_at').datepicker({
		dateFormat : 'dd-mm-yy',
		
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
	});
	$('#ps_off_school_filters_stop_at').datepicker({
		dateFormat : 'dd-mm-yy',
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
	});

	
	$('#ps_off_school_date_at').datepicker({
		dateFormat : 'dd-mm-yy',
		maxDate : new Date(),
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
	});

	
	$('#ps_off_school_date_from').datepicker(
			{
				dateFormat : 'dd-mm-yy',
				minDate : new Date(),
				prevText : '<i class="fa fa-chevron-left"></i>',
				nextText : '<i class="fa fa-chevron-right"></i>',
				onSelect : function(selectedDate) {
					$('#ps_off_school_date_to')
							.datepicker('option',
									'minDate',
									selectedDate);
				}
			}).on(
			'changeDate',
			function(e) {
				// Revalidate the date field
				$('#ps_off_school_form').formValidation(
						'revalidateField',
						'ps_off_school[date][from]');
			});

	$('#ps_off_school_date_to')
		.datepicker(
				{
					dateFormat : 'dd-mm-yy',
					minDate : new Date(),
					prevText : '<i class="fa fa-chevron-left"></i>',
					nextText : '<i class="fa fa-chevron-right"></i>',
					onSelect : function(selectedDate) {
						$('#ps_off_school_date_from')
								.datepicker('option',
										'maxDate',
										selectedDate);
					}
				}).on(
				'changeDate',
				function(e) {
					// Revalidate the date field
					$('#ps_off_school_form').formValidation(
							'revalidateField',
							'ps_off_school[date][to]');
				});
	
	
});
</script>

<?php include_partial('global/include/_box_modal');?>