<div class="btn-group" style="margin: 0 auto;">
	<b class="btn btn-default btn-xs txt-color-red"><b><?php echo $service->getCountSubjectOption();?></b></b>    
    <?php if ($sf_user->hasCredential(array('PS_STUDENT_SUBJECT_ADD', 'PS_STUDENT_SUBJECT_EDIT'), false)): ?>
    <?php echo link_to('<i class="fa fa-pencil" aria-hidden="true"></i>', '@ps_feature_option_subject_service?service_id='.$service->getId(),array('class' => 'btn btn-default btn-xs pull-right action_edit'));?>
    <?php endif; ?>
</div>
