$(document).ready(function() {
	
	function formatData (data) {
		 
		if (!data.id) { return data.text; }
		 
		if (!document.getElementById('feature_ps_image_id').options[data.id]) { return data.text; }
		
		var file_icon = document.getElementById('feature_ps_image_id').options[data.id].getAttribute('imagesrc');
	 
		if (!file_icon) { return data.text; }
	 
		var $result = $('<span><img src="' + file_icon + '" class="img-flag" style="width:20px;height:20px;margin-right: 15px;" />' + data.text + '</span>');
		
		return $result;
	};
	
	$("#feature_ps_image_id").select2({
	    allowClear: true,
		language: "vi",
	    placeholder: "-Select icon-",
	    formatResult: formatData,
	    formatSelection: formatData
	});
	
	var numberFeatureBranch = $('#count').val();
		
	var FeatureBranchModeValidators = {
            row: '.col-xs-12',
			validators: {
				notEmpty: {
                    message: {vi_VN: 'Nhập giá trị'},
                }
			}
        },
        FeatureBranchNameValidators = {
            row: '.col-xs-12',
			validators: {
				notEmpty: {
                    message: {vi_VN: 'Vui lòng nhập Tên hoạt động.'},
                }
			}
        },
        rownew = 1;
  
	$('#ps-form')
		.formValidation({
		framework: 'bootstrap',
		excluded: [':disabled'],
          addOns: {
               i18n: {}
          },
          errorElement: "div",
          errorClass: "help-block with-errors",
          message: {vi_VN: 'This value is not valid'},
          icon: {},		  
		  fields: {
            'feature[new][0][mode]': FeatureBranchModeValidators,
            'feature[new][0][name]': FeatureBranchNameValidators
          }
    })
	
	// Add button click handler
	.on('click', '#btn_addbranch2', function() {
		rownew++;
		var $template = $('#bookTemplate'),
            $clone    = $template
                                .clone()
                                .removeClass('hide')
                                .removeAttr('id')
                                .attr('data-book-index', rownew)
                                .insertBefore($template);
		
		// Update the name attributes feature[new][0][mode]
        $clone
			.find('[name="feature[new][0][mode]"]').attr('name', 'feature[new][' + rownew + '][mode]').end()
			.find('[name="feature[new][0][name]"]').attr('name', 'feature[new][' + rownew + '][name]').end();
		
		// Add new fields
		// Note that we also pass the validator rules for new field as the third parameter
		$('#ps-form')
			.formValidation('addField', 'feature[new][' + rownew + '][mode]', FeatureBranchModeValidators)
			.formValidation('addField', 'feature[new][' + rownew + '][name]', FeatureBranchNameValidators);
		
		
	})
	// Remove button click handler
	.on('click', '.action_delete', function() {
		var $row  = $(this).parents('.tr_row'),
			index = $row.attr('data-book-index');		

		// Remove fields
		$('#ps-form')
			.formValidation('removeField', $row.find('[name="feature[new][' + index + '][mode]"]'))
			.formValidation('removeField', $row.find('[name="feature[new][' + index + '][name]"]'));
		
		//$row.remove();
		
		$(this).parent().parent().remove();
		
	});

    $('#ps-form').formValidation('setLocale', PS_CULTURE);
});