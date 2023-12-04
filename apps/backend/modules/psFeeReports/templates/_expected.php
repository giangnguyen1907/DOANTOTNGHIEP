<?php
echo ($ps_fee_reports->getId () > 0) ? PreNumber::number_format ( $ps_fee_reports->getReceivable () ) : __ ( 'Data not available' );
