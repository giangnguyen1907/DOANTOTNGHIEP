<td class="sf_admin_text sf_admin_list_td_file_name">
  <?php echo get_partial('psSubjects/file_name', array('type' => 'list', 'service' => $service)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_title">
  <?php if ($sf_user->hasCredential(['PS_STUDENT_SUBJECT_EDIT'])):?>
  <?php echo link_to($service->getTitle(), 'ps_subjects_edit', $service);?>
  <?php else:?>
  <?php echo $service->getTitle();?>
  <?php endif;?>
</td>
<td class="sf_admin_text sf_admin_list_td_group_name">
  <?php echo $service->getGroupName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_iorder">
  <?php echo $service->getIorder() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_number_course text-center">
<?php echo link_to($service->getNumberCourse(), '@ps_service_courses?sid='.$service->getId() ) ?>
</td>
<td class="sf_admin_text sf_admin__list_field_number_option_subject">
  <?php echo get_partial('psSubjects/list_field_number_option_subject', array('type' => 'list', 'service' => $service)) ?>
</td>

<td class="sf_admin_text sf_admin_list_td_service_detail">
  <?php echo get_partial('psSubjects/service_detail', array('type' => 'list', 'service' => $service)) ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_activated">
  <?php echo get_partial('psSubjects/list_field_boolean', array('value' => $service->getIsActivated())) ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($service->getUpdatedAt()) ? format_date($service->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
