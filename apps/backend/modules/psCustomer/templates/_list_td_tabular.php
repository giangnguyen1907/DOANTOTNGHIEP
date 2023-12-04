<td class="sf_admin_text sf_admin_list_td_view_logo">
  <?php echo get_partial('psCustomer/view_logo', array('type' => 'list', 'ps_customer' => $ps_customer)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_school_code"><a
	data-backdrop="static" data-toggle="modal" data-target="#remoteModal"
	href="<?php echo url_for('@ps_customer_detail?id='.$ps_customer->getId())?>">
   	<?php echo $ps_customer->getSchoolCode() ?>
   </a></td>
<td class="sf_admin_text sf_admin_list_td_school_name"><a
	data-backdrop="static" data-toggle="modal" data-target="#remoteModal"
	href="<?php echo url_for('@ps_customer_detail?id='.$ps_customer->getId())?>">
   	<?php echo $ps_customer->getSchoolName() ?>
   </a></td>
<td class="sf_admin_text sf_admin_list_td_principal">
  <?php echo $ps_customer->getPrincipal() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_address">
	<div>
		<i class="fa fa-map-marker"></i> <?php echo $ps_customer->getAddress() ?></div>
	<div>
		<i class="fa fa-phone"></i> <?php echo $ps_customer->getMobile() ?></div>
	<div>
		<i class="fa fa-envelope-o"></i> <?php echo '<a href="mailto:'.$ps_customer->getEmail().'">'.$ps_customer->getEmail().'</a>';?></div>
</td>
<td class="sf_admin_text sf_admin_list_td_is_activated">
  <?php echo get_partial('psCustomer/is_activated', array('type' => 'list', 'ps_customer' => $ps_customer)) ?>
</td>
<td class="sf_admin_list_td_is_deploy sf_admin_list_td_is_activated">
  <?php echo get_partial('psCustomer/list_field_boolean', array('value' => $ps_customer->getIsDeploy())) ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
	<?php echo $ps_customer->getUpdatedBy() ?><br />
	<?php echo false !== strtotime($ps_customer->getUpdatedAt()) ? format_date($ps_customer->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
