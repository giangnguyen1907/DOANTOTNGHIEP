<?php
if ($ps_camera->getImageName () != '') {
	$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_CAMERA . '/' . $ps_camera->getSchoolCode () . '/' . $ps_camera->getYearData () . '/' . $ps_camera->getImageName ();
	echo '<img style="max-width: 50px; text-align: center;" src="' . $path_file . '">';
}