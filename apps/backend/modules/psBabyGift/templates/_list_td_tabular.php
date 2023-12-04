<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo $ps_baby_gift->getTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_image">
    <img style="width: 80px;" src="<?=$ps_baby_gift->getImage()?>" />
</td>
<td class="sf_admin_boolean sf_admin_list_td_status">
  <?php echo get_partial('psBabyGift/list_field_boolean', array('value' => $ps_baby_gift->getStatus())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $ps_baby_gift->getUpdatedBy() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($ps_baby_gift->getUpdatedAt()) ? format_date($ps_baby_gift->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
