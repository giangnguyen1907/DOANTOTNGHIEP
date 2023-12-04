$(document)
.ready(
		function() {
			
			$('#ps_off_school_date_from')
					.datepicker(
							{
								dateFormat : 'dd-mm-yy',
								prevText : '<i class="fa fa-chevron-left"></i>',
								nextText : '<i class="fa fa-chevron-right"></i>',
								onSelect : function(selectedDate) {
									$('#ps_off_school_date_to')
											.datepicker('option',
													'minDate',
													selectedDate);
								}
							}).on(
							'changeDate',
							function(e) {
								// Revalidate the date field
								$('#ps_off_school_form').formValidation(
										'revalidateField',
										'ps_off_school[date][from]');
							});

			$('#ps_off_school_date_to')
					.datepicker(
							{
								dateFormat : 'dd-mm-yy',
								prevText : '<i class="fa fa-chevron-left"></i>',
								nextText : '<i class="fa fa-chevron-right"></i>',
								onSelect : function(selectedDate) {
									$('#ps_off_school_date_from')
											.datepicker('option',
													'maxDate',
													selectedDate);
								}
							}).on(
							'changeDate',
							function(e) {
								// Revalidate the date field
								$('#ps_off_school_form').formValidation(
										'revalidateField',
										'ps_off_school[date][to]');
							});

			$('#ps_off_school_form')
					
					.find('[name="ps_off_school[date][from]"]')
					.change(
							function(e) {
								$('#ps_off_school_form').formValidation(
										'revalidateField',
										'ps_off_school[date][from]');
							})
					.end()
					.find('[name="ps_off_school[date][to]"]')
					.change(
							function(e) {
								$('#ps_off_school_form').formValidation(
										'revalidateField',
										'ps_off_school[date][to]');
							})
					.end()
					.formValidation(
							{

								framework : 'bootstrap',
								excluded: [':disabled', ':hidden'],
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
									
									"ps_off_school[date][from]" : {
										validators : {
											notEmpty : {},
											date : {
												format : 'DD-MM-YYYY',
												max : 'ps_off_school[date][to]'
											}
										},
										onSuccess : function(e, data) {
											if (!data.fv
													.isValidField('ps_off_school[date][to]')) {
												data.fv
														.revalidateField('ps_off_school[date][to]');
											}
										}
									},

									"ps_off_school[date][to]" : {
										validators : {
											notEmpty : {},
											date : {
												format : 'DD-MM-YYYY',
												// min:
												// $('#ps_school_year_date_from').val(),
												min : 'ps_off_school[date][from]',
											}
										},
										onSuccess : function(e, data) {
											if (!data.fv
													.isValidField('ps_off_school[date][from]')) {
												data.fv
														.revalidateField('ps_off_school[date][from]');
											}
										}
									},
								}

							})
					.on(
							'success.field.fv',
							function(e, data) {
								
								if (data.field === "ps_off_school_form[date][from]"
										&& !data.fv
												.isValidField('ps_off_school_form[date][to]')) {
									// need to revalidate the end date
									data.fv
											.revalidateField('ps_off_school_form[date][to]');
								}
								if (data.field === 'ps_off_school_form[date][to]'
										&& !data.fv
												.isValidField('ps_off_school_form[date][from]')) {
									// need to revalidate the start date
									data.fv
											.revalidateField('ps_off_school_form[date][from]');
								}
							});

			$('#ps_off_school_form').formValidation('setLocale', PS_CULTURE);

		});

