<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<div class="sf_admin_form widget-body">
  

    <?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>
      
      <?php include_partial('psMemberDepartments/form_fieldset', array('ps_member_departments' => $ps_member_departments, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?>
    
    <?php endforeach; ?>

<!--   </form> -->
</div>
