<input type="number" min="0"
	id="iorder_<?php echo $my_class->getId();?>"
	name="order_by[<?php echo $my_class->getId();?>]"
	class="sf_admin_batch_number form-control"
	value="<?php echo $my_class->getIorder(); ?>"
	style="text-align: center; max-width: 50px;"
	onchange="javascript:setCheck(this,'<?php echo $my_class->getId();?>');" />
