<?php
if ($ps_cms_notifications->getIsSystem () == 1) {
	echo __ ( 'All system' );
} else if ($ps_cms_notifications->getIsAll () == 1) {
	echo __ ( 'All school' );
} else {
	$list_received_id = explode ( ',', $ps_cms_notifications->getTextObjectReceived () );
	$user_list = Doctrine::getTable ( 'sfGuardUser' )->getUserInfo ( $list_received_id, PreSchool::USER_TYPE_RELATIVE );

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
