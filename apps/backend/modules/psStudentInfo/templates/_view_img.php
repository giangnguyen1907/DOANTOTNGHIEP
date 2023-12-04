<?php
if ($student->getImage () != '') {
	$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $student->getSchoolCode () . '/' . $student->getYearData () . '/' . $student->getImage ();
	echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
}