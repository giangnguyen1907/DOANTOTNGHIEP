<td class="sf_admin_text sf_admin_list_td_student_name">
  <?php echo $student_service->getStudentName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_birthday text-center">
  <?php echo get_partial('global/field_custom/_field_birthday_student', array('value' => $student_service->getBirthday())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_service_title">
  <?php echo $student_service->getServiceTitle() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_created_at text-center">
  <?php echo false !== $student_service->getCreatedBy() ? $student_service->getCreatedBy().'<br/>' : ''  ?>
  <?php echo false !== strtotime($student_service->getCreatedAt()) ? format_date($student_service->getCreatedAt(), "HH:mm dd-MM-yyyy") : '&nbsp;' ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at text-center">
  <?php echo false !== $student_service->getUpdatedBy() ? $student_service->getUpdatedBy().'<br/>' : ''  ?>
  <?php echo false !== strtotime($student_service->getUpdatedAt()) ? format_date($student_service->getUpdatedAt(), "HH:mm dd-MM-yyyy") : '&nbsp;' ?>
</td>
