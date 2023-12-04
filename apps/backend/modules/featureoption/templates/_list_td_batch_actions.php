<td class="text-center hidden-md hidden-sm hidden-xs">
 <?php
	if ($feature_option->getPsCustomerId () > 0 || myUser::credentialPsCustomers ( 'PS_SYSTEM_FEATURE_OPTION_FILTER_SCHOOL' )) :
		?>
	<label class="checkbox-inline"> <input type="checkbox" name="ids[]"
		id="chk_id_<?php echo $feature_option->getPrimaryKey() ?>"
		value="<?php echo $feature_option->getPrimaryKey() ?>"
		class="sf_admin_batch_checkbox checkbox style-0" /> <span></span>
</label>
 <?php

else :
		echo '&nbsp;';
	endif;
	?>
</td>