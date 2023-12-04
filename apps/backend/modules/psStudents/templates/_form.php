<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<div class="sf_admin_form widget-body">
  <?php echo form_tag_for($form, '@ps_students', array('class' => 'form-horizontal', 'id' => 'ps-form', 'data-fv-addons' => 'i18n')) ?>
    <?php echo $form->renderHiddenFields(true) ?>
    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>    
    <div class="tab-content">    
    <?php
				$index = 1;
				foreach ( $configuration->getFormFields ( $form, $form->isNew () ? 'new' : 'edit' ) as $fieldset => $fields ) :
					?>	  
	  <div class="tab-pane <?php if ($index == 1) echo 'active'; ?>"
			id="pstab_<?php echo $index;?>">
	  <?php if( $form->isNew()) :?>
	  <?php $list_class = array()?>
	  <?php $list_service = array()?>
	  <?php $list_relative = array()?>
	  <?php endif;?>
      <?php include_partial('psStudents/form_fieldset', array('student' => $student,'list_class' => $list_class, 'list_service' => $list_service , 'list_relative' => $list_relative , 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?>
	  </div>
    <?php

$index ++;
				endforeach
				;
				?>    
    </div>
    <?php include_partial('psStudents/form_actions', array('student' => $student , 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </form>
</div>