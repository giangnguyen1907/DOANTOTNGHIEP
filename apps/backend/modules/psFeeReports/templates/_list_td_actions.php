<td class="text-center">
	<div class="btn-group display-inline pull-right text-align-left">

		<a class="btn btn-xs btn-default" data-backdrop="static"
			data-toggle="modal" data-target="#remoteModal"
			title="<?php echo __('See receivable of student')?>"
			href="<?php echo url_for('@ps_fee_reports_receivable_student_detail?sid='.$ps_fee_reports->getStudentId().'&kstime='.$ktime)?>"><i
			class="fa-fw fa fa-money txt-color-blue"></i></a>
		
		<?php if ($sf_user->hasCredential('PS_FEE_REPORT_DETAIL')): ?>
			<?php echo $helper->linkToDetail($ps_fee_reports, array(  'credentials' => 'PS_FEE_REPORT_DETAIL',  'params' =>   array(  ),  'class_suffix' => 'detail',  'label' => 'Detail',)) ?>
		<?php endif;?>
		
		<?php if ($sf_user->hasCredential('PS_FEE_REPORT_EDIT')): ?>
		<a class="btn btn-xs btn-default btn-edit-td-action "
			href="<?php echo url_for('@ps_receipts_edit?id='.$ps_fee_reports->getReId())?>"><i
			class="fa-fw fa fa-pencil txt-color-orange"
			title="<?php echo __('Edit receipt')?>"></i></a>
		<?php endif;?>
		
	    <?php if ($sf_user->hasCredential('PS_FEE_REPORT_DELETE')): ?>
			<?php echo $helper->linkToDelete($ps_fee_reports, array(  'credentials' => 'PS_FEE_REPORT_DELETE',  'confirm' => 'Are you sure wish delete this fee report?',  'title' => 'Delete fee report',  'params' =>   array(  ),  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
		<?php endif;?>
		
		<button class="btn btn-xs btn-default dropdown-toggle"
			data-toggle="dropdown" aria-expanded="false">
			<i class="fa fa-caret-down fa-lg"></i>
		</button>
		
		<?php if (myUser::isAdministrator() && $sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_FEE_REPORT_EXPORT'),)) && $ps_fee_reports->getId() > 0): ?>
		<ul class="dropdown-menu dropdown-menu-xs pull-right">
			<li><a
				data-action="<?php echo url_for('@ps_fee_receipt_export?id='.$ps_fee_reports->getId())?>"
				data-item="<?php echo $ps_fee_reports->getId();?>"
				href="javascript:void(0);" class="exportFeeReceipt"><i
					class="fa fa-file-excel-o fa-lg fa-fw txt-color-greenLight"></i> <?php echo __('Export receipt') ?></a>
			</li>

			<li><a
				data-action="<?php echo url_for('@ps_fee_reports_export?id='.$ps_fee_reports->getId())?>"
				data-item="<?php echo $ps_fee_reports->getId();?>"
				href="javascript:void(0);" class="exportFeeReports"><i
					class="fa fa-file-excel-o fa-lg fa-fw txt-color-greenLight"></i> <?php echo __('Export fee report') ?></a>
			</li>

			<li><a
				data-action="<?php echo url_for('@ps_fee_reports_export?id='.$ps_fee_reports->getId())?>"
				data-item="<?php echo $ps_fee_reports->getId();?>"
				href="javascript:void(0);" class="exportStatisticFeeReports"><i
					class="fa fa-file-excel-o fa-lg fa-fw txt-color-greenLight"></i> <?php echo __('Export statistic') ?></a>
			</li>

			<li class="divider"></li>
			<li class="text-align-center"><a href="javascript:void(0);"><?php echo __('Cancel')?></a>
			</li>
		</ul>
		<?php endif;?>
	</div>
</td>