<td class="sf_admin_text sf_admin_list_td_member_name">
  <?php echo $ps_review->getMemberName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_student_name">
  <?php echo $ps_review->getStudentName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_class_name">
  <?php echo $ps_review->getClassName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_review_relative">
  <?php echo $ps_review->getReviewRelative() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_note">
  <?php echo $ps_review->getNote() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_status">
   <?php echo get_partial('psReview/list_field_boolean_type', array('value' => $ps_review->getStatus())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $ps_review->getUpdatedBy() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($ps_review->getUpdatedAt()) ? format_date($ps_review->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
