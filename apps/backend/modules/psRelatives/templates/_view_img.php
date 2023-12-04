<?php
if ($relative->getImage () != '') {
	// echo image_tag('/pschool/'.$relative->getSchoolCode().'/relative/thumb/'.$relative->getImage(), array('style' => 'max-width:45px;text-align:center;'));

	$path_file = '/media-web/02/' . $relative->getSchoolCode () . '/' . $relative->getYearData () . '/' . $relative->getImage ();
	echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
}
?>