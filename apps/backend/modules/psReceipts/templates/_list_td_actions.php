<td class="text-center">
	<div class="btn-group">
		<?php if($receipt->getId() > 0){ ?>
		<a class="btn btn-xs btn-default" target="_blank"
			href="<?php echo url_for('@ps_receipts_show?id='.$receipt->getId()); ?>"><i
			class="fa-fw fa fa-eye txt-color-blue" title="<?php echo __('Detail')?>"></i><?php echo __('View')?></a> 
		<?php }else{?>
		<a class="btn btn-xs btn-default disabled"><i
			class="fa-fw fa fa-eye txt-color-blue" title="<?php echo __('Detail')?>"></i><?php echo __('View')?></a> 
		<?php }?>
		<a class="btn btn-xs btn-default" data-backdrop="static"
			data-toggle="modal" data-target="#remoteModal"
			title="<?php echo __('See receivable of student')?>"
			href="<?php echo url_for('@ps_fee_reports_receivable_student_detail?sid='.$receipt->getStudentId().'&kstime='.$ktime)?>"><i
			class="fa-fw fa fa-money txt-color-blue"></i></a>
	
	<?php if ($sf_user->hasCredential('PS_FEE_REPORT_EDIT')): ?>
	<?php echo $helper->linkToEdit($receipt, array(  'credentials' => 'PS_FEE_REPORT_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
	<?php endif; ?>
	
	<?php if ($sf_user->hasCredential('PS_FEE_REPORT_DELETE')): ?>
	<?php echo $helper->linkToDelete($receipt, array(  'credentials' => 'PS_FEE_REPORT_DELETE',  'confirm' => 'Are you sure wish delete this fee report?',  'title' => 'Delete fee report',  'params' =>   array(  ),  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
	<?php endif; ?>

  </div>
</td>