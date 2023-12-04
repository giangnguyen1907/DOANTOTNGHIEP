<?php use_helper('I18N', 'Date')?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title"><?php echo __('Errors', array(), 'messages') ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<h2 class="font-md">
			<div class="alert alert-danger fade in">
				<button class="close" data-dismiss="alert">×</button>
				<i class="fa-fw fa fa-times ps-fa-2x"></i> <?php echo __('Page Not Found or The data you asked for is secure and you do not have proper credentials.') ?>
			 </div>
		</h2>
	</div>
</div>
<div class="modal-footer">
	<button type="button"
		class="btn btn-default btn-sm btn-psadmin btn-cancel"
		data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i> <?php echo __('Close')?></button>
</div>