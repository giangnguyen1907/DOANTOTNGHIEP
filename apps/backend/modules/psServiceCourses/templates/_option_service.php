<?php
echo '<option selected="selected" value="">' . __ ( '-Select service-' ) . '</option>';
foreach ( $service as $_service ) {
	echo '<option value="' . $_service->getId () . '" >' . $_service->getTitle () . '</option>';
}
?>
