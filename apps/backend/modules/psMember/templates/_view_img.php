<?php
if ($ps_member->getImage () != '') {
	echo image_tag ( '/pschool/' . $ps_member->getSchoolCode () . '/hr/thumb/' . $ps_member->getImage (), array (
			'style' => 'max-width:45px;text-align:center;' ) );
}
?>