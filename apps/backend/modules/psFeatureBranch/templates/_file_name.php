<?php
$image_file = $feature_branch->getFileName ();
if ($image_file != '') {
	echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center vertical-middle">' . image_tag ( '/sys_icon/' . $image_file, array (
			'style' => 'max-width:35px;max-height:35px;text-align:center;vertical-align:middle;' ) ) . '</div>';
}