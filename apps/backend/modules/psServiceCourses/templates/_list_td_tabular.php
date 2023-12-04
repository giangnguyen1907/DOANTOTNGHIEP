<td class="sf_admin_text sf_admin_list_td_course_title">
  <?php echo link_to($ps_service_courses->getCourseTitle(), 'ps_service_courses_edit', $ps_service_courses) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_subjects_title">
  <?php echo $ps_service_courses->getSubjectsTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_teacher">
  <?php echo $ps_service_courses->getTeacher() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_start_at">
  <?php echo false !== strtotime($ps_service_courses->getStartAt()) ? format_date($ps_service_courses->getStartAt(), "dd-MM-yyyy") : '&nbsp;' ?>
</td>
<td class="sf_admin_date sf_admin_list_td_end_at">
  <?php echo false !== strtotime($ps_service_courses->getEndAt()) ? format_date($ps_service_courses->getEndAt(), "dd-MM-yyyy") : '&nbsp;' ?>
</td>
<td class="sf_admin_text sf_admin_list_td_note">
  <?php echo $ps_service_courses->getNote() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_activated">
  <?php echo get_partial('psServiceCourses/list_field_boolean', array('value' => $ps_service_courses->getIsActivated())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $ps_service_courses->getUpdatedBy() ?>
  <p><?php echo false !== strtotime($ps_service_courses->getUpdatedAt()) ? format_date($ps_service_courses->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?></p>
</td>
