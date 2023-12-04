
<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false"
	data-widget-colorbutton="false" data-widget-grid="false"
	data-widget-collapsed="false" data-widget-fullscreenbutton="false"
	data-widget-deletebutton="false" data-widget-togglebutton="false">
	<header>
		<span class="widget-icon"><i class="fa fa-table"></i></span>
		<h2><?php echo __('Select Featureoptionfeature')?></h2>
	</header>
	<div>
		<div class="widget-body no-padding">
			<div id="datatable_fixed_column_wrapper" class="dataTables_wrapper form-inline no-footer no-padding">
				<div class="alert alert-info no-margin fade in">
					<i class="fa-fw fa fa-info"></i>
				</div>
				<form id="feature_option_list" name="feature_option_list"
					method="post"
					action="<?php echo url_for('ps_feature_option_feature_collection', array('action' => 'SaveOptionFeature')) ?>">
					<input type="hidden" name="branch_id" id="feature_option_feature_branch_id" value="<?php echo $feature_branch_id;?>"> 
					<input id="form_list_boxchecked" type="hidden">
					<?php $form = new BaseForm(); if ($form->isCSRFProtected()): ?>
				    <input type="hidden"
						name="<?php echo $form->getCSRFFieldName() ?>"
						value="<?php echo $form->getCSRFToken() ?>" />
				    <?php endif; ?>
				    
				    <div id="ic-loading" style="display: none;">
						<i class="fa fa-spinner fa-2x fa-spin text-success" style="padding: 3px;"></i><?php echo __('Loading...')?>
			        </div>
			        
				    <div id="load-ajax">
				    	
				    	<?php include_partial('psFeatureOptionFeature/tpl_custom/table_list_option', array('feature_options' => $feature_options)) ?>
				    	
					</div>
					<div class="sf_admin_actions dt-toolbar-footer no-border-transparent" style="text-align: right;">
				      <?php include_partial('psFeatureOptionFeature/tpl_custom/feature_option_list_actions') ?>
				    </div>
				</form>
			</div>
		</div>
	</div>
</div>
