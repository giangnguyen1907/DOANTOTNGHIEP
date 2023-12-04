$(document).ready(function() {
    
	function formatData(data) {

		var options = document.getElementById('service_ps_image_id').options;
		
		var index = 1;

		for (var i = 0; i < options.length; i++) {
			if (options[i].value == data.id)
				index = i;
		}

		if (!data.id) {
			return data.text;
		}

		if (!document.getElementById('service_ps_image_id').options[index]) {
			return data.text;
		}

		var file_icon = document.getElementById('service_ps_image_id').options[index].getAttribute('imagesrc');

		if (!file_icon) {
			return data.text;
		}

		var $result = $('<span><img src="'
				+ file_icon
				+ '" class="img-flag" style="width:20px;height:20px;margin-right: 15px;" />'
				+ data.text + '</span>');

		return $result;
	};

	$("#service_ps_image_id").select2({
	    allowClear: true,
	    placeholder: "-Select icon-",
	    formatResult: formatData,
	    formatSelection: formatData
	});
	
	$.datepicker.setDefaults($.datepicker.regional[ "vi" ]);	
	
	$('.list-detail .startDate')
	 	.datepicker({
	 		monthNamesShort: monthNameTypeNumber,
	 		prevText : '<i class="fa fa-chevron-left"></i>',
		    nextText : '<i class="fa fa-chevron-right"></i>',
			changeMonth: true,
	        changeYear: true,
	        dateFormat: 'dd-mm-yy',
	        onSelect : function(selectedDate) {
	        	$('#psactivitie_end_at').datepicker('option','minDate',selectedDate);
				$('#ps-form').formValidation('revalidateField', 'psactivitie[end_at]');
			}
	 	})	 	
	 	.on('change', function(e) {
	      // Revalidate the date field
	      $('#ps-form').formValidation('revalidateField', $(this).attr('name'));
    	});

	$('.list-detail .endDate')
	 	.datepicker({
	 		monthNamesShort: monthNameTypeNumber,
	 		prevText : '<i class="fa fa-chevron-left"></i>',
		    nextText : '<i class="fa fa-chevron-right"></i>',
			changeMonth: true,
	        changeYear: true,
	        dateFormat: 'dd-mm-yy'
	 	})
	 	.on('change', function(e) {
	      // Revalidate the date field
	      $('#ps-form').formValidation('revalidateField', $(this).attr('name'));
    	});
 		
	var serviceDetailIndex = 0,	MAX_ROW = 5;
	
	var numberDetail = $('#count').val();
	
	for (var i = 0; i < numberDetail; i++) {
		
		$('#ps-form').formValidation('addField', 'service[ServiceDetail][' + i + '][amount]', {
			row: '.col-md-3',
    		validators: {
    			numeric: {
                	message: msg_invalid_amount
                }
            }
		});
		
		$('#ps-form').formValidation('addField', 'service[ServiceDetail][' + i + '][by_number]', {
			row: '.col-md-2',
            validators: {
            	numeric: {
                    message: msg_invalid_number
                },
                between: {
                    min: 1,
                    max: 100,
                    message: msg_invalid_by_number_between
                }
            }
		});
		
		var startDate 	= 'service[ServiceDetail][' + i + '][detail_at]',
			endDate 	= 'service[ServiceDetail][' + i + '][detail_end]';
		
		$('#ps-form').formValidation('addField', startDate, {
			row: '.col-md-3',
			validators: {                
                date: {
                	format: 'DD-MM-YYYY',
                    separator: '-',
                    message: msg_start_date_invalid
                }
                /*
                callback: {
                        message: msg_start_date_invalid,
                        callback: function (value, validator) {
                            var start_date = new moment(value, 'MM-YYYY', true);
                            
                            if (!start_date.isValid()) {
                                //return false;
                            }                            
                            start_year  = start_date.year();
                            start_month = start_date.months();

                            alert(start_month);
                            alert(start_year);
                        }
                }*/
            },
            onSuccess: function(e, data) {
                if (!data.fv.isValidField(endDate)) {
                    data.fv.revalidateField(endDate);
                }
            }
		} );
		
		$('#ps-form').formValidation('addField', endDate, {
			row: '.col-md-3',
			validators: {
                date: {
                	format: 'DD-MM-YYYY',
                    separator: '-',
                    message: msg_end_date_invalid
                }
            },
            onSuccess: function(e, data) {
                if (!data.fv.isValidField(startDate)) {
                    data.fv.revalidateField(startDate);
                }
            }
		} );
	}
	
	$('#ps-form')
	    .formValidation({
	        framework: 'bootstrap',
	        excluded: [':disabled', ':hidden', ':not(:visible)'],
	        icon: {
	            valid: 'glyphicon glyphicon-ok',
	            invalid: 'glyphicon glyphicon-remove',
	            validating: 'glyphicon glyphicon-refresh'
	        },
	        addOns: {
	            i18n: {}
	        },
	        fields: {
	            
	        }
	    })

	    .on('success.field.fv', function(e, data) {
            
        	var add_start_date 	= 'service[new][' + serviceDetailIndex +'][detail_at]';
            var add_end_date 	= 'service[new][' + serviceDetailIndex +'][detail_end]';
            
            //console.log(data);

        	if (data.field === add_start_date && !data.fv.isValidField(add_end_date)) {
                // We need to revalidate the end date
                data.fv.revalidateField(add_end_date);
            }

            if (data.field === add_end_date && !data.fv.isValidField(add_start_date)) {
                // We need to revalidate the start date
                data.fv.revalidateField(add_start_date);
            }

            /*
            for (var i = 0; i < numberDetail; i++) {
            	var start_date 	= 'service[ServiceDetail][' + i + '][detail_at]',
					end_date 	= 'service[ServiceDetail][' + i + '][detail_end]';

					if (data.field === start_date && !data.fv.isValidField(end_date)) {
                		// We need to revalidate the end date
                		data.fv.revalidateField(end_date);
            		}

            		if (data.field === end_date && !data.fv.isValidField(start_date)) {
                		// We need to revalidate the start date
                		data.fv.revalidateField(start_date);
            		}
            }*/
        })

        // Called after removing the field
        .on('removed.field.fv', function(e, data) {
           
           var $row  = data.element.parents('.form-group'),index = $row.attr('data-book-index');

           $('#ps-form').formValidation('revalidateField', 'newfieldscount');

           var newfieldscount = $('#newfieldscount').val();

           	if (newfieldscount < MAX_ROW) {
                $('#btn_add_servicedetail').attr('disabled', null);
            }

        })
	    
	    .on('added.field.fv', function(e, data) {            

	    	$('#ps-form').formValidation('revalidateField', 'newfieldscount');

	    	var $row  = data.element.parents('.form-group'),
                index = $row.attr('data-book-index');

            var newfieldscount = $('#newfieldscount').val();

        	if (newfieldscount >= MAX_ROW) {
                $('#btn_add_servicedetail').attr('disabled', 'disabled');
            }

            var add_start_date 	= 'service[new][' + index +'][detail_at]';
            var add_end_date 	= 'service[new][' + index +'][detail_end]';
            
            var id_detail_at   = 'service_new_' + index +'_detail_at'
            var id_detail_end   = 'service_new_' + index +'_detail_end';

            
            if (data.field === add_start_date) {
                // The new due date field is just added
                // Create a new date picker
	    		data.element
	    		 	.datepicker({
	    			 	monthNamesShort: monthNameTypeNumber,
	    		 		prevText : '<i class="fa fa-chevron-left"></i>',
	    			    nextText : '<i class="fa fa-chevron-right"></i>',
	    				changeMonth: true,
	    		        changeYear: true,
	    		        showButtonPanel: true,
	    		        currentText: currentText_datepicker,
	    		        closeText: closeText_datepicker,
	    		        dateFormat: 'dd-mm-yy',	    		      
	    		        onSelect : function(selectedDate) {
	    		        	$('#' + id_detail_end).datepicker('option','minDate',selectedDate);
	    					
	    					//$('#ps-form').formValidation('revalidateField', add_end_date);
	    					
	    					if ($('[name="'+ add_end_date +'"]').val() )

		    		          	$('#ps-form').formValidation('revalidateField', add_start_date);
		    		          else

		    		          	$('#ps-form').formValidation('revalidateField', add_end_date);
	    				}
                    })
	    		 	.on('change', function(e) {
	      				// Revalidate the date field
			      		$('#ps-form').formValidation('revalidateField', data.element);
		    		});

            }

            if (data.field === add_end_date) {
	            // Create a new date picker
	    		data.element
	    		 	.datepicker({
	    			 	monthNamesShort: monthNameTypeNumber,
	    		 		prevText : '<i class="fa fa-chevron-left"></i>',
	    			    nextText : '<i class="fa fa-chevron-right"></i>',
	    				changeMonth: true,
	    		        changeYear: true,
	    		        showButtonPanel: true,
	    		        currentText: currentText_datepicker,
	    		        closeText: closeText_datepicker,
	    		        dateFormat: 'dd-mm-yy',
	    		        onSelect : function(selectedDate) {
	    		        	$('#' + id_detail_at).datepicker('option','maxDate',selectedDate);
	    					
	    					//$('#ps-form').formValidation('revalidateField', add_start_date);
	    					
	    					if ($('[name="'+ add_start_date +'"]').val() )

		    		          	$('#ps-form').formValidation('revalidateField', add_end_date);
		    		        else
		    		        	$('#ps-form').formValidation('revalidateField', add_start_date);
	    				}
                })
	    		.on('change', function(e) {
	      			// Revalidate the date field
			    	$('#ps-form').formValidation('revalidateField', data.element);
		    	});
	        }

        })
	    
	    // Add button click handler
        .on('click', '#btn_add_servicedetail', function() {
        	
        	serviceDetailIndex++;

        	var newfieldscount = $('#newfieldscount').val();

        	$('#newfieldscount').val(++newfieldscount);

        	var $template = $('#sevice_detail_template'),
                $clone    = $template
                                .clone()
                                .removeClass('hide')
                                .removeAttr('id')
                                .attr('data-book-index', serviceDetailIndex)
                                .insertBefore($template);
            
            $clone
            	.find('[name="service[new][temp][amount]"]').attr('name', 'service[new][' + serviceDetailIndex + '][amount]').end()
            	.find('[name="service[new][temp][by_number]"]').attr('name', 'service[new][' + serviceDetailIndex + '][by_number]').end()
            
            	.find('[name="service[new][temp][detail_at]"]').attr('name', 'service[new][' + serviceDetailIndex + '][detail_at]').end()
            	.find('[name="service[new][temp][detail_end]"]').attr('name', 'service[new][' + serviceDetailIndex + '][detail_end]').end()
            
            	.find('[id="service_new_temp_amount"]').attr('id', 'service_new_' + serviceDetailIndex + '_amount').end()
            	.find('[id="service_new_temp_by_number"]').attr('id', 'service_new_' + serviceDetailIndex + '_by_number').end()  
            	.find('[id="service_new_temp_detail_at"]').attr('id', 'service_new_' + serviceDetailIndex + '_detail_at').end()
            	.find('[id="service_new_temp_detail_end"]').attr('id', 'service_new_' + serviceDetailIndex + '_detail_end').end();
            
            // Add new fields
            var add_start_date 	= 'service[new][' + serviceDetailIndex + '][detail_at]';
            var add_end_date 	= 'service[new][' + serviceDetailIndex + '][detail_end]';

            $('#ps-form')
                .formValidation('addField', 'service[new][' + serviceDetailIndex + '][amount]', {
        			row: '.col-md-3',
            		validators: {
            			numeric: {
                        	message: msg_invalid_amount
                        }
                    }
        		})
                .formValidation('addField', 'service[new][' + serviceDetailIndex + '][by_number]', {
        			row: '.col-md-2',
                    validators: {
                    	numeric: {
                            message: msg_invalid_number
                        },
                        between: {
                            min: 1,
                            max: 100,
                            message: msg_invalid_by_number_between
                        }
                	}
        		})
                .formValidation('addField', add_start_date, {
        			row: '.col-md-3',
        			validators: {
                        date: {
                        	format: 'DD-MM-YYYY',
                            separator: '-',
                            message: msg_start_date_invalid
                        }
                    },
                    onSuccess: function(e, data) {
		                // Revalidate the start date if it's not valid
		                if (!data.fv.isValidField(add_start_date)) {
		                    data.fv.revalidateField(add_start_date);
		                }
		            }
	        	})
                .formValidation('addField', add_end_date, {
        			row: '.col-md-3',
        			validators: {
                        date: {
                        	format: 'DD-MM-YYYY',
                            separator: '-',
                            message: msg_end_date_invalid
                        }
                    },
                    onSuccess: function(e, data) {
		                // Revalidate the start date if it's not valid
		                if (!data.fv.isValidField(add_start_date)) {
		                    data.fv.revalidateField(add_start_date);
		                }
		            }
        		});            
        })// end on click

		// Remove button click handler
        .on('click', '.removeButton', function() {
            
            var $row  = $(this).parents('.form-group'),index = $row.attr('data-book-index');
            
            // Remove fields
            $('#ps-form')
                .formValidation('removeField', $row.find('[name="service[new][' + index + '][amount]"]'))
                .formValidation('removeField', $row.find('[name="service[new][' + index + '][by_number]"]'))
                .formValidation('removeField', $row.find('[name="service[new][' + index + '][detail_at]"]'))
                .formValidation('removeField', $row.find('[name="service[new][' + index + '][detail_end]"]'));

            // Remove element containing the fields
            $row.remove();

            var newfieldscount = $('#newfieldscount').val();

            if (newfieldscount >= 1)
        	   $('#newfieldscount').val(newfieldscount - 1);

        });
	
	$('#ps-form').formValidation('setLocale', PS_CULTURE);
});