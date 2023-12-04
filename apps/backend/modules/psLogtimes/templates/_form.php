<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<div class="sf_admin_form widget-body">
  <?php echo form_tag_for($form, '@ps_logtimes', array('class' => 'form-horizontal', 'id' => 'ps-form-logtimes', 'data-fv-addons' => 'i18n')) ?>
    
    <?php if ($form->getObject()->getStudentId() != '' ):?>
    
    <?php echo $form->renderHiddenFields(true) ?>

    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>

	<?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>

      <?php include_partial('psLogtimes/form_fieldset', array('ps_logtimes' => $ps_logtimes, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?>

    <?php endforeach; ?>

    <?php include_partial('psLogtimes/form_actions', array('ps_logtimes' => $ps_logtimes, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper, 'check_current_date' => $check_current_date)) ?>
    <?php else:?>
    	<?php include_partial('global/include/_msg_warning', array('ps_warning' => __('Please select students from the list of parties to enter data.')));?>
    	
    <?php endif;?>
  </form>
</div>
