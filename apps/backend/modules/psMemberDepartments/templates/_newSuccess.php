<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psMemberDepartments/assets') ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<?php if ( $form->isNew()) :?>
	<h4 class="modal-title"><?php echo __('Add member department: %%ps_member%%', array('%%ps_member%%' => $ps_member_departments->getPsMember()->getFirstName().' '.$ps_member_departments->getPsMember()->getLastName()), 'messages') ?>
		<small>
			(<?php echo $ps_member_departments->getPsMember()->getMemberCode()?>)
		</small>
	</h4>
	<?php else : ?>
	<h4 class="modal-title"><?php echo __('Edit member department: %%ps_member%%', array('%%ps_member%%' => $ps_member_departments->getPsMember()->getFirstName().' '.$ps_member_departments->getPsMember()->getLastName()), 'messages') ?>
		<small>
			(<?php echo $ps_member_departments->getPsMember()->getMemberCode()?>)
		</small>
	</h4>
	<?php endif;?>
</div>

<?php echo form_tag_for($form, '@ps_member_departments', array('class' => 'form-horizontal fv-form-bootstrap', 'id' => 'memberDepartmentsNewForm', 'data-fv-addons' => 'i18n')) ?>
    <?php echo $form->renderHiddenFields(true) ?>
    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif;?>
<div class="modal-body" style="overflow: hidden;">	
		<?php include_partial('psMemberDepartments/form', array('ps_member_departments' => $ps_member_departments, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
	</div>
<div class="modal-footer">
	    <?php include_partial('psMemberDepartments/form_actions', array('ps_member_departments' => $ps_member_departments, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
	</div>
</form>

<!-- Copy tu newSuccess Studentclass -->
<script type="text/javascript">

	$(document).ready(function() {

		$('#ps_member_departments_ps_department_id, #ps_member_departments_ps_function_id').select2({
			dropdownParent: $('#remoteModal')
			//dropdownCssClass : 'no-search'
		});
		
		$.datepicker.setDefaults($.datepicker.regional[ "vi" ]);

		// START AND FINISH DATE	
		$('#ps_member_departments_start_at').datepicker({			
			prevText : '<i class="fa fa-chevron-left"></i>',
		    nextText : '<i class="fa fa-chevron-right"></i>',
			changeMonth: true,
	        changeYear: true,
	        dateFormat: 'dd-mm-yy',
			onSelect : function(selectedDate) {
				$('#ps_member_departments_stop_at').datepicker('option', 'minDate', selectedDate);
			}
		}).on('changeDate', function(e) {
		     $('#memberDepartmentsNewForm').formValidation('revalidateField', 'ps_member_departments[start_at]');
		});
	
		$('#sps_member_departments[start_at]').change(function() {      
			$('#memberDepartmentsNewForm').formValidation('revalidateField', 'ps_member_departments[start_at]');    	
	    });
		
		$('#ps_member_departments_stop_at').datepicker({			
			prevText : '<i class="fa fa-chevron-left"></i>',
		    nextText : '<i class="fa fa-chevron-right"></i>',
			changeMonth: true,
	        changeYear: true,
	        dateFormat: 'dd-mm-yy',
			onSelect : function(selectedDate) {
				$('#ps_member_departments_start_at').datepicker('option', 'maxDate', selectedDate);
			}
		}).on('changeDate', function(e) {
		     $('#memberDepartmentsNewForm').formValidation('revalidateField', 'ps_member_departments[stop_at]');
		});	


		var msg_select_ps_department_id	= '<?php echo __('Please select department to filter the data.')?>';
		var msg_select_ps_function_id 	= '<?php echo __('Please select function to filter the data.')?>';
		var msg_select_start_at	= '<?php echo __('Please select start at to filter the data.')?>';

		$('#memberDepartmentsNewForm').formValidation({
	    	framework : 'bootstrap',
	    	excluded: [':disabled', ':hidden'],
	    	excluded: [':disabled', ':hidden'],
	    	addOns : {
				i18n : {}
			},
			
			icon : {},
	    	fields : {
				"ps_member_departments[ps_department_id]": {
	                validators: {
	                    notEmpty: {
	                        message: msg_select_ps_department_id
	                        }
	                    }
	            },
	            
	            "ps_member_departments[ps_function_id]": {
	                validators: {
	                    notEmpty: {
	                        message: msg_select_ps_function_id
	                        }
	                    }
	            },

	            "ps_member_departments[start_at]": {
	                validators: {
	                    notEmpty: {
	                        message: msg_select_start_at
	                        }
	                    }
	            }
	            
			}
	    });

	    $('#memberDepartmentsNewForm').formValidation('setLocale', PS_CULTURE);

	});
</script>

