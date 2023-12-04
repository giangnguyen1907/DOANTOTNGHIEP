<?php use_helper('I18N', 'Number') ?>
<?php $modeText = PreSchool :: loadPsBranchMode();?>
<?php $servicedetails = $service->getServiceDetailByDate(time());?>
<div class="alert alert-info no-margin fade in">
	<button class="close" data-dismiss="alert">×</button>
	<i class="fa-fw fa fa-info"></i><span class="lable"><?php echo __('Subject name')?>:</span>
	<code><?php echo $service->getTitle();?></code>
	<span class="lable"><?php echo __('Service amount')?>: </span>
	<code><?php echo PreNumber::format_currency($servicedetails['amount']);?></code>
	<span class="lable"><?php echo __('Service detail at')?>: </span>
	<code><?php echo format_date($servicedetails['detail_at'],"MM/yyyy" )?></code><?php echo __('Mode')?>: <code><?php echo __($modeText[$service->getMode()]);?></code>
</div>
