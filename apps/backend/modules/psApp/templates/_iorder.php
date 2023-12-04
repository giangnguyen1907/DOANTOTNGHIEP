<input type="number" style="max-width: 70px;"
	id="iorder_<?php echo $ps_app->getId();?>"
	name="iorder[<?php echo $ps_app->getId();?>]"
	class="form-control sf_admin_batch_number text-center"
	value="<?php echo $ps_app->getIorder(); ?>"
	onchange="javascript:setCheck(this,'<?php echo $ps_app->getId();?>');" />
