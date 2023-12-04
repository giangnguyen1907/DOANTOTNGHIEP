<td>
	<?php if ($service_group->getPsCustomerId() > 0 || myUser::isAdministrator()):?>
  <input type="checkbox" id="chk_id_<?php echo $service_group->getPrimaryKey() ?>" name="ids[]" value="<?php echo ($service_group->getPsCustomerId() == myUser::getPscustomerID()) ? $service_group->getPrimaryKey() : ''?>" class="sf_admin_batch_checkbox" />
  <?php else:
  	echo '&nbsp;';
  	endif;
  ?>
</td>