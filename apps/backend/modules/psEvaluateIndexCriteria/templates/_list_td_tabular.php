<td class="sf_admin_text sf_admin_list_td_school_name">
  <?php echo $ps_evaluate_index_criteria->getSchoolName() ?>
  <p>
		<small><?php echo $ps_evaluate_index_criteria->getWpName() ?></small>
	</p>
</td>
<td class="sf_admin_text sf_admin_list_td_criteria_code">
  <?php echo $ps_evaluate_index_criteria->getCriteriaCode() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo $ps_evaluate_index_criteria->getTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_subject_title">
  <?php echo $ps_evaluate_index_criteria->getSubjectTitle() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_activated">
  <?php echo get_partial('psEvaluateIndexCriteria/list_field_boolean', array('value' => $ps_evaluate_index_criteria->getIsActivated())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_iorder">
  <?php echo $ps_evaluate_index_criteria->getIorder() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $ps_evaluate_index_criteria->getUpdatedBy() ?>
  <br>
  <?php echo false !== strtotime($ps_evaluate_index_criteria->getUpdatedAt()) ? format_date($ps_evaluate_index_criteria->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
