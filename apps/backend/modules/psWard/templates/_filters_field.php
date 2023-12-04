<?php if ($field->isPartial()): ?>
  <?php include_partial('psWard/'.$name, array('type' => 'filter', 'form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php elseif ($field->isComponent()): ?>
  <?php include_component('psWard', $name, array('type' => 'filter', 'form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php else: ?>

<div class="form-group <?php echo $class ?>">
	<label>
  		<?php echo $form[$name]->renderError() ?>
  		
  		<?php echo $form[$name]->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes) ?>
  
        <?php if ($help || $help = $form[$name]->renderHelp()): ?>
          <p class="help-block"><?php echo __($help, array(), 'messages') ?></p>
        <?php endif; ?>	
      </label>
</div>

<?php endif; ?>
