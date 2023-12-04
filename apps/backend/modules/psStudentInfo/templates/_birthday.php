<?php
if (false !== strtotime ( $student->getBirthday () ))
	echo '<div class="date">' . format_date ( $student->getBirthday (), "dd-MM-yyyy" ) . '</div><div><code>' . PreSchool::getAge ( $student->getBirthday (), false ) . '</code></div>';
else
	echo '&nbsp;';