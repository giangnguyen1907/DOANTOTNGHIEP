<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<div class="sf_admin_form widget-body">
  <?php echo form_tag_for($form, '@ps_menus_imports', array('class' => 'form-horizontal', 'id' => 'ps-form', 'data-fv-addons' => 'i18n')) ?>
    <?php echo $form->renderHiddenFields(true) ?>
    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>	
	<div class="tab-content">
	<?php
	$index = 1;
	foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>
		<div class="tab-pane <?php if ($index == 1) echo 'active'; ?>" id="pstab_<?php echo $index;?>">
		<?php include_partial('psMenusImports/form_fieldset', array('ps_menus_imports' => $ps_menus_imports, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?>
		</div>
    <?php $index++; endforeach; ?>	
	</div>	
    <?php //include_partial('psMenusImports/form_actions', array('ps_menus_imports' => $ps_menus_imports, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>
