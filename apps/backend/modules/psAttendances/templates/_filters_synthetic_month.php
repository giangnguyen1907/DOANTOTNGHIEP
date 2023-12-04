<div id="dt_basic_filter" class="sf_admin_filter dataTables_filter">
	<?php if ($formFilter->hasGlobalErrors()): ?>
      <?php echo $formFilter->renderGlobalErrors() ?>
    <?php endif; ?>
	<form id="ps-filter" class="form-inline pull-left"
		action="<?php echo url_for('ps_attendances_collection', array('action' => 'syntheticMonth')) ?>"
		method="post">
		<div class="pull-left">
    	 	<?php echo $formFilter->renderHiddenFields(true) ?>
    	 	<div class="form-group">
				<label>
        		 	<?php echo $formFilter['ps_school_year_id']->render() ?>
        		 	<?php echo $formFilter['ps_school_year_id']->renderError() ?>
        		 </label>
			</div>
			<div class="form-group">
				<label>
        		 	<?php echo $formFilter['year_month']->render() ?>
        		 	<?php echo $formFilter['year_month']->renderError() ?>
        		 </label>
			</div>
			<div class="form-group">
				<label>
        		 	<?php echo $formFilter['ps_customer_id']->render() ?>
        		 	<?php echo $formFilter['ps_customer_id']->renderError() ?>
        		</label>
			</div>
			<div class="form-group">
				<label>
        		 	<?php echo $formFilter['ps_workplace_id']->render() ?>
        		 	<?php echo $formFilter['ps_workplace_id']->renderError() ?>
        		</label>
			</div>

			<div class="form-group">
				<label>
    		 		<?php echo $formFilter['class_id']->render() ?>
    		 		<?php echo $formFilter['class_id']->renderError() ?>
    		  	</label>
			</div>

			<div class="form-group">
				<label>
    				<?php echo $helper->linkToFilterSearch() ?>
    			</label>
			</div>

			<div class="form-group">
				<label>
    				<?php echo $helper->linkToFilterReset3() ?>
    			</label>
			</div>


		</div>
	</form>
</div>