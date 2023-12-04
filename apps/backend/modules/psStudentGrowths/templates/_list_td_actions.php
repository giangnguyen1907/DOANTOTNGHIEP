<td class="text-center">
  <div class="btn-group">
    <?php if ($sf_user->hasCredential('PS_MEDICAL_GROWTH_DETAIL')): ?>
<?php echo $helper->linkToDetail($ps_student_growths, array(  'credentials' => 'PS_MEDICAL_GROWTH_DETAIL',  'params' =>   array(  ),  'class_suffix' => 'detail',  'label' => 'Detail',)) ?>
<?php endif; ?>

    <?php if ($sf_user->hasCredential('PS_MEDICAL_GROWTH_EDIT')): ?>
<?php echo $helper->linkToEdit($ps_student_growths, array(  'credentials' => 'PS_MEDICAL_GROWTH_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
<?php endif; ?>
    <?php if ($sf_user->hasCredential('PS_MEDICAL_GROWTH_DELETE')): ?>
<?php echo $helper->linkToDelete($ps_student_growths, array(  'credentials' => 'PS_MEDICAL_GROWTH_DELETE',  'confirm' => 'Are you sure?',  'params' =>   array(  ),  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
<?php endif; ?>

  </div>
  
</td>