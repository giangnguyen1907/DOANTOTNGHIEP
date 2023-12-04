<?php
if ($student->getImage () != '') {
	$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $student->getSchoolCode () . '/' . $student->getYearData () . '/' . $student->getImage ();
} else {
	$path_file = '/media-web/no_img.png';
}
echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';