<td class="text-center">
	<?php if($feature_option_feature->getSfId() == '' || $feature_option_feature->getSfId() <= 0){?>
	<label class="checkbox-inline">
		<input type="checkbox" name="ids[]" id="chk_id_<?php echo $feature_option_feature->getPrimaryKey() ?>" value="<?php echo $feature_option_feature->getPrimaryKey() ?>" class="sf_admin_batch_checkbox checkbox style-0" />
		<span></span>
	</label>
 	<?php }?>
</td>