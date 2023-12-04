<div id="dt_basic_filter" class="sf_admin_filter dataTables_filter">
	<?php if ($formFilter->hasGlobalErrors()): ?>
      <?php echo $formFilter->renderGlobalErrors() ?>
    <?php endif; ?>
	<form id="ps-filter" class="form-inline pull-left"
		action="<?php echo url_for('ps_logtimes_collection', array('action' => 'history')) ?>"
		method="post">
		<input type="hidden" name="ps_logtimes_history"
			value="ps_logtimes_history" id="ps_logtimes_history_url">
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
    		 		<?php echo $formFilter['student_id']->render() ?>
    		 		<?php echo $formFilter['student_id']->renderError() ?>
    		  	</label>
			</div>

			<div class="form-group">
				<label>
    		 		<?php echo $formFilter['date_at_from']->render() ?>
    		 		<?php echo $formFilter['date_at_from']->renderError() ?>
    		  	</label>
			</div>

			<div class="form-group">
				<label>
    		 		<?php echo $formFilter['date_at_to']->render() ?>
    		 		<?php echo $formFilter['date_at_to']->renderError() ?>
    		  	</label>
			</div>

			<div class="form-group">
				<label>
    				<?php echo $helper->linkToFilterSearch() ?>
    			</label>
			</div>
		</div>
	</form>
</div>