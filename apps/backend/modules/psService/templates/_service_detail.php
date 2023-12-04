<?php $servicedetails = $service->getServiceDetailByDate(time());?>
<div class="col-md-12 pull-left">
	<div class="col-md-4 pull-left text-right border-right">
	 <?php echo PreNumber::number_format($servicedetails['amount']);?>&nbsp;
	</div>
	<div class="col-md-4 pull-left text-center">
		<code><?php echo format_date($servicedetails['detail_at'],"MM/yyyy" )?></code>
		&nbsp;
	</div>
	<div class="col-md-4 pull-left text-center border-left">
		<?php if ($sf_user->hasCredential(array('PS_STUDENT_SERVICE_EDIT', 'PS_STUDENT_SERVICE_ADD'), false) && $service->getEnableRoll() == PreSchool::SERVICE_TYPE_NOT_FIXED):?>
		<?php echo link_to(__('Split'),'@ps_service_splits_new?sid='.$service->getId(), array('class' => 'btn btn-xs btn-default btn-success btn-psadmin'))?>
		<?php endif;?>
	</div>
</div>