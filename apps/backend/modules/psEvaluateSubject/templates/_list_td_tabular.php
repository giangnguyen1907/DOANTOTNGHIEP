<td class="sf_admin_text sf_admin_list_td_school_name">
  <?php echo $ps_evaluate_subject->getSchoolName() ?>
  <?php if($ps_evaluate_subject->getSyTitle() > 0): ?>
  <br> <small><?php echo $ps_evaluate_subject->getWpTitle() . ' ( ' .$ps_evaluate_subject->getSyTitle() . ' )' ?></small>
  <?php else:?>
  <br> <small><?php echo $ps_evaluate_subject->getWpTitle() ?></small>
  <?php endif;?>
</td>
<td class="sf_admin_text sf_admin_list_td_subject_code">
  <?php echo $ps_evaluate_subject->getSubjectCode() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo $ps_evaluate_subject->getTitle() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_activated">
  <?php echo get_partial('psEvaluateSubject/list_field_boolean', array('value' => $ps_evaluate_subject->getIsActivated())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $ps_evaluate_subject->getUpdatedBy() ?>
  <br>
  <?php echo false !== strtotime($ps_evaluate_subject->getUpdatedAt()) ? format_date($ps_evaluate_subject->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
