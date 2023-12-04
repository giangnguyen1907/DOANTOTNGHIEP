<span id="batch-actions">  
  	<?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_STUDENT_FEATURE_ADD',    1 => 'PS_STUDENT_FEATURE_EDIT',    2 => 'PS_STUDENT_ATTENDANCE_TEACHER',  ),))): ?>
	<button type="submit"
		class="btn btn-default btn-success btn-sm btn-psadmin"
		onclick="return submit_Click();">
		<i class="fa-fw fa fa-floppy-o" aria-hidden="true"></i> <?php echo __('Save', array(), 'sf_admin') ?></button>
	<?php endif; ?>
	
	<?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_STUDENT_FEATURE_DELETE',    1 => 'PS_STUDENT_ATTENDANCE_TEACHER',  ),))): ?>
	<button type="button" id="batch_action_batchDelete"
		class="btn btn-default btn-danger  btn-sm btn-psadmin hidden-xs"
		value="batchDelete" data-action="batchDelete">
		<span class="fa fa-trash-o"></span> <?php echo __('Delete', array(), 'sf_admin') ?></button>
	<?php endif; ?>
	
    <input type="hidden" name="batch_action" id="batch_action" value="" />
    <?php $form = new BaseForm(); if ($form->isCSRFProtected()): ?>
    <input type="hidden" name="<?php echo $form->getCSRFFieldName() ?>"
	value="<?php echo $form->getCSRFToken() ?>" />
    <?php endif; ?>
</span>

<script type="text/javascript">
$(function () {
	$('#batch-actions button').click(function(){
		var value = $(this).attr("data-action");

    	$('#batch_action').val($(this).attr("data-action"));

		$('#frm_batch').submit();

		return true;
	});
});
</script>
