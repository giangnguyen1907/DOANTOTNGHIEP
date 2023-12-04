<?php
echo ($receipt->getId () > 0) ? PreNumber::number_format ( $receipt->getReceivable () ) : __ ( 'Data not available' );
