$(document).ready(function() {
	
	function formatData(data) {

		var options = document.getElementById('ps_menus_imports_ps_image_id').options;
		
		var index = 1;

		for (var i = 0; i < options.length; i++) {
			if (options[i].value == data.id)
				index = i;
		}

		if (!data.id) {
			return data.text;
		}

		if (!document.getElementById('ps_menus_imports_ps_image_id').options[index]) {
			return data.text;
		}

		var file_icon = document.getElementById('ps_menus_imports_ps_image_id').options[index].getAttribute('imagesrc');

		if (!file_icon) {
			return data.text;
		}

		var $result = $('<span><img src="'
				+ file_icon
				+ '" class="img-flag" style="width:20px;height:20px;margin-right: 15px;" />'
				+ data.text + '</span>');

		return $result;
	};

	$("#ps_menus_imports_ps_image_id").select2({
	    allowClear: true,
	    placeholder: "-Select icon-",
	    formatResult: formatData,
	    formatSelection: formatData
	});
	
	$('#ps_menus_imports_filters_date_at_from').datepicker(
			{
				dateFormat : 'dd-mm-yy',
				changeMonth : true,
				changeYear : true,
				prevText : '<i class="fa fa-chevron-left"></i>',
				nextText : '<i class="fa fa-chevron-right"></i>',
				onSelect : function(selectedDate) {
					$('#ps_menus_imports_filters_date_at_to').datepicker(
							'option', 'minDate', selectedDate);
				}
			}).on(
			'changeDate',
			function(e) {
				// Revalidate the date field
				$('#ps-filter').formValidation('revalidateField',
						'ps_menus_imports_filters[date_at_from]');
	});

	$('#ps_menus_imports_filters_date_at_to').datepicker(
			{
				dateFormat : 'dd-mm-yy',
				prevText : '<i class="fa fa-chevron-left"></i>',
				nextText : '<i class="fa fa-chevron-right"></i>',
				changeMonth : true,
				changeYear : true,
				onSelect : function(selectedDate) {
					$('#ps_menus_imports_filters_date_at_from').datepicker(
							'option', 'maxDate', selectedDate);
				}
			}).on(
			'changeDate',
			function(e) {
				// Revalidate the date field
				$('#ps-filter').formValidation('revalidateField',
						'ps_menus_imports_filters[date_at_to]');
			});

	$('#ps_menus_imports_date_at').datepicker({
		dateFormat : 'dd-mm-yy',
		changeMonth : true,
		changeYear : true,
		firstDay : 1,
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
	}).on(
		'changeDate',
		function(e) {
		$('#ps-form').formValidation('revalidateField',
				'ps_menus_imports[date_at]');
	});

	$('#ps_menus_imports').formValidation({

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
		fields : {
			"ps_menus_imports[file_image]" : {
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
	$('#ps_menus_imports').formValidation('setLocale', PS_CULTURE);	
});
