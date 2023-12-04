<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo $ps_config_payments->getTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_price">
  <?php echo $ps_config_payments->getPrice() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_number_month">
  <?php echo $ps_config_payments->getNumberMonth() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_note">
  <?php echo $ps_config_payments->getNote() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_is_activated text-center">
  <?php echo $ps_config_payments->getIsActivated() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at text-center">
	<?php echo $ps_config_payments->getUpdatedBy() ?><br />
	<?php echo false !== strtotime($ps_config_payments->getUpdatedAt()) ? format_date($ps_config_payments->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
