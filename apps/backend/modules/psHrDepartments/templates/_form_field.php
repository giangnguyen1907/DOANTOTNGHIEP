<?php if ($field->isPartial()): ?>
  <?php include_partial('psHrDepartments/'.$name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php elseif ($field->isComponent()): ?>
  <?php include_component('psHrDepartments', $name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php else: ?>

<?php if ($name != 'image'):?>
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
<?php else:?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<?php endif; ?>

  <div class="form-group <?php echo $class ?> <?php $form[$name]->hasError() and print 'has-error' ?>">
    
    <?php if ($name != 'image'):?>    
    <?php echo $form[$name]->renderLabel($label, array('class' => 'col-md-3 control-label')) ?>    
	<div class="col-md-9">
	<?php echo $form[$name]->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes) ?>
	<small class="help-block" data-fv-result="INVALID"><?php echo $form[$name]->renderError() ?></small>
	<?php if ($help): ?>
	<p class="help-block font-xs"><?php echo __($help, array(), 'messages') ?></p>
	<?php elseif ($help = $form[$name]->renderHelp()): ?>
	<p class="help-block font-xs"><?php echo $help ?></p>
	<?php endif; ?>
	</div>
	<?php else:?>
	
	<div class="col-md-12">
	<?php echo $form[$name]->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes) ?>
	<small class="help-block" data-fv-result="INVALID"><?php echo $form[$name]->renderError() ?></small>
	<?php if ($help): ?>
	<p class="help-block font-xs"><?php echo __($help, array(), 'messages') ?></p>
	<?php elseif ($help = $form[$name]->renderHelp()): ?>
	<p class="help-block font-xs"><?php echo $help ?></p>
	<?php endif; ?>
	</div>
	
	<?php endif;?>
  </div>
 </div>
<?php endif; ?>