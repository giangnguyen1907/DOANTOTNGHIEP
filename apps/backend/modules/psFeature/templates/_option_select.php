<?php
echo '<option value="">' . __ ( '-Select feature-' ) . '</option>';
foreach ( $option_select as $option ) {
	echo '<option value="' . $option->getId () . '" >' . $option->getTitle () . '</option>';
}
?>
