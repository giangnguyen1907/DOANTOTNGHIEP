<?php
if ($student_feature->getImage () != '') {
	$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $student_feature->getSchoolCode () . '/' . $student_feature->getYearData () . '/' . $student_feature->getImage ();
	echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
}