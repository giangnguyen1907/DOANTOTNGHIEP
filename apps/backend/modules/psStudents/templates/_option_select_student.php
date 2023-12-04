<?php use_helper('I18N', 'Date') ?>
<?php
echo '<option value="">' . __ ( '-Select student-' ) . '</option>';
foreach ( $option_select as $option ) {
	echo '<option value="' . $option->getStudentId () . '" >' . $option->getStudentCode () . ' - ' . $option->getFullName () . ' - ' . format_date ( $option->getBirthday (), "dd/MM/yyyy" ) . '</option>';
}