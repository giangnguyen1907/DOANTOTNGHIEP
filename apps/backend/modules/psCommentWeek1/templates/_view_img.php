<?php
if ($ps_comment_week->getImage () != '') {
	$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $ps_comment_week->getSchoolCode () . '/' . $ps_comment_week->getYearData () . '/' . $ps_comment_week->getImage ();
} else {
	$path_file = '/images/no_img.png';
}
echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '"/>';