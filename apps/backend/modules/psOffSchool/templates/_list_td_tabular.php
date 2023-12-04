<td class="sf_admin_text sf_admin_list_td_ps_class_id">
  <?php echo $ps_off_school->getMcName()?>
</td>
<td class="sf_admin_text sf_admin_list_td_relative_id">
  <?php echo $ps_off_school->getRelativeName()?>
</td>
<td class="sf_admin_text sf_admin_list_td_student_id">
  <?php echo $ps_off_school->getStudentName()?>
</td>
<td class="sf_admin_text sf_admin_list_td_description">
	<p class="description-off-school"><?php echo $ps_off_school->getDescription(); 
		if ($ps_off_school->getIsActivated () == 2) {
			echo __ ( 'Reason illegal' ) . ' : ' . $ps_off_school->getReasonIllegal ();
		}
		?></p>
</td>
<td class="sf_admin_text sf_admin_list_td_is_activated">
  <span class="btn-action" data-item="<?php echo $ps_off_school->getId();?>" id="status-<?php echo $ps_off_school->getId();?>">
        <?php echo get_partial('psOffSchool/ajax_activated', array('ps_off_school' => $ps_off_school)) ?>
  </span>
</td>
<td class="sf_admin_date sf_admin_list_td_date_at">
  <?php echo date('d-m-Y H:i',strtotime($ps_off_school->getDateAt()))?>
</td>
<td class="sf_admin_date sf_admin_list_td_date">
  <?php $date = date('d/m/Y',strtotime($ps_off_school->getFromDate())) . ' - ' . date('d/m/Y',strtotime($ps_off_school->getToDate())) ?>
  <?php echo $date ?>
</td>
<td class="sf_admin_date sf_admin_list_td_created_at">
  <?php //echo false !== strtotime($ps_off_school->getCreatedAt()) ? format_date($ps_off_school->getCreatedAt(), "f") : '&nbsp;' ?>
  <?php echo date('d-m-Y',strtotime($ps_off_school->getCreatedAt()))?>
</td>
