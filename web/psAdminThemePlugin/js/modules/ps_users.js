$(document).ready(function() {	
	
	$('#ps-form')
		.formValidation({
			framework: 'bootstrap',
			excluded: [':disabled', ':hidden'],
	          addOns: {
	               i18n: {}
	        },
	        errorElement: "div",
	        errorClass: "help-block with-errors",
	        message: {vi_VN: 'This value is not valid'},
	        icon: {
	        	
	        },
			fields: {
				"sf_guard_user[username]": {
                    verbose: false,
					threshold: 4,
					trigger: 'blur',
                    validators: {
                        regexp: {
                        	regexp: /^[a-zA-Z0-9@_\.]+$/,
                        	message: {
                        		en_US: 'The username can only consist of alphabetical, number, dot, @ and underscore.',
                        		vi_VN: 'Tên người dùng chỉ có thể bao gồm các chữ cái, số, dấu chấm, @ và gạch dưới.'
                        	}
                        },
                        stringLength: {
                            min: 4,
                            max: 50,
                            message: {
                                en_US: 'The username must be more than 4 and less than 50 characters long.',
                                vi_VN: 'Tên người dùng phải từ 4 đến 50 ký tự.'
                            }
                        },
                        remote: {
							url: router_check,
							data: function(validator, $field, value) {
								return {
									userid: $('#sf_guard_user_id').val()
								};
							},
							message: {
								en_US: 'Username already exist.',
								vi_VN: 'Tên đăng nhập này đã tồn tại.'
							},
							type: 'POST',
							delay: 1000
						}                        
                    }
                }
			}
    });
	
    $('#ps-form').formValidation('setLocale', PS_CULTURE);    
});