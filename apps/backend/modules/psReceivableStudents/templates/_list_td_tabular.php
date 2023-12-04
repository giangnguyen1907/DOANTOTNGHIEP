<td class="sf_admin_foreignkey sf_admin_list_td_student_id"><a
	href="<?php echo url_for(@ps_receivable_students).'/'.$receivable_student->getStudentId().'/'.$receivable_student->getReceivableId().'/'; ?>detail"
	data-backdrop="static" data-toggle="modal" data-target="#remoteModal">
  <?php echo $receivable_student->getStudentName() ?></a><br /> <code><?php echo $receivable_student->getStudentCode() ?></code>

</td>
<?php if($filter_value['receivable_id'] == ''): ?>
<td class="sf_admin_text sf_admin_list_td_receivable text-center"><a
	href="<?php echo url_for(@ps_receivable_students).'/'.$receivable_student->getStudentId().'/'.$receivable_student->getReceivableId().'/'; ?>detail"
	data-backdrop="static" data-toggle="modal" data-target="#remoteModal"><?php echo $receivable_student->getReceivableTitle() ?></a>
</td>
<?php endif;?>
<td class="sf_admin_text sf_admin_list_td_amount text-center">
  <?php echo number_format($receivable_student->getAmount(),2,",",".");?>
</td>
<td class="sf_admin_text sf_admin_list_td_is_number text-center">
  <?php echo $receivable_student->getIsNumber() ?>
</td>
<?php if($filter_value['receivable_id'] > 0): ?>
<td class="sf_admin_date sf_admin_list_td_receivable_at text-center">
  <?php echo false !== strtotime($receivable_student->getReceivableAt()) ? format_date($receivable_student->getReceivableAt(), "MM/yyyy") : '&nbsp;' ?>
</td>
<?php endif?>
<td class="sf_admin_text sf_admin_list_td_note">
  <?php echo $receivable_student->getNote() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at text-center">
  <?php echo $receivable_student->getUpdatedBy() ?><br />
  <?php echo false !== strtotime($receivable_student->getUpdatedAt()) ? format_date($receivable_student->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
