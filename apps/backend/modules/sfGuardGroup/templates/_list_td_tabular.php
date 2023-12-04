<td class="sf_admin_text sf_admin_list_td_name">
  
  <?php if ($sf_user->hasCredential(array(0 =>array( 0 => 'PS_SYSTEM_GROUP_USER_EDIT'),))): ?>
  <?php echo link_to($sf_guard_group->getName(), 'sf_guard_group_edit', $sf_guard_group) ?>
  <?php else:?>
  <?php echo $sf_guard_group->getName();?>
  <?php endif;?>
</td>
<td class="sf_admin_text sf_admin_list_td_customer_title">
  <?php echo $sf_guard_group->getCustomerTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_description">
  <p style="white-space: pre-line; word-break: break-all;">
  <?php echo $sf_guard_group->getDescription()?>
  </p>
</td>
<td class="sf_admin_text sf_admin_list_td_iorder">
  <?php echo get_partial('sfGuardGroup/iorder', array('type' => 'list', 'sf_guard_group' => $sf_guard_group)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $sf_guard_group->getUpdatedBy() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($sf_guard_group->getUpdatedAt()) ? format_date($sf_guard_group->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
