<?php
echo '<option value="">' . __ ( '-Select province-' ) . '</option>';
foreach ( $psProvinces as $psProvince ) {
	echo '<option value="' . $psProvince->getId () . '" >' . $psProvince->getName () . '</option>';
}
?>