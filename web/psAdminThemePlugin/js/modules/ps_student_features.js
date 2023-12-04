$(document).ready(
		function() {

			$('#student_feature_filters_tracked_at').datepicker({
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
						$('#ps-filter-student-features').formValidation('revalidateField','student_feature_filters[tracked_at]');
					});

			
			// -- Form Filter ->
			$('#student_feature_filters_tracked_at').on('dp.change dp.show', function(e) {
		        $('#ps-filter-student-features').formValidation('revalidateField', 'student_feature_filters[tracked_at]');
		    });
			
			$('#ps-filter-student-features').formValidation({
				framework : 'bootstrap',
				excluded: [':disabled', ':hidden', ':not(:visible)'],
				addOns : {
					i18n : {}
				},
				err : {
					container: '#errors'
				},
				message : {
					vi_VN : 'This value is not valid'
				},
				icon : {},
				fields : {
					"student_feature_filters[ps_customer_id]": {
	                    validators: {
	                        notEmpty: {
	                            message: {vi_VN: 'Bạn chưa chọn trường.'}
	                        }
	                    }
	                },
	                
	                "student_feature_filters[school_year_id]": {
	                    validators: {
	                        notEmpty: {
	                            message: {vi_VN: 'Bạn chưa chọn năm học.'}
	                        }
	                    }
	                },
	                
	                "student_feature_filters[ps_class_id]": {
	                    validators: {
	                        notEmpty: {
	                            message: {vi_VN: 'Bạn chưa chọn lớp học.'}
	                        }
	                    }
	                },
	                
	                "student_feature_filters[feature_branch_id]": {
	                    validators: {
	                        notEmpty: {
	                            message: {vi_VN: 'Bạn chưa chọn hoạt động.'}
	                        }
	                    }
	                },
	                
					"student_feature_filters[tracked_at]" : {
						validators : {
							date : {
								format : 'DD-MM-YYYY',
								max : 'student_feature_filters[tracked_at]'
							}
						}
					}
				}
			})
			.on('err.form.fv', function(e) {
				// Show the message modal
				$('#messageModal').modal('show');
			});

			$('#ps-filter-student-features').formValidation('setLocale', PS_CULTURE);
});
