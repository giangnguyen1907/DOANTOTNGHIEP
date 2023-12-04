$(document)
		.ready(
				function() {
					
					$('#relative_mobile').change(function(){
				        var mobile = $(this).val();
						if (mobile.length > 0) {
							mobile = mobile.replace(/[.,:\" \"]/g,'');				        
							$(this).val(mobile);
						}						
				    });
					
					$('#relative_email').change(function() {
						if ($('#ps_member_email').length > 0) {
							$('#error_list').html('');
						}
					});

					$('#relative_birthday').datepicker({
						dateFormat : 'dd-mm-yy',
						changeMonth : true,
						changeYear : true,
						prevText : '<i class="fa fa-chevron-left"></i>',
						nextText : '<i class="fa fa-chevron-right"></i>',
					}).on(
							'changeDate',
							function(e) {
								// Revalidate the date field
								$('#ps-form').formValidation('revalidateField',
										'relative[birthday]');
							});

					$('#relative_card_date').datepicker({
						dateFormat : 'dd-mm-yy',
						changeMonth : true,
						changeYear : true,
						prevText : '<i class="fa fa-chevron-left"></i>',
						nextText : '<i class="fa fa-chevron-right"></i>',
					}).on(
							'changeDate',
							function(e) {
								// Revalidate the date field
								$('#ps-form').formValidation('revalidateField',
										'relative[card_date]');
							});

					var url_util = PS_URL_PATH
							+ '/psAdminThemePlugin/js/plugin/intl-tel/js/utils.js';

//					$("#relative_mobile").intlTelInput({
//						utilsScript : url_util,
//						autoPlaceholder : true,
//						numberType : "MOBILE",
//						initialCountry : 'vn',
//						/*onlyCountries: ['vn'],*/
//						preferredCountries : 'vn'
//					});

					var url_check_email = URL_CHECKEMAIL + '?';

					$('#ps-form')
							.formValidation(
									{

										framework : 'bootstrap',
										//excluded : [':disabled'],
										addOns : {
											i18n : {}
										},
										errorElement : "div",
										errorClass : "help-block with-errors",
										message : {
											vi_VN : 'This value is not valid'
										},
										icon : {
											valid : 'glyphicon glyphicon-ok-circle',
											invalid : 'glyphicon glyphicon-remove-circle',
											validating : 'glyphicon glyphicon-refresh'
										},
										fields : {
											"relative[birthday]" : {
												validators : {
													date : {
														format : 'DD-MM-YYYY'
													}
												}
											},

											"relative[card_date]" : {
												validators : {
													date : {
														format : 'DD-MM-YYYY'
													}
												}
											},
											
											/*

											"relative[mobile]" : {
												validators : {
													callback : {
														message : {
															vi_VN : msg_mobile_invalid,
															en_US : msg_mobile_invalid
														},
														callback : function(
																value,
																validator,
																$field) {
															return {
																valid : value === ''
																		|| $field
																				.intlTelInput('isValidNumber'),
																type : $field
																		.intlTelInput('getNumberType')
															};
														}
													}
												}
											},*/

											"relative[identity_card]" : {
												//verbose: false,
												//threshold: 8,
												//trigger: 'blur',
												validators : {
													regexp : {
														regexp : /^[a-zA-Z0-9\.]+$/,
														message : {
															vi_VN : msg_identity_card_invalid,
															en_US : msg_identity_card_invalid
														}
													},
													stringLength : {
														min : 8,
														max : 12,
														message : {
															vi_VN : msg_identity_card_lenght,
															en_US : msg_identity_card_lenght
														}
													}
												}
											},

											"relative[email]" : {
												verbose : false,
												trigger : 'blur',
												validators : {
													regexp : {
														regexp : '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
														message : {
															vi_VN : msg_email_invalid,
															en_US : msg_email_invalid
														},
													},
													remote : {
														url : url_check_email,
														data : function(
																validator,
																$field, value) {
															return {
																email : $(
																		'#relative_email')
																		.val(),
																objid : $(
																		'#relative_id')
																		.val(),
																objtype : 'R'
															};
														},
														message : {
															vi_VN : msg_email_exist,
															en_US : msg_email_exist
														},
														type : 'GET',
														delay : 1000
													}

												}
											},
											"relative[image]" : {
												validators : {
													file : {
														extension : 'jpeg,jpg,png,gif',
														type : 'image/jpeg,image/png,image/gif',
														maxSize : PsMaxSizeFile * 1024
													}
												}
											}

										}

									})
							.on(
									'err.validator.fv',
									function(e, data) {
										if (data.field === 'relative[email]') {
											// The email field is not valid
											data.element
													.data('fv.messages')
													// Hide all the messages
													.find(
															'.help-block[data-fv-for="'
																	+ data.field
																	+ '"]')
													.hide()
													// Show only message associated with current validator
													.filter(
															'[data-fv-validator="'
																	+ data.validator
																	+ '"]')
													.show();
										}
									});

					$('#ps-form').formValidation('setLocale', PS_CULTURE);

				});