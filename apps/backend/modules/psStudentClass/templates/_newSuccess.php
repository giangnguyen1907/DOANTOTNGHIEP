<?php use_helper('I18N', 'Date')?>
<?php include_partial('psStudentClass/assets')?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<?php if ( $form->isNew()) :?>
	<h4 class="modal-title"><?php echo __('Change class for student: %%ps_student%%', array('%%ps_student%%' => $ps_student->getFirstName().' '.$ps_student->getLastName()), 'messages') ?>
		<small>
			(<?php if (false !== strtotime($ps_student->getBirthday())) echo format_date($ps_student->getBirthday(), "dd-MM-yyyy").'<code>'.PreSchool::getAge($ps_student->getBirthday(),false).'</code>';?>)
		</small>
	</h4>
	<?php else : ?>
	<h4 class="modal-title"><?php echo __('Edit student class',array(), 'messages') ?>
		<small>
			(<?php if (false !== strtotime($ps_student->getBirthday())) echo format_date($ps_student->getBirthday(), "dd-MM-yyyy").'<code>'.PreSchool::getAge($ps_student->getBirthday(),false).'</code>';?>)
		</small>
	</h4>
	<?php endif;?>
</div>
<?php echo form_tag_for($form, '@ps_student_class', array('class' => 'form-horizontal fv-form-bootstrap', 'id' => 'studentNewForm', 'data-fv-addons' => 'i18n')) ?>
    <?php echo $form->renderHiddenFields(true) ?>
    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif;?>
<div class="modal-body" style="overflow: hidden;">	
		<?php include_partial('psStudentClass/form', array('student_class' => $student_class,'ps_student' => $ps_student, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper))?>
		<?php include_partial('psStudentClass/list_service', array('ps_student' => $ps_student, 'list_service' => $list_service, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper))?>
	</div>
<div class="modal-footer">
	    <?php include_partial('psStudentClass/form_actions', array('student_class' => $student_class, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
	</div>
</form>

<script type="text/javascript">
	$('#student_class_myclass_id, #student_class_type').select2({
		dropdownParent: $('#remoteModal')
		//dropdownCssClass : 'no-search'
	});

	$(document).ready(function() {
		$('#student_class_myclass_id').change(function() {
			$.ajax({
	            url: '<?php echo url_for('@ps_service_by_myclass_id?myclass_id=') ?>' + $(this).val() + '&student_id=<?php echo $ps_student->getId()?>&ps_customer_id=<?php echo $ps_student->getPsCustomerId()?>',          
	            type: 'POST',
	            data: 'myclass_id=' + $(this).val()+ '&student_id=<?php echo $ps_student->getId()?>&ps_customer_id=<?php echo $ps_student->getPsCustomerId()?>',
	            success: function(data) {
	            	$('#list_service').html(data);    			
	            }
	    	});
		});

		$('#student_class_type').change(function() {
			if($(this).val() == "<?php echo PreSchool::SC_STATUS_STOP_STUDYING ?>"){
				alert('<?php echo __("Note: Type class is stop studying")?>');
			}
		});
		
		$.datepicker.setDefaults($.datepicker.regional[ "vi" ]);
		
		// START AND FINISH DATE	
		$('#student_class_start_at').datepicker({			
			prevText : '<i class="fa fa-chevron-left"></i>',
		    nextText : '<i class="fa fa-chevron-right"></i>',
			changeMonth: true,
	        changeYear: true,
	        dateFormat: 'dd-mm-yy',
			onSelect : function(selectedDate) {
				$('#student_class_stop_at').datepicker('option', 'minDate', selectedDate);
			}
		}).on('changeDate', function(e) {
		     $('#studentNewForm').formValidation('revalidateField', 'student_class[start_at]');
		});
	
		$('#student_class[start_at]').change(function() {      
			$('#studentNewForm').formValidation('revalidateField', 'student_class[start_at]');    	
	    });
		
		$('#student_class_stop_at').datepicker({			
			prevText : '<i class="fa fa-chevron-left"></i>',
		    nextText : '<i class="fa fa-chevron-right"></i>',
			changeMonth: true,
	        changeYear: true,
	        dateFormat: 'dd-mm-yy',
			onSelect : function(selectedDate) {
				$('#student_class_start_at').datepicker('option', 'maxDate', selectedDate);
			}
		}).on('changeDate', function(e) {
		     $('#studentNewForm').formValidation('revalidateField', 'student_class[stop_at]');
		});	

	});
</script>
