<td class="text-center hidden-md hidden-sm hidden-xs">
	<?php if (myUser::checkAccessObject($ps_department, 'PS_HR_DEPARTMENT_FILTER_SCHOOL')):?>
	<label class="checkbox-inline"> <input type="checkbox" name="ids[]"
		id="chk_id_<?php echo $ps_department->getPrimaryKey() ?>"
		value="<?php echo $ps_department->getPrimaryKey() ?>"
		class="sf_admin_batch_checkbox checkbox style-0" /> <span></span>
</label>
	<?php

else :
		echo '&nbsp;';
	endif;
	?> 
</td>