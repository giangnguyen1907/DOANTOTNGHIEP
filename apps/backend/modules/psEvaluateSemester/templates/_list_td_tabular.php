<td class="sf_admin_foreignkey sf_admin_list_td_student_id text-center">
  <?php echo $ps_evaluate_semester->getStudentCode() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_student">
  <?php echo $ps_evaluate_semester->getStudentName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo $ps_evaluate_semester->getTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_url_file text-center"><a
	class="btn btn-default" title="<?php echo __('View file') ?>"
	target="_blank"
	href="<?php echo $ps_evaluate_semester->getUrlFile() ?>"><i
		class="fa fa-eye"></i></a></td>
<td class="sf_admin_boolean sf_admin_list_td_is_public text-center">
  <?php echo get_partial('psEvaluateSemester/list_field_boolean', array('value' => $ps_evaluate_semester->getIsPublic())) ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at text-center">
  <?php echo $ps_evaluate_semester->getUpdatedBy() ?><br />
  <?php echo false !== strtotime($ps_evaluate_semester->getUpdatedAt()) ? format_date($ps_evaluate_semester->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
  
</td>
