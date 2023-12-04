
<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<div class="sf_admin_form widget-body">
  <?php echo form_tag_for($form, '@ps_service_courses', array('class' => 'form-horizontal', 'id' => 'ps-form', 'data-fv-addons' => 'i18n')) ?>
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
	  <?php if( $form->isNew()) :?>
	  <?php $list_student = array()?>
	  
	  <?php endif;?>
      <?php include_partial('psServiceCourses/form_fieldset', array('ps_service_courses' => $ps_service_courses,'list_student' => $list_student, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?>
	  </div>
    <?php

$index ++;
				endforeach
				;
				?>    
    </div>
    <?php include_partial('psServiceCourses/form_actions', array('ps_service_courses' => $ps_service_courses , 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </form>
</div>