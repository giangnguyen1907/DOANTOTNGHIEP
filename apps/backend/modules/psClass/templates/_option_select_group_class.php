<?php
echo '<option value="">' . __ ( '-Select class-' ) . '</option>';
$ps_workplace = Doctrine::getTable ( 'PsWorkplaces' )->setSQLByCustomerId ( $getField = 'id, title', $psCustomerId = $customer_id, $is_activated = PreSchool::ACTIVE )
	->execute ();
$ps_school = Doctrine::getTable ( 'PsSchoolYear' )->findOneById ( $schoolyear_id );
$schoolyear = $ps_school->getTitle ();
foreach ( $ps_workplace as $option ) {

	$class = Doctrine::getTable ( 'MyClass' )->getClassByParams ( array (
			'ps_customer_id' => $customer_id,
			'ps_school_year_id' => $schoolyear_id,
			'ps_workplace_id' => $option->getId () ) );
	echo '<optgroup label= "' . $option->getTitle () . '">';
	foreach ( $class as $opt ) {
		echo '<option value="' . $opt->getId () . '" >' . $opt->getName () . ' (' . $schoolyear . ') </option>' . '<br>';
	}
	echo '</optgroup>';
}
