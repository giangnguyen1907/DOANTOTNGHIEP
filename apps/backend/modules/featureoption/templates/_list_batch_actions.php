
<span id="batch-actions">
  
  <?php if ($sf_user->hasCredential('PS_SYSTEM_FEATURE_OPTION_EDIT')): ?>
<button type="button" id="batch_action_batchUpdateOrder"
		class="btn btn-default btn-success btn-psadmin "
		value="batchUpdateOrder" data-action="batchUpdateOrder">
		<i class="fa-fw fa fa-floppy-o" aria-hidden="true"
			title="<?php echo __('Update Order', array(), 'sf_admin') ?>"></i> <?php echo __('Update Order', array(), 'sf_admin') ?></button>
<?php endif; ?>
  <?php if ($sf_user->hasCredential('PS_SYSTEM_FEATURE_OPTION_DELETE')): ?>
<button type="button" id="batch_action_batchDelete"
		class="btn btn-default btn-success btn-psadmin hidden-xs"
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
