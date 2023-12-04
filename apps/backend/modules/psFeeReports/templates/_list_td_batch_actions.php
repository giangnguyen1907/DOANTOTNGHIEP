<td class="text-center"><label class="checkbox-inline"> <input
		<?php echo ($ps_fee_reports->getId() <= 0) ? 'disabled="disabled"' : '';?>
		type="checkbox" name="ids[]"
		id="chk_id_<?php echo $ps_fee_reports->getId()?>"
		value="<?php echo $ps_fee_reports->getId() ?>"
		class="sf_admin_batch_checkbox checkbox style-0" /> <span></span>
</label></td>