<?php use_helper('I18N', 'Date', 'Number')?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<?php if($app_amount): ?>
		<h4 class="modal-title" id="myModalLabel"><?php echo __('App amount history: %%username%%, last pay: %%amount%%, duration: %%duration%% days', array('%%username%%' => '<code>'.$app_amount->getUserMobileAppAmounts()->getFirstName()." ".$app_amount->getUserMobileAppAmounts()->getLastName().'('.$app_amount->getUserMobileAppAmounts()->getUserName().')</code>', '%%amount%%' => "<code>".format_currency($app_amount->getAmount())."</code>", '%%duration%%' => "<code>".$app_amount->getDuration()."</code>") ) ?></h4>
	<?php else: ?>
		<h4 class="modal-title" id="myModalLabel"><?php echo __('App amount history') ?></h4>
	<?php endif; ?>

</div>
<div class="modal-body">
	<?php include_partial('psMobileAppAmounts/list_history', array('list_history' => $list_history, 'pager' => $pager, 'app_amount' => $app_amount)) ?>
	
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-left">
		<?php if($pager->haveToPaginate()): ?>
			<?php include_partial('psMobileAppAmounts/pagination', array('pager' => $pager)) ?>
		<?php endif; ?>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close') ?></button>
</div>


