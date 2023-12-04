$(document).ready(function() {
	
	var url_check_schedules = URL_CHECKSCHEDULES + '?';
	
	$('#ps_service_course_schedules_date_from').timepicker({
		timeFormat : 'HH:mm',
		showMeridian : false
	});
	$('#ps_service_course_schedules_date_to').timepicker({
		timeFormat : 'HH:mm',
		showMeridian : false
	});
	$('#ps_service_course_schedules_date_at')
    .datepicker({
      dateFormat : 'dd-mm-yy',
	  changeMonth: true,
	  changeYear: true,
      prevText : '<i class="fa fa-chevron-left"></i>',
      nextText : '<i class="fa fa-chevron-right"></i>',
    })
    .on('changeDate', function(e) {
      // Revalidate the date field
      $('#ps-form').formValidation('revalidateField', 'ps_service_course_schedules[date_at]');
    });
	

			 
	 $.datepicker.regional["vi_VN"] =
		{
			closeText: "Đóng",
			prevText : "Trước",
	      	nextText : "Sau",
			currentText: "Hôm nay",
			monthNames: ["Tháng một", "Tháng hai", "Tháng ba", "Tháng tư", "Tháng năm", "Tháng sáu", "Tháng bảy", "Tháng tám", "Tháng chín", "Tháng mười", "Tháng mười một", "Tháng mười hai"],
			monthNamesShort: ["Một", "Hai", "Ba", "Tư", "Năm", "Sáu", "Bảy", "Tám", "Chín", "Mười", "Mười một", "Mười hai"],
			dayNames: ["Chủ nhật", "Thứ hai", "Thứ ba", "Thứ tư", "Thứ năm", "Thứ sáu", "Thứ bảy"],
			dayNamesShort: ["CN", "Hai", "Ba", "Tư", "Năm", "Sáu", "Bảy"],
			dayNamesMin: ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
			weekHeader: "Tuần",
			dateFormat: "dd/mm/yy",
			firstDay: 1,
			isRTL: false,
			showMonthAfterYear: false,
			yearSuffix: ""
		};
	$.datepicker.setDefaults($.datepicker.regional[PS_CULTURE]);
			
	
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
			"ps_service_course_schedules[date_at]" : {
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
                          url: url_check_schedules,
                          data: function(validator, $field, value) {
                            return {
                                ps_customer_id: $('#form_ps_customer_id').val(),                                
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
	});
	 $('#ps-form').formValidation('setLocale', PS_CULTURE);

});
