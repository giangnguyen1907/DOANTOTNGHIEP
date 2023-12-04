$(document)
		.ready(
				function() {

					function formatData(data) {

						var options = document
								.getElementById('feature_branch_ps_image_id').options;

						var index = 1;

						for (var i = 0; i < options.length; i++) {
							if (options[i].value == data.id)
								index = i;
						}

						if (!data.id) {
							return data.text;
						}

						if (!document
								.getElementById('feature_branch_ps_image_id').options[index]) {
							return data.text;
						}

						var file_icon = document
								.getElementById('feature_branch_ps_image_id').options[index]
								.getAttribute('imagesrc');

						if (!file_icon) {
							return data.text;
						}

						var $result = $('<span><img src="'
								+ file_icon
								+ '" class="img-flag" style="width:25px;height:25px;margin-right: 15px;" />'
								+ data.text + '</span>');

						return $result;
					};

					$("#feature_branch_ps_image_id").select2({
						allowClear : true,
						formatResult : formatData,
						formatSelection : formatData
					});

					$.datepicker.setDefaults($.datepicker.regional['vi']);

					$('.list-times .startDate')
							.datepicker(
									{
										prevText : '<i class="fa fa-chevron-left"></i>',
										nextText : '<i class="fa fa-chevron-right"></i>',
										changeMonth : true,
										changeYear : true,
										showButtonPanel : true,
										dateFormat : 'dd-mm-yy',
										onSelect : function(selectedDate) {
											$('#' + $(this).attr('id'))
													.datepicker('option',
															'minDate',
															selectedDate);
											$('#ps-form').formValidation(
													'revalidateField',
													$(this).attr('name'));
										}
									}).on(
									'change',
									function(e) {
										// Revalidate the date field
										$('#ps-form').formValidation(
												'revalidateField',
												$(this).attr('name'));
									});

					$('.list-times .endDate')
							.datepicker(
									{
										prevText : '<i class="fa fa-chevron-left"></i>',
										nextText : '<i class="fa fa-chevron-right"></i>',
										changeMonth : true,
										changeYear : true,
										showButtonPanel : true,
										dateFormat : 'dd-mm-yy',
										onSelect : function(selectedDate) {
											$('#' + $(this).attr('id'))
													.datepicker('option',
															'maxDate',
															selectedDate);
											$('#ps-form').formValidation(
													'revalidateField',
													$(this).attr('name'));
										}
									}).on(
									'change',
									function(e) {
										// Revalidate the date field
										$('#ps-form').formValidation('revalidateField',$(this).attr('name'));
									});
					
					var rowIndex = 0, MAX_ROW = 5;
					
					var numberFeatureBranchTimes = $('#count').val();

					$('#ps-form')
						.formValidation({
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
							fields : {}
						})
						
						.on('success.field.fv', function(e, data) {
            
							var add_start_date 	= 'feature_branch[new][' + rowIndex +'][start_at]';
							var add_end_date 	= 'feature_branch[new][' + rowIndex +'][end_at]';
							
							//console.log(data);

							if (data.field === add_start_date && !data.fv.isValidField(add_end_date)) {
								// We need to revalidate the end date
								data.fv.revalidateField(add_end_date);
							}

							if (data.field === add_end_date && !data.fv.isValidField(add_start_date)) {
								// We need to revalidate the start date
								data.fv.revalidateField(add_start_date);
							}							
						})
						
						// Called after removing the field
						.on('removed.field.fv', function(e, data) {
						   
						   var $row  = data.element.parents('.list-new'),index = $row.attr('data-book-index');

						   $('#ps-form').formValidation('revalidateField', 'newfieldscount');

						   var newfieldscount = $('#newfieldscount').val();

							if (newfieldscount < MAX_ROW) {
								$('#btn_add').attr('disabled', null);
							}
						})
						
						.on('added.field.fv', function(e, data) {            

							$('#ps-form').formValidation('revalidateField', 'newfieldscount');

							var $row  = data.element.parents('.list-new'),
								index = $row.attr('data-book-index');

							var newfieldscount = $('#newfieldscount').val();
							
							if (newfieldscount >= MAX_ROW) {
								$('#btn_add').attr('disabled', 'disabled');
							}
							
							var add_start_at 	= 'feature_branch[new][' + index +'][start_at]';
							var add_end_at 		= 'feature_branch[new][' + index +'][end_at]';
							
							var add_id_start_at 	= 'feature_branch_new_' + index + '_start_at'; 
							
							if (data.field === add_start_at) {
							
							//alert($(this).attr('id'));
							
							// The new due date field is just added
							// Create a new date picker
								data.element
								.datepicker({
									prevText : '<i class="fa fa-chevron-left"></i>',
										nextText : '<i class="fa fa-chevron-right"></i>',
										changeMonth : true,
										changeYear : true,
										showButtonPanel : true,
										dateFormat : 'dd-mm-yy',
										onSelect : function(selectedDate){
											$('#' + add_id_start_at).datepicker('option','minDate',selectedDate);											
											$('#ps-form').formValidation('revalidateField', add_start_at);											
										}
								})
								.on('change', function(e) {
									// Revalidate the date field
									$('#ps-form').formValidation('revalidateField', data.element);
								});

							}
						})
						
						// Add button click handler
						.on('click', '#btn_add', function() {
							
							rowIndex++;
							
							var newfieldscount = $('#newfieldscount').val();

							$('#newfieldscount').val(++newfieldscount);

							var $template = $('#rowTemplate'),
								$clone    = $template
												.clone()
												.removeClass('hide')
												.removeAttr('id')
												.attr('data-book-index', rowIndex)
												.insertBefore($template);
												
							$clone
								.find('[name="feature_branch[new][temp][start_at]"]').attr('name', 'feature_branch[new][' + rowIndex + '][start_at]').end()
								.find('[name="feature_branch[new][temp][end_at]"]').attr('name', 'feature_branch[new][' + rowIndex + '][end_at]').end()            
								.find('[name="feature_branch[new][temp][start_time]"]').attr('name', 'feature_branch[new][' + rowIndex + '][start_time]').end()
								.find('[name="feature_branch[new][temp][end_time]"]').attr('name', 'feature_branch[new][' + rowIndex + '][end_time]').end()
								.find('[name="feature_branch[new][temp][ps_class_room_id]"]').attr('name', 'feature_branch[new][' + rowIndex + '][ps_class_room_id]').end()
								.find('[name="feature_branch[new][temp][note]"]').attr('name', 'feature_branch[new][' + rowIndex + '][note]').end()
							
								.find('[id="feature_branch_new_temp_start_at"]').attr('id', 'feature_branch_new_' + rowIndex + '_start_at').end()  
								.find('[id="feature_branch_new_temp_end_at"]').attr('id', 'feature_branch_new_' + rowIndex + '_end_at').end()  
								.find('[id="feature_branch_new_temp_start_time"]').attr('id', 'feature_branch_new_' + rowIndex + '_start_time').end()  
								.find('[id="feature_branch_new_temp_end_time"]').attr('id', 'feature_branch_new_' + rowIndex + '_end_time').end()  
								.find('[id="feature_branch_new_temp_ps_class_room_id"]').attr('id', 'feature_branch_new_' + rowIndex + '_ps_class_room_id').end()  
								.find('[id="feature_branch_new_temp_note"]').attr('id', 'feature_branch_new_' + rowIndex + '_note').end();
							
							// Add new fields
							$('#ps-form')
								.formValidation('addField', 'feature_branch[new][' + rowIndex + '][start_at]', {
									row: '.col-md-12'
								})
								.formValidation('addField', 'feature_branch[new][' + rowIndex + '][end_at]', {
									row: '.col-md-12'
								})
								.formValidation('addField', 'feature_branch[new][' + rowIndex + '][start_time]', {
									row: '.col-md-12'
								})
								.formValidation('addField', 'feature_branch[new][' + rowIndex + '][end_time]', {
									row: '.col-md-12'
								})
								.formValidation('addField', 'feature_branch[new][' + rowIndex + '][ps_class_room_id]', {
									row: '.col-md-12'
								})
								.formValidation('addField', 'feature_branch[new][' + rowIndex + '][note]', {
									row: '.col-md-12'
								});            
						})
						// Remove button click handler
						.on('click', '.removeButton', function() {
							
							var $row  = $(this).parents('.list-new'),index = $row.attr('data-book-index');
							
							// Remove fields
							$('#ps-form')
								.formValidation('removeField', $row.find('[name="feature_branch[new][' + index + '][start_at]"]'))
								.formValidation('removeField', $row.find('[name="feature_branch[new][' + index + '][end_at]"]'))
								.formValidation('removeField', $row.find('[name="feature_branch[new][' + index + '][start_time]"]'))
								.formValidation('removeField', $row.find('[name="feature_branch[new][' + index + '][end_time]"]'))
								.formValidation('removeField', $row.find('[name="feature_branch[new][' + index + '][ps_class_room_id]"]'))
								.formValidation('removeField', $row.find('[name="feature_branch[new][' + index + '][note]"]'));

							// Remove element containing the fields
							$row.remove();

							var newfieldscount = $('#newfieldscount').val();

							if (newfieldscount >= 1)
							   $('#newfieldscount').val(newfieldscount - 1);

						})
						;// end on click

					$('.list-times .startTime').timepicker({
						timeFormat : 'HH:mm',
						showMeridian : false
					});
					
					$('.list-times .endTime').timepicker({
						timeFormat : 'HH:mm',
						showMeridian : false
					});

					$('#ps-form').formValidation('setLocale', PS_CULTURE);
				});