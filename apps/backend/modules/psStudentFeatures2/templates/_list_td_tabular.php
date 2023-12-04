<?php
$list_options = Doctrine::getTable ( 'FeatureOptionFeature' )->getFeatureOptions ( $filter_value ['feature_branch_id'], $filter_value ['tracked_at'], $student_feature->getStudentId () );
?>
<td class="sf_admin_text sf_admin_list_td_view_img">
  <?php echo get_partial('psStudentFeatures/view_img', array('type' => 'list', 'student_feature' => $student_feature)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_student_name">
  <?php echo $student_feature->getStudentName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_list_feature_option">
  <?php echo get_partial('psStudentFeatures/list_feature_option', array('type' => 'list', 'student_feature' => $student_feature, 'list_options' => $list_options)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_view_img"><label
	class="checkbox"> <input class="checkbox"
		name="feature_option[<?php echo $student_feature->getStudentId();?>][email]"
		type="checkbox" value="1"> <span></span>
</label>
  <?php //echo get_partial('psStudentFeatures/send_email', array('type' => 'list', 'student_feature' => $student_feature)) ?>
</td>