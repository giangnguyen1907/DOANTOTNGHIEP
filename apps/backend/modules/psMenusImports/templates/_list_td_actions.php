<td class="text-center">
  <div class="btn-group">
    <?php if ($sf_user->hasCredential('PS_NUTRITION_MENUS_EDIT')): ?>
    
    <a class="btn btn-xs btn-default btn-edit-td-action" data-backdrop="static" data-toggle="modal" data-target="#remoteModal"
		href="<?php echo url_for(@ps_menus_imports).'/'.$ps_menus_imports->getId().'/'?>edit"><i class="fa-fw fa fa-pencil txt-color-orange" title="<?php echo __('Edit')?>"></i></a>
    
    
<?php //echo $helper->linkToEdit($ps_menus_imports, array(  'credentials' => 'PS_NUTRITION_MENUS_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
<?php endif; ?>
    <?php if ($sf_user->hasCredential('PS_NUTRITION_MENUS_DELETE')): ?>
<?php echo $helper->linkToDelete($ps_menus_imports, array(  'credentials' => 'PS_NUTRITION_MENUS_DELETE',  'confirm' => 'Are you sure?',  'params' =>   array(  ),  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
<?php endif; ?>

  </div>
</td>