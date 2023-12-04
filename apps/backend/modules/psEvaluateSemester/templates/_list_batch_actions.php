<span id="batch-actions" class="batch-actions">  
<?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_SYSTEM_FEATURE_BRANCH_EDIT',    1 => 'PS_SYSTEM_FEATURE_BRANCH_EDIT',  ),))): ?>
<button type="button" id="batch_action_batchPublishReceipts"
		class="btn btn-default btn-success  btn-sm btn-psadmin "
		value="batchPublishReceipts" data-action="batchPublishReceipts">
		<i class="fa-fw fa fa-floppy-o" aria-hidden="true"
			title="<?php echo __('Publish receipt') ?>"></i> <?php echo __('Publish receipt');?></button>
<?php endif; ?>
</span>

<span id="batch-actions">
	<button type="button" id="batch_action_batchDelete"
		class="btn btn-default btn-danger  btn-sm btn-psadmin hidden-xs"
		value="batchDelete" data-action="batchDelete">
		<span class="fa fa-trash-o"></span> <?php echo __('Delete', array(), 'sf_admin') ?></button>
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
