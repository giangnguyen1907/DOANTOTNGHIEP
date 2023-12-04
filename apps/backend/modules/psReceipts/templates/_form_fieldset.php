<fieldset
	id="sf_fieldset_<?php echo preg_replace('/[^a-z0-9_]/', '_', strtolower($fieldset)) ?>">
	<legend><?php echo __('Info receipt', array(), 'messages') ?>. <?php echo __('Receipt no')?>: <?php echo $receipt->getReceiptNo();?></legend>
  <?php $index = 0;?>

  <?php foreach ($fields as $name => $field): ?>
    <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>

    <?php if($index%4 == 0):?>
    <div class="row">
    <?php endif; ?>
    
    <?php

include_partial ( 'psReceipts/form_field', array (
					'name' => $name,
					'attributes' => $field->getConfig ( 'attributes', array () ),
					'label' => $field->getConfig ( 'label' ),
					'help' => $field->getConfig ( 'help' ),
					'form' => $form,
					'field' => $field,
					'class' => 'sf_admin_form_row sf_admin_' . strtolower ( $field->getType () ) . ' sf_admin_form_field_' . $name ) )?>

    <?php
			$index ++;
			if ($index % 4 == 0) :
				?>
    </div>
    <?php endif;

			?>

  <?php endforeach; ?>
</fieldset>
