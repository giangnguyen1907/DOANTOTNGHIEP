<?php
echo '<option selected="selected" value="">' . __ ( '-Select service courses-' ) . '</option>';
foreach ( $service_courses as $service_course ) {
	echo '<option value="' . $service_course->getId () . '" >' . $service_course->getTitle () . '</option>';
}
?>
