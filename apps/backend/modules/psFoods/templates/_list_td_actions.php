<td class="text-center hidden-md hidden-sm hidden-xs">
  <?php if (myUser::checkAccessObject($ps_foods, 'PS_NUTRITION_FOOD_FILTER_SCHOOL')):?>
  <div class="btn-group">
    <?php if ($sf_user->hasCredential('PS_NUTRITION_FOOD_EDIT')): ?>
    
	<?php echo $helper->linkToEdit($ps_foods, array(  'credentials' => 'PS_NUTRITION_FOOD_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
	
	<?php endif; ?>

    <?php if ($sf_user->hasCredential('PS_NUTRITION_FOOD_DELETE')): ?>
	<?php echo $helper->linkToDelete($ps_foods, array(  'credentials' => 'PS_NUTRITION_FOOD_DELETE',  'confirm' => 'Are you sure?',  'params' =>   array(  ),  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
	<?php endif; ?>

  </div>
  <?php endif;?>
</td>