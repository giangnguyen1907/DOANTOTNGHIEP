<small>
<?php
if (false !== strtotime ( $receipt->getBirthday () ))
	echo '<p class="date">' . format_date ( $receipt->getBirthday (), "dd-MM-yyyy" ) . '</p><p><code>' . PreSchool::getAge ( $receipt->getBirthday (), true ) . '</code></p>';
else
	echo '&nbsp;';
?>
</small>