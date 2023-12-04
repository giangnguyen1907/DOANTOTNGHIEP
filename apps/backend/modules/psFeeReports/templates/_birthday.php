<?php
if (false !== strtotime ( $ps_fee_reports->getBirthday () ))
	echo '<div class="date">' . format_date ( $ps_fee_reports->getBirthday (), "dd-MM-yyyy" ) . '</div><div><code>' . PreSchool::getAge ( $ps_fee_reports->getBirthday (), false ) . '</code></div>';
else
	echo '&nbsp;';