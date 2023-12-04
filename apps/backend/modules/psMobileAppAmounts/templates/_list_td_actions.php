<td class="text-center hidden-md hidden-sm hidden-xs"
	style="width: 150px;">
	<div class="btn-group">
		<a data-backdrop="static" data-toggle="modal"
			data-target="#remoteModal"
			href="<?php echo url_for('@ps_mobile_app_amounts_history?user_id='.$ps_mobile_app_amounts->getUserId())?>"
			class="btn btn-xs btn-default"> <i
			class="fa-fw fa fa-eye txt-color-blue"></i>
		</a> <a data-backdrop="static" data-toggle="modal"
			data-target="#remoteModal"
			href="<?php echo url_for('@ps_history_mobile_app_pay_amounts_new?use_id='.$ps_mobile_app_amounts->getUserId())?>"
			class="btn btn-xs btn-default"> <i
			class="fa-fw fa fa-money txt-color-green"></i>
		</a>
    	<?php if ($sf_user->hasCredential('PS_MOBILE_APP_AMOUNTS_EDIT') and $ps_mobile_app_amounts->getAmountId()): ?>
			<a
			href="<?php echo url_for('@ps_history_mobile_app_pay_amounts_edit?id='.$ps_mobile_app_amounts->getHistoryId()) ?>"
			class="btn btn-xs btn-default"> <i
			class="fa fa-fw fa-pencil txt-color-orange"></i>
		</a>	
		<?php endif; ?>	
	    <?php if ($sf_user->hasCredential('PS_MOBILE_APP_AMOUNTS_DELETE') and $ps_mobile_app_amounts->getAmountId()): ?>
<!-- 	    	<a href="<?php echo url_for('@ps_history_mobile_app_pay_amounts_delete?id='.$ps_mobile_app_amounts->getHistoryId())?>" class="btn btn-xs btn-default">
	    		<i class="fa fa-fw fa-times txt-color-red"></i>
	    	</a>  -->
		<a class="btn btn-xs btn-default delete" data-backdrop="static"
			data-toggle="modal" data-target="#deleteConfirm"
			data-id="<?php echo $ps_mobile_app_amounts->getHistoryId() ?>"> <i
			class="fa fa-fw fa-times txt-color-red"></i>
		</a>
		<?php endif; ?>			
	</div>
</td>