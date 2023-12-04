$(document).ready(function() {
	
	$('.timepicker').timepicker({timeFormat : 'HH:mm',showMeridian : false, minuteStep: 5});
	
	$('#ps_work_places_config_start_date_system_fee').datepicker({
		dateFormat : 'dd-mm-yy',
		maxDate : new Date(),
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true
	})
	.on('change', function(e) {
		// Revalidate the date field
		$('#ps_form_work_places').formValidation('revalidateField', $(this).attr('name'));
	});
	
	$('#ps-form')
		.formValidation({
			framework: 'bootstrap',
			excluded: [':disabled'],
	        addOns: { i18n: {} },
	        errorElement: "div",
	        errorClass: "help-block with-errors",
	        message: {vi_VN: 'This value is not valid'},
	        icon: {},		  
	        fields: {
	        	'ps_work_places[config_start_date_system_fee]': {
            		validators: {
                        date: {
                        	format: 'DD-MM-YYYY',
                            separator: '-'
                        }
                    }
                }	        	
	        }
    });

    $('#ps-form').formValidation('setLocale', PS_CULTURE);
});