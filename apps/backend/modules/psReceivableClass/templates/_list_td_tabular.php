<td class="sf_admin_text sf_admin_list_td_re_title">
  <?php echo $receivable_temp->getReTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_amount">
  <?php echo $receivable_temp->getAmount() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_receivable_at">
  <?php echo false !== strtotime($receivable_temp->getReceivableAt()) ? format_date($receivable_temp->getReceivableAt(), "HH:mm  dd-MM-yyyy") : '&nbsp;' ?>
</td>
<td class="sf_admin_text sf_admin_list_td_class_name">
  <?php echo $receivable_temp->getClassName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $receivable_temp->getUpdatedBy() ?>
<br>
  <?php echo false !== strtotime($receivable_temp->getUpdatedAt()) ? format_date($receivable_temp->getUpdatedAt(), "HH:mm  dd-MM-yyyy") : '&nbsp;' ?>
</td>