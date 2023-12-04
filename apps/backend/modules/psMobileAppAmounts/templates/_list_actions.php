<?php

echo link_to ( '<i class="fa-fw fa fa-plus"></i> ' . __ ( 'Pay' ), 'ps_history_mobile_app_pay_amounts_new', array (), array (
		'class' => 'btn btn-default btn-success btn-sm btn-psadmin' ) );
?>
<a data-toggle="modal" data-target="#pay_multi_modal"
	class="btn btn-info btn-sm btn-psadmin btn-default"
	style="margin-left: 15px;"><i class="fa fa-fw fa-money"></i> <?php echo __('Pay for multiple') ?></a>
