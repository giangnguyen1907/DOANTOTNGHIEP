$(document)
		.ready(
				function() {
					// cleanup the content of the hidden remote modal because it
					// is cached
					$('#remoteModal').on('hide.bs.modal', function(e) {
						$(this).removeData('bs.modal');
						//$('#ps-form-teacher-class').formValidation('resetForm', true);
					});
				});