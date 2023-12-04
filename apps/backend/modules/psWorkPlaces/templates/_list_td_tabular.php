<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo $ps_work_places->getTitle();?>
</td>
<td class="sf_admin_text sf_admin_list_td_principal">
  <?php echo $ps_work_places->getPrincipal() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_address">
  <?php echo $ps_work_places->getAddress() ?>
  <div>
		<i class="fa fa-phone"></i> <?php echo $ps_work_places->getPhone() ?></div>
	<div>
		<i class="fa fa-envelope-o"></i> <?php echo '<a href="mailto:'.$ps_work_places->getEmail().'">'.$ps_work_places->getEmail().'</a>';?></div>
</td>

<td class="sf_admin_boolean sf_admin_list_td_is_activated">
  <?php echo get_partial('psWorkPlaces/list_field_boolean', array('value' => $ps_work_places->getIsActivated())) ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo $ps_work_places->getUpdatedBy() ?><br />
  <?php echo false !== strtotime($ps_work_places->getUpdatedAt()) ? format_date($ps_work_places->getUpdatedAt(), "HH:mm dd-MM-yyyy") : '&nbsp;' ?>
</td>
