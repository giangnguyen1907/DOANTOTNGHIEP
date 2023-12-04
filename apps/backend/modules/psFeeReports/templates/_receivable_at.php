<?php
if (false !== strtotime ( $ps_fee_reports->getReceivableAt () ))
	echo '<div class="date">' . format_date ( $ps_fee_reports->getReceivableAt (), "dd/MM/yyyy" ) . '</div>';
else
	echo '&nbsp;';