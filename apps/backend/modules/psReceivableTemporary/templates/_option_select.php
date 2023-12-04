<?php
echo '<option value="">' . __ ( '-Select receivable-' ) . '</option>';
foreach ( $option_select as $option ) {
	echo '<option value="' . $option->getId () . '" >' . $option->getTitle () . '</option>';
}
?>
