<input type="number" min="1" style="max-width: 70px;"
	id="iorder_<?php echo $feature->getId();?>"
	name="iorder[<?php echo $feature->getId();?>]"
	class="form-control sf_admin_batch_number text-center"
	value="<?php echo $feature->getIorder(); ?>"
	onchange="javascript:setCheck(this,'<?php echo $feature->getId();?>');" />
