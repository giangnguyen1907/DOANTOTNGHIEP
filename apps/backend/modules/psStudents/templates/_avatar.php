<?php
if ($student->getAvatar () != '') {
	echo image_tag ( '/pschool/' . $student->getSchoolCode () . '/profile/avatar/' . $student->getAvatar (), array (
			'style' => 'max-width:45px;text-align:center;' ) );
}