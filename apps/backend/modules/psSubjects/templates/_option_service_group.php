<?php
echo '<option selected="selected" value=""></option>';
foreach ( $serviceGroups as $serviceGroup ) {
	echo '<option value="' . $serviceGroup->getId () . '" >' . $serviceGroup->getTitle () . '</option>';
}