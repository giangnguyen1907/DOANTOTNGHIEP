<?php
$value = $sf_guard_user->getUserType ();
?>
<div class="text-center">
<?php
if ($value == PreSchool::USER_TYPE_TEACHER)
	echo '<code>' . __ ( PreSchool::loadPsUserType () [$value] ) . '</code>';
else
	echo __ ( PreSchool::loadPsUserType () [$value] );
?>
</div>