$(document).ready(
		function() {
			
			var url_check_menu = URL_CHECKEMENU + '?';
			
			function formatData(data) {

				var options = document.getElementById('ps_menus_ps_food_id').options;
				
				var index = 1;

				for (var i = 0; i < options.length; i++) {
					if (options[i].value == data.id)
						index = i;
				}

				if (!data.id) {
					return data.text;
				}

				if (!document.getElementById('ps_menus_ps_food_id').options[index]) {
					return data.text;
				}

				var file_icon = document.getElementById('ps_menus_ps_food_id').options[index].getAttribute('imagesrc');

				if (!file_icon) {
					return data.text;
				}
				
				
				var $result = $('<span><img src="' + file_icon + '" class="img-flag" style="width:40px;height:40px;margin-right: 15px;" />' + data.text + '</span>');

//				var $result = $('<span><img src="http://127.0.0.1:8889/images/banner_kidsschool.vn.png" class="img-flag" style="width:40px;height:40px;margin-right: 15px;" />' + data.text + '</span>');
				
				
				return $result;
			};

			$("#ps_menus_ps_food_id").select2({
			    allowClear: true,
			    placeholder: "-Select food-",
			    formatResult: formatData,
			    formatSelection: formatData
			});
			
			$('#ps_menus_filters_date_at_from').datepicker(
					{
						dateFormat : 'dd-mm-yy',
						changeMonth : true,
						changeYear : true,
						prevText : '<i class="fa fa-chevron-left"></i>',
						nextText : '<i class="fa fa-chevron-right"></i>',
						onSelect : function(selectedDate) {
							$('#ps_menus_filters_date_at_to').datepicker(
									'option', 'minDate', selectedDate);
						}
					}).on(
					'changeDate',
					function(e) {
						// Revalidate the date field
						$('#ps-filter').formValidation('revalidateField',
								'ps_menus_filters[date_at_from]');
			});

			$('#ps_menus_filters_date_at_to').datepicker(
					{
						dateFormat : 'dd-mm-yy',
						prevText : '<i class="fa fa-chevron-left"></i>',
						nextText : '<i class="fa fa-chevron-right"></i>',
						changeMonth : true,
						changeYear : true,
						onSelect : function(selectedDate) {
							$('#ps_menus_filters_date_at_from').datepicker(
									'option', 'maxDate', selectedDate);
						}
					}).on(
					'changeDate',
					function(e) {
						// Revalidate the date field
						$('#ps-filter').formValidation('revalidateField',
								'ps_menus_filters[date_at_to]');
					});

			$('#ps_menus_date_at').datepicker({
				dateFormat : 'dd-mm-yy',
				changeMonth : true,
				changeYear : true,
				firstDay : 1,
				prevText : '<i class="fa fa-chevron-left"></i>',
				nextText : '<i class="fa fa-chevron-right"></i>',
			}).on(
					'changeDate',
					function(e) {
						$('#ps-form').formValidation('revalidateField',
								'ps_menus[date_at]');
					});

			$('#ps-form').formValidation({

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
				icon : {},
				fields : {
					"ps_menus[date_at]" : {
						validators : {
							date : {
								format : 'DD-MM-YYYY'
							}
						}
					}
				}
			});
			
			$('#ps-form-copy').formValidation({

				framework : 'bootstrap',
				excluded : [':disabled'],
				addOns : {
					i18n : {}
				},
				errorElement : "div",
				errorClass : "help-block with-errors",
				
				icon : {},
				fields : {
					"form[week_destination]" : {
						validators : {							 
		                     remote: {
		                          url: url_check_menu,
		                          data: function(validator, $field, value) {
		                            return {
		                                ps_customer_id: $('#form_ps_customer_id').val(),
		                                ps_workplace_id: $('#form_ps_workplace_id').val(),
		                                ps_object_group_id: $('#form_ps_object_group_id_destination').val(),
		                                ps_week: $('#form_ps_week_destination').val(),
		                                ps_year: $('#form_ps_year_destination').val()
		                            };
		                          },		                          
		                          type: 'GET',		                       
		                        }
						}
					}
				}
			})
				.on('success.validator.fv', function(e, data) {

			        if (data.field === 'form[week_destination]'
			            && data.validator === 'remote'
			            && (data.result.available === false || data.result.available === 'false'))
			        {
			            // The userName field passes the remote validator
			            data.element                    // Get the field element
			                .closest('.form-group')     // Get the field parent

			                // Add has-warning class
			                .removeClass('has-success')
			                .addClass('has-warning')

			                // Show message
			                .find('small[data-fv-validator="remote"][data-fv-for="form[week_destination]"]')
			                    .show();
			        }
			    })
			    // This event will be triggered when the field doesn't pass given validator
			    .on('success.validator.fv', function(e, data) {
			        // We need to remove has-warning class
			        // when the field doesn't pass any validator
			    	if (data.field === 'form[week_destination]'
			            && data.validator === 'remote'
			            && (data.result.available === true || data.result.available === 'true'))
			        {
			            data.element
			                .closest('.form-group')			           
			                .removeClass('has-warning');
			                
			        }
			})
		
	});
