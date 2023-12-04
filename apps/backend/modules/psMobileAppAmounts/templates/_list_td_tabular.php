<td class="sf_admin_text sf_admin_list_td_user_name">
  <?php echo $ps_mobile_app_amounts->getRelativeUserName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_amount">
  <?php echo format_currency($ps_mobile_app_amounts->getAmount()) ?>
</td>
<td class="sf_admin_date sf_admin_list_td_expiration_date_at">
  <?php echo false !== strtotime($ps_mobile_app_amounts->getExpirationDateAt()) ? format_date($ps_mobile_app_amounts->getExpirationDateAt(), "HH:mm:ss dd/MM/yyyy") : '&nbsp;' ?>
</td>
<td class="sf_admin_text sf_admin_list_td_description">
  <?php echo $ps_mobile_app_amounts->getDescription() ?>
</td>
