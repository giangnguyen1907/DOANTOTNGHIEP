<td class="sf_admin_text sf_admin_list_td_title"><a
	href="<?php echo $ps_cms_use_guide->getUrlFile() ?>" target="_blank">
		<?php echo $ps_cms_use_guide->getTitle() ?>
	</a></td>
<td class="sf_admin_text sf_admin_list_td_note">
  <?php echo $ps_cms_use_guide->getNote() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_iorder">
  <?php echo $ps_cms_use_guide->getIorder() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_activated">
  <?php echo get_partial('psCmsUseGuides/list_field_boolean', array('value' => $ps_cms_use_guide->getIsActivated())) ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($ps_cms_use_guide->getUpdatedAt()) ? format_date($ps_cms_use_guide->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
