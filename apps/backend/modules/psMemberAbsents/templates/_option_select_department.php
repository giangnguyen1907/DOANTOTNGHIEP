<?php
echo '<option value="">' . __ ( '-Select department-', array (), 'messages' ) . '</option>';
foreach ( $option_select as $option ) {
	echo '<option value="' . $option->getId () . '" >' . $option->getTitle () . '</option>';
}