<td class="sf_admin_foreignkey sf_admin_list_td_ps_customer_id">
  <?php echo $ps_examination->getCusTitle() ?>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_ps_workplace_id">
  <?php echo $ps_examination->getWpTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_name">
  <?php echo $ps_examination->getName() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_input_date_at">
  <?php echo false !== strtotime($ps_examination->getInputDateAt()) ? format_date($ps_examination->getInputDateAt(), "dd-MM-yyyy") : '&nbsp;' ?>
  
</td>
<td class="sf_admin_text sf_admin_list_td_note">
  <?php echo $ps_examination->getNote() ?>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_user_updated_id">
  <?php echo $ps_examination->getUpdatedBy() ?><br />
  <?php echo false !== strtotime($ps_examination->getUpdatedAt()) ? format_date($ps_examination->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
