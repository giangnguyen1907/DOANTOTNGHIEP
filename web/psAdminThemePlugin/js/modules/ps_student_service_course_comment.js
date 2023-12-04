$(document).ready(
		function() {

			$('#student_service_course_comment_filters_tracked_at').datepicker({
				dateFormat : 'dd-mm-yy',
				maxDate : new Date(),
				prevText : '<i class="fa fa-chevron-left"></i>',
				nextText : '<i class="fa fa-chevron-right"></i>',
				changeMonth : true,
				changeYear : true,
			}).on(
					'changeDate',
					function(e) {
						// Revalidate the date field
						$('#ps-filter-form').formValidation('revalidateField','student_service_course_comment_filters[tracked_at]');
					});

			;
			
			// -- Form Filter ->
			$('#ps-filter-form').formValidation({
				framework : 'bootstrap',
				// excluded : [ ':disabled' ],
				addOns : {
					i18n : {}
				},
				err : {
					//container : 'tooltip',
					container: '#errors'
				},
				message : {
					vi_VN : 'This value is not valid'
				},
				icon : {},
				fields : {
					"student_service_course_comment_filters[ps_customer_id]": {
		                validators: {
		                    notEmpty: {
		                        message: {vi_VN: msg_select_ps_customer_id,
		                        		  en_US: msg_select_ps_customer_id
		                        }
		                    }
		                }
		            },
		            
		            "student_service_course_comment_filters[ps_service_id]": {
		                validators: {
		                    notEmpty: {
		                        message: {vi_VN: msg_select_ps_service_id,
		                        		  en_US: msg_select_ps_service_id
		                        }
		                    }
		                }
		            },
		            
		            "student_service_course_comment_filters[ps_service_course_id]": {
		                validators: {
		                    notEmpty: {
		                        message: {vi_VN: msg_ps_service_course_id,
		                        		  en_US: msg_ps_service_course_id
		                        }
		                    }
		                }
		            },
		            "student_service_course_comment_filters[tracked_at]": {
		                validators: {
		                    notEmpty: {
		                        message: {vi_VN: msg_select_date,
		                        		  en_US: msg_select_date
		                        }
		                    },
		                    date: {
		                        format: 'DD-MM-YYYY',
		                        message: {vi_VN: 'The value is not a valid date',
		                        		  en_US: 'The value is not a valid date'
		                        }
		                    }
		                }
		            },
		            
		            "student_service_course_comment_filters[ps_service_course_schedule_id]": {
		                validators: {
		                    notEmpty: {
		                        message: {vi_VN: msg_select_ps_service_course_schedule_id,
		                        		  en_US: msg_select_ps_service_course_schedule_id
		                        }
		                    }
		                }
		            },
				}
			})
			.on('err.form.fv', function(e) {
				// Show the message modal
				$('#messageModal').modal('show');
			});

			$('#ps-filter-form').formValidation('setLocale', PS_CULTURE);
			
			$('#sstudent_service_course_comment_filters_tracked_at').on('dp.change dp.show', function(e) {
		        $('#ps-filter-form').formValidation('revalidateField', 'student_service_course_comment_filters[tracked_at]');
		    });

		})
