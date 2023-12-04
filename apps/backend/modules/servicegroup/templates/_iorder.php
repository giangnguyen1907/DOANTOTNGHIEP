<input type="number" style="max-width: 70px;"
	id="iorder_<?php echo $service_group->getId();?>"
	name="iorder[<?php echo $service_group->getId();?>]"
	class="form-control sf_admin_batch_number"
	value="<?php echo $service_group->getIorder(); ?>"
	style="width:40px;text-align:center;"
	onchange="javascript:setCheck(this,'<?php echo $service_group->getId();?>');" />