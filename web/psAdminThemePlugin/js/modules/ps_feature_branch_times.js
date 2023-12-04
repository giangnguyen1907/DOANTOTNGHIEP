$(document).ready(function() {
	/*
	$('.list-times .startTime').timepicker({
		timeFormat : 'HH:mm',
		showMeridian : false
	});
	
	$('.list-times .endTime').timepicker({
		timeFormat : 'HH:mm',
		showMeridian : false
	});
	*/
	
	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');						
	});
	
	$('#psactivitie_start_time').timepicker({timeFormat : 'HH:mm',showMeridian : false, minuteStep: 5});
	
	$('#psactivitie_end_time').timepicker({timeFormat : 'HH:mm',showMeridian : false, minuteStep: 5});
	
	$('#ps-filter-form').formValidation({
		framework : 'bootstrap',
		//excluded: [':disabled', ':hidden', ':not(:visible)'],
		addOns : {
			i18n : {}
		},
		icon : {
			valid : null,
			invalid : null,
			validating : null
		},
		err : {
			container: '#errors'
		},
		fields : {
			"feature_branch_times_filters[school_year_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_school_year,
                        		  en_US: msg_select_school_year
                        }
                    }
                }
            },
            
            "feature_branch_times_filters[ps_customer_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_customer_id,
                        		  en_US: msg_select_ps_customer_id
                        }
                    }
                }
            }         
		}
	}).on('err.form.fv', function(e) {
		// Show the message modal
		$('#messageModal').modal('show');
	});
	
	$('#ps-filter-form').formValidation('setLocale', PS_CULTURE);
})
