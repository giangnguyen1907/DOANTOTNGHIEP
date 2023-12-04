<fieldset
	id="sf_fieldset_<?php echo preg_replace('/[^a-z0-9_]/', '_', strtolower($fieldset)) ?>">
  <?php if ('NONE' != $fieldset): ?>
    <legend><?php echo __($fieldset, array(), 'messages') ?></legend>
  <?php endif; ?>

  <?php $index = 1;?>

  <?php foreach ($fields as $name => $field): ?>
    <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>


    <div class="row">

    <?php

include_partial ( 'psAlbums/form_field', array (
					'name' => $name,
					'attributes' => $field->getConfig ( 'attributes', array () ),
					'label' => $field->getConfig ( 'label' ),
					'help' => $field->getConfig ( 'help' ),
					'form' => $form,
					'field' => $field,
					'remaining'=> 'remainingInput_'.$name,
					'class' => 'sf_admin_form_row sf_admin_' . strtolower ( $field->getType () ) . ' sf_admin_form_field_' . $name ) )?>

    </div>
    <?php $index++;?>

  <?php endforeach; ?>
  
</fieldset>
