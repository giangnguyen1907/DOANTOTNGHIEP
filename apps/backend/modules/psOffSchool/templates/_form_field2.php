<?php if ($field->isPartial()): ?>
  <?php include_partial('psOffSchool/'.$name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php elseif ($field->isComponent()): ?>
  <?php include_component('psOffSchool', $name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php else: ?>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
  
  <div class="form-group <?php echo $class ?> <?php $form[$name]->hasError() and print 'has-error' ?>">
    <?php echo $form[$name]->renderLabel($label, array('class' => 'col-md-3 control-label')) ?>
	<div class="col-md-9">
	<?php echo $form[$name]->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes, array('class' => 'form-control')) ?>
	<small class="help-block" data-fv-result="INVALID"><?php echo $form[$name]->renderError() ?></small>
	<?php if ($help): ?>
	<p class="help-block font-xs"><?php echo __($help, array(), 'messages') ?></p>
	<?php elseif ($help = $form[$name]->renderHelp()): ?>
	<p class="help-block font-xs"><?php echo $help ?></p>
	<?php endif; ?>
	</div>
  </div>
 </div>
<?php endif; ?>
