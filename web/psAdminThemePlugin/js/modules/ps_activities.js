$(document)
		.ready(
				function() {

					function formatData(data) {

						var options = document
								.getElementById('feature_branch_ps_image_id').options;

						var index = 1;

						for (var i = 0; i < options.length; i++) {
							if (options[i].value == data.id)
								index = i;
						}

						if (!data.id) {
							return data.text;
						}

						if (!document
								.getElementById('feature_branch_ps_image_id').options[index]) {
							return data.text;
						}

						var file_icon = document
								.getElementById('feature_branch_ps_image_id').options[index]
								.getAttribute('imagesrc');

						if (!file_icon) {
							return data.text;
						}

						var $result = $('<span><img src="'
								+ file_icon
								+ '" class="img-flag" style="width:20px;height:20px;margin-right: 15px;" />'
								+ data.text + '</span>');

						return $result;
					};

					$("#feature_branch_ps_image_id").select2({
						allowClear : true,
						formatResult : formatData,
						formatSelection : formatData
					});

					$('#remoteModal').on('hide.bs.modal', function(e) {
						$(this).removeData('bs.modal');
						//$('#ps-form-feature-branch-times').formValidation('resetForm',true);
					});
					
					$('#confirmDelete').on('hide.bs.modal', function(e) {
						$(this).removeData('bs.modal');
					});

					$('#ps-form').formValidation({
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
						icon : {},
						fields : {}
					});

					$('#ps-form').formValidation('setLocale', PS_CULTURE);
				});