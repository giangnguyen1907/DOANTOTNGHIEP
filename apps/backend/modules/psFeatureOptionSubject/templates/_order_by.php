<input type="number" min="0"
	id="order_by_<?php echo $feature_option_subject->getId();?>"
	name="order_by[<?php echo $feature_option_subject->getId();?>]"
	class="sf_admin_batch_number form-control"
	value="<?php echo $feature_option_subject->getIorder(); ?>"
	style="text-align: center;"
	onchange="javascript:setCheck(this,'<?php echo $feature_option_subject->getId();?>');" />
