<?php
echo '<option value="">' . __ ( '-Select evaluate subject-' ) . '</option>';
// echo '<option></option>';
foreach ( $option_select as $option ) {
	echo '<option value="' . $option->getId () . '" >' . $option->getTitle () . '</option>';
}