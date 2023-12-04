<fieldset
	id="sf_fieldset_<?php echo preg_replace('/[^a-z0-9_]/', '_', strtolower($fieldset)) ?>">
  <?php if ('NONE' != $fieldset): ?>
    <legend><?php echo __($fieldset, array(), 'messages') ?></legend>
  <?php endif; ?>

  <?php $index = 1;?>

  <?php foreach ($fields as $name => $field): ?>
    <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>

    <?php if($index%2 != 0):?>
    <div class="row">
    	<?php endif; ?>
	    <?php

include_partial ( 'psServiceSplits/form_field', array (
					'name' => $name,
					'attributes' => $field->getConfig ( 'attributes', array () ),
					'label' => $field->getConfig ( 'label' ),
					'help' => $field->getConfig ( 'help' ),
					'form' => $form,
					'field' => $field,
					'class' => 'sf_admin_form_row sf_admin_' . strtolower ( $field->getType () ) . ' sf_admin_form_field_' . $name ) )?>
    <?php if($index%2 == 0):?>
    </div>
    <?php endif; $index++;?>

  <?php endforeach; ?>
  
<!-- <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		  <div class="form-group">
		  	<?php echo $form['count_value']->renderLabel('count_value', array('class' => 'col-md-12')) ?>
		  	<div class="col-md-6">
				<?php echo $form['count_value']->render() ?>
				<?php echo $form['count_value']->renderError() ?>
			</div>
			<div class="col-md-6">
				<?php echo $form['count_ceil']->render() ?>
				<?php echo $form['count_ceil']->renderError() ?>
			</div>
		  </div>
	  </div>
	  	  
	  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		  <div class="form-group">
		  	<div class="col-md-12">
		  	</div>
		  </div>
	  </div> -->
</fieldset>
