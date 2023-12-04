<?php
if ($student_service_course_comment->getImage () != '') {
	$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $student_service_course_comment->getSchoolCode () . '/' . $student_service_course_comment->getYearData () . '/' . $student_service_course_comment->getImage ();
	echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
}