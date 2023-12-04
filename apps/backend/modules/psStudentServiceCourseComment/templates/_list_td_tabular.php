<?php
$list_options = Doctrine::getTable ( 'FeatureOptionSubject' )->getFeatureOptions ( $filter_value ['ps_service_id'], $filter_value ['ps_service_course_schedule_id'], $student_service_course_comment->getStudentId () );
?>
<td class="sf_admin_text sf_admin_list_td_view_img">
  <?php echo get_partial('psStudentServiceCourseComment/view_img', array('type' => 'list', 'student_service_course_comment' => $student_service_course_comment)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_student_name">
  <?php echo $student_service_course_comment->getStudentName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_list_subject_option">
  <?php echo get_partial('psStudentServiceCourseComment/list_feature_option', array('type' => 'list', 'student_service_course_comment' => $student_service_course_comment, 'list_options' => $list_options)) ?>
</td>
