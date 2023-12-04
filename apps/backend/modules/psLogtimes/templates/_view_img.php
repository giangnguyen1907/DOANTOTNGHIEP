<?php
if ($ps_logtimes->getImage () != '') {
	$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $ps_logtimes->getSchoolCode () . '/' . $ps_logtimes->getYearData () . '/' . $ps_logtimes->getImage ();
	echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '"/>';
}