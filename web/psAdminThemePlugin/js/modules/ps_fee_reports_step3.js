$(document).ready(function() {
	
	$('#messageModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	$('#fstep3').formValidation({
		framework : 'bootstrap',
		excluded : [':disabled', ':hidden', ':not(:visible)'],
		addOns : {
			i18n : {}
		},
		err : {
			container : '#errors'
		},
		message : {
			vi_VN : 'This value is not valid'
		},
		icon : {},
		fields : {
			
			"control_filter[year_month]" : {
				validators : {
					notEmpty : {
						message : {
							en_US : "Month",
							vi_VN : "Month"
						}
					}
				}
			},			
			
			"control_filter[normal_day]" : {
				validators : {
					notEmpty : {
						message : {
							en_US : msg_normal_day_invalid,
							vi_VN : msg_normal_day_invalid
						}
					}
				}
			},
			"control_filter[saturday_day]" : {
				validators : {
					notEmpty : {
						message : {
							en_US : msg_saturday_day_invalid,
							vi_VN : msg_saturday_day_invalid
						}
					}
				}
			}
		}
	}).on('err.form.fv', function(e) {
		// Show the message modal
		$('#messageModal').modal('show');
	});

	$('#fstep3').formValidation('setLocale', PS_CULTURE);
});
