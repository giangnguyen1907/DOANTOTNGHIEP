<?php
if ($ps_member->getImage () != '') {
	$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_TEACHER . '/' . $ps_member->getSchoolCode () . '/' . $ps_member->getYearData () . '/' . $ps_member->getImage ();
	echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
}