<?php
if (false !== strtotime ( $value ))
	echo '<span class="date">' . format_date ( $value, "H:mm dd-MM-yyyy" ) . '</span>';
else
	echo '&nbsp;';