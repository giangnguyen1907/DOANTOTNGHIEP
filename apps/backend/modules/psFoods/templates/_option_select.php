<?php
/*
foreach ( $option_select as $option ) {
	echo '<option class="thucdon" value="' . $option->getId () . '" imagesrc="' . $option->getFileName () . '" >' . $option->getTitle () . '</option>';
}
*/
$url_root = sfContext::getInstance ()->getRequest () ->getRelativeUrlRoot ();
foreach ( $option_select as $option ) {
	
	$path_file = '';
	
	if($option->getFileImage () !=''){
		
		$path_file = '/uploads/ps_nutrition/thumb/' . $option->getFileImage ();
		
	} elseif ($option->getFileName () != '') {
		
		$path_file = '/sys_icon/' . $option->getFileName ();
		
	}
	
	if ($path_file != '')
		echo '<option class="thucdon" value="' . $option->getId () . '" imagesrc="' . $url_root.'/'.$path_file . '" >' . $option->getTitle () . '</option>';
	else
		echo '<option class="thucdon" value="' . $option->getId () . '" >' . $option->getTitle () . '</option>';
}
