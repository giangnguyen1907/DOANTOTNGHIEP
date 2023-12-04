<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo link_to($service_group->getTitle(), 'service_group_edit', $service_group) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_note">
  <?php echo $service_group->getNote() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_iorder">
  <input id="iorder_<?php echo $service_group->getId();?>" name="iorder[<?php echo $service_group->getId();?>]" class="sf_admin_batch_number" value="<?php echo $service_group->getIorder() ?>" style="width:40px;text-align:center;" onkeypress="javascript:return keyNumber(event);" onchange="javascript:setCheck(this,'<?php echo $service_group->getId();?>');"/>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $service_group->getUpdatedBy() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($service_group->getUpdatedAt()) ? format_date($service_group->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
