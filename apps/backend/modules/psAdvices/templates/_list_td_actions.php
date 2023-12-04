<td class="text-center" style="width: 90px;">
	<div class="btn-group">
    <?php if ($sf_user->hasCredential('PS_STUDENT_RELATIVE_ADVICE_DETAIL')): ?>
<?php echo $helper->linkToDetail($ps_advices, array(  'credentials' => 'PS_STUDENT_RELATIVE_ADVICE_DETAIL',  'params' =>   array(  ),  'class_suffix' => 'detail',  'label' => 'Detail',)) ?>
<?php endif; ?>

    <?php if ($sf_user->hasCredential('PS_STUDENT_RELATIVE_ADVICE_EDIT')): ?>
    
    <a class="btn btn-xs btn-default" data-backdrop="static"
			data-toggle="modal" data-target="#remoteModal"
			title="<?php echo __('Edit')?>"
			href="<?php echo url_for('@ps_advices_edit?id='.$ps_advices->getId())?>"><i
			class="fa-fw fa fa-pencil txt-color-blue"></i></a>
    
<?php //echo $helper->linkToEdit($ps_advices, array(  'credentials' => 'PS_STUDENT_RELATIVE_ADVICE _EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
<?php endif; ?>
    <?php if ($sf_user->hasCredential('PS_STUDENT_RELATIVE_ADVICE_DELETE')): ?>
<?php echo $helper->linkToDelete($ps_advices, array(  'credentials' => 'PS_STUDENT_RELATIVE_ADVICE_DELETE',  'confirm' => 'Are you sure?',  'params' =>   array(  ),  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
<?php endif; ?>

  </div>
</td>