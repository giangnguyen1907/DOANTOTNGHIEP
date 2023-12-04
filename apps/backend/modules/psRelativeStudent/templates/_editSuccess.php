<?php use_helper('I18N', 'Date')?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title"><?php echo __('Update role of relative: %%relative%%', array('%%relative%%' => $relative_student->getRelative()->getFirstName().' '.$relative_student->getRelative()->getLastName()), 'messages') ?></h4>
</div>
<?php echo form_tag_for($form, '@ps_relative_student', array('class' => 'form-horizontal', 'id' => 'ps-form', 'data-fv-addons' => 'i18n')) ?>
    <?php echo $form->renderHiddenFields(true) ?>

    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>
<div class="modal-body" style="overflow: hidden;">
	<?php include_partial('psRelativeStudent/formEdit', array('relative_student' => $relative_student,'ps_student' => $ps_student, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper))?>
</div>
<div class="modal-footer">
    <?php include_partial('psRelativeStudent/form_actions', array('relative_student' => $relative_student, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
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
	$('#relative_student_relationship_id').select2({
		  dropdownParent: $('#remoteModal'),
		  dropdownCssClass : 'no-search'
	});	
</script>
