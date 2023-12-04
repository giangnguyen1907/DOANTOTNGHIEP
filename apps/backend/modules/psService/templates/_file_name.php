<?php
$image_file = $service->getFileName ();
if ($image_file != '') {
	echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">' . image_tag ( '/sys_icon/' . $image_file, array (
			'style' => 'max-width:35px;text-align:center;' ) ) . '</div>';
}
