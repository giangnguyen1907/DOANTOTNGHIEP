<span id="batch-actions">  
  <?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_FEE_REPORT_ADD',    1 => 'PS_FEE_REPORT_EDIT',  ),))): ?>
<button type="button" id="batch_action_batchPublishReceipts"
		class="btn btn-default btn-success  btn-sm btn-psadmin "
		value="batchPublishReceipts" data-action="batchPublishReceipts">
		<i class="fa-fw fa fa-floppy-o" aria-hidden="true"
			title="<?php echo __('Publish receipt') ?>"></i> <?php echo __('Publish receipt');?></button>
<?php endif; ?>
</span>

<span id="batch-actions">  
  <?php if ($sf_user->hasCredential(array(0 => 'PS_FEE_REPORT_PUSH' ))): ?>
<button type="button" id="batch_action_batchPublishReceipts"
		class="btn btn-default btn-success  btn-sm btn-psadmin "
		value="batchPushNotication" data-action="batchPushNotication">
		<i class="fa-fw fa fa-bell" aria-hidden="true"
			title="<?php echo __('Push notication') ?>"></i> <?php echo __('Push notication');?></button>
<?php endif; ?>
</span>

<button
	class="btn btn-sm btn-default btn-success txt-color-white dropdown-toggle"
	data-toggle="dropdown" aria-expanded="false"
	style="float: none !important;">
	<i class="fa fa-caret-down fa-lg"></i> <?php echo __('Export data')?> 
</button>
<ul class="dropdown-menu dropdown-menu-sm pull-right">
		
			<?php if ($sf_user->hasCredential(array(0 => 'PS_FEE_REPORT_EXPORT' ))): ?>
			
			<li><a href="javascript:void(0);" id="btn-export-student-payment"
		class="btn-export-student-payment"> <i
			class="fa-fw fa fa-cloud-download txt-color-greenLight" data-class=""></i>
				<?php echo __('Export student receipt')?></a></li>

	<li><a href="javascript:void(0);" id="btn-export-student-balance-month"
		class="btn-export-student-balance-month"> <i
			class="fa-fw fa fa-cloud-download txt-color-greenLight" data-class=""></i>
				<?php echo __('Export balance last month amount')?></a></li>

	<li><a href="javascript:void(0);" id="btn-export-fee-receipt"
		class="btn-export-fee-receipt"> <i
			class="fa-fw fa fa-cloud-download txt-color-greenLight" data-class=""></i>
				<?php echo __('Export fee receipt by class')?></a></li>

	<li><a href="javascript:void(0);" id="btn-export-fee-report"
		class="btn-export-fee-report"> <i
			class="fa-fw fa fa-cloud-download txt-color-greenLight" data-class=""></i>
				<?php echo __('Export fee report by class')?></a></li>

	<li><a href="javascript:void(0);" id="btn-export-fee-receipt-workplace"
		class="btn-export-fee-receipt-workplace"> <i
			class="fa-fw fa fa-cloud-download txt-color-greenLight" data-class=""></i>
				<?php echo __('Export fee receipt by workplace')?></a></li>

	<li><a href="javascript:void(0);" id="btn-export-fee-report-workplace"
		class="btn-export-fee-report-workplace"> <i
			class="fa-fw fa fa-cloud-download txt-color-greenLight" data-class=""></i>
				<?php echo __('Export fee report by workplace')?></a></li>

	<li><a href="javascript:void(0);" id="btn-print-fee-receipt-class"
		class="btn-print-fee-receipt-class"> <i
			class="fa-fw fa fa-print txt-color-greenLight" data-class=""></i>
				<?php echo __('Print fee receipt by class')?></a></li>

	<li><a href="javascript:void(0);" id="btn-print-fee-report-class"
		class="btn-print-fee-report-class"> <i
			class="fa-fw fa fa-print txt-color-greenLight" data-class=""></i>
				<?php echo __('Print fee report by class')?></a></li>

	<li><a href="javascript:void(0);" id="btn-print-fee-receipt-workplace"
		class="btn-print-fee-receipt-workplace"> <i
			class="fa-fw fa fa-print txt-color-greenLight" data-class=""></i>
				<?php echo __('Print fee receipt by workplace')?></a></li>

	<li><a href="javascript:void(0);" id="btn-print-fee-report-workplace"
		class="btn-print-fee-report-workplace"> <i
			class="fa-fw fa fa-print txt-color-greenLight" data-class=""></i>
				<?php echo __('Print fee report by workplace')?></a></li>
			
			<?php endif; ?>
			
		</ul>
