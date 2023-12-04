<?php
if (count ( $ps_service_course_shedules ) > 0) {
	echo '<option selected="selected" value="">' . __ ( '-Choose a schedule-' ) . '</option>';
	foreach ( $ps_service_course_shedules as $ps_service_course_shedule ) {
		echo '<option value="' . $ps_service_course_shedule->getId () . '" >' . $ps_service_course_shedule->getTitle () . '</option>';
	}
}