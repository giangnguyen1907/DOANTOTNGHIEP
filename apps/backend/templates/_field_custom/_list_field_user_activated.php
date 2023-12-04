<div class="text-center">
<?php

if ($value == PreSchool::CUSTOMER_ACTIVATED) :
	echo '<i class="fa fa-check-circle-o txt-color-green" title="' . __ ( 'Activated' ) . '"></i>';
 elseif ($value == PreSchool::CUSTOMER_LOCK) :
	echo '<i class="fa fa-lock txt-color-red" title="' . __ ( 'Lock' ) . '"></i>';
 elseif ($value == PreSchool::CUSTOMER_NOT_ACTIVATED) :
	echo '<i class="fa fa-ban txt-color-darken" title="' . __ ( 'Not active' ) . '"></i>';
endif;
?>
</div>