<?php use_helper('I18N', 'Date')?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">X</button>
	<?php
	$teacher = ($ps_advices->getUserId ()) ? (__ ( 'Teacher' ) . ' ' . $ps_advices->getUserId ()) : '';
	$student = ($obj_student) ? ($obj_student->getStudentCode () . ' ' . $obj_student->getFirstName () . ' ' . $obj_student->getLastName ()) : '';
								?>
	<h4 class="modal-title"><?php echo __('Edit PsAdvices: %%student%% - %%teacher%%', array('%%student%%' => $student, '%%teacher%%' => $teacher), 'messages') ?></h4>
	  
</div>

<?php echo form_tag_for($form, '@ps_advices', array('class' => 'form-horizontal', 'id' => 'form_ps_advices', 'data-fv-addons' => 'i18n')) ?>
<div class="modal-body">
	<?php include_partial('psAdvices/form', array('ps_advices' => $ps_advices,'form' => $form, 'configuration' => $configuration, 'helper' => $helper))?>
</div>

<div class="modal-footer">	
	
</div>
</form>
