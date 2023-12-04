<?php if (($sf_guard_group->getPsCustomerId() == '' && myUser::isAdministrator()) || ($sf_guard_group->getPsCustomerId() > 0 && ($sf_guard_group->getPsCustomerId() == myUser::getPscustomerID() || myUser::credentialPsCustomers('PS_SYSTEM_GROUP_USER_FILTER_SCHOOL')) ) ) :?>
<input type="number" id="iorder_<?php echo $sf_guard_group->getId();?>"
	name="iorder[<?php echo $sf_guard_group->getId();?>]"
	class="sf_admin_batch_number form-control"
	value="<?php echo $sf_guard_group->getIorder(); ?>"
	style="width: 100%; text-align: center;"
	onkeypress="javascript:return keyNumber(event);"
	onchange="javascript:setCheck(this,'<?php echo $sf_guard_group->getId();?>');" />
<?php else: ?>
<input type="text" id="iorder_<?php echo $sf_guard_group->getId();?>"
	name="iorder[<?php echo $sf_guard_group->getId();?>]"
	class="sf_admin_batch_number form-control disabled" readonly="readonly"
	value="<?php echo $sf_guard_group->getIorder(); ?>"
	style="width: 100%; text-align: center;" />
<?php endif; ?>