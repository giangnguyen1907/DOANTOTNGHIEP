<?php //if ($sf_user->hasCredential(array(0 => array(0 => 'PS_FEE_REPORT_EXPORT')))): ?>
<?php if (myUser::isAdministrator()): ?>
<div class="btn-group display-inline pull-right text-align-left">
	<button class="btn btn-sm btn-default btn-success dropdown-toggle"
		data-toggle="dropdown" aria-expanded="false">
		<i class="fa fa-caret-down fa-lg"></i> <?php echo __('Export data')?>
		</button>

	<ul class="dropdown-menu dropdown-menu-xs pull-right">
		<li><a href="javascript:void(0);" class="batchExportFeeReports"><i
				class="fa fa-file-excel-o fa-lg fa-fw txt-color-greenLight"></i> <?php echo __('Export fee report for class') ?></a>
		</li>

		<li><a href="javascript:void(0);" class="batchExportFeeReceipt"><i
				class="fa fa-file-excel-o fa-lg fa-fw txt-color-greenLight"></i> <?php echo __('Export receipt of class') ?></a>
		</li>

		<li class="divider"></li>

		<li><a href="javascript:void(0);"
			class="batchExportStatisticFeeReports"><i
				class="fa fa-file-excel-o fa-lg fa-fw txt-color-greenLight"></i> <?php echo __('Export statistic collection of class') ?></a>
		</li>

		<li class="divider"></li>

		<li><a href="javascript:void(0);" id="btnExportStatisticFeeDebt"><i
				class="fa fa-file-excel-o fa-lg fa-fw txt-color-greenLight"></i> <?php echo __('Export statistic debt') ?></a>
		</li>

		<li class="divider"></li>

		<li class="text-align-center"><a href="javascript:void(0);"><?php echo __('Cancel')?></a>
		</li>
	</ul>
</div>
<?php endif;?>