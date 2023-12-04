<div id="dt_basic_filter" class="sf_admin_filter dataTables_filter">
	<?php if ($formFilter->hasGlobalErrors()): ?>
      <?php echo $formFilter->renderGlobalErrors() ?>
    <?php endif; ?>
	<form id="relative_cross_checking_filters"
		class="form-inline pull-left"
		action="<?php echo url_for('ps_mobile_apps_collection', array('action' => 'crossChecking')) ?>"
		method="post">
		<input type="hidden" name="relative_cross_checking"
			value="relative_cross_checking" id="relative_cross_checking">
		<div class="pull-left">
             <?php echo $formFilter->renderHiddenFields(true) ?>
             
			<div class="form-group">
				<label>
                      <?php echo $formFilter['school_year_id']->render() ?>
                </label>
			</div>

			<div class="form-group">
				<label>
                      <?php echo $formFilter['ps_customer_id']->render() ?>
                </label>
			</div>

			<div class="form-group">
				<label>
        		 	<?php echo $formFilter['ps_workplace_id']->render() ?>
        		</label>
			</div>

			<div class="form-group">
				<label>
                      <?php echo $formFilter['ps_class_id']->render() ?>
                </label>
			</div>

			<div class="form-group">
				<label>
                      <?php echo $formFilter['from_date']->render() ?>
                </label>
			</div>

			<div class="form-group">
				<label>
                      <?php echo $formFilter['to_date']->render() ?>
                </label>
			</div>

			<div class="form-group">
				<label>
                    	<?php echo $helper->linkToFilterSearch() ?>
                </label>
			</div>
			<div class="form-group">
				<label>
    				<?php echo $helper->linkToFilterReset2() ?>
    			</label>
			</div>

		</div>
	</form>
</div>