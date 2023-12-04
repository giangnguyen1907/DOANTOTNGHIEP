<?php use_helper('I18N', 'Date')?>
<?php include_partial('psTeacherClass/assets')?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title"><?php echo __('Assigned teachers for class: %%my_class%%', array('%%my_class%%' => $my_class->getName()), 'messages') ?></h4>
</div>

<?php echo form_tag_for($form, '@ps_teacher_class', array('class' => 'form-horizontal', 'id' => 'ps-form-teacher-class', 'data-fv-addons' => 'i18n')) ?>
<div class="modal-body" style="overflow: hidden;">
	<?php include_partial('psTeacherClass/form', array('ps_teacher_class' => $ps_teacher_class, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper))?>
</div>

<div class="modal-footer">	
	<?php include_partial('psTeacherClass/form_actions', array('ps_teacher_class' => $ps_teacher_class, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>

</form>

<style>
<!--
.no-search .select2-search {
	/*display:none*/
	
}
-->
</style>
<script type="text/javascript">
	$('#ps_teacher_class_ps_member_id, #ps_teacher_class_ps_myclass_id').select2({
		  dropdownParent: $('#remoteModal'),
		  dropdownCssClass : 'no-search'
	});	
</script>
<script type="text/javascript">
	$(document).ready(function() {

	// START AND FINISH DATE
	$('#ps_teacher_class_start_at').datepicker({			
		prevText : '<i class="fa fa-chevron-left"></i>',
	    nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy',
		onSelect : function(selectedDate) {
			$('#ps_teacher_class_stop_at').datepicker('option', 'minDate', selectedDate);
		}
	}).on('changeDate', function(e) {
	     $('#ps-form-teacher-class').formValidation('revalidateField', 'ps_teacher_class[start_at]');
	});

	$('#ps_teacher_class[start_at]').change(function() {      
		$('#ps-form-teacher-class').formValidation('revalidateField', 'ps_teacher_class[start_at]');    	
    });
	
	$('#ps_teacher_class_stop_at').datepicker({			
		prevText : '<i class="fa fa-chevron-left"></i>',
	    nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy',
		onSelect : function(selectedDate) {
			$('#ps_teacher_class_start_at').datepicker('option', 'maxDate', selectedDate);
		}
	}).on('changeDate', function(e) {
	     $('#ps-form-teacher-class').formValidation('revalidateField', 'ps_teacher_class[stop_at]');
	}).on('onClose', function(e) {
	     $('#ps-form-teacher-class').formValidation('revalidateField', 'ps_teacher_class[stop_at]');
	});

	$('#ps-form-teacher-class')
	.find('[name="ps_teacher_class[start_at]"]')      
        .change(function(e) {
            $('#ps-form-teacher-class').formValidation('revalidateField', 'ps_teacher_class[start_at]');
        })
      .end()
	.find('[name="ps_teacher_class[stop_at]"]')     
        .change(function(e) {
            $('#ps-form-teacher-class').formValidation('revalidateField', 'ps_teacher_class[stop_at]');
        })
      .end()
	.formValidation({
		framework: 'bootstrap',
		excluded: [':disabled', ':hidden'],
        addOns: {i18n: {}},
        errorElement: "div",
        errorClass: "help-block with-errors",
        message: {vi_VN: 'This value is not valid'},
        icon: {},
		fields: {
			"ps_teacher_class[start_at]": {
                row: '.col-md-8',                
				validators: {
					notEmpty: {
	                },
                   date: {
                        format: 'DD-MM-YYYY',
                        max: "ps_teacher_class[stop_at]"
                    }
                },
                onSuccess: function(e, data) {
                    if (!data.fv.isValidField('ps_teacher_class[stop_at]')) {
                        data.fv.revalidateField('ps_teacher_class[stop_at]');
                    }
                }
            },

            "ps_teacher_class[stop_at]": {
            	row: '.col-md-8',
				validators: {
					notEmpty: {
	                },
                   date: {
                        format: 'DD-MM-YYYY',
                        min: "ps_teacher_class[start_at]",
                    }
                },
                onSuccess: function(e, data) {
                    if (!data.fv.isValidField('ps_teacher_class[start_at]')) {
                        data.fv.revalidateField('ps_teacher_class[start_at]');
                    }
                }
            },
		}
	})
	.on('success.field.fv', function(e, data) {
        if (data.field === 'ps_teacher_class[start_at]' && !data.fv.isValidField('ps_teacher_class[stop_at]')) {
            // We need to revalidate the end date
            data.fv.revalidateField('ps_teacher_class[stop_at]');
        }

        if (data.field === 'ps_teacher_class[stop_at]' && !data.fv.isValidField('ps_teacher_class[start_at]')) {
            // We need to revalidate the end date
            data.fv.revalidateField('ps_teacher_class[start_at]');
        }
    });
	/*	
	.on('success.field.fv', function(e, data) {
        if (data.field === "ps_teacher_class[date][from]" && !data.fv.isValidField('ps_teacher_class[date][to]')) {
            // We need to revalidate the end date
            data.fv.revalidateField('ps_teacher_class[date][to]');
        }
        if (data.field === 'ps_teacher_class[date][to]' && !data.fv.isValidField('ps_teacher_class[date][from]')) {
            // We need to revalidate the start date
            data.fv.revalidateField('ps_teacher_class[date][from]');
        }
    });
    */

	$('#ps-form-teacher-class').formValidation('setLocale', PS_CULTURE);


});
</script>