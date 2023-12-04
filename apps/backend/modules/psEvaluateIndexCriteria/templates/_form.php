<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<div class="sf_admin_form widget-body">
  <?php echo form_tag_for($form, '@ps_evaluate_index_criteria', array('class' => 'form-horizontal', 'id' => 'ps-form', 'data-fv-addons' => 'i18n')) ?>
    <?php echo $form->renderHiddenFields(true) ?>

    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>

	<?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>

      <?php include_partial('psEvaluateIndexCriteria/form_fieldset', array('ps_evaluate_index_criteria' => $ps_evaluate_index_criteria, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?>

    <?php endforeach; ?>
	
	<?php if(!$form->isNew()) :?>
	<?php include_partial('psEvaluateIndexCriteria/list_class', array('ps_evaluate_index_criteria' => $ps_evaluate_index_criteria, 'list_class' => $list_class, 'schoolyear' =>$schoolyear))?>
	<?php endif;?>
    <?php include_partial('psEvaluateIndexCriteria/form_actions', array('ps_evaluate_index_criteria' => $ps_evaluate_index_criteria, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </form>
</div>
