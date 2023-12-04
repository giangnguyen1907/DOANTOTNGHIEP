<?php
$html = '<option value="">' . __ ( '-Select class-' ) . '</option>';

foreach ( $ps_class as $class ) {

	$html .= '<option value="' . $class->getId () . '" >' . $class->getTitle () . '</option>';
}

echo $html;
exit ( 0 );