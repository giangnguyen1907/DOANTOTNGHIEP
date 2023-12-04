<?php
if (false !== strtotime ( $receipt->getReceivableAt () ))
	echo '<div class="date">' . format_date ( $receipt->getReceivableAt (), "dd/MM/yyyy" ) . '</div>';
else
	echo '&nbsp;';