<?php
echo '<option selected="selected" value="">' . __ ( '-Select courses-' ) . '</option>';
foreach ( $courses as $_courses ) {
	echo '<option value="' . $_courses->getId () . '" >' . $_courses->getTitle () . '</option>';
}
?>
