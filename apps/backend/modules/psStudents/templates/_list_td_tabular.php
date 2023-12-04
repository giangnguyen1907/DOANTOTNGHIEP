<td class="sf_admin_text sf_admin_list_td_view_img">
  <?php echo get_partial('psStudents/view_img', array('type' => 'list', 'student' => $student)) ?>  
</td>

<?php if ($sf_user->hasCredential([['PS_STUDENT_MSTUDENT_DETAIL','PS_STUDENT_MSTUDENT_EDIT','PS_STUDENT_MSTUDENT_ADD','PS_STUDENT_MSTUDENT_DELETE']])):?>
<td class="sf_admin_text sf_admin_list_td_student_code"><a
	data-backdrop="static" data-toggle="modal" data-target="#remoteModal"
	href="<?php echo url_for('@ps_students_detail?id='.$student->getId())?>"><?php echo $student->getStudentCode(); ?></a>
</td>
<td class="sf_admin_text sf_admin_list_td_first_name"><a
	data-backdrop="static" data-toggle="modal" data-target="#remoteModal"
	href="<?php echo url_for('@ps_students_detail?id='.$student->getId())?>"><?php echo $student->getFirstName(); ?></a>
</td>
<td class="sf_admin_text sf_admin_list_td_last_name"><a
	data-backdrop="static" data-toggle="modal" data-target="#remoteModal"
	href="<?php echo url_for('@ps_students_detail?id='.$student->getId())?>"><?php echo $student->getLastName(); ?></a>
	<?php if($student->getCommonName() !=""){
	  echo '<br><code>('.$student->getCommonName().')</code>';
 }?>
</td>
<?php else:?>
<td class="sf_admin_text sf_admin_list_td_student_code">
   <?php echo $student->getStudentCode();?>
</td>
<td class="sf_admin_text sf_admin_list_td_first_name">
  <?php echo $student->getFirstName();?>
</td>
<td class="sf_admin_text sf_admin_list_td_last_name">
  <?php echo $student->getLastName();?>
  <?php if($student->getCommonName() !=""){
	  echo '<code>('.$student->getCommonName().')</code>';
 }?>
</td>
<?php endif;?>
<td class="sf_admin_text sf_admin_list_td_birthday text-center">
  <?php echo get_partial('global/field_custom/_field_birthday_student', array('value' => $student->getBirthday())) ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_sex">
  <?php echo get_partial('global/field_custom/_field_sex', array('value' => $student->getSex())) ?>
</td>
<td class="text-center">
	<?php echo false !== strtotime($student->getStartDateAt()) ? format_date($student->getStartDateAt(), "dd/MM/yyyy") : '&nbsp;' ?>
</td>
<td class="sf_admin_text sf_admin_list_td_last_name">
  <?php echo $student->getClassName();?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo $student->getUpdatedBy() ?><br />
  <?php echo false !== strtotime($student->getUpdatedAt()) ? format_date($student->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>


