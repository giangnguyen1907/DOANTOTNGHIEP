$(document).ready(function() {
	
	if (typeof number_page !== 'undefined' && number_page > 0) {

		$('.time_picker').timepicker({
			timeFormat : 'HH:mm',
			showMeridian : false,
			defaultTime : null
		});
	}

	/*

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

		}
	}).on('err.form.fv', function(e) {
		// Show the message modal
		$('#messageModal').modal('show');
	});
	*/
	
	/*
	.on('err.field.fv', function(e, data) {
	        // $(e.target)  --> The field element
	        // data.fv      --> The FormValidation instance
	        // data.field   --> The field name
	        // data.element --> The field element

	        // Hide the messages
	        data.element
	            .data('fv.messages')
	            .find('.help-block[data-fv-for="' + data.field + '"]').hide();
	});	*/

	//$('#ps-filter-form').formValidation('setLocale', PS_CULTURE);
	
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
			"ps_logtimes_filters[school_year_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_date,
                        		  en_US: msg_select_date
                        }
                    }
                }
            },
            
            "ps_logtimes_filters[ps_customer_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_customer_id,
                        		  en_US: msg_select_ps_customer_id
                        }
                    }
                }
            },
            
            "ps_logtimes_filters[ps_class_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_class_id,
                        		  en_US: msg_select_ps_class_id
                        }
                    }
                }
            },

            "ps_logtimes_filters[tracked_at]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_date,
                        		  en_US: msg_select_date
                        }
                    },
                    date: {
                        format: 'DD-MM-YYYY',
                        message: {vi_VN: 'The value is not a valid date',
                        		  en_US: 'The value is not a valid date'
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
	
	$('#ps_logtimes_filters_tracked_at').datepicker({
		dateFormat : 'dd-mm-yy',
		maxDate : new Date(),
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,

	})
	.on('change', function(e) {
		// Revalidate the date field
		$('#ps-filter-form').formValidation('revalidateField', $(this).attr('name'));
	});
	
	$('#ps-form-logtimes').formValidation({
		framework : 'bootstrap',
		excluded: [':disabled', ':hidden', ':not(:visible)'],
		addOns : {
			i18n : {}
		},
		icon : {
			valid : null,
			invalid : null,
			validating : null
		},
		fields : {
			"ps_logtimes[student_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_student,
                        		  en_US: msg_select_student
                        }
                    }
                }
            },
            
            "ps_logtimes[login_relative_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_login_relative,
                        		  en_US: msg_select_login_relative
                        }
                    }
                }
            },
            
            "ps_logtimes[login_member_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_teacher_received,
                        		  en_US: msg_select_teacher_received
                        }
                    }
                }
            }           
		}
	});
	
	$('#ps-form-logtimes').formValidation('setLocale', PS_CULTURE);

})
