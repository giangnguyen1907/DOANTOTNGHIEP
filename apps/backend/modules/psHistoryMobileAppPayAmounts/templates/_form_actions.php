<div class="form-actions">

	<div class="sf_admin_actions">
			<?php if ($form->isNew()): ?>
			
				<a
			class="btn btn-default btn-success bg-color-green btn-sm btn-psadmin pull-left"
			href='<?php echo url_for('@ps_mobile_app_amounts') ?>'><i
			class="fa-fw fa fa-list-ul" title="<?php echo __('Back to list') ?>"></i> <?php echo __('Back to list') ?></a>

		  		<?php echo $helper->linkToCancel(array(  'params' =>   array(  ),  'class_suffix' => 'cancel',  'label' => 'Cancel',)) ?>	
			
		  		<?php if ($sf_user->hasCredential('PS_STUDENT_TEACHER_CLASS_EDIT')): ?>
					<?php echo $helper->linkToSave($form->getObject(), array(  'credentials' => 'PS_STUDENT_TEACHER_CLASS_EDIT',  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>
<?php endif; ?>
	
					<?php else: ?>
		
		
		  <?php echo $helper->linkToFormDelete($form->getObject(), array(  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>	
			
			<a
			class="btn btn-default btn-success bg-color-green btn-sm btn-psadmin pull-left"
			href='<?php echo url_for('@ps_mobile_app_amounts') ?>'><i
			class="fa-fw fa fa-list-ul" title="<?php echo __('Back to list') ?>"></i> <?php echo __('Back to list') ?></a>
		  <?php echo $helper->linkToSave($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>	
			
		  <?php echo $helper->linkToSaveAndAdd($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save_and_add',  'label' => 'Save and add',)) ?>	  
		<?php endif; ?>
</div>
</div>