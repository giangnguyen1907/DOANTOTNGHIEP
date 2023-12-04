$(document).ready(function() {
    
	var rowIndex = 0,	MAX_ROW = 100;
	
	var numberServiceSplit = $('#count').val();
	
	$('#ps-form')
	    .formValidation({
	        framework: 'bootstrap',
	        excluded: [':disabled'],
	        icon: {
	            /*valid: 'glyphicon glyphicon-ok',
	            invalid: 'glyphicon glyphicon-remove',
	            validating: 'glyphicon glyphicon-refresh'*/
	        },
	        addOns: {
	            i18n: {}
	        },
	        fields: {
	            
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
        })
	    
	    // Add button click handler
        .on('click', '#btn_add', function() {
        	
        	rowIndex++;

        	var newfieldscount = $('#newfieldscount').val();

        	$('#newfieldscount').val(++newfieldscount);

        	var $template = $('#row-template'),
                $clone    = $template
                                .clone()
                                .removeClass('hide')
                                .removeAttr('id')
                                .attr('data-book-index', rowIndex)
                                .insertBefore($template);
        	
        	// service[new][temp][count_value]
            
            $clone
            	.find('[name="service[new][temp][count_value]"]').attr('name', 'service[new][' + rowIndex + '][count_value]').end()
            	.find('[name="service[new][temp][count_ceil]"]').attr('name', 'service[new][' + rowIndex + '][count_ceil]').end()            
            	.find('[name="service[new][temp][split_value]"]').attr('name', 'service[new][' + rowIndex + '][split_value]').end()
            
            	.find('[id="service_new_temp_count_value"]').attr('id', 'service_new_' + rowIndex + 'count_value').end()  
            	.find('[id="service_new_temp_count_ceil"]').attr('id', 'service_new_' + rowIndex + 'count_ceil').end()
            	.find('[id="service_new_temp_split_value"]').attr('id', 'service_new_' + rowIndex + 'split_value').end();
            
            // Add new fields
            $('#ps-form')
                .formValidation('addField', 'service[new][' + rowIndex + '][count_value]', {
        			row: '.col-md-12'
        		})
                .formValidation('addField', 'service[new][' + rowIndex + '][count_ceil]', {
        			row: '.col-md-12'
        		})
        		.formValidation('addField', 'service[new][' + rowIndex + '][split_value]', {
        			row: '.col-md-12'
        		});            
        })// end on click

		// Remove button click handler
        .on('click', '.removeButton', function() {
            
            var $row  = $(this).parents('.list-new'),index = $row.attr('data-book-index');
            
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