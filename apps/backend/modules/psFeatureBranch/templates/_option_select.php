<?php
echo '<option value="">' . __ ( '-Select feature branch-' ) . '</option>';
foreach ( $option_select as $option ) {
	echo '<option value="' . $option->getId () . '" >' . $option->getTitle () . '</option>';
}
?>
