<?php use_stylesheets_for_form($form) ?>

<?php if ($form->getObject()->getStudentId()):?>
<div class="sf_admin_form widget-body">
  <?php echo form_tag_for($form, '@ps_student_growths', array('class' => 'form-horizontal', 'id' => 'ps-form', 'data-fv-addons' => 'i18n')) ?>
    <?php echo $form->renderHiddenFields(true) ?>

    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>

	<?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>

      <?php include_partial('psStudentGrowths/form_fieldset', array('ps_student_growths' => $ps_student_growths, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?>

    <?php endforeach; ?>

    <?php include_partial('psStudentGrowths/form_actions', array('ps_student_growths' => $ps_student_growths, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </form>
</div>
<?php
else :
	echo 'Chọn học sinh từ danh sách bên';
endif;
?>
