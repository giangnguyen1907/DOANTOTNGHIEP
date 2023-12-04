$(document).ready(function() {
    var countValueValidators = {
    		row: '.col-md-12',
			validators: {
				integer: {}
            }
        },
        countCeilValidators = {
    		row: '.col-md-12',
			validators: {
				integer: {}
            }
        },
        splitValueValidators = {
    		row: '.col-md-12',
			validators: {
                numeric: {
                    // The default separators
                    thousandsSeparator: ',',
                    decimalSeparator: '.'
                }
            }
        },
        rowIndex = 0;
    
    var MAX_ROW = 5;
	
	var numberServiceSplit = $('#count').val();
	alert(MAX_ROW);
    $('#ps-form')
        .formValidation({
            framework: 'bootstrap',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                'service[new][temp][count_value]': countValueValidators,
                'service[new][temp][count_ceil]': countCeilValidators,
                'service[new][temp][split_value]': splitValueValidators
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

            // Update the name attributes
            $clone
	        	.find('[name="service[new][temp][count_value]"]').attr('name', 'service[new][' + rowIndex + '][count_value]').end()
	        	.find('[name="service[new][temp][count_ceil]"]').attr('name', 'service[new][' + rowIndex + '][count_ceil]').end()            
	        	.find('[name="service[new][temp][split_value]"]').attr('name', 'service[new][' + rowIndex + '][split_value]').end()
	        
	        	.find('[id="service_new_temp_count_value"]').attr('id', 'service_new_' + rowIndex + 'count_value').end()  
	        	.find('[id="service_new_temp_count_ceil"]').attr('id', 'service_new_' + rowIndex + 'count_ceil').end()
	        	.find('[id="service_new_temp_split_value"]').attr('id', 'service_new_' + rowIndex + 'split_value').end();

            // Add new fields
            // Note that we also pass the validator rules for new field as the third parameter
            $('#ps-form')
                .formValidation('addField', 'service[new][' + rowIndex + '][count_value]', countValueValidators)
                .formValidation('addField', 'service[new][' + rowIndex + '][count_ceil]', countCeilValidators)
                .formValidation('addField', 'service[new][' + rowIndex + '][split_value]', splitValueValidators);
            
            var count_value 	= 'service[new][' + rowIndex + '][count_value]';
            var count_ceil 		= 'service[new][' + rowIndex + '][count_ceil]';
           
            $('#ps-form')
	            .formValidation('addField', count_value, {
	            	row: '.col-md-12',
	    			validators: {
	    				integer: {},
	    				between: {
	                        min:1,
	                    	max: count_ceil,
	                        message: msg_invalid_value_from
	                    }
	                },
	                onSuccess: function(e, data) {
	                    if (!data.fv.isValidField(count_ceil)) {
	                        data.fv.revalidateField(count_ceil);
	                    }
	                }
	    		})
	            .formValidation('addField', count_ceil, {
	            	row: '.col-md-12',
	    			validators: {
	    				integer: {},
	    				between: {
	                        min: count_value,
	                        max: 100,
	                        message: msg_invalid_value_from
	                    }
	                },
	                onSuccess: function(e, data) {
	                    if (!data.fv.isValidField(count_value)) {
	                        data.fv.revalidateField(count_value);
	                    }
	                }
	    		})
	    		.formValidation('addField', 'service[new][' + rowIndex + '][split_value]', {
	    			row: '.col-md-12',
	    			validators: {
	                    numeric: {
	                        // The default separators
	                        thousandsSeparator: ',',
	                        decimalSeparator: '.'
	                    }
	                }
	    		});
        })
        
        .on('added.field.fv', function(e, data) {            

	    	$('#ps-form').formValidation('revalidateField', 'newfieldscount');

	    	var $row  = data.element.parents('.list-new'),
                index = $row.attr('data-book-index');

            var newfieldscount = $('#newfieldscount').val();

        	if (newfieldscount >= MAX_ROW) {
                $('#btn_add').attr('disabled', 'disabled');
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

        // Remove button click handler
        .on('click', '.removeButton', function() {
            
        	var $row  = $(this).parents('.list-new'),
                index = $row.attr('data-book-index');

            // Remove fields
            $('#ps-form')
	            .formValidation('removeField', $row.find('[name="service[new][' + index + '][count_value]"]'))
	            .formValidation('removeField', $row.find('[name="service[new][' + index + '][count_ceil]"]'))
	            .formValidation('removeField', $row.find('[name="service[new][' + index + '][split_value]"]'));

            // Remove element containing the fields
            $row.remove();
            
            var newfieldscount = $('#newfieldscount').val();
            
            if (newfieldscount >= 1)
            	$('#newfieldscount').val(newfieldscount - 1);
        });
    
    for (var i = 0; i < numberServiceSplit ; i++) {
		
		var count_value = 'service[ServiceSplit][' + i + '][count_value]';
		
		var	count_ceil  = 'service[ServiceSplit][' + i + '][count_ceil]';
		
		var	split_value = 'service[ServiceSplit][' + i + '][split_value]';
		
		$('#ps-form').formValidation('addField', count_value, {
			row: '.col-md-12',
			validators: {
				integer: {},
				between: {
                    min:1,
                	max: count_ceil,
                    message: msg_invalid_value_from
                }
            },
            onSuccess: function(e, data) {
                if (!data.fv.isValidField(count_ceil)) {
                    data.fv.revalidateField(count_ceil);
                }
            }
		});
		
		$('#ps-form').formValidation('addField', count_ceil, {
			row: '.col-md-12',
			validators: {
				integer: {},
				between: {
                    min: count_value,
                    max: 100,
                    message: msg_invalid_value_from
                }
            },
            onSuccess: function(e, data) {
                if (!data.fv.isValidField(count_value)) {
                    data.fv.revalidateField(count_value);
                }
            }
		});
		
		$('#ps-form').formValidation('addField', split_value, {
			row: '.col-md-12',
			validators: {
                numeric: {
                    // The default separators
                    thousandsSeparator: ',',
                    decimalSeparator: '.'
                }
            }
		});
	}
    
    $('#ps-form').formValidation('setLocale', PS_CULTURE);
});