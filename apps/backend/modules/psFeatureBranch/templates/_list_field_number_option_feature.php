<div class="btn-group center" style="margin: 0 auto;">
	<!-- <b class="btn btn-default btn-xs txt-color-red"><b><?php //echo $feature_branch->getCountBranchOption();?></b></b> -->
	<b class="btn btn-default btn-xs txt-color-red"><b><?php echo $feature_branch->getNumberOption();?></b></b>     
    <?php if ($sf_user->hasCredential('PS_SYSTEM_FEATURE_EDIT')): ?>
    <?php echo link_to('<i class="fa fa-pencil" aria-hidden="true"></i>', '@ps_feature_option_feature_branch?branch_id='.$feature_branch->getId(),array('class' => 'btn btn-default btn-xs pull-right action_edit'));?>
    <?php endif; ?>
</div>
