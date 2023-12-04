<td class="sf_admin_text sf_admin_list_td_student_code text-center">
  <?php
		// if ($ps_receivable_temporary->getImage() != '') {
		$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $ps_receivable_temporary->getSchoolCode () . '/' . $ps_receivable_temporary->getYearData () . '/' . $ps_receivable_temporary->getImage ();
		echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '"><br>';
		// }
		?>
  
  <?php //echo $ps_receivable_temporary->getStudentCode() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_student_name">
  <?php echo $ps_receivable_temporary->getStudentName() ?><br /> <code><?php echo $ps_receivable_temporary->getStudentCode() ?></code>
</td>
<td class="sf_admin_text sf_admin_list_td_receivable_title">
  <?php echo $ps_receivable_temporary->getReceivableTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_amount text-right">
  <?php echo $ps_receivable_temporary->getAmount() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_receivable_at text-center">
  <?php echo false !== strtotime($ps_receivable_temporary->getReceivableAt()) ? format_date($ps_receivable_temporary->getReceivableAt(), "dd-MM-yyyy") : '&nbsp;' ?>
</td>
<td class="sf_admin_text sf_admin_list_td_note">
  <?php echo $ps_receivable_temporary->getNote() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by text-center">
  <?php echo $ps_receivable_temporary->getUpdatedBy() ?>
<br>
  <?php echo false !== strtotime($ps_receivable_temporary->getUpdatedAt()) ? format_date($ps_receivable_temporary->getUpdatedAt(), "HH:mm  dd-MM-yyyy") : '&nbsp;' ?>
</td>
