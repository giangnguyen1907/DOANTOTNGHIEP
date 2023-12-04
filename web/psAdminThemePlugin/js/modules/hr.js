$(document)
		.ready(
				function() {

					$('#ps_member_mobile').change(function(){
				        var mobile = $(this).val();
						if (mobile.length > 0) {
							mobile = mobile.replace(/[.,:\" \"]/g,'');				        
							$(this).val(mobile);
						}						
				    });
					
					$('#ps_member_email').change(function() {
						if ($('#ps_member_email').length > 0) {
							$('#error_list').html('');
						}
					});

					$('#ps_member_birthday').datepicker({
						dateFormat : 'dd-mm-yy',
						changeMonth : true,
						changeYear : true,
						prevText : '<i class="fa fa-chevron-left"></i>',
						nextText : '<i class="fa fa-chevron-right"></i>',
					}).on('changeDate',
							function(e) {
								// Revalidate the date field
								$('#ps-form').formValidation('revalidateField',
										'ps_member[birthday]');
							});

					$('#ps_member_card_date').datepicker({
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
										'ps_member[card_date]');
							});

					var url_util = PS_URL_PATH
							+ '/psAdminThemePlugin/js/plugin/intl-tel/js/utils.js';
/*
					$("#ps_member_mobile").intlTelInput({
						utilsScript : url_util,
						autoPlaceholder : true,
						numberType : "MOBILE",
						initialCountry : 'vn',
						preferredCountries : 'vn'
					});
*/
					var url_check_email = URL_CHECKEMAIL + '?';

					var _PS_CULTURE = 'vi_VN';

					$('#ps-form')
							.formValidation(
									{

										framework : 'bootstrap',
										excluded : [':disabled'],
										addOns : {
											i18n : {}
										},
										errorElement : "div",
										errorClass : "help-block with-errors",
										icon : {
											valid : 'glyphicon glyphicon-ok-circle',
											invalid : 'glyphicon glyphicon-remove-circle',
											validating : 'glyphicon glyphicon-refresh'
										},
										fields : {
											"ps_member[birthday]" : {
												validators : {
													date : {
														format : 'DD-MM-YYYY'
													}
												}
											},

											"ps_member[card_date]" : {
												validators : {
													date : {
														format : 'DD-MM-YYYY',
														message : 'The full name must be less than 50 characters'
													}
												}
											},
											
											/*
											"ps_member[mobile]" : {
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
											
											"ps_member[identity_card]" : {
												//verbose: false,
												//threshold: 4,
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
											"ps_member[email]" : {
												verbose : false,
												trigger : 'blur',
												validators : {													
													regexp : {
														regexp : '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$'
													},
													remote : {
														url : url_check_email,
														data : function(
																validator,
																$field, value) {
															return {
																email : $(
																		'#ps_member_email')
																		.val(),
																objid : $(
																		'#ps_member_id')
																		.val(),
																objtype : 'T'
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
											"ps_member[image]" : {
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
										if (data.field === 'ps_member[email]') {
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

					$('#ps-form').formValidation('revalidateField',
							"ps_member[identity_card]");

					$('#ps-form').formValidation('setLocale', PS_CULTURE);

				});