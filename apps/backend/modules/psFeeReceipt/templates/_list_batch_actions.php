<span id="batch-actions" class="batch-actions">  
  <?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_FEE_RECEIPT_NOTICATION_ADD',    1 => 'PS_FEE_RECEIPT_NOTICATION_EDIT',  ),))): ?>
<button type="button" id="batch_action_batchPublishReceipts"
		class="btn btn-default btn-success  btn-sm btn-psadmin "
		value="batchPublishReceipts" data-action="batchPublishReceipts">
		<i class="fa-fw fa fa-floppy-o" aria-hidden="true"
			title="<?php echo __('Publish receipt') ?>"></i> <?php echo __('Publish receipt');?></button>
<?php endif; ?>
</span>

<span id="batch-actions" class="batch-actions">  
  <?php if ($sf_user->hasCredential(array(0 => 'PS_FEE_RECEIPT_NOTICATION_PUSH' ))): ?>
<button type="button" id="batch_action_batchPublishReceipts"
		class="btn btn-default btn-success  btn-sm btn-psadmin "
		value="batchPushNotication" data-action="batchPushNotication">
		<i class="fa-fw fa fa-bell" aria-hidden="true"
			title="<?php echo __('Push notication') ?>"></i> <?php echo __('Push notication');?></button>
<?php endif; ?>
</span>

<span id="batch-actions" class="batch-actions">  
  <?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_FEE_RECEIPT_NOTICATION_ADD',    1 => 'PS_FEE_RECEIPT_NOTICATION_EDIT',  ),))): ?>
<button type="button" id="batch_action_batchPublishReceipts"
		class="btn btn-default btn-success  btn-sm btn-psadmin "
		value="batchPayment" data-action="batchPayment">
		<i class="fa-fw fa fa-money" aria-hidden="true"
			title="<?php echo __('Payment') ?>"></i> <?php echo __('Payment');?></button>
<?php endif; ?>
</span>

<span id="batch-actions" class="batch-actions">
	<button type="button" id="batch_action_batchDelete"
		class="btn btn-default btn-danger  btn-sm btn-psadmin hidden-xs"
		value="batchDelete" data-action="batchDelete">
		<span class="fa fa-trash-o"></span> <?php echo __('Delete', array(), 'sf_admin') ?></button>
</span>

<script type="text/javascript">
$(function () {
	$('.batch-actions button').click(function(){
		var value = $(this).attr("data-action");

    	$('#batch_action').val($(this).attr("data-action"));

		$('#frm_batch').submit();

		return true;
	});
});
</script>