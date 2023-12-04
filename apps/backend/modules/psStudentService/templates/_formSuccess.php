<?php use_helper('I18N', 'Date')?>
<style>
#remoteModal .modal-dialog {
	width: 90% !important;
}
</style>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h5 class="modal-title"><?php echo __('Register service for student: %%ps_student%%', array('%%ps_student%%' => $ps_student->getFirstName().' '.$ps_student->getLastName()), 'messages') ?>
		<small>
		(<?php if (false !== strtotime($ps_student->getBirthday())) echo format_date($ps_student->getBirthday(), "dd-MM-yyyy").'<code>'.PreSchool::getAge($ps_student->getBirthday(),false).'</code>';?>) - <?php echo __('Class')?>: <?php echo ($student_class) ? $student_class->getName() : '';?>, <?php echo ($student_class) ? __('School year').' '.$student_class->getPsSchoolYear() : ''?>
		</small>
	</h5>
</div>
<?php echo form_tag_for($form, '@ps_student_service') ?>
    <?php echo $form->renderHiddenFields(false) ?>

    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>

<div class="modal-body" style="overflow: hidden;">
	<?php include_partial('psStudentService/form_new', array('student_service' => $student_service,'ps_student' => $ps_student, 'list_service' => $list_service, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper))?>
</div>

<div class="modal-footer">
   <?php include_partial('psStudentService/form_actions_new', array( 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>
</form>
<style>
<!--
.no-search .select2-search {
	display: none
}
-->
</style>
<script type="text/javascript">

	function addService_Click() {
	
		if (!CheckRelationship()) {
			alert('<?php echo __('You have not selected any service !')?>');
			return false;
		}
	
	}
	
	function CheckRelationship() {
		 var boxes = document.getElementsByTagName('input');
		 for (i = 0; i < boxes.length; i++ ) {
				box = boxes[i];
				if ( box.type == 'checkbox' && box.className == 'select checkbox') {						
					if (box.checked == true)
			  		 return true;	
			  	}
			  }
	  return false;		   
	}
	
</script>