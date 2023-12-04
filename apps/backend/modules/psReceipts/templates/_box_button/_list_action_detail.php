<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">			
		<?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_FEE_REPORT_EXPORT'),))): ?>		
			<button type="button"
			class="btn btn-default btn-success bg-color-green btn-sm btn-psadmin exportFeeReceipt">
			<span class="fa fa-file-excel-o"></span> <?php echo __('Export receipt') ?></button>
		<button type="button"
			class="btn btn-default btn-success bg-color-green btn-sm btn-psadmin printFeeReceipt">
			<span class="fa fa-print"></span> <?php echo __('Print receipt') ?></button>
			<?php if($receipt->getPaymentStatus() != PreSchool::ACTIVE):?>
			<button type="button"
			class="btn btn-default btn-success bg-color-green btn-sm btn-psadmin exportStatisticFeeReports">
			<span class="fa fa-file-excel-o"></span> <?php echo __('Export statistic') ?></button>
		<button type="button"
			class="btn btn-default btn-success bg-color-green txt-color-white btn-sm btn-psadmin exportFeeReports">
			<span class="fa fa-file-excel-o"></span> <?php echo __('Export fee report') ?></button>
		<button type="button"
			class="btn btn-default btn-success bg-color-green btn-sm btn-psadmin printFeeReports">
			<span class="fa fa-print"></span> <?php echo __('Print fee report') ?></button>
			
			<?php if ($sf_user->hasCredential('PS_FEE_REPORT_EDIT')): ?>			
				<?php
					echo link_to ( '<i class="fa-fw fa fa-pencil"></i> ' . __ ( 'Edit' ), 'ps_receipts_edit', $receipt, array (
							'class' => 'btn btn-default btn-success btn-sm btn-psadmin' ) );
					?>			
			<?php endif; ?>
			
			<?php endif;?>
		<?php endif; ?>
		
		<button type="button" class="btn btn-default btn-sm"
			onclick="javascript:window.close();">
			<span class="fa-fw fa fa-ban"></span><?php echo __('Close') ?></button>


	</div>
</div>