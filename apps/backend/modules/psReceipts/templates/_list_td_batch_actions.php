<td class="text-center hidden-md hidden-sm hidden-xs">
	<?php if ($receipt->getPrimaryKey() > 0):?>
	<label class="checkbox-inline"> <input type="checkbox" name="ids[]"
		id="chk_id_<?php echo $receipt->getPrimaryKey() ?>"
		value="<?php echo $receipt->getPrimaryKey() ?>"
		class="sf_admin_batch_checkbox checkbox style-0" /> <span></span>
</label>
 	<?php endif;?>
</td>