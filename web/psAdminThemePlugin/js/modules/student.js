$(document).ready(function() {
	
	// is cached
	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	$('#confirmDeleteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	$('#confirmDeleteService').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	$('#confirmDeleteClass').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	$('#confirmRestoreService').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	$('#student_birthday').datepicker({
		dateFormat : 'dd-mm-yy',
		changeMonth : true,
		changeYear : true,
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
	}).on('changeDate', function(e) {

		$('#ps-form').formValidation('revalidateField', 'student[birthday]');
	});

	$('#ps-form')
		.find('[name="student[birthday]"]')      
	    .change(function(e) {
	        $('#ps-form').formValidation('revalidateField', 'student[birthday]');
	    }).end()
	.formValidation({

		framework : 'bootstrap',
		excluded : [':disabled'],
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
			"student[birthday]" : {
				validators : {
					date : {
						format : 'DD-MM-YYYY'
					}
				}
			},
			"student[image]" : {
				validators : {
					file : {
						extension : 'jpeg,jpg,png,gif',
						type : 'image/jpeg,image/png,image/gif',
						maxSize : PsMaxSizeFile * 1024
					}
				}
			}
		}
	});
	
	$('#ps-form').formValidation('setLocale', PS_CULTURE);
});