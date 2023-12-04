$(document).ready(function() {

	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});

	$('#confirmDeleteRT').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	$('#confirmDeleteReceivableStudent').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	
	
	$('#ps-filter-fee-reports2').formValidation({
		framework : 'bootstrap',
		//excluded : [':disabled', ':hidden', ':not(:visible)'],
		excluded : [':disabled'],
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
			"ps_fee_reports_filters[ps_customer_id]" : {
				validators : {
					notEmpty : {
						message : {
							en_US : msg_select_ps_customer_id,
							vi_VN : msg_select_ps_customer_id
						}
					}
				}
			},			
			/*
			"ps_fee_reports_filters[ps_workplace_id]" : {
				validators : {
					notEmpty : {
						message : {
							en_US : msg_select_ps_workplace_id,
							vi_VN : msg_select_ps_workplace_id
						}
					}
				}
			},

			"ps_fee_reports_filters[ps_class_id]" : {
				validators : {
					notEmpty : {
						message : {
							en_US : msg_select_ps_class_id,
							vi_VN : msg_select_ps_class_id
						}
					}
				}
			},
			*/
			"ps_fee_reports_filters[ps_month]" : {
				validators : {
					notEmpty : {
						message : {
							en_US : msg_select_month,
							vi_VN : msg_select_month
						}
					}
				}
			},

			"ps_fee_reports_filters[ps_year]" : {
				validators : {
					notEmpty : {
						message : {
							en_US : msg_select_year,
							vi_VN : msg_select_year
						}
					}
				}
			}
		
		}
	}).on('err.form.fv', function(e) {
		// Show the message modal
		$('#messageModal').modal('show');
	});

	$('#ps-filter-fee-reports2').formValidation('setLocale', PS_CULTURE);
});
