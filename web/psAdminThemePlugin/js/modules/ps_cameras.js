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
		icon : {},
		fields : {
			'ps_camera[image_name]' : {
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
			}
		}
	});

	$('#ps-form').formValidation('setLocale', PS_CULTURE);
});