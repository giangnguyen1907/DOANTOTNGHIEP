<?php
if (count ( $psConstants ) > 0) {
	echo '<option value=""></option>';
	foreach ( $psConstants as $psConstant ) {
		echo '<option value="' . $psConstant->getId () . '" >' . $psConstant->getTitle () . '</option>';
	}
} else {
	echo '<option value="">' . __ ( 'Cấu hình hằng số đã đủ' ) . '</option>';
}
?>
