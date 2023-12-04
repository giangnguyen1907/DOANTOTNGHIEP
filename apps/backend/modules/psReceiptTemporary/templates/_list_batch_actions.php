<span id="batch-actions">
	<button type="button" id="batch_action_batchDelete"
		class="btn btn-default btn-success btn-sm btn-psadmin hidden-xs"
		value="batchDelete" data-action="batchDelete">
		<span class="fa fa-mail-forward"></span> 
      <?php echo __('Forward') ?>
  </button> <input type="hidden" name="batch_action" id="batch_action"
	value="" />
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
