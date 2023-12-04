$(document).ready(function() {
	
	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	$('#messageModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	$('#remoteModalContent').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	$('#ps-filter-form').formValidation({
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
			
			"fee_receivable_student_filter[year_month]" : {
				validators : {
					notEmpty : {
						message : {
							en_US : msg_select_month,
							vi_VN : msg_select_month
						}
					}
				}
			},			
			
			"fee_receivable_student_filter[ps_customer_id]" : {
				validators : {
					notEmpty : {
						message : {
							en_US : msg_select_ps_customer_id,
							vi_VN : msg_select_ps_customer_id
						}
					}
				}
			},
			"fee_receivable_student_filter[ps_workplace_id]" : {
				validators : {
					notEmpty : {
						message : {
							en_US : msg_select_ps_workplace_id,
							vi_VN : msg_select_ps_workplace_id
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
});
