<?php
if ($ps_customer->getIsActivated () == 1)
	$style = 'txt-color-green';
elseif ($ps_customer->getIsActivated () == 2)
	$style = 'txt-color-red';
else
	$style = 'txt-color-orange';

$text = __ ( PreSchool::loadPsCustomerActivated () [$ps_customer->getIsActivated ()] );

echo '<code title="' . $text . '" class="' . $style . '">' . $text . '</code>';

