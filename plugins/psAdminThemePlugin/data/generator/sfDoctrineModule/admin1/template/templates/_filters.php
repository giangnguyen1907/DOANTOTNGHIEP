[?php use_stylesheets_for_form($form) ?]
[?php use_javascripts_for_form($form) ?]

<div id="dt_basic_filter" class="sf_admin_filter dataTables_filter">	
  [?php if ($form->hasGlobalErrors()): ?]
    [?php echo $form->renderGlobalErrors() ?]
  [?php endif; ?]

	<form id="ps-filter" class="form-inline pull-right" action="[?php echo url_for('<?php echo $this->getUrlForAction('collection') ?>', array('action' => 'filter')) ?]" method="post">
  	[?php echo $form->renderHiddenFields() ?]
  	<div class="pull-left">
    [?php foreach ($configuration->getFormFilterFields($form) as $name => $field): ?]
        [?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?]          
          [?php include_partial('<?php echo $this->getModuleName() ?>/filters_field', array(
            'name'       => $name,
            'attributes' => $field->getConfig('attributes', array()),
            'label'      => $field->getConfig('label'),
            'help'       => $field->getConfig('help'),
            'form'       => $form,
            'field'      => $field,
            'class'      => 'sf_admin_form_row sf_admin_'.strtolower($field->getType()).' sf_admin_filter_field_'.$name,
          )) ?]

    [?php endforeach; ?]
    <div class="form-group" style="padding-left: 3px;">
      <label>
    	[?php echo $helper->linkToFilterSearch() ?]
    	[?php echo $helper->linkToFilterReset() ?]
      </label>
 	</div>
    </div>
     	
  </form>
</div>