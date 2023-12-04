<?php
echo '<option value="">' . __ ( '-Select user-' ) . '</option>';
foreach ( $option_select as $option ) {
	echo '<option value="' . $option->getId () . '" >' . $option->getFullname () . '</option>';
}
?>
