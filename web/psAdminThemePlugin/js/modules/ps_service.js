 $(document).ready(function(){
	 
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
	
	var ServiceServiceDetailAmountValidators = {
            row: '.col-md-12',
			validators: {
				notEmpty: {
                    message: {vi_VN: 'Vui lòng nhập giá tiền.'},
                }
			}
        },
        
        ServiceServiceDetailNumberValidators = {
            row: '.col-md-12',
			validators: {
				notEmpty: {
                    message: {vi_VN: 'Vui lòng nhập số lượng.'},
                }
			}
        },
        
        ServiceServiceDetailDetailAtCheckValidators = {
                row: '.col-md-12',
                excluded: false,    // Don't ignore me
                validators: {
                    notEmpty: {
                    	message: {vi_VN: 'Vui lòng chọn Tháng/ Năm.'},
                    },
	              date: {
	                  format: 'dd/mm/yyyy',
	                  separator: '/',
	                  message: {vi_VN: 'Tháng/ Năm chưa hợp lệ.'},
	              }
                }
        },
        
        ServiceServiceDetailDetailEndCheckValidators = {
                row: '.col-md-12',
                excluded: false,    // Don't ignore me
                validators: {
                    notEmpty: {
                    	message: {vi_VN: 'Vui lòng chọn Tháng/ Năm.'},
                    },
	              date: {
	                  format: 'dd/mm/yyyy',
	                  separator: '/',
	                  message: {vi_VN: 'Tháng/ Năm chưa hợp lệ.'},
	              }
                }
        },
        rownew = 1;
	
	$('#ps-form').formValidation({
	      framework: 'bootstrap',
	      excluded: [':disabled'],
	          addOns: {
	               i18n: {}
	          },
	          errorElement: "div",
	          errorClass: "help-block with-errors",
	          message: {vi_VN: 'This value is not valid'},
	          icon: {
	                valid: 'glyphicon glyphicon-ok-circle',
	                invalid: 'glyphicon glyphicon-remove-circle',
	                validating: 'glyphicon glyphicon-refresh'
	          },
	          
	          fields: {
	              'service[new][0][amount]': ServiceServiceDetailAmountValidators,
	              'service[new][0][by_number]': ServiceServiceDetailNumberValidators,	              
	              "service[new][0][detail_at_check]": ServiceServiceDetailDetailAtCheckValidators,
	              "service[new][0][detail_end_check]": ServiceServiceDetailDetailEndCheckValidators,	              
	         }
	         
	         /*
	          fields: {               
	            PositionNameValidator: {
					selector: ".[detail_at][month]",
					validators: {
							notEmpty: {
								message: 'The position name required and cannot be empty'
							}
					}
				},  
	          }*/         
	          
	})
	
	.on('keyup', 'input[name="service[new][0][detail_at][day]"], input[name="service[new][0][detail_at][month]"], input[name="service[new][0][detail_at][year]"]', function(e) {
            var d = $('#frm_service').find('[name="service[new][0][detail_at][day]"]').val(),
                m = $('#frm_service').find('[name="service[new][0][detail_at][month]"]').val(),
                y = $('#frm_service').find('[name="service[new][0][detail_at][year]"]').val();

            // Set the dob field value
            $('#frm_service').find('[name="service[new][0][detail_at_check]"]').val(y === '' || m === '' || d === '' ? '' : [d, m, y].join('/'));

            // Revalidate it
            $('#frm_service').formValidation('revalidateField', 'service[new][0][detail_at_check]');
        });
	
	$('#ps-form').formValidation('setLocale', PS_CULTURE);
  
});

