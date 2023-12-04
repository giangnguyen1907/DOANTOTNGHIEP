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
														$('#ps_baby_gift_form')
																.formValidation(
																		'revalidateField',
																		'ps_baby_gift[content]');
													});
								},
								selector : '#ps_baby_gift_content',
								height : '400',
								indentation : '13px',
								font_formats : 'Arial=arial,sans-serif;',
								relative_urls : false,
								remove_script_host : false,
								convert_urls : true,
								plugins : [
										'advlist autolink lists link charmap preview hr anchor pagebreak',
										'searchreplace wordcount visualblocks visualchars code fullscreen',
										'insertdatetime nonbreaking save table contextmenu directionality',
										'template paste textcolor colorpicker textpattern imagetools' ],
								toolbar : 'undo redo | fontselect fontsizeselect | bold italic forecolor backcolor alignleft aligncenter alignright alignjustify bullist numlist outdent indent link',

							});
				});
