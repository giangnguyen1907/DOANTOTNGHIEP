<?php
	$name_fieldset = preg_replace('/[^a-z0-9_]/', '_', strtolower($fieldset));
	
	if ($name_fieldset == 'infomation') {
		$class = 'col-xs-12 col-sm-12 col-md-8 col-lg-8';
	} else {
		$class = 'col-xs-12 col-sm-12 col-md-4 col-lg-4';
	}
?>
<fieldset id="sf_fieldset_<?php echo $name_fieldset;?>" class="<?php echo $class;?>">
  <?php if ('NONE' != $fieldset): ?>
    <legend><?php echo __($fieldset, array(), 'messages') ?></legend>
  <?php endif; ?>
  <?php foreach ($fields as $name => $field): ?>
    <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>

    <?php include_partial('psUserDepartments/form_field', array(
      'name'       => $name,
      'attributes' => $field->getConfig('attributes', array()),
      'label'      => $field->getConfig('label'),
      'help'       => $field->getConfig('help'),
      'form'       => $form,
      'field'      => $field,
      'class'      => 'sf_admin_form_row sf_admin_'.strtolower($field->getType()).' sf_admin_form_field_'.$name,
    )) ?>    
  <?php endforeach; ?>
</fieldset>
