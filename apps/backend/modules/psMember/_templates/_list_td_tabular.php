<td class="sf_admin_text sf_admin_list_td_member_code">
  <?php echo link_to($ps_member->getMemberCode(), 'ps_member_edit', $ps_member) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_first_name">
  <?php echo $ps_member->getFirstName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_last_name">
  <?php echo $ps_member->getLastName() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_sex">
  <?php echo get_partial('global/list_field_boolean', array('value' => $ps_member->getSex())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_mobile" style="display: none;">
  <?php echo $ps_member->getMobile() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_email">
  <?php echo $ps_member->getEmail() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_username">
  <?php echo $ps_member->getUsername() ?>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_updated_by"
	style="display: none;">  
  <?php echo $ps_ethnic->getUpdatedBy() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at"
	style="display: none;">
  <?php echo false !== strtotime($ps_member->getUpdatedAt()) ? format_date($ps_member->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
