<div class="text-center">
<?php

if ($value == PreSchool::PUBLISH) :
	echo '<i class="fa fa-check-circle-o txt-color-green" title="' . __ ( 'Publish' ) . '"></i>';
 elseif ($value == PreSchool::LOCK) :
	echo '<i class="fa fa-lock txt-color-red" title="' . __ ( 'Lock' ) . '"></i>';
 elseif ($value == PreSchool::NOT_PUBLISH) :
	echo '<i class="fa fa-ban txt-color-darken" title="' . __ ( 'Not publish' ) . '"></i>';
endif;
?>
</div>