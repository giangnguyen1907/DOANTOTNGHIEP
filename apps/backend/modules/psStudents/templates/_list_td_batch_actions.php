<td class="text-center">
<label class="checkbox-inline">		
		<?php if($student->getDeletedAt() == ''): ?>
		<input type="checkbox" name="ids[]"
		id="chk_id_<?php echo $student->getPrimaryKey() ?>"
		value="<?php echo $student->getPrimaryKey() ?>"
		class="sf_admin_batch_checkbox checkbox style-0" />
		<?php else:?>
		<input type="checkbox"
		class="sf_admin_batch_checkbox checkbox style-0" disabled />
		<?php endif;?>
		<span></span>
</label>
</td>