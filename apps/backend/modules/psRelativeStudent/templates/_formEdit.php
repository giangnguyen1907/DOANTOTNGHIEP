<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<div class="sf_admin_form widget-body">
	<fieldset>
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<label class="col-md-4 control-label">
    	<?php echo __('Relation')?></label>
				<div class="col-md-8">
    	<?php echo $form['relationship_id']->render()?>
    	</div>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<label class="col-md-4 control-label"><?php echo __('Role')?></label>
				<div class="col-md-8">
					<div class="checkbox">
						<label>
                        <?php  echo $form['is_parent_main']->render()?><span><?php echo __('Main')?></span>
						</label>
					</div>
					<div class="checkbox">
						<label class="checkbox-inline">
                      	<?php  echo $form['is_parent']->render()?><span><?php echo __('Parent')?></span>
						</label>
					</div>
					<div class="checkbox">
						<label class="checkbox-inline">
               			<?php  echo $form['is_role']->render()?><span><?php echo __('Avatar')?></span>
						</label>
					</div>
					<div class="checkbox">
						<label class="checkbox-inline">
                        <?php  echo $form['role_service']->render()?><span><?php echo __('Service')?></span>
						</label>
					</div>
				</div>
			</div>
		</div>
	</fieldset>
</div>

