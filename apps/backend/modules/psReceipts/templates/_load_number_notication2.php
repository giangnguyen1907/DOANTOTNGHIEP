<a class="btn btn-labeled btn-success <?php if($receipt->getIsPublic() > 0){ echo 'push_notication';}else{ echo 'not_relative_see disabled';} ?>" id="push_notication-<?php echo $receipt->getId() ?>" href="javascript:;" value="<?php echo $receipt->getStudentId() ?>" data-value="<?php echo $receipt->getId() ?>" >
   <span class="btn-label">
		<?php echo $receipt->getNumberPushNotication() ?>
	</span>
	<span class="btn-control">
		<i class="fa fa-bell"></i>
	</span>
</a>