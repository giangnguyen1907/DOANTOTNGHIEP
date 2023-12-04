<?php
echo '<option value="">' . __ ( '-Select district-' ) . '</option>';
foreach ( $option_select as $option ) {
	echo '<option value="' . $option->getId () . '" >' . $option->getName () . '</option>';
}
?>