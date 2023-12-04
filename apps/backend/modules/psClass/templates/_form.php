<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<div class="sf_admin_form widget-body">
  <?php echo form_tag_for($form, '@ps_class', array('class' => 'form-horizontal', 'id' => 'ps-form', 'data-fv-addons' => 'i18n')) ?>
    <?php echo $form->renderHiddenFields(true) ?>
    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>    
    <div class="tab-content">    
    <?php
				$index = 1;
				foreach ( $configuration->getFormFields ( $form, $form->isNew () ? 'new' : 'edit' ) as $fieldset => $fields ) :
					?>	  
	  <div class="tab-pane" id="pstab_<?php echo $index;?>">
      <?php include_partial('psClass/form_fieldset', array('my_class' => $my_class, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset));?>
	  </div>
    <?php

$index ++;
				endforeach
				;
				?>    
    </div>
    <?php include_partial('psClass/form_actions', array('my_class' => $my_class, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper));?>
  </form>
</div>