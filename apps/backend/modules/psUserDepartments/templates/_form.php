<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<div class="sf_admin_form widget-body">
  <?php echo form_tag_for($form, '@ps_user_departments', array('class' => 'form-horizontal', 'id' => 'ps-form', 'data-fv-addons' => 'i18n')) ?>
    <?php echo $form->renderHiddenFields(true) ?>

    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>
	<div class="row">
	<?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>

      <?php include_partial('psUserDepartments/form_fieldset', array('sf_guard_user' => $sf_guard_user, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?>

    <?php endforeach; ?>
	</div>
	
    <?php include_partial('psUserDepartments/form_actions', array('sf_guard_user' => $sf_guard_user, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </form>
</div>
