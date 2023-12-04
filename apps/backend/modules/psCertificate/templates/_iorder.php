<input type="number" style="max-width: 70px;"
	id="iorder_<?php echo $ps_certificate->getId();?>"
	name="iorder[<?php echo $ps_certificate->getId();?>]"
	class="form-control sf_admin_batch_number"
	value="<?php echo $ps_certificate->getIorder(); ?>"
	style="width:40px;text-align:center;"
	onchange="javascript:setCheck(this,'<?php echo $ps_certificate->getId();?>');" />