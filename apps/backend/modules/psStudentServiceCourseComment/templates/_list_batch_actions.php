<span id="batch-actions">

	<button type="submit"
		class="btn btn-default btn-success btn-sm btn-psadmin"
		onclick="return submit_Click();">
		<i class="fa-fw fa fa-floppy-o" aria-hidden="true"></i> <?php echo __('Save', array(), 'sf_admin') ?></button>
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
