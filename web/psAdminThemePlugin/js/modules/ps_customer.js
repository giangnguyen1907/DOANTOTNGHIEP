$(document).ready(function() {
	
	// $('#tabs').tabs();
	if ($("#ps_customer_ps_province_id").val() <= 0) {
		$("#ps_customer_ps_district_id").attr('disabled', 'disabled');
	}
	;

	if ($("#ps_customer_ps_district_id").val() <= 0) {
		$("#ps_customer_ps_ward_id").attr('disabled', 'disabled');
	} else {
		$("#ps_customer_ps_ward_id").attr('disabled', null);
	}

	$("#ps_customer_ps_district_id").on('change', function(e) {

		if ($("#ps_customer_ps_district_id").val() <= 0) {

			$("#ps_customer_ps_ward_id").attr('disabled', 'disabled');

		} else {

			$("#ps_customer_ps_ward_id").attr('disabled', null);
		}
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

		},
		fields : {
			'ps_customer[logo]' : {
				validators : {
					file : {
						extension : 'jpeg,jpg,png,gif',
						type : 'image/jpeg,image/png,image/gif',
						maxSize : PsMaxSizeFile * 1024,
						message : {
							en_US : msg_file_invalid,
							vi_VN : msg_file_invalid
						}
					}
				}
			},
			
			"ps_customer[description]": {
				validators: {
					stringLength: {
						max: function (value, validator, $field) {
                            return 2000 - (value.match(/\r/g) || []).length;
                        },
						message: {
							en_US: 'Không quá 2000 ký tự',
							vi_VN: 'Không quá 2000 ký tự'
						}
                    },
				}
			}
		}
	});

	$('#ps-form').formValidation('setLocale', PS_CULTURE);
});