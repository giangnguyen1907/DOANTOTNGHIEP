<span id="batch-actions">  
<?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_FEE_REPORT_ADD',    1 => 'PS_FEE_REPORT_EDIT',  ),))): ?>
<button type="button" id="action_batchPublishReceipts"
		class="btn btn-default btn-success  btn-sm btn-psadmin"
		value="batchPublishReceipts" data-action="batchPublishReceipts"
		title="<?php echo __('The parents see');?>"><?php echo __('Publish receipt', array(), 'sf_admin') ?></button>
<?php endif;?>
  <?php if ($sf_user->hasCredential('PS_FEE_REPORT_DELETE')): ?>
<button type="button" id="batch_action_batchDelete"
		class="btn btn-default btn-danger  btn-sm btn-psadmin"
		value="batchDelete" data-action="batchDelete">
		<span class="fa fa-trash-o"></span> <?php echo __('Delete', array(), 'sf_admin') ?></button>
<?php endif;?>
	<input type="hidden" name="batch_action" id="batch_action" value="" />
	<?php $form = new BaseForm(); if ($form->isCSRFProtected()): ?>
    <input type="hidden" name="<?php echo $form->getCSRFFieldName() ?>"
	value="<?php echo $form->getCSRFToken() ?>" />
  <?php endif;?>
</span>