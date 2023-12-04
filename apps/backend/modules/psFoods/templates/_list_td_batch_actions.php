<td class="text-center hidden-md hidden-sm hidden-xs">
	<?php if (($ps_foods->getPsCustomerId() == myUser::getPscustomerID()) || myUser::credentialPsCustomers('PS_NUTRITION_FOOD_FILTER_SCHOOL')):?>
	<label class="checkbox-inline"> <input type="checkbox" name="ids[]"
		id="chk_id_<?php echo $ps_foods->getPrimaryKey() ?>"
		value="<?php echo $ps_foods->getPrimaryKey() ?>"
		class="sf_admin_batch_checkbox checkbox style-0" /> <span></span>
</label>
 	<?php endif;?>
</td>