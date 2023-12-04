<small>
<?php
if (false !== strtotime ( $value ))
	echo '<div class="date">' . format_date ( $value, "dd-MM-yyyy" ) . '</div><div><code>' . PreSchool::getAge ($value, false ) . '(' . PreSchool::getMonthYear ( $value, date ( 'Y-m-d' )) . ' ' . __ ("month") . ')</code></div>';
else
	echo '&nbsp;';
?>
</small>