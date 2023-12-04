<td class="sf_admin_text sf_admin_list_td_id">
  <?php echo $service->getId() ?>
</td>

<?php if ($sf_user->hasCredential(['PS_STUDENT_SERVICE_DETAIL'])):?>
<td class="sf_admin_text sf_admin_list_td_title"><a
	data-backdrop="static" data-toggle="modal" data-target="#remoteModal"
	href="<?php echo url_for('@ps_service_detail?id='.$service->getId())?>"
	id="detail_title">
   	<?php echo $service->getTitle() ?>
   </a></td>
<?php else: ?>
<td class="sf_admin_text sf_admin_list_td_title">
    <?php echo $service->getTitle() ?>
</td>
<?php endif; ?>
<td class="sf_admin_text sf_admin_list_td_list_enable_roll">
  <?php echo get_partial('psService/list_enable_roll', array('type' => 'list', 'service' => $service)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_group_name">
  <?php echo $service->getGroupName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_iorder">
  <?php echo get_partial('psService/iorder', array('service' => $service)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_service_detail">
  <?php echo get_partial('psService/service_detail', array('type' => 'list', 'service' => $service)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_list_is_default">
  <?php echo get_partial('psService/list_is_default', array('type' => 'list', 'service' => $service)) ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_activated">
  <?php echo get_partial('psService/list_field_boolean', array('value' => $service->getIsActivated())) ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
	<?php echo $service->getUpdatedBy() ?><br />
	<?php echo false !== strtotime($service->getUpdatedAt()) ? format_date($service->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
