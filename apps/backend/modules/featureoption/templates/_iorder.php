<?php
if (myUser::checkAccessObject ( $feature_option, 'PS_SYSTEM_ROOMS_FILTER_SCHOOL' )) :
	?>
<input type="number" style="max-width: 70px;"
	id="iorder_<?php echo $feature_option->getId();?>"
	name="iorder[<?php echo $feature_option->getId();?>]"
	class="form-control sf_admin_batch_number text-center"
	value="<?php echo $feature_option->getIorder(); ?>"
	onchange="javascript:setCheck(this,'<?php echo $feature_option->getId();?>');" />
<?php else:?>
<input type="text" style="max-width: 70px;"
	id="iorder_<?php echo $feature_option->getId();?>"
	name="iorder[<?php echo $feature_option->getId();?>]"
	class="form-control sf_admin_batch_number text-center"
	value="<?php echo $feature_option->getIorder(); ?>" readonly />
<?php endif;?>
