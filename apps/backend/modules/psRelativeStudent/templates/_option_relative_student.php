<?php
echo '<option value="">' . __ ( '-Relative student-' ) . '</option>';
foreach ( $option_select as $option ) {
	echo '<option value="' . $option->getRelativeId () . '" >' . $option->getTitle () . '</option>';
}