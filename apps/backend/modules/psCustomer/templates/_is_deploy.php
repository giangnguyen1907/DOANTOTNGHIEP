<?php
if ($ps_customer->getIsDeploy () == 1) {
	$style = 'txt-color-green';
} else {
	$style = 'txt-color-orange';
}
$text = __ ( PreSchool::loadPsCustomerDeploy () [$ps_customer->getIsDeploy ()] );

echo '<code title="' . $text . '" class="' . $style . '">' . $text . '</code>';

