<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">			
		<?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_FEE_REPORT_EXPORT'),))): ?>		
			
			<button type="button"
			class="btn btn-default btn-success btn-sm btn-psadmin exportFeeReceipt">
			<span class="fa fa-file-excel-o"></span> <?php echo __('Export receipt') ?></button>
			
			<?php if($receipt->getPaymentStatus() != PreSchool::ACTIVE):?>
			<button type="button"
			class="btn btn-default btn-success btn-sm btn-psadmin exportStatisticFeeReports">
			<span class="fa fa-file-excel-o"></span> <?php echo __('Export statistic') ?></button>

		<button type="button"
			class="btn btn-default btn-success bg-color-green txt-color-white btn-sm btn-psadmin exportFeeReports">
			<span class="fa fa-file-excel-o"></span> <?php echo __('Export fee report') ?></button>
			
			<?php endif;?>
			
		<?php endif; ?>
		<button type="button" class="btn btn-default" data-dismiss="modal">
			<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close') ?></button>
	</div>
</div>