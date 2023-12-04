<?php
echo '<option value="">' . __ ( '-Select service-' ) . '</option>';

$ps_work_places = Doctrine::getTable('PsWorkPlaces')->sqlGetLisstByCustomerId($ps_customer_id, $ps_workplace_id, PreSchool::ACTIVE)->execute ();

$params = array (
		'ps_customer_id' => $ps_customer_id,
		'school_year_id' => $ps_school_year_id,
		'ps_workplace_id' => $ps_workplace_id,
		'is_activated' => PreSchool::ACTIVE );

$list_service = Doctrine::getTable('Service')->setSQLServiceByParamsForChois($params) ->execute ();

foreach ( $ps_work_places as $option ) {
	echo '<optgroup label= "' . $option->getTitle () . '">';
	foreach ( $list_service as $key=> $opt ) {
		if($option->getId () == $opt->getPsWorkplaceId ()){
			echo '<option value="' . $opt->getId () . '" >' . $opt->getTitle () . '</option>' . '<br>';
			unset($list_service[$key]);
		}
	}
	echo '</optgroup>';
}
echo '<optgroup label= "' . __('All school') . '">';
foreach ( $list_service as $key2=> $opt2 ) {
	echo '<option value="' . $opt2->getId () . '" >' . $opt2->getTitle () . '</option>' . '<br>';
	unset($list_service[$key2]);
}
echo '</optgroup>';
?>