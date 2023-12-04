$(document).ready(function() {
	
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
			'ps_template_exports[name_file]' : {
				validators : {
					file : {
						extension : 'xls,xlsx',
						type : 'application/vnd.ms-excel [official], application/msexcel, application/x-msexcel, application/x-ms-excel, application/vnd.ms-excel, application/x-excel, application/x-dos_ms_excel, application/xls',
						maxSize : 5 * 1024,
						message : {
							en_US : msg_name_file_invalid,
							vi_VN : msg_name_file_invalid
						}
					}
				}
			},
			'ps_template_exports[img_file]' : {
				validators : {
					file : {
						extension : 'jpeg,jpg,png,gif',
						type : 'image/jpeg,image/png,image/gif',
						maxSize : 5 * 1024,
						message : {
							en_US : msg_file_invalid,
							vi_VN : msg_file_invalid
						}
					}
				}
			},
			
			"ps_template_exports[note]": {
				validators: {
					stringLength: {
						max: function (value, validator, $field) {
                            return 300 - (value.match(/\r/g) || []).length;
                        }
                    },
				}
			}
		}
	});

	$('#ps-form').formValidation('setLocale', PS_CULTURE);
});