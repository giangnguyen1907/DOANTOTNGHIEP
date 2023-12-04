<?php use_helper('I18N', 'Date')?>
<?php include_partial('psStudentClass/assets')?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title"><?php echo __('Assign students to class: %%my_class%%', array('%%my_class%%' => $my_class->getName()), 'messages') ?></h4>
</div>

<div class="modal-body" style="overflow: hidden;">
	<?php include_partial('psStudentClass/list_student', array('students' => $students))?>
</div>

<div class="modal-footer">
	<button type="button"
		class="btn btn-default btn-sm btn-psadmin btn-cancel"
		data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i> <?php echo __('Cancel')?></button>
	<button type="submit"
		class="btn btn-default btn-success btn-sm btn-psadmin">
		<i class="fa-fw fa fa-floppy-o" aria-hidden="true"
			title="<?php echo __('Save')?>"></i> <?php echo __('Save')?></button>
</div>