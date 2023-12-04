<div class="form-actions">

	<div class="sf_admin_actions">
			<?php if ($form->isNew()): ?>
		
		  <?php //echo $helper->linkToList(array(  'params' =>   array(  ),  'class_suffix' => 'list',  'label' => 'Back to list',)) ?>	
			
		  <?php echo $helper->linkToSave($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>	
			
		  <button type="button" class="btn btn-default btn-sm btn-psadmin btn-cancel" data-dismiss="modal"><i class="fa-fw fa fa-ban"></i> <?php echo __('Cancel')?></button>	  
		  	  
					<?php else: ?>
		
		  <?php //echo $helper->linkToList(array(  'params' =>   array(  ),  'class_suffix' => 'list',  'label' => 'Back to list',)) ?>	
			
		  <?php echo $helper->linkToSave($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>	
			
		  <button type="button" class="btn btn-default btn-sm btn-psadmin btn-cancel" data-dismiss="modal"><i class="fa-fw fa fa-ban"></i> <?php echo __('Cancel')?></button>
		<?php endif; ?>
</div>
</div>