$(document).ready(function() {

	$('#ps_student_growths_filters_input_date_at_from').datepicker({
		dateFormat : 'dd-mm-yy',
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
	});

	$('#ps_student_growths_filters_input_date_at_to').datepicker({
		dateFormat : 'dd-mm-yy',
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
	});
	
	$('#ps_student_growths_input_date_at').datepicker({
		dateFormat : 'dd-mm-yy',
		changeMonth : true,
		changeYear : true,
		maxDate: new Date(),
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
	}).on('changeDate', function(e) {

		$('#ps-form').formValidation('revalidateField', 'ps_student_growths[input_date_at]');
	});
	$('#ps-form').formValidation({

		framework : 'bootstrap',
		excluded : [ ':disabled' ],
		addOns : {
			i18n : {}
		},
		errorElement : "div",
		errorClass : "help-block with-errors",
		message : {
			vi_VN : 'This value is not valid'
		},
		icon : {
			valid : 'glyphicon glyphicon-ok-circle',
			invalid : 'glyphicon glyphicon-remove-circle',
			validating : 'glyphicon glyphicon-refresh'
		},
		fields : {
			"ps_student_growths[input_date_at]" : {
				validators : {
					date : {
						format : 'DD-MM-YYYY'
					}
				}
			}
		}
	});
	 $('#ps-form').formValidation('setLocale', PS_CULTURE);

			
			
	
		
		


	 
})
