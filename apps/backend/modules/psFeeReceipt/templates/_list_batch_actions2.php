<span id="batch-actions">  
  <?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_FEE_RECEIPT_NOTICATION_ADD',    1 => 'PS_FEE_RECEIPT_NOTICATION_EDIT',  ),))): ?>
<button type="button" id="batch_action_batchPublishReceipts"
		class="btn btn-default btn-success  btn-sm btn-psadmin "
		value="batchPublishReceipts2" data-action="batchPublishReceipts2">
		<i class="fa-fw fa fa-floppy-o" aria-hidden="true"
			title="<?php echo __('Publish receipt') ?>"></i> <?php echo __('Publish receipt');?></button>
<?php endif; ?>
</span>
