<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
	<?php
	$feature_branch_times = $feature_branch->getFeatureBranchTimes();		
	echo '<div class="bs-example">';
		echo '<dl>';	
		foreach ($feature_branch_times as $feature_branch_time) {
			echo '<dd class="text-success"><i class="fa fa-calendar-o txt-color-orange" aria-hidden="true"></i> - '.PsDateHelper::format_date($feature_branch_time->getStartAt()).' - '.PsDateHelper::format_date($feature_branch_time->getEndAt()).'</dd>';
			echo ' <dd style="padding-left: 25px;"><i>'.$feature_branch_time->getStartTime().' - '.$feature_branch_time->getEndTime().', '.$feature_branch_time->getClassRoomName().'</i></dd>';
		}	
		echo '</dl>';
	echo '</div>';
	?>
	</div>	
	<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
		<?php if ($sf_user->hasCredential('PS_SYSTEM_FEATURE_BRANCH_EDIT')): ?>
		<a href="<?php echo url_for('@ps_feature_branch_times?branch_id='.$feature_branch->getId());?>"  class="btn btn-default btn-xs pull-right">
		 <span class="">
		  <i class="fa fa-pencil"></i>
		 </span>
		</a>		
		<?php endif; ?>		
	</div>
</div>