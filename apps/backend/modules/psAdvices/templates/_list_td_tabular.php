<td class="sf_admin_text sf_admin_list_td_title">
	<p><?php echo $ps_advices->getTitle() ?></p>
	<p>
		<small class="text-muted"><i> <?php echo $ps_advices->getAcTitle() ?><i></i></i></small>
	</p>
</td>
<td class="sf_admin_text sf_admin_list_td_content">
  <?php echo $ps_advices->getContent() ?>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_student_id">
  <?php echo $ps_advices->getStudentName() ?>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_user_created_id">
  <?php echo $ps_advices->getRelativeName() ?>
  <br>
  <?php echo date('H:i d/m/Y', strtotime($ps_advices->getCreatedAt())) ?>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_user_id">
  <?php echo $ps_advices->getTeacherReceive() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_feedback_content">
  <?php echo $ps_advices->getFeedbackContent() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_activated">
  <?php echo get_partial('psAdvices/list_field_boolean', array('value' => $ps_advices->getIsActivated())) ?>  
</td>
