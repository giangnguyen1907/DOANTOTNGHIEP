<td class="text-center">
	<?php if ($service_group->getPsCustomerId() > 0 || myUser::credentialPsCustomers('PS_STUDENT_SERVICE_GROUP_FILTER_SCHOOL')):?>
	<label class="checkbox-inline"> <input type="checkbox" name="ids[]"
		id="chk_id_<?php echo $service_group->getPrimaryKey() ?>"
		value="<?php echo $service_group->getPrimaryKey() ?>"
		class="sf_admin_batch_checkbox checkbox style-0" /> <span></span>
</label>
 	<?php

else :
		echo '&nbsp;';
	endif;
	?>
</td>