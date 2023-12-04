<?php use_helper('I18N', 'Date')?>
<?php include_partial('psMenusImports/assets')?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title"><?php echo __('Edit PsMenusImports') ?></h4>
</div>

<?php echo form_tag_for($form, '@ps_menus_imports', array('class' => 'form-horizontal', 'id' => 'ps_menus_imports', 'data-fv-addons' => 'i18n')) ?>
<div class="modal-body">
	<?php include_partial('psMenusImports/form', array('ps_menus_imports' => $ps_menus_imports, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>

<div class="modal-footer">	
		<?php include_partial('psMenusImports/form_actions_custom', array('ps_menus_imports' => $ps_menus_imports, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>

</form>
<script type="text/javascript">
    $('#ps_menus_imports_ps_image_id').select2({
    	dropdownParent: $('#remoteModal')
    });
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
	
	var msg_file_invalid = '<?php echo __('The image file must be in the format: jpg, png, gif. File size less than %value%KB.', array('%value%' => $app_upload_max_size))?>';
	var PsMaxSizeFile 	 = 500;
/*
	
$(document).ready(function() {	
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
});	*/	
</script>