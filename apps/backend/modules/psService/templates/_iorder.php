<input type="number" id="iorder_<?php echo $service->getId();?>"
	name="iorder[<?php echo $service->getId();?>]"
	class="form-control sf_admin_batch_number"
	value="<?php echo $service->getIorder(); ?>"
	style="width: 80px; text-align: center;"
	onkeypress="javascript:return keyNumber(event);"
	onchange="javascript:setCheck(this,'<?php echo $service->getId();?>');" />