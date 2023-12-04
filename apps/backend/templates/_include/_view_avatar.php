<?php
// Path folder luu anh
/*
 * $root_path = sfConfig::get('app_ps_upload_dir').'pschool/logo/';
 * if ($logo != '' && ) {
 * }
 * sfContext::getInstance()->getRequest()->getRelativeUrlRoot().'/pschool/logo/'.$this->getObject()->logo;
 */
if ($ps_member->getImage () != '') {
	echo image_tag ( '/pschool/' . $ps_member->getSchoolCode () . '/hr/' . $ps_member->getImage (), array (
			'style' => 'max-width:50px;text-align:center;' ) );
}