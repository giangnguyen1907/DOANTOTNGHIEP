<?php use_helper('I18N', 'Date')?>
<?php //include_partial('psFeatureBranchTimes/assets')?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title"><?php echo __('Add calendar for activities: %%ps_feature_branch%%', array('%%ps_feature_branch%%' => $ps_feature_branch->getName()), 'messages') ?></h4>
</div>

<?php echo form_tag_for($form, '@ps_feature_branch_times', array('class' => 'form-horizontal', 'id' => 'ps-form-feature-branch-times', 'data-fv-addons' => 'i18n')) ?>
<div class="modal-body">
	<?php include_partial('psFeatureBranchTimes/form_custom', array('feature_branch_times' => $feature_branch_times, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper))?>
	
	<div class="row">
	<?php include_partial('psFeatureBranchTimes/form_class_apply', array('list_myclass' => $list_myclass))?>
	</div>
</div>

<div class="modal-footer">	
		<?php include_partial('psFeatureBranchTimes/form_actions_custom', array('feature_branch_times' => $feature_branch_times, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>
</form>
<script type="text/javascript">

	$('#psactivitie_ps_class_room_id').select2({
		  dropdownParent: $('#remoteModal'),
		  dropdownCssClass : 'no-search'
	});

$(document).ready(function() {

	$(".chk_ids").on("click",function(){
		var mcids = $(this).val();

		if($(this).is(':checked')) {

			$("#psactivitie_my_class_" + mcids + "_note").attr('disabled', null);
			$("#psactivitie_my_class_" + mcids + "_ps_class_room").attr('disabled', null);
		} else {

			$("#psactivitie_my_class_" + mcids + "_note").attr('disabled', 'disabled');
			$("#psactivitie_my_class_" + mcids + "_ps_class_room").attr('disabled', 'disabled');

		}
	});
	
	$('#psactivitie_start_time').timepicker({timeFormat : 'HH:mm',showMeridian : false, minuteStep: 5});
	
	$('#psactivitie_end_time').timepicker({timeFormat : 'HH:mm',showMeridian : false, minuteStep: 5});

	$.datepicker.setDefaults($.datepicker.regional[ "vi" ]);

	// START AND FINISH DATE
	$('#pickerAt1 .form-control').datepicker({			
		prevText : '<i class="fa fa-chevron-left"></i>',
	    nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth: true,
        changeYear: true,
        autoclose: true,
        dateFormat: 'dd-mm-yy',
        onSelect : function(selectedDate) {
        	$('#psactivitie_end_at').datepicker('option','minDate',selectedDate);
			$('#psactivitie_start_at').change();
			$('#ps-form-feature-branch-times').formValidation('revalidateField', 'psactivitie[end_at]');
		}
	}).on('changeDate', function(e) {
		$('#psactivitie_end_at').datepicker('setStartDate', new Date($(this).val()));
	    $('#ps-form-feature-branch-times').formValidation('revalidateField', 'psactivitie[start_at]');
	});
	
	$('#pickerAt2 .form-control').datepicker({			
		prevText : '<i class="fa fa-chevron-left"></i>',
	    nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth: true,
        changeYear: true,
        autoclose: true,
        dateFormat: 'dd-mm-yy',
        onSelect : function(selectedDate) {
        	$('#psactivitie_start_at').datepicker('option','maxDate',selectedDate);
        	$(this).change();
        	$('#ps-form-feature-branch-times').formValidation('revalidateField', 'psactivitie[start_at]');			
		}
	}).on('changeDate', function(e) {
	    $('#psactivitie_start_at').datepicker('setStartDate', new Date($(this).val()));
	    $('#ps-form-feature-branch-times').formValidation('revalidateField', 'psactivitie[end_at]');
	});
	
	var startDate 	= "psactivitie[start_at]",endDate 		= "psactivitie[end_at]";
	
	$('#ps-form-feature-branch-times')
		.find('[name="psactivitie[start_at]"]')      
        	.change(function(e) {
            	$('#ps-form-feature-branch-times').formValidation('revalidateField', 'psactivitie[start_at]');
        	})
        	.end()
		.find('[name="psactivitie[end_at]"]')     
        	.change(function(e) {
            	$('#ps-form-feature-branch-times').formValidation('revalidateField', 'psactivitie[end_at]');
        	})
      		.end()      	    	 
	.formValidation({
		framework: 'bootstrap',
		excluded: [':disabled', ':hidden'],
        addOns: {i18n: {}},
        //errorElement: "div",
        //errorClass: "help-block with-errors",
        message: {vi_VN: 'This value is not valid'},
        icon: {},
		fields: {
			"psactivitie[start_at]": {
				validators: {
					notEmpty: {
                        
                    },
                    date: {
                        format: 'DD-MM-YYYY',
                        separator: '-'
                    }
                },
                onSuccess: function(e, data) {					
                    if (!data.fv.isValidField(endDate)) {
                        data.fv.revalidateField(endDate);
                    }
                }
            },

            "psactivitie[end_at]": {
                validators: {
                   notEmpty: {
                        
                   },
                   date: {
                       format: 'DD-MM-YYYY',
                       separator: '-'
                    }
                },
                onSuccess: function(e, data) {					
					if (!data.fv.isValidField(startDate)) {
                        data.fv.revalidateField(startDate);
                    }
                }
            },
            "psactivitie[start_time]": {
            	verbose: false,
        		date: {
                    format: 'h:m A'
                }
            },
            "psactivitie[end_time]": {
            	verbose: false,
        		date: {
                    format: 'h:m A'
                }
            }
		}
	})
	.on('success.field.fv', function(e, data) {
        if (data.field === 'psactivitie[start_at]' && !data.fv.isValidField('psactivitie[end_at]')) {
            // We need to revalidate the end date
            data.fv.revalidateField('psactivitie[end_at]');            
        }
        
        if (data.field === 'psactivitie[end_at]' && !data.fv.isValidField('psactivitie[start_at]')) {
            // We need to revalidate the end date
            data.fv.revalidateField('psactivitie[start_at]');
        }
        
    });
	$('#ps-form-feature-branch-times').formValidation('setLocale', PS_CULTURE);	
});
</script>