<?php
if ($feature_branch->getIsActivated () == PreSchool::ACTIVE) {

	$status = __ ( 'Active' );

	$str = "<span class='btn btn-success btn-xs'>" . $status . "</span>";
} else {

	$status = __ ( 'Inactive' );

	$str = "<span class='btn btn-danger btn-xs'>" . $status . "</span>";
}
?>
<a class="item-activated"
	id="item-activated-<?php echo $feature_branch->getId(); ?>"
	href="javascript:;" item="<?php echo $feature_branch->getId();?>"
	rel="tooltip" data-placement="bottom"
	data-original-title="<?php echo $status ?>">
  <?php
		// echo get_partial('psFeatureBranch/list_field_boolean', array('value' => $feature_branch->getIsActivated()))
		echo $str;
		?>  
</a>