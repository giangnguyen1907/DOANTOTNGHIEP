<?php
echo '<option value="">' . __ ( '-Select month-' ) . '</option>';
foreach ( $option_select as $option ) {
	echo '<option value="' . $option . '" >' . $option . '</option>';
}
?>