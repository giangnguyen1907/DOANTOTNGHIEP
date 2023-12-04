$(document).ready(function() {

	$('#ps_service_courses_start_at')
    .datepicker({
      dateFormat : 'dd-mm-yy',
	  changeMonth: true,
	  changeYear: true,
      prevText : '<i class="fa fa-chevron-left"></i>',
      nextText : '<i class="fa fa-chevron-right"></i>',
    })
    .on('changeDate', function(e) {
      // Revalidate the date field
      $('#ps-form').formValidation('revalidateField', 'ps_service_courses[start_at]');
    });
		
	
	$('#ps_service_courses_end_at')
	.datepicker({
		dateFormat : 'dd-mm-yy',
		changeMonth : true,
		changeYear : true,
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
	}).on('changeDate', function(e) {
		$('#ps-form').formValidation('revalidateField', 'ps_service_courses[end_at]');
	});
	
	$('#ps-form').formValidation({

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
		icon : {
			valid : 'glyphicon glyphicon-ok-circle',
			invalid : 'glyphicon glyphicon-remove-circle',
			validating : 'glyphicon glyphicon-refresh'
		},
		fields : {
//			"ps_service_courses[ps_customer_id]": {
//                validators: {
//                    notEmpty: {
//                        message: {vi_VN: 'Bạn chưa chọn trường.'}
//                    }
//                }
//            },
//            
//            "ps_service_courses[school_year_id]": {
//                validators: {
//                    notEmpty: {
//                        message: {vi_VN: 'Bạn chưa chọn năm học.'}
//                    }
//                }
//            },
//            "ps_service_courses[ps_service_id]": {
//                validators: {
//                    notEmpty: {
//                        message: {vi_VN: 'Bạn chưa chọn môn học.'}
//                    }
//                }
//            },
			"ps_service_courses[start_at]" : {
				validators : {
					date : {
						format : 'DD-MM-YYYY'
					}
				}
			},
			"ps_service_courses[end_at]" : {
				validators : {
					date : {
						format : 'DD-MM-YYYY'
					}
				}
			}
		}
	})
//	.on('err.form.fv', function(e) {
//		// Show the message modal
//		$('#messageModal').modal('show');
//	});
	$('#ps-form').formValidation('setLocale', PS_CULTURE);

});
