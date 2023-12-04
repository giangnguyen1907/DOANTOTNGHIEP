<td class="sf_admin_foreignkey sf_admin_list_td_student_id">
  <?php echo $ps_service_saturday->getStudentName() ?>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_service_id">
  <?php echo $ps_service_saturday->getSvTitle() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_service_date">
  <?php echo false !== strtotime($ps_service_saturday->getServiceDate()) ? format_date($ps_service_saturday->getServiceDate(), "dd/MM/yyyy") : '&nbsp;' ?>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_relative_id">
  <?php echo $ps_service_saturday->getFullName() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_input_date_at">
  <?php echo false !== strtotime($ps_service_saturday->getInputDateAt()) ? format_date($ps_service_saturday->getInputDateAt(), "dd/MM/yyyy") : '&nbsp;' ?>
</td>
<td class="sf_admin_text sf_admin_list_td_note">
  <?php echo $ps_service_saturday->getNote() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo $ps_service_saturday->getUpdatedBy() ?><br />
  <?php echo false !== strtotime($ps_service_saturday->getUpdatedAt()) ? format_date($ps_service_saturday->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>