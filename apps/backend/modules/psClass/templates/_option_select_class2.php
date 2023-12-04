<?php
echo '<option value="">' . __ ( '-Select class-' ) . '</option>';
foreach ( $option_select as $option ) {
	echo '<option value="' . $option->getId () . '" >' . $option->getTitle () . '</option>';
}
echo '<option value="'.PreSchool::NOT_IN_CLASS.'">' . __ ( '-Not in class-' ) . '</option>';
echo '<option value="'.PreSchool::CLASS_LOCKED.'">' . __ ( '-Class locked-' ) . '</option>';
?>