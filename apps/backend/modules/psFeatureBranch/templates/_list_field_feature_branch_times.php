<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<?php
	$feature_branch_times = $feature_branch->getFeatureBranchTimes ();
	echo '<ul class="media-list">';
	echo '<li class="media">';
	echo '<div class="media-body">';
	foreach ( $feature_branch_times as $feature_branch_time ) {
		echo '<a rel="popover-hover" data-placement="right" data-original-title="' . __ ( 'Description' ) . '" data-content="' . $feature_branch_time->getNote () . '">';
		echo '<i class="fa fa-calendar-o txt-color-orange" aria-hidden="true"></i> ' . PsDateHelper::format_date ( $feature_branch_time->getStartAt () ) . ' ; ' . PsDateHelper::format_date ( $feature_branch_time->getEndAt () );
		echo '<p style="padding-left:25px;"><i>' . $feature_branch_time->getStartTime () . ' - ' . $feature_branch_time->getEndTime () . ', ' . $feature_branch_time->getClassRoomName () . '</i></p>';
		echo '</a>';
	}
	echo '</div>';
	echo '</li>';
	echo '</ul>';
	?>
</div>