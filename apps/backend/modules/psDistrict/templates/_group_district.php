<?php
$html = '<option value="">' . __ ( '-Select district-' ) . '</option>';

foreach ( $psDistricts as $psProvince => $districts ) {
	$html .= '<optgroup label="' . $psProvince . '">';

	foreach ( $districts as $d_id => $district ) {
		$html .= '<option value="' . $d_id . '" >' . $district . '</option>';
	}

	$html .= '</optgroup>';
}

echo $html;
exit ( 0 );