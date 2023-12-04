<td class="sf_admin_text sf_admin_list_td_id">
  <?php echo $my_class->getId() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_code">
  <?php echo $my_class->getCode() ?>
</td>
<?php if ($sf_user->hasCredential(['PS_STUDENT_CLASS_DETAIL'])):?>
<td class="sf_admin_text sf_admin_list_td_name"><a
	data-backdrop="static" data-toggle="modal" data-target="#remoteModal"
	href="<?php echo url_for('@ps_class_detail?id='.$my_class->getId())?>"
	id="detail_name">
    <?php echo $my_class->getName() ?>
   </a></td>
<?php else: ?>
<td class="sf_admin_text sf_admin_list_td_name">
    <?php echo $my_class->getName() ?>
</td>
<?php endif; ?>
<td class="sf_admin_text sf_admin_list_td_iorder">
  <?php echo $my_class->getIorder() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_obj_group_title">
  <?php echo $my_class->getObjGroupTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_list_field_class_room">
  <?php echo get_partial('psClass/list_field_class_room', array('type' => 'list', 'my_class' => $my_class)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_list_field_teacher_class">
  <?php echo get_partial('psClass/list_field_teacher_class', array('type' => 'list', 'my_class' => $my_class)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_note">
  <?php echo $my_class->getNote() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_activated">
  <?php echo get_partial('psClass/list_field_boolean', array('value' => $my_class->getIsActivated())) ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
	<?php echo $my_class->getUpdatedBy() ?><br />
	<?php echo false !== strtotime($my_class->getUpdatedAt()) ? format_date($my_class->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
