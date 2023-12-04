<td class="sf_admin_date sf_admin_list_td_start_at">
  <?php echo false !== strtotime($ps_active_student->getStartAt()) ? format_date($ps_active_student->getStartAt(), "dd/MM/yyyy") : '&nbsp;' ?>
</td>

<td class="sf_admin_text sf_admin_list_td_title">  <?php echo $ps_active_student->getTitle() ?></td>
<td class="sf_admin_text sf_admin_list_td_note">
  <?php echo $ps_active_student->getNote() ?>
</td>

<td class="sf_admin_text sf_admin_list_td_start_time">
  <?php echo $ps_active_student->getStartTime() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_end_time">
  <?php echo $ps_active_student->getEndTime() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_class_name">
  <?php echo $ps_active_student->getClassName() ?>
</td>

<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $ps_active_student->getUpdatedBy() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($ps_active_student->getUpdatedAt()) ? format_date($ps_active_student->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
