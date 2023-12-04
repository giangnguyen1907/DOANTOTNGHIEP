<td class="sf_admin_text sf_admin_list_td_fb_title">
	<p><?php echo $feature_branch_times->getFbTitle() ?></p>
	<p>
		<small class="text-muted"><i> <?php echo $feature_branch_times->getWpTitle() ?><i></i></i></small>
	</p>
</td>
<td class="sf_admin_text sf_admin_list_td_date_at">
  <?php echo get_partial('psFeatureBranchTimes/date_at', array('type' => 'list', 'feature_branch_times' => $feature_branch_times)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_time_at">
  <?php echo get_partial('psFeatureBranchTimes/time_at', array('type' => 'list', 'feature_branch_times' => $feature_branch_times)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_note"
	style="white-space: pre-wrap;">
  <?php echo $feature_branch_times->getNote() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_saturday">
  <?php echo get_partial('psFeatureBranchTimes/is_saturday', array('type' => 'list', 'feature_branch_times' => $feature_branch_times)) ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_sunday">
  <?php echo get_partial('psFeatureBranchTimes/is_sunday', array('type' => 'list', 'feature_branch_times' => $feature_branch_times)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_note_class_name">
  <?php echo $feature_branch_times->getNoteClassName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo get_partial('psFeatureBranchTimes/updated_by', array('type' => 'list', 'feature_branch_times' => $feature_branch_times)) ?>
</td>
