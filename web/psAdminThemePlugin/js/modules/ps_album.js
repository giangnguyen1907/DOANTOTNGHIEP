$(document)
		.ready(
				function() {

					tinymce
							.init({
								language : PS_CULTURE,
								setup : function(editor) {
									editor
											.on(
													'keyup',
													function(e) {
														$(
																'#ps_album_form')
																.formValidation(
																		'revalidateField',
																		'ps_album[content]');
													});
								},
								height : 250,
								selector : '#ps_album_content',
								indentation : '13px',
								font_formats : 'Arial=arial,sans-serif;',
								relative_urls : false,
								remove_script_host : false,
								convert_urls : true,
								plugins : [
										'advlist autolink lists link image media charmap print preview hr anchor pagebreak',
										'searchreplace wordcount visualblocks visualchars code fullscreen',
										'insertdatetime media nonbreaking save table contextmenu directionality',
										'emoticons template paste textcolor colorpicker textpattern imagetools' ],
								toolbar1 : 'undo redo | fontselect | fontsizeselect | styleselect | bold italic | forecolor backcolor | table | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
								toolbar2 : 'link image media | preview | code',
								font_formats : 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats',
								fontsize_formats : '8pt 9pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 17pt 18pt 19pt 20pt 21pt 22pt 23pt 24pt 26pt 28pt 30pt 32pt 34pt 36pt',
								fullpage_default_font_family : "Helvetica,Verdana,Georgia, Serif;",
								image_advtab : true,
								file_picker_types: 'file, image',
								file_browser_callback_types: 'file, image',
								file_browser_callback : function(field, url,
										type, win) {

									tinyMCE.activeEditor.windowManager.open({
										file : url_toolfile
												+ '?opener=tinymce4&field='
												+ field + '&type=' + type
												+ '&lang=vi',
										title : 'KidsSchool.vn',
										width : 1024,
										height : 600,
										resizable : true,
										inline : true,
										close_previous : false,
										popup_css : false
									}, {
										window : win,
										input : field
									});

									return false;
								}
							});

					$('#ps_album_form')
							.formValidation(
									{
										framework : 'bootstrap',
										addOns : {

											i18n : {}
										},

										icon : {},
										fields : {
											// 'ps_album[title]' : {
											// 	validators : {
											// 		notEmpty : {
											// 			message : {
											// 				en_US : 'Please enter title',
											// 				vi_VN : 'Vui lòng nhập tiêu đề'
											// 			}
											// 		},
											// 		stringLength : {
											// 			max : 150,
											// 			message : {
											// 				en_US : 'Title must be lower than %s characters long',
											// 				vi_VN : 'Tối đa %s ký tự'
											// 			}
											// 		}
											// 	}
											// },
											'ps_album[note]' : {
												validators : {
													notEmpty : {
														message : {
															en_US : 'Please enter note',
															vi_VN : 'Vui lòng nhập mô tả ngắn'
														}
													},
													stringLength : {
														max : 250,
														message : {
															en_US : 'Note must be lower than %s characters long',
															vi_VN : 'Tối đa %s ký tự'
														}
													}
												}
											},

											'ps_album[content]' : {
												validators : {

													callback : {
														message : {
															en_US : 'Please enter description',
															vi_VN : 'Vui lòng nhập nội dung'
														},
														callback : function(
																value,
																validator,
																$field) {
															var text = tinyMCE.activeEditor
																	.getContent({
																		format : 'text'
																	});
															return text.length > 0;
														}
													}
												}
											},
											'ps_album[file_name]' : {
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
										}
									});

					$('#ps_album_form').formValidation('setLocale',
							PS_CULTURE);

				});