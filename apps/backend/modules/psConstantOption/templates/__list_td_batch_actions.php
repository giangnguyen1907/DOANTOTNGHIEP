<td>
  <?php if (myUser::checkAccessObject($ps_constant_option, 'PS_SYSTEM_CONSTANT_OPTION_FILTER_SCHOOL')):?>
  <input type="checkbox" name="ids[]"
	value="<?php echo ($ps_constant_option->getPsCustomerId() == myUser::getPscustomerID()) ? $ps_constant_option->getPrimaryKey() : ''?>"
	class="sf_admin_batch_checkbox" />
  <?php

else :
			echo '&nbsp;';
		endif;
		?>    
</td>