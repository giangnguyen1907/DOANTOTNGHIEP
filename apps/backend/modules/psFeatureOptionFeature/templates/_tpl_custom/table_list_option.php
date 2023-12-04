<div class="custom-scroll table-responsive" style="width: 100%; max-height: 550px; overflow-y: scroll;">
	<table id="tbl_feature_option_list"
		class="table_list table table-striped table-bordered table-hover no-footer no-padding"
		width="100%">
		<tr>
			<th class="title"><?php echo __('Feature option')?></th>
			<th class="title"><?php echo __('Mode');?></th>
			<th class="title" style="width: 20px;"><input
				id="sf_admin_list_batch_checkbox" type="hidden"></th>
		</tr>
		<?php
		$ps_featureOptionFeatures = array (
				"" => null ) + PreSchool::loadPsFeatureOptionFeature ();
		foreach ( $feature_options as $i => $feature_option ) :
			?>
			<tr class="<?php echo fmod($i, 2) ? 'color' : 'odd' ?>"
			id="tr_<?php echo $feature_option->getId();?>">
			<td>
					<?php echo $feature_option->getName(); ?>
					<b class="tooltip tooltip-top"> <i
					class="fa fa-question txt-color-white"></i> 
						<?php echo $feature_option->getDescription(); ?>
					</b>
			</td>
			<td><select id="_type[<?php echo $feature_option->getId()?>]"
				name="type[<?php echo $feature_option->getId()?>]"
				class="sf_admin_batch_checkbox form-control list_feature_option"
				onchange="javascript:setOptionFeature('<?php echo $feature_option->getId();?>');">
					<?php
			foreach ( $ps_featureOptionFeatures as $key => $ps_featureOptionFeature ) {
				echo '<option  value="' . $key . '">' . __ ( $ps_featureOptionFeature ) . '</option>';
			}
			?>
					</select></td>
			<td class="action"><label class="checkbox-inline"> <input
					id="of_chk_id_<?php echo $feature_option->getId();?>"
					name="ids[]" value="<?php echo $feature_option->getId();?>"
					class="sf_admin_list_batch_checkbox checkbox style-0"
					type="checkbox"> <span></span>
			</label></td>
		</tr>
		<?php endforeach;?>				
	</table>
</div>