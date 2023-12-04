<?php
if ($ps_cms_notifications->getIsSystem () == 1) {
	echo __ ( 'Is system' );
} elseif ($ps_cms_notifications->getIsSystem () == 2) {
	echo __ ( 'All teacher' );
} elseif ($ps_cms_notifications->getIsSystem () == 3) {
	echo __ ( 'All relative' );
} elseif ($ps_cms_notifications->getIsAll () == 1) {
	echo __ ( 'Is school' );
} elseif ($ps_cms_notifications->getIsAll () == 2) {
	echo __ ( 'Is worplace' );
} elseif ($ps_cms_notifications->getIsObject () == 1) {
	echo __ ( 'Is teacher' );
} elseif ($ps_cms_notifications->getIsObject () == 2) {
	echo __ ( 'Is relative' );
} else {
	$list_teacher = explode ( ',', $ps_cms_notifications->getTextObjectReceived () );
	$user_list = Doctrine::getTable ( 'sfGuardUser' )->getUserNotification ( $list_teacher );

	$i = 0;
	foreach ( $user_list as $user ) {
		echo $user->getFullname () . ' (' . $user->getUsername () . '). ';
		$i ++;
		if ($i == 3) {
			echo '....';
			break;
		}
	}
}

?>