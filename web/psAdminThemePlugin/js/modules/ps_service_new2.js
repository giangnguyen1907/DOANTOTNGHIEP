$(document).ready(function() {
    
	function formatData (data) {
		 
		 if (!data.id) { return data.text; }
		 
		 if (!document.getElementById('service_ps_image_id').options[data.id]) { return data.text; }
			
		 var file_icon = document.getElementById('service_ps_image_id').options[data.id].getAttribute('imagesrc');
		 
		 if (!file_icon) { return data.text; }
		 
		  var $result = $(
		    '<span><img src="' + file_icon + '" class="img-flag" style="width:20px;height:20px;margin-right: 15px;" />' + data.text + '</span>'
		  );
		return $result;
	};

	$("#service_ps_image_id").select2({
	    allowClear: true,
	    placeholder: "-Select icon-",
	    formatResult: formatData,
	    formatSelection: formatData
	});
	/*
	$('#service_new_0_detail_at')
        .datepicker({
	      dateFormat : 'dd-mm-yy',
	      prevText : '<i class="fa fa-chevron-left"></i>',
	      nextText : '<i class="fa fa-chevron-right"></i>',
	      changeMonth: true,
	      changeYear: true
	    })
        .on('changeDate', function(e) {
	      // Revalidate the date field
	      $('#ps-form').formValidation('revalidateField', 'service[new][0][detail_at]');
	});*/
	
	/*
	$('#service_new_0_detail_end')
    	.datepicker({
	      dateFormat : 'dd-mm-yy',
	      prevText : '<i class="fa fa-chevron-left"></i>',
	      nextText : '<i class="fa fa-chevron-right"></i>',
	      changeMonth: true,
	      changeYear: true
	    })
	    .on('changeDate', function(e) {
	      // Revalidate the date field
	      $('#ps-form').formValidation('revalidateField', 'service[new][0][detail_end]');
    });*/
	
	
	 $('#service_new_0_detail_at')
	 	.datepicker({
			prevText : '<i class="fa fa-chevron-left"></i>',
		    nextText : '<i class="fa fa-chevron-right"></i>',
			changeMonth: true,
	        changeYear: true,
	        showButtonPanel: false,
	        dateFormat: 'mm/yy',
	        onClose: function(dateText, inst) { 
	          var month=$("#ui-datepicker-div .ui-datepicker-month :selected").val();
	          var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
	          $('#datepickerTEXT').datepicker('setDate', new Date(year, month, 1));
	        },
	        beforeShow : function(input, inst) {
	          var tmp = $('#service_new_0_detail_at').val().split('/');
	          $('#service_new_0_detail_at').datepicker('option','defaultDate',new Date(tmp[1],tmp[0]-1,1));
	          $('#service_new_0_detail_at').datepicker('setDate', new Date(tmp[1], tmp[0]-1, 1));
	        }
	 });
          
    //$("#service_new_0_detail_at").datepicker($.datepicker.regional['vi']);    
	//$.datepicker.regional["vi-VN"];	
	//$.datepicker.setDefaults($.datepicker.regional['vi']);
		
	var amountValidators = {
    		row: '.col-md-3',
    		excluded: false,
    		validators: {
                numeric: {
                	message: msg_invalid_amount
                }
            }
        },
        numberValidators = {
    		row: '.col-md-2',
    		excluded: false,
            validators: {
                numeric: {
                    message: msg_invalid_number
                }
            }
        },
        detailAtValidators = {
    		row: '.col-md-6',
    		//err: '.err_msg_detailAt_month',
    		//excluded: false,
    		validators: {
                date: {
                	format: 'DD/MM/YYYY',
                    separator: '/',
                    message: msg_invalid_month_year,
                    max: 'detailAtValidators'
                }
            }
        },
        detailEndValidators = {
    		row: '.col-md-6',
    		//excluded: false,
    		validators: {
                date: {
                	format: 'DD/MM/YYYY',
                    separator: '/',
                    message: msg_invalid_month_year,
                    //min: 'detailAtValidators'
                }
            }
        },
        bookIndex = 0;
	
	$('#ps-form')
        .formValidation({
            framework: 'bootstrap',
            excluded: [':disabled'],
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            addOns: {
                i18n: {}
            },
            fields: {
                'service[new][0][amount]': amountValidators,
                'service[new][0][by_number]': numberValidators,
                //'service[new][0][detail_at_check]': detailAtValidators,
                //'service[new][0][detail_end_check]': detailEndValidators
                
                'service[new][0][detail_at]': detailAtValidators,
                'service[new][0][detail_end]': detailEndValidators
            }
        })

        // Add button click handler
        .on('click', '#btn_adddetail3', function() {
            
        	bookIndex++;
            
            $('#newfieldscount').val(bookIndex);
            
            var $template = $('#bookTemplate'),
                $clone    = $template
                                .clone()
                                .removeClass('hide')
                                .removeAttr('id')
                                .attr('data-book-index', bookIndex)
                                .insertBefore($template);

            // Update the name attributes
            /*
            $clone
                .find('[name="service_detail_amount"]').attr('name', 'service[new][' + bookIndex + '][amount]').end()
                .find('[name="service_detail_by_number"]').attr('name', 'service[new][' + bookIndex + '][by_number]').end()
                
                .find('[name="service_detail_at_month"]').attr('name', 'service[new][' + bookIndex + '][detail_at_month]').end()
                .find('[name="service_detail_at_year"]').attr('name', 'service[new][' + bookIndex + '][detail_at_year]').end()                
                .find('[name="service_detail_at_check"]').attr('name', 'service[new][' + bookIndex + '][detail_at_check]').end()
                
                .find('[name="service_detail_end_month"]').attr('name', 'service[new][' + bookIndex + '][detail_end_month]').end()
                .find('[name="service_detail_end_year"]').attr('name', 'service[new][' + bookIndex + '][detail_end_year]').end()
                .find('[name="service_detail_end_check"]').attr('id', 'service[new][' + bookIndex + '][detail_end_check]').end()
                
                .find('[id="service_detail_amount"]').attr('id', 'service_new_' + bookIndex + '_amount').end()
                .find('[id="service_detail_by_number"]').attr('id', 'service_new_' + bookIndex + '_by_number').end()                
                .find('[id="service_detail_at_month"]').attr('id', 'service_new_' + bookIndex + '_detail_at_month').end()
                .find('[id="service_detail_at_year"]').attr('id', 'service_new_' + bookIndex + '_detail_at_year').end()
                
                .find('[id="service_detail_at_check"]').attr('id', 'service_new_' + bookIndex + '_detail_at_check').end()
                
                .find('[id="service_detail_end_month"]').attr('id', 'service_new_' + bookIndex + '_detail_end_month').end()
                .find('[id="service_detail_end_year"]').attr('id', 'service_new_' + bookIndex + '_detail_end_year').end();
                .find('[id="service_detail_end_check"]').attr('id', 'service_new_' + bookIndex + '_detail_end_check').end();
                */
            
            $clone
            .find('[name="service_detail_amount"]').attr('name', 'service[new][' + bookIndex + '][amount]').end()
            .find('[name="service_detail_by_number"]').attr('name', 'service[new][' + bookIndex + '][by_number]').end()
            
            .find('[name="service_detail_at"]').attr('name', 'service[new][' + bookIndex + '][detail_at]').end()
            .find('[name="service_detail_end"]').attr('name', 'service[new][' + bookIndex + '][detail_end]').end()
            
            .find('[id="service_detail_amount"]').attr('id', 'service_new_' + bookIndex + '_amount').end()
            .find('[id="service_detail_by_number"]').attr('id', 'service_new_' + bookIndex + '_by_number').end()  
            .find('[id="service_detail_at"]').attr('id', 'service_new_' + bookIndex + '_detail_at').end()
            .find('[id="service_detail_end"]').attr('id', 'service_new_' + bookIndex + '_detail_end').end();

            // Add new fields
            // Note that we also pass the validator rules for new field as the third parameter
            $('#ps-form')
                .formValidation('addField', 'service[new][' + bookIndex + '][amount]', amountValidators)
                .formValidation('addField', 'service[new][' + bookIndex + '][by_number]', numberValidators)
                //.formValidation('addField', 'service[new][' + bookIndex + '][detail_at_check]', detailAtValidators)
                //.formValidation('addField', 'service[new][' + bookIndex + '][detail_end_check]', detailEndValidators);
                .formValidation('addField', 'service[new][' + bookIndex + '][detail_at]', detailAtValidators)
                .formValidation('addField', 'service[new][' + bookIndex + '][detail_end]', detailEndValidators)
        })

        // Remove button click handler
        .on('click', '.removeButton', function() {
            var $row  = $(this).parents('.form-group'),
                index = $row.attr('data-book-index');
            
            var newfieldscount = $('#newfieldscount').val();
            
            if (newfieldscount > 0)
            	$('#newfieldscount').val(newfieldscount - 1);

            // Remove fields
            $('#ps-form')
                .formValidation('removeField', $row.find('[name="service[new][' + index + '][amount]"]'))
                .formValidation('removeField', $row.find('[name="service[new][' + index + '][by_number]"]'))
                //.formValidation('removeField', $row.find('[name="service[new][' + index + '][detail_at_check]"]'))
                //.formValidation('removeField', $row.find('[name="service[new][' + index + '][detail_end_check]"]'));
                
                .formValidation('removeField', $row.find('[name="service[new][' + index + '][detail_at]"]'))
                .formValidation('removeField', $row.find('[name="service[new][' + index + '][detail_end]"]'));

            // Remove element containing the fields
            $row.remove();
        })
        
        // Check value of select at_month at_year
        /*
        .on('change', '.at_month, .at_year', function(e) {
        	var $row  = $(this).parents('.form-group'),
            	index = $row.attr('data-book-index');
        	
        	var month = $('#service_new_' + index + '_detail_at_month').val(),
            	year = $('#service_new_' + index + '_detail_at_year').val();
        	
        	$('#ps-form').find('[name="service[new]['+index+'][detail_at_check]"]').val(year === '' || month === '' ? '' : [year, month, '01'].join('/'));
	        
	        // Revalidate it
	        $('#ps-form').formValidation('revalidateField', 'service[new]['+index+'][detail_at_check]');        	
        })
        
        // Check value of select end_month end_year
        .on('change', '.end_month, .end_year', function(e) {
        	var $row  = $(this).parents('.form-group'),
            	index = $row.attr('data-book-index');
        	
        	var month = $('#service_new_' + index + '_detail_end_month').val(),
            	year = $('#service_new_' + index + '_detail_end_year').val();
        	
        	$('#ps-form').find('[name="service[new]['+index+'][detail_end_check]"]').val(year === '' || month === '' ? '' : [year, month, '01'].join('/'));
	        
	        // Revalidate it
	        $('#ps-form').formValidation('revalidateField', 'service[new]['+index+'][detail_end_check]');        	
        })
        
        .on('change', 'select[name="service[new][0][detail_at][month]"], select[name="service[new][0][detail_at][year]"]', function(e) {
            var m = $('#ps-form').find('[name="service[new][0][detail_at][month]"]').val(),
                y = $('#ps-form').find('[name="service[new][0][detail_at][year]"]').val();
            
            	if (m > 0 && m < 10)            	
            		m = '0' + 1;
            
            // Set the dob field value
            $('#ps-form').find('[name="service[new][0][detail_at_check]"]').val(y === '' || m === '' ? '' : [y, m, "01"].join('/'));
            
            // Revalidate it
            $('#ps-form').formValidation('revalidateField', 'service[new][0][detail_at_check]');
            
        })
        .on('change', 'select[name="service[new][0][detail_end][month]"], select[name="service[new][0][detail_end][year]"]', function(e) {
            var m = $('#ps-form').find('[name="service[new][0][detail_end][month]"]').val(),
                y = $('#ps-form').find('[name="service[new][0][detail_end][year]"]').val();
            
            	if (m > 0 && m < 10)            	
            		m = '0' + 1;
            
            // Set the dob field value
            $('#ps-form').find('[name="service[new][0][detail_end_check]"]').val(y === '' || m === '' ? '' : [y, m, "01"].join('/'));
            
            // Revalidate it
            $('#ps-form').formValidation('revalidateField', 'service[new][0][detail_end_check]');
            
            
        })
        
        .on('success.field.fv', function(e, data) {
            
        	//console.log(data);
            
        	if (data.field === 'service[new][0][detail_at]' && !data.fv.isValidField('service[new][0][detail_end]')) {
                // We need to revalidate the end date
                data.fv.revalidateField('service[new][0][detail_end]');
            }

            if (data.field === 'service[new][0][detail_end]' && !data.fv.isValidField('service[new][0][detail_at]')) {
                // We need to revalidate the start date
                data.fv.revalidateField('service[new][0][detail_at]');
            }
        });	*/
        
	
	$('#ps-form').formValidation('setLocale', PS_CULTURE);
});


