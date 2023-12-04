<h5 class="margin-top-0">
	<i class="fa fa-search"></i><span> <?php echo __('Blog Search...')?></span>
</h5>
<div class="row" style="display: table;">
  <?php if ($form->hasGlobalErrors()): ?>
    <?php echo $form->renderGlobalErrors() ?>
  <?php endif; ?>

	<form id="ps-filter" class="form-horizontal"
		action="<?php echo url_for('ps_cms_articles_collection', array('action' => 'filter')) ?>"
		method="post">
  	<?php echo $form->renderHiddenFields() ?>
  	<div class="pull-left">
	    <?php foreach ($configuration->getFormFilterFields($form) as $name => $field): ?>
	        <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>          
	          <?php

include_partial ( 'psCmsArticles/filters_field', array (
								'name' => $name,
								'attributes' => $field->getConfig ( 'attributes', array () ),
								'label' => $field->getConfig ( 'label' ),
								'help' => $field->getConfig ( 'help' ),
								'form' => $form,
								'field' => $field,
								'class' => 'sf_admin_form_row sf_admin_' . strtolower ( $field->getType () ) . ' sf_admin_filter_field_' . $name ) )?>
	
	    <?php endforeach; ?>
	    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="input-group">
					<label>
			    	<?php echo $helper->linkToFilterSearch() ?>
			    	<?php echo $helper->linkToFilterReset() ?>
			      </label>
				</div>
			</div>
		</div>
	</form>
</div>