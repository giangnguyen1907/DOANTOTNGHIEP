<td class="sf_admin_text sf_admin_list_td_file_name">
  <?php echo $feature_branch->getId();?>
</td>
<td class="sf_admin_text sf_admin_list_td_file_name">
  <?php echo get_partial('psFeatureBranch/file_name', array('type' => 'list', 'feature_branch' => $feature_branch)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_name">
  <?php echo get_partial('psFeatureBranch/name', array('type' => 'list', 'feature_branch' => $feature_branch)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_list_field_mode">
  <?php echo get_partial('psFeatureBranch/list_field_mode', array('type' => 'list', 'feature_branch' => $feature_branch)) ?>
</td>
<td
	class="sf_admin_text sf_admin_list_td_list_field_number_option_feature">
  <?php echo get_partial('psFeatureBranch/list_field_number_option_feature', array('type' => 'list', 'feature_branch' => $feature_branch)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_is_activated">	
  <?php
		echo get_partial ( 'psFeatureBranch/ajax_activated', array (
				'feature_branch' => $feature_branch ) )?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
	<?php echo $feature_branch->getUpdatedBy();?>
	<p><?php echo false !== strtotime($feature_branch->getUpdatedAt()) ? format_date($feature_branch->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?></p>
</td>
