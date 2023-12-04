<td width="65%">
	<ul class="sf_admin_td_app_permission"
		style="float: left; width: 100%; padding: 0px;">
     <?php
     	$ps_app_permission_list = $ps_app_permission->getPsAppPermissions ();
     	foreach ( $ps_app_permission_list as $permission ) :
	?>
     <li style="float: left; width: 45%; list-style: none; border-bottom: 1px dashed #ccc; padding: 0px;text-align: left;">
     <input style="width: 16px; height: 16px;float: left;" type="checkbox" name="" value="">
     <?php echo $permission->getTitle()?>
     </li>
     <li style="float: left; width: 45%; list-style: none; border-bottom: 1px dashed #ccc; padding: 0px;"><?php echo $permission->getAppPermissionCode()?></li>
     <li class="text-center" style="float: left; list-style: none; width: 10%; padding: 0px;">
		<div class="btn-group">
			<?php echo $helper->linkToEdit($permission, array(  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
		    <?php echo $helper->linkToDelete($permission, array(  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>			
		</div>		
		
	</li>
     <?php endforeach; ?>     
  </ul>
</td>
