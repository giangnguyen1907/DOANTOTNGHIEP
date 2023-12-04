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
	
	$('#service_new_0_detail_at')
	 	.datepicker({
	 		prevText : '<i class="fa fa-chevron-left"></i>',
		    nextText : '<i class="fa fa-chevron-right"></i>',
			changeMonth: true,
	        changeYear: true,	       
	        dateFormat: 'dd-mm-yy'
	 	})
	 	.on('change', function(e) {
	      // Revalidate the date field
	      $('#ps-form').formValidation('revalidateField', 'service[new][0][detail_at]');
	    });
	
	$('#service_new_0_detail_end')
	 	.datepicker({
	 		prevText : '<i class="fa fa-chevron-left"></i>',
		    nextText : '<i class="fa fa-chevron-right"></i>',
			changeMonth: true,
	        changeYear: true,
	        dateFormat: 'dd-mm-yy'	        
	 	})
	 	.on('change', function(e) {
	      // Revalidate the date field
	      $('#ps-form').formValidation('revalidateField', 'service[new][0][detail_end]');
	    });
	
	$('#service_new_1_detail_at')
 		.datepicker({
	 		monthNamesShort: monthNameTypeNumber,
	 		prevText : '<i class="fa fa-chevron-left"></i>',
		    nextText : '<i class="fa fa-chevron-right"></i>',
			changeMonth: true,
	        changeYear: true,
	        dateFormat: 'dd-mm-yy'
 	}).on('change', function(e) {
      // Revalidate the date field
      $('#ps-form').formValidation('revalidateField', 'service[new][1][detail_at]');
    });
	
	
	$('#ps-form')
        .find('[name="service[new][0][detail_at]"]')      
            .change(function(e) {
                $('#ps-form').formValidation('revalidateField', 'service[new][0][detail_at]');
            })
          .end()
          .find('[name="service[new][0][detail_end]"]')     
            .change(function(e) {
                $('#ps-form').formValidation('revalidateField', 'service[new][0][detail_end]');
            })
          .end()
        .find('[name="service[new][1][detail_at]"]')      
            .change(function(e) {
                $('#ps-form').formValidation('revalidateField', 'service[new][1][detail_at]');
            })
            .end()
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
                'service[new][0][amount]': {
            		row: '.col-md-3',
            		excluded: false,
            		validators: {
            			numeric: {
                        	message: msg_invalid_amount
                        }
                    }
                },
                'service[new][0][by_number]': {
            		row: '.col-md-3',
            		excluded: false,
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
                },                
                'service[new][0][detail_at]': {
            		row: '.col-md-3',
            		validators: {
                        date: {
                        	format: 'DD-MM-YYYY',
                            separator: '-',
                            message: {vi_VN: msg_start_date_invalid },
                            max: 'service[new][0][detail_at]'
                        }
                    }
                },
                'service[new][0][detail_end]': {
            		row: '.col-md-3',
            		validators: {
                        date: {
                        	format: 'DD-MM-YYYY',
                            separator: '-',
                            message: {vi_VN: msg_end_date_invalid },
                            min: 'service[new][0][detail_at]'
                        }
                    }
                }
            }
        })
        
        // Add new row
        .on('click', '#btn_adddetail', function() {
        	var $template = $('#rowTemplate'),
            $clone    = $template
                            .clone()
                            .removeClass('hide')
                            .removeAttr('id')
                            .attr('data-book-index', 1)
                            .insertBefore($template);
        	
        	$('#ps-form')
            	.formValidation('addField', 'service[new][1][amount]', 'service[new][1][amount]')
            	.formValidation('addField', 'service[new][1][by_number]', 'service[new][1][by_number]')
            	.formValidation('addField', 'service[new][1][detail_at]', 'service[new][1][detail_at]')
            	.formValidation('addField', 'service[new][1][detail_end]', 'service[new][1][detail_end]');
        })
        
        // Remove button click handler
        .on('click', '.removeButton', function() {
            var $row  = $(this).parents('.form-group'),
                index = $row.attr('data-book-index');
            
            $('#newfieldscount').val(0);

            // Remove fields
            $('#ps-form')
                .formValidation('removeField', $row.find('[name="service[new][1][amount]"]'))
                .formValidation('removeField', $row.find('[name="service[new][1][by_number]"]'))                
                .formValidation('removeField', $row.find('[name="service[new][1][detail_at]"]'))
                .formValidation('removeField', $row.find('[name="service[new][1][detail_end]"]'));
            // Remove element containing the fields
            $row.remove();
        })
        
        .on('success.field.fv', function(e, data) {
            if (data.field === "service[new][0][detail_at]" && !data.fv.isValidField('service[new][0][detail_end]')) {
                // We need to revalidate the end date
                data.fv.revalidateField('service[new][0][detail_end]');
            }

            if (data.field === 'service[new][0][detail_end]' && !data.fv.isValidField('service[new][0][detail_at]')) {
                // We need to revalidate the start date
                data.fv.revalidateField('service[new][0][detail_at]');
            }
        });      
	
	$('#ps-form').formValidation('setLocale', PS_CULTURE);
});


